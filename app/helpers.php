<?php

use App\Models\NguoiDung;
use App\Models\SanPham;
use App\Models\KhuyenMai;
use App\Models\SanPhamKhuyenMai;
use App\Support\Auth\JwtSessionManager;
use Illuminate\Support\Str;

if (!function_exists('auth_user')) {
    /**
     * Lấy thông tin user đã đăng nhập
     * 
     * @return NguoiDung|null
     */
    function auth_user()
    {
        static $cachedUser = null;
        static $cachedToken = null;

        $token = session('jwt_token');

        if (!$token) {
            $cachedUser = null;
            $cachedToken = null;
            return null;
        }

        if ($cachedUser && $cachedToken === $token) {
            return $cachedUser;
        }

        try {
            /** @var JwtSessionManager $manager */
            $manager = app(JwtSessionManager::class);
            $user = $manager->resolveUser($token);
        } catch (\Exception $e) {
            $user = null;
        }

        if ($user) {
            $cachedUser = $user;
            $cachedToken = session('jwt_token');
        } else {
            $cachedUser = null;
            $cachedToken = null;
        }

        return $user;
    }
}

if (!function_exists('is_admin')) {
    /**
     * Kiểm tra user hiện tại có phải Admin không
     * 
     * @return bool
     */
    function is_admin()
    {
        $user = auth_user();
        return $user && $user->isAdmin();
    }
}

if (!function_exists('is_product_manager')) {
    /**
     * Kiểm tra user hiện tại có phải Product Manager không
     * 
     * @return bool
     */
    function is_product_manager()
    {
        $user = auth_user();
        return $user && $user->isProductManager();
    }
}

if (!function_exists('is_order_manager')) {
    /**
     * Kiểm tra user hiện tại có phải Order Manager không
     * 
     * @return bool
     */
    function is_order_manager()
    {
        $user = auth_user();
        return $user && $user->isOrderManager();
    }
}

if (!function_exists('is_logged_in')) {
    /**
     * Kiểm tra user đã đăng nhập chưa
     * 
     * @return bool
     */
    function is_logged_in()
    {
        return auth_user() !== null;
    }
}

if (!function_exists('can_access_admin')) {
    /**
     * Kiểm tra user có thể truy cập khu vực admin không
     * 
     * @return bool
     */
    function can_access_admin()
    {
        return is_admin() || is_product_manager() || is_order_manager();
    }
}

if (!function_exists('calculate_promotion_pricing')) {
    function calculate_promotion_pricing(?SanPham $product, ?KhuyenMai $promotion): ?array
    {
        if (!$product || !$promotion) {
            return null;
        }

        $original = (float) ($product->Gia ?? 0);
        $typeKey = Str::lower(Str::ascii((string) $promotion->LoaiKhuyenMai));
        $isPercent = Str::contains($typeKey, 'phan tram');

        $discount = 0;
        if ($isPercent) {
            $discount = $original * ((float) $promotion->GiaTriGiam / 100);
            if ($promotion->GiamToiDa) {
                $discount = min($discount, (float) $promotion->GiamToiDa);
            }
        } else {
            $discount = (float) $promotion->GiaTriGiam;
        }

        $final = max(0, $original - $discount);
        $percent = $original > 0 ? round(($discount / $original) * 100, 1) : 0;

        $discountText = $isPercent
            ? ('Giảm ' . rtrim(rtrim(number_format((float) $promotion->GiaTriGiam, 2, '.', ''), '0'), '.') . '%'
                . ($promotion->GiamToiDa ? ' (tối đa ' . number_format((float) $promotion->GiamToiDa, 0, ',', '.') . ' đ)' : ''))
            : 'Giảm ' . number_format((float) $promotion->GiaTriGiam, 0, ',', '.') . ' đ';

        return [
            'original_price' => round($original, 2),
            'final_price' => round($final, 2),
            'discount_amount' => round($discount, 2),
            'discount_percent' => $percent,
            'discount_text' => $discountText,
            'is_percent' => $isPercent,
        ];
    }
}

if (!function_exists('format_promoted_product')) {
    function format_promoted_product(SanPhamKhuyenMai $pivot): ?array
    {
        $product = $pivot->sanPham;
        $promotion = $pivot->khuyenMai;

        if (!$product || !$promotion || !$product->TrangThai) {
            return null;
        }

        $pricing = calculate_promotion_pricing($product, $promotion);
        if (!$pricing) {
            return null;
        }

        return array_merge($pricing, [
            'id' => $product->ID,
            'name' => $product->TenSanPham,
            'image' => $product->HinhAnh,
            'unit' => $product->DonViTinh ?? 'Gói',
            'promotion_name' => $promotion->TenKhuyenMai,
            'promotion_note' => $pivot->GhiChu,
            'promotion_start' => optional($promotion->NgayBatDau)->toDateTimeString(),
            'promotion_end' => optional($promotion->NgayKetThuc)->toDateTimeString(),
            'stock' => $product->SoLuongTon,
        ]);
    }
}

if (!function_exists('product_image_url')) {
    function product_image_url(?string $path): string
    {
        if (empty($path)) {
            return asset('template/Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp');
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}
