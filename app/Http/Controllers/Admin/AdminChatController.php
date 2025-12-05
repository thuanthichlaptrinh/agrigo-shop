<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CuocHoiThoai;
use App\Models\TinNhan;
use Illuminate\Http\Request;

class AdminChatController extends Controller
{
    /**
     * Hiển thị trang quản lý chat
     */
    public function index()
    {
        return view('admin.chat.index');
    }

    /**
     * Lấy danh sách cuộc hội thoại
     */
    public function getConversations(Request $request)
    {
        $status = $request->get('status', 'all');
        $adminId = auth()->id();

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

        $adminId = auth()->id();
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

        return response()->json([
            'success' => true,
            'message' => $message->load('nguoiGui:ID,TenNguoiDung,HinhAnh'),
        ]);
    }

    /**
     * Nhận cuộc hội thoại
     */
    public function assignConversation(Request $request, $conversationId)
    {
        $conversation = CuocHoiThoai::findOrFail($conversationId);
        $adminId = auth()->id();

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
    public function closeConversation($conversationId)
    {
        $conversation = CuocHoiThoai::findOrFail($conversationId);
        
        $conversation->update(['TrangThai' => 'Đóng']);

        // Gửi tin nhắn thông báo
        TinNhan::create([
            'IDCuocHoiThoai' => $conversation->ID,
            'IDNguoiGui' => auth()->id(),
            'LoaiNguoiGui' => 'HeThong',
            'NoiDung' => 'Cuộc hội thoại đã được đóng. Cảm ơn bạn đã liên hệ với chúng tôi!',
            'ThoiGianGui' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã đóng cuộc hội thoại',
        ]);
    }

    /**
     * Đếm số cuộc hội thoại chờ
     */
    public function getStats()
    {
        $waiting = CuocHoiThoai::where('TrangThai', 'Chờ')->count();
        $open = CuocHoiThoai::where('TrangThai', 'Mở')->count();
        $myChats = CuocHoiThoai::where('IDAdmin', auth()->id())
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
