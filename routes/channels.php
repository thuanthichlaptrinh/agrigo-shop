<?php

use App\Models\CuocHoiThoai;
use App\Support\Auth\JwtSessionManager;
use Illuminate\Support\Facades\Broadcast;

/**
 * Helper function để lấy user từ JWT session
 */
function getJwtUser() {
    try {
        $manager = app(JwtSessionManager::class);
        return $manager->resolveUser();
    } catch (\Exception $e) {
        return null;
    }
}

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Chat conversation channel - User có thể join nếu là chủ cuộc hội thoại hoặc admin được assign
 * Hỗ trợ cả guest (qua session) và logged-in users
 */
Broadcast::channel('chat.conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = CuocHoiThoai::find($conversationId);
    
    if (!$conversation) {
        return false;
    }

    // Nếu có user từ default auth
    if ($user) {
        // User là chủ cuộc hội thoại
        if ($conversation->IDNguoiDung === $user->ID) {
            return ['id' => $user->ID, 'name' => $user->TenNguoiDung, 'type' => 'user'];
        }

        // Admin được assign hoặc bất kỳ admin nào
        if (method_exists($user, 'isAdmin') && ($user->isAdmin() || $user->isProductManager() || $user->isOrderManager())) {
            return ['id' => $user->ID, 'name' => $user->TenNguoiDung, 'type' => 'admin'];
        }
    }

    // Thử lấy user từ JWT session
    $jwtUser = getJwtUser();
    if ($jwtUser) {
        if ($conversation->IDNguoiDung === $jwtUser->ID) {
            return ['id' => $jwtUser->ID, 'name' => $jwtUser->TenNguoiDung, 'type' => 'user'];
        }
        
        if (method_exists($jwtUser, 'isAdmin') && ($jwtUser->isAdmin() || $jwtUser->isProductManager() || $jwtUser->isOrderManager())) {
            return ['id' => $jwtUser->ID, 'name' => $jwtUser->TenNguoiDung, 'type' => 'admin'];
        }
    }

    // Guest user - kiểm tra session ID
    $sessionId = request()->session()->getId();
    if ($conversation->SessionID === $sessionId) {
        return ['id' => 'guest-' . substr($sessionId, 0, 8), 'name' => 'Khách', 'type' => 'guest'];
    }

    return false;
});

/**
 * Admin channel - Chỉ admin mới có thể join
 */
Broadcast::channel('chat.admin', function ($user) {
    // Thử default auth trước
    if ($user && method_exists($user, 'isAdmin')) {
        if ($user->isAdmin() || $user->isProductManager() || $user->isOrderManager()) {
            return ['id' => $user->ID, 'name' => $user->TenNguoiDung];
        }
    }

    // Thử JWT session
    $jwtUser = getJwtUser();
    if ($jwtUser && method_exists($jwtUser, 'isAdmin')) {
        if ($jwtUser->isAdmin() || $jwtUser->isProductManager() || $jwtUser->isOrderManager()) {
            return ['id' => $jwtUser->ID, 'name' => $jwtUser->TenNguoiDung];
        }
    }

    return false;
});
