<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CuocHoiThoai;
use App\Models\TinNhan;
use App\Events\NewChatMessage;
use App\Events\ConversationUpdated;
use App\Events\UserTyping;
use Illuminate\Http\Request;

class AdminChatController extends Controller
{
    /**
     * Lấy user hiện tại từ request (được set bởi middleware)
     */
    protected function getAuthUser(Request $request)
    {
        return $request->auth_user ?? $request->get('auth_user');
    }

    /**
     * Hiển thị trang quản lý chat
     */
    public function index(Request $request)
    {
        $user = $this->getAuthUser($request);
        return view('admin.chat.index', ['authUser' => $user]);
    }

    /**
     * Lấy danh sách cuộc hội thoại
     */
    public function getConversations(Request $request)
    {
        $status = $request->get('status', 'all');
        $user = $this->getAuthUser($request);
        $adminId = $user?->ID;

        $query = CuocHoiThoai::with([
            'nguoiDung:ID,TenNguoiDung,HinhAnh,Email',
            'admin:ID,TenNguoiDung,HinhAnh',
            'tinNhanMoiNhat',
        ])
        ->withCount(['tinNhanChuaDoc as chua_doc' => function ($q) {
            $q->where('LoaiNguoiGui', 'NguoiDung');
        }]);

        if ($status === 'waiting') {
            $query->where('TrangThai', 'Chờ');
        } elseif ($status === 'open') {
            $query->where('TrangThai', 'Mở');
        } elseif ($status === 'closed') {
            $query->where('TrangThai', 'Đóng');
        } elseif ($status === 'mine') {
            $query->where('IDAdmin', $adminId)->whereIn('TrangThai', ['Mở', 'Chờ']);
        }

        $conversations = $query->orderByDesc('LanHoatDongCuoi')->paginate(20);

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
        ]);
    }

    /**
     * Lấy tin nhắn của cuộc hội thoại
     */
    public function getMessages($conversationId)
    {
        $conversation = CuocHoiThoai::with(['nguoiDung:ID,TenNguoiDung,HinhAnh,Email,SDT'])
            ->findOrFail($conversationId);

        $messages = TinNhan::where('IDCuocHoiThoai', $conversationId)
            ->with(['nguoiGui:ID,TenNguoiDung,HinhAnh'])
            ->orderBy('ThoiGianGui', 'asc')
            ->get();

        // Đánh dấu đã đọc các tin nhắn từ người dùng
        TinNhan::where('IDCuocHoiThoai', $conversationId)
            ->where('LoaiNguoiGui', 'NguoiDung')
            ->where('DaXem', false)
            ->update(['DaXem' => true]);

        return response()->json([
            'success' => true,
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    /**
     * Gửi tin nhắn từ Admin
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'message' => 'required|string|max:2000',
        ]);

        $user = $this->getAuthUser($request);
        $adminId = $user?->ID;

        if (!$adminId) {
            return response()->json([
                'success' => false,
                'message' => 'Không xác định được người dùng'
            ], 401);
        }

        $conversation = CuocHoiThoai::findOrFail($request->conversation_id);

        // Nhận cuộc hội thoại nếu chưa có admin
        if (!$conversation->IDAdmin) {
            $conversation->update(['IDAdmin' => $adminId]);
        }

        $message = TinNhan::create([
            'IDCuocHoiThoai' => $conversation->ID,
            'IDNguoiGui' => $adminId,
            'LoaiNguoiGui' => 'Admin',
            'NoiDung' => $request->message,
            'ThoiGianGui' => now(),
        ]);

        // Cập nhật trạng thái
        $conversation->update([
            'TrangThai' => 'Mở',
            'LanHoatDongCuoi' => now(),
        ]);

        // Broadcast tin nhắn mới qua WebSocket (nếu Reverb đang chạy)
        try {
            broadcast(new NewChatMessage($message))->toOthers();
        } catch (\Exception $e) {
            \Log::warning('WebSocket broadcast failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => $message->load('nguoiGui:ID,TenNguoiDung,HinhAnh'),
        ]);
    }

    /**
     * Gửi trạng thái đang gõ
     */
    public function typing(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'is_typing' => 'required|boolean',
        ]);

        $conversation = CuocHoiThoai::findOrFail($request->conversation_id);
        $user = $this->getAuthUser($request);
        
        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        try {
            broadcast(new UserTyping(
                $conversation->ID,
                $user->ID,
                $user->TenNguoiDung,
                'Admin',
                $request->is_typing
            ))->toOthers();
        } catch (\Exception $e) {
            // Ignore WebSocket errors for typing status
        }

        return response()->json(['success' => true]);
    }

    /**
     * Nhận cuộc hội thoại
     */
    public function assignConversation(Request $request, $conversationId)
    {
        $conversation = CuocHoiThoai::findOrFail($conversationId);
        $user = $this->getAuthUser($request);
        $adminId = $user?->ID;

        if (!$adminId) {
            return response()->json([
                'success' => false,
                'message' => 'Không xác định được người dùng'
            ], 401);
        }

        $conversation->update([
            'IDAdmin' => $adminId,
            'TrangThai' => 'Mở',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã nhận cuộc hội thoại',
        ]);
    }

    /**
     * Đóng cuộc hội thoại
     */
    public function closeConversation(Request $request, $conversationId)
    {
        $conversation = CuocHoiThoai::findOrFail($conversationId);
        $user = $this->getAuthUser($request);
        
        $conversation->update(['TrangThai' => 'Đóng']);

        // Gửi tin nhắn thông báo
        $message = TinNhan::create([
            'IDCuocHoiThoai' => $conversation->ID,
            'IDNguoiGui' => $user?->ID,
            'LoaiNguoiGui' => 'HeThong',
            'NoiDung' => 'Cuộc hội thoại đã được đóng. Cảm ơn bạn đã liên hệ với chúng tôi!',
            'ThoiGianGui' => now(),
        ]);

        // Broadcast tin nhắn và cập nhật cuộc hội thoại (nếu Reverb đang chạy)
        try {
            broadcast(new NewChatMessage($message));
            broadcast(new ConversationUpdated($conversation->fresh(), 'closed'));
        } catch (\Exception $e) {
            \Log::warning('WebSocket broadcast failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã đóng cuộc hội thoại',
        ]);
    }

    /**
     * Đếm số cuộc hội thoại chờ
     */
    public function getStats(Request $request)
    {
        $user = $this->getAuthUser($request);
        $adminId = $user?->ID;

        $waiting = CuocHoiThoai::where('TrangThai', 'Chờ')->count();
        $open = CuocHoiThoai::where('TrangThai', 'Mở')->count();
        $myChats = CuocHoiThoai::where('IDAdmin', $adminId)
            ->whereIn('TrangThai', ['Mở', 'Chờ'])
            ->count();

        $unreadMessages = TinNhan::whereHas('cuocHoiThoai', function ($q) {
            $q->whereIn('TrangThai', ['Mở', 'Chờ']);
        })
        ->where('LoaiNguoiGui', 'NguoiDung')
        ->where('DaXem', false)
        ->count();

        return response()->json([
            'success' => true,
            'stats' => [
                'waiting' => $waiting,
                'open' => $open,
                'my_chats' => $myChats,
                'unread' => $unreadMessages,
            ],
        ]);
    }
}
