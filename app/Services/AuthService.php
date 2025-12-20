<?php

namespace App\Services;

use App\Models\NguoiDung;
use App\Models\Token;
use App\Models\VaiTro;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Đăng nhập user
     */
    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'error' => 'email',
                'message' => 'Email không tồn tại'
            ];
        }

        if (!$user->TrangThai) {
            return [
                'success' => false,
                'error' => 'email',
                'message' => 'Tài khoản đã bị khóa'
            ];
        }

        if (!Hash::check($password, $user->MatKhau)) {
            return [
                'success' => false,
                'error' => 'password',
                'message' => 'Mật khẩu không đúng'
            ];
        }

        try {
            // Đăng nhập qua session
            Auth::login($user);
            request()->session()->regenerate();

            // Tạo JWT token
            $token = JWTAuth::fromUser($user);
            
            // Lưu token vào database
            Token::createToken(
                $user->ID,
                $token,
                Token::TYPE_JWT,
                config('jwt.ttl', 60)
            );

            // Lưu vào session
            session(['jwt_token' => $token, 'user_id' => $user->ID]);

            return [
                'success' => true,
                'user' => $user,
                'token' => $token,
                'redirect' => $this->getRedirectRoute($user)
            ];

        } catch (JWTException $e) {
            return [
                'success' => false,
                'error' => 'system',
                'message' => 'Lỗi hệ thống'
            ];
        }
    }

    /**
     * Đăng ký user mới
     */
    public function register(array $data): array
    {
        try {
            $userRole = VaiTro::where('TenVaiTro', VaiTro::USER)->first();
            
            if (!$userRole) {
                return [
                    'success' => false,
                    'error' => 'system',
                    'message' => 'Lỗi hệ thống'
                ];
            }

            $user = $this->userRepository->create([
                'TenNguoiDung' => trim($data['TenNguoiDung']),
                'Email' => strtolower(trim($data['Email'])),
                'SDT' => $data['SDT'] ?? null,
                'MatKhau' => Hash::make($data['MatKhau']),
                'DiaChi' => $data['DiaChi'] ?? null,
                'NgaySinh' => $data['NgaySinh'] ?? null,
                'GioiTinh' => $data['GioiTinh'] ?? null,
                'TrangThai' => 1,
                'IDVaiTro' => $userRole->ID
            ]);

            // Đăng nhập luôn sau khi đăng ký
            Auth::login($user);
            request()->session()->regenerate();

            $token = JWTAuth::fromUser($user);
            Token::createToken($user->ID, $token, Token::TYPE_JWT, config('jwt.ttl', 60));

            session(['jwt_token' => $token, 'user_id' => $user->ID]);

            return [
                'success' => true,
                'user' => $user,
                'token' => $token
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'system',
                'message' => 'Có lỗi xảy ra'
            ];
        }
    }

    /**
     * Đăng xuất
     */
    public function logout(): void
    {
        $token = session('jwt_token');
        $userId = session('user_id');

        if ($token) {
            try {
                JWTAuth::setToken($token)->invalidate();
            } catch (\Exception $e) {
                // Token đã hết hạn
            }

            Token::where('Token', $token)
                ->where('IDNguoiDung', $userId)
                ->delete();

            if ($userId) {
                Token::revokeUserTokens($userId, Token::TYPE_JWT);
            }
        }

        Auth::logout();
        
        request()->session()->flush();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * Lấy route redirect theo vai trò
     */
    protected function getRedirectRoute(NguoiDung $user): string
    {
        $vaiTro = $user->vaiTro->TenVaiTro ?? 'User';

        return match ($vaiTro) {
            VaiTro::ADMIN => 'admin.dashboard',
            VaiTro::PRODUCT_MANAGER => 'admin.products.index',
            VaiTro::ORDER_MANAGER => 'admin.dashboard',
            default => 'user.home'
        };
    }

    /**
     * Kiểm tra email đã tồn tại
     */
    public function emailExists(string $email): bool
    {
        return $this->userRepository->findByEmail($email) !== null;
    }

    /**
     * Kiểm tra số điện thoại đã tồn tại
     */
    public function phoneExists(string $phone): bool
    {
        return $this->userRepository->findByPhone($phone) !== null;
    }
}
