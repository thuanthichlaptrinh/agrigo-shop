<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VaiTro;
use App\Support\Auth\JwtSessionManager;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var JwtSessionManager $manager */
        $manager = app(JwtSessionManager::class);
        $user = $manager->resolveUser();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại');
        }

        // Kiểm tra quyền Admin/Manager (Admin, ProductManager, OrderManager)
        $vaiTro = $user->vaiTro->TenVaiTro ?? null;

        $allowedRoles = [VaiTro::ADMIN, VaiTro::PRODUCT_MANAGER, VaiTro::ORDER_MANAGER];

        if (!in_array($vaiTro, $allowedRoles)) {
            return redirect()->route('unauthorized')->with('error', 'Bạn không có quyền truy cập khu vực quản trị');
        }

        // Giới hạn ProductManager chỉ được thao tác sản phẩm, loại sản phẩm, danh mục, sản phẩm khuyến mãi
        if ($vaiTro === VaiTro::PRODUCT_MANAGER) {
            $pmAllowed = [
                'admin.dashboard',
                'admin.products.*',
                'admin.categories.*',
                'admin.catalog.*',
                'admin.promotions.*',
            ];

            if (!$request->routeIs($pmAllowed)) {
                return response()->view('admin.unauthorized', [], 403);
            }
        }

        // Giới hạn OrderManager chỉ được thao tác đơn hàng
        if ($vaiTro === VaiTro::ORDER_MANAGER) {
            $omAllowed = [
                'admin.dashboard',
                'admin.orders.*',
            ];

            if (!$request->routeIs($omAllowed)) {
                return response()->view('admin.unauthorized', [], 403);
            }
        }

        // Lưu user vào request để sử dụng trong controller
        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}
