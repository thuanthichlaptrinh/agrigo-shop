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

            // Kiểm tra quyền Admin/Manager (Admin, ProductManager, OrderManager)
            $vaiTro = $user->vaiTro->TenVaiTro ?? null;
            
            $allowedRoles = [VaiTro::ADMIN, VaiTro::PRODUCT_MANAGER, VaiTro::ORDER_MANAGER];
            
            if (!in_array($vaiTro, $allowedRoles)) {
                return redirect()->route('unauthorized')->with('error', 'Bạn không có quyền truy cập khu vực quản trị');
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
