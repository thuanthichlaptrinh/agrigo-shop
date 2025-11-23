<?php

namespace App\Providers;

use App\Models\DanhMuc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

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
                $user = JWTAuth::setToken($token)->toUser();

                if ($user && $user->TrangThai) {
                    Auth::setUser($user);
                } else {
                    // User không hợp lệ, chỉ xóa jwt_token và user_id
                    session()->forget(['jwt_token', 'user_id']);
                }
            }
        } catch (\Exception $e) {
            // Token không hợp lệ hoặc đã hết hạn, chỉ xóa jwt_token và user_id
            session()->forget(['jwt_token', 'user_id']);
        }

        // Chia sẻ dữ liệu danh mục cho sidebar
        View::composer(['user.partials.sidebar-dropdown', 'user.partials.sidebar'], function ($view) {
            $categories = Cache::remember('sidebar_categories', 300, function () {
                return DanhMuc::query()
                    ->where('TrangThai', 1)
                    ->orderBy('ThuTu')
                    ->with(['loaiSanPham' => function ($query) {
                        $query->where('TrangThai', 1)
                            ->orderBy('TenLoai')
                            ->withCount(['sanPham as active_products_count' => function ($productQuery) {
                                $productQuery->where('TrangThai', 1);
                            }]);
                    }])
                    ->get()
                    ->map(function ($category) {
                        $subcategories = $category->loaiSanPham->map(function ($subCategory) {
                            return [
                                'id' => $subCategory->ID,
                                'name' => $subCategory->TenLoai,
                                'product_count' => (int) ($subCategory->active_products_count ?? 0),
                            ];
                        })->filter(fn ($sub) => !empty($sub['name']))->values();

                        return [
                            'id' => $category->ID,
                            'name' => $category->TenDanhMuc,
                            'product_count' => $subcategories->sum('product_count') ?: 0,
                            'icon' => $category->HinhAnh,
                            'subcategories' => $subcategories,
                        ];
                    })->values();
            });

            $view->with('sidebarCategories', $categories);
        });
    }
}
