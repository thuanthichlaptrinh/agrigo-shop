<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = session('jwt_token');
            
            if (!$token) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập');
            }

            // Xác thực token
            $user = JWTAuth::setToken($token)->toUser();

            if (!$user || !$user->TrangThai) {
                session()->forget(['jwt_token', 'user_id']);
                return redirect()->route('login')->with('error', 'Tài khoản không hợp lệ');
            }

            // Lưu user vào request để sử dụng trong controller
            $request->merge(['auth_user' => $user]);

            return $next($request);
            
        } catch (\Exception $e) {
            session()->forget(['jwt_token', 'user_id']);
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại');
        }
    }
}
