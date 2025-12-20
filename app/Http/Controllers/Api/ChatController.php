<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CuocHoiThoai;
use App\Models\TinNhan;
use App\Models\NguoiDung;
use App\Events\NewChatMessage;
use App\Events\ConversationUpdated;
use App\Events\UserTyping;
use App\Support\Auth\JwtSessionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Lấy user hiện tại từ JWT session
     */
    protected function getAuthUser(): ?NguoiDung
    {
        /** @var JwtSessionManager $manager */
        $manager = app(JwtSessionManager::class);
        return $manager->resolveUser();
    }
    /**
     * Lấy hoặc tạo cuộc hội thoại với Admin
     */
    public function getOrCreateConversation(Request $request)
    {
        $user = $this->getAuthUser();
        $userId = $user?->ID;
        $sessionId = $request->session()->getId();

        $conversation = null;

        if ($userId) {
            // 1. Tìm cuộc hội thoại của user đang đăng nhập
            $conversation = CuocHoiThoai::where('IDNguoiDung', $userId)
                ->whereIn('TrangThai', ['Mở', 'Chờ'])
                ->first();

            // 2. Nếu không có, tìm cuộc hội thoại guest của session hiện tại để merge
            if (!$conversation) {
                $guestConversation = CuocHoiThoai::where('SessionID', $sessionId)
                    ->whereNull('IDNguoiDung')
                    ->whereIn('TrangThai', ['Mở', 'Chờ'])
                    ->first();

                if ($guestConversation) {
                    $guestConversation->update(['IDNguoiDung' => $userId]);
                    $conversation = $guestConversation;
                }
            }
        } else {
            // Khách vãng lai
            $conversation = CuocHoiThoai::where('SessionID', $sessionId)
                ->whereIn('TrangThai', ['Mở', 'Chờ'])
                ->first();
        }

        if (!$conversation) {
            // Tạo cuộc hội thoại mới
            $conversation = CuocHoiThoai::create([
                'IDNguoiDung' => $userId,
                'SessionID' => $userId ? null : $sessionId,
                'TieuDe' => 'Hỗ trợ khách hàng',
                'TrangThai' => 'Chờ',
                'LanHoatDongCuoi' => now(),
            ]);

            // Tạo tin nhắn chào mừng từ hệ thống
            TinNhan::create([
                'IDCuocHoiThoai' => $conversation->ID,
                'IDNguoiGui' => null,
                'LoaiNguoiGui' => 'HeThong',
                'NoiDung' => 'Xin chào! Bạn đang được kết nối với bộ phận hỗ trợ. Vui lòng để lại tin nhắn, Admin sẽ phản hồi trong thời gian sớm nhất.',
                'ThoiGianGui' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'conversation' => $conversation->load(['admin:ID,TenNguoiDung,HinhAnh']),
        ]);
    }

    /**
     * Lấy danh sách tin nhắn của cuộc hội thoại
     */
    public function getMessages(Request $request, $conversationId)
    {
        $conversation = $this->getAuthorizedConversation($conversationId, $request);
        
        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy cuộc hội thoại'], 404);
        }

        $messages = TinNhan::where('IDCuocHoiThoai', $conversationId)
            ->with(['nguoiGui:ID,TenNguoiDung,HinhAnh'])
            ->orderBy('ThoiGianGui', 'asc')
            ->get();

        // Đánh dấu đã đọc các tin nhắn từ Admin
        TinNhan::where('IDCuocHoiThoai', $conversationId)
            ->where('LoaiNguoiGui', 'Admin')
            ->where('DaXem', false)
            ->update(['DaXem' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'admin' => $conversation->admin ? [
                'ID' => $conversation->admin->ID,
                'TenNguoiDung' => $conversation->admin->TenNguoiDung,
                'HinhAnh' => $conversation->admin->HinhAnh,
            ] : null,
        ]);
    }

    /**
     * Gửi tin nhắn từ người dùng
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'message' => 'required|string|max:2000',
        ]);

        $conversation = $this->getAuthorizedConversation($request->conversation_id, $request);
        
        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy cuộc hội thoại'], 404);
        }

        $user = $this->getAuthUser();
        $userId = $user?->ID;

        $message = TinNhan::create([
            'IDCuocHoiThoai' => $conversation->ID,
            'IDNguoiGui' => $userId,
            'LoaiNguoiGui' => 'NguoiDung',
            'NoiDung' => $request->message,
            'ThoiGianGui' => now(),
        ]);

        // Cập nhật thời gian hoạt động
        $conversation->update([
            'LanHoatDongCuoi' => now(),
            'TrangThai' => 'Chờ', // Đợi admin phản hồi
        ]);

        // Broadcast tin nhắn mới qua WebSocket (nếu Reverb đang chạy)
        try {
            broadcast(new NewChatMessage($message))->toOthers();
            broadcast(new ConversationUpdated($conversation->fresh(), 'updated'));
        } catch (\Exception $e) {
            // Reverb không chạy, bỏ qua broadcast - chat vẫn hoạt động qua polling
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

        $conversation = $this->getAuthorizedConversation($request->conversation_id, $request);
        
        if (!$conversation) {
            return response()->json(['success' => false], 404);
        }

        $user = $this->getAuthUser();
        
        try {
            broadcast(new UserTyping(
                $conversation->ID,
                $user?->ID,
                $user?->TenNguoiDung ?? 'Khách',
                'NguoiDung',
                $request->is_typing
            ))->toOthers();
        } catch (\Exception $e) {
            // Ignore WebSocket errors for typing status
        }

        return response()->json(['success' => true]);
    }

    /**
     * Đóng cuộc hội thoại
     */
    public function closeConversation(Request $request, $conversationId)
    {
        $conversation = $this->getAuthorizedConversation($conversationId, $request);
        
        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy cuộc hội thoại'], 404);
        }

        $conversation->update(['TrangThai' => 'Đóng']);

        return response()->json([
            'success' => true,
            'message' => 'Đã đóng cuộc hội thoại',
        ]);
    }

    /**
     * Kiểm tra quyền truy cập cuộc hội thoại
     */
    private function getAuthorizedConversation($conversationId, Request $request)
    {
        $user = $this->getAuthUser();
        $userId = $user?->ID;
        $sessionId = $request->session()->getId();

        return CuocHoiThoai::where('ID', $conversationId)
            ->where(function ($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('IDNguoiDung', $userId);
                } else {
                    $q->where('SessionID', $sessionId);
                }
            })
            ->first();
    }
}
