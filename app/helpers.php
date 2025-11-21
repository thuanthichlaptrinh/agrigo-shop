<?php

use App\Models\NguoiDung;
use Tymon\JWTAuth\Facades\JWTAuth;

if (!function_exists('auth_user')) {
    /**
     * Lấy thông tin user đã đăng nhập
     * 
     * @return NguoiDung|null
     */
    function auth_user()
    {
        try {
            $token = session('jwt_token');
            
            if (!$token) {
                return null;
            }

            return JWTAuth::setToken($token)->toUser();
        } catch (\Exception $e) {
            return null;
        }
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
