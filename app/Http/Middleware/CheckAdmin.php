<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\VaiTro;

class CheckAdmin
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

            // Lấy user từ token
            $user = JWTAuth::setToken($token)->toUser();

            if (!$user || !$user->TrangThai) {
                return redirect()->route('login')->with('error', 'Tài khoản không hợp lệ');
            }

            // Kiểm tra quyền Admin
            $vaiTro = $user->vaiTro->TenVaiTro ?? null;
            
            if ($vaiTro !== VaiTro::ADMIN) {
                return redirect()->route('user.home')->with('error', 'Bạn không có quyền truy cập');
            }

            return $next($request);
            
        } catch (\Exception $e) {
            session()->forget(['jwt_token', 'user_id']);
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại');
        }
    }
}
