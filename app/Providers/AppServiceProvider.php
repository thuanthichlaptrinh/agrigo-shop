<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đăng ký user vào Auth facade từ JWT token
        try {
            $token = session('jwt_token');
            
            if ($token) {
                // Kiểm tra token có hợp lệ không
                $user = \Tymon\JWTAuth\Facades\JWTAuth::setToken($token)->toUser();
                
                if ($user && $user->TrangThai) {
                    \Illuminate\Support\Facades\Auth::setUser($user);
                } else {
                    // User không hợp lệ, chỉ xóa jwt_token và user_id
                    session()->forget(['jwt_token', 'user_id']);
                }
            }
        } catch (\Exception $e) {
            // Token không hợp lệ hoặc đã hết hạn, chỉ xóa jwt_token và user_id
            session()->forget(['jwt_token', 'user_id']);
        }
    }
}
