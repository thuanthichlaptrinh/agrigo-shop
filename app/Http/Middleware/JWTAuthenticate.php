<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\NguoiDung;

class JWTAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Lấy token từ session
            $token = session('jwt_token');
            
            if (!$token) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập');
            }

            // Xác thực token
            JWTAuth::setToken($token)->checkOrFail();
            
            // Lấy user từ token
            $user = JWTAuth::setToken($token)->toUser();
            
            // Kiểm tra trạng thái tài khoản
            if (!$user || !$user->TrangThai) {
                session()->forget(['jwt_token', 'user_id']);
                return redirect()->route('login')->with('error', 'Tài khoản đã bị khóa');
            }

            // Lưu user vào request
            $request->merge(['auth_user' => $user]);
            
            return $next($request);
            
        } catch (\Exception $e) {
            session()->forget(['jwt_token', 'user_id']);
            return redirect()->route('login')->with('error', 'Phiên đăng nhập đã hết hạn');
        }
    }
}
