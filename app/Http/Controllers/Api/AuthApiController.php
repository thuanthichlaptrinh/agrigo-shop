<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\VaiTro;
use App\Models\Token;
use App\Support\Cart\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthApiController extends Controller
{
    /**
     * Đăng ký tài khoản mới
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'TenNguoiDung' => 'required|string|min:2|max:255',
            'Email' => 'required|email|max:255|unique:NguoiDung,Email',
            'SDT' => ['nullable', 'regex:/^(0|\+84)[0-9]{9,10}$/', 'unique:NguoiDung,SDT'],
            'MatKhau' => 'required|string|min:6|max:255|confirmed',
            'DiaChi' => 'nullable|string|max:500',
            'NgaySinh' => 'nullable|date|before:today',
            'GioiTinh' => 'nullable|in:Nam,Nữ,Khác'
        ], [
            'TenNguoiDung.required' => 'Vui lòng nhập họ và tên',
            'TenNguoiDung.min' => 'Họ và tên phải có ít nhất 2 ký tự',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không hợp lệ',
            'Email.unique' => 'Email đã được sử dụng',
            'SDT.regex' => 'Số điện thoại không hợp lệ (VD: 0912345678)',
            'SDT.unique' => 'Số điện thoại đã được sử dụng',
            'MatKhau.required' => 'Vui lòng nhập mật khẩu',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'MatKhau.confirmed' => 'Xác nhận mật khẩu không khớp',
            'NgaySinh.before' => 'Ngày sinh phải trước ngày hôm nay'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Dữ liệu không hợp lệ', 422, $validator->errors());
        }

        try {
            $userRole = VaiTro::where('TenVaiTro', VaiTro::USER)->first();
            
            if (!$userRole) {
                return $this->errorResponse('Lỗi hệ thống: Không tìm thấy vai trò', 500);
            }

            $user = NguoiDung::create([
                'TenNguoiDung' => trim($request->TenNguoiDung),
                'Email' => strtolower(trim($request->Email)),
                'SDT' => $request->SDT,
                'MatKhau' => Hash::make($request->MatKhau),
                'DiaChi' => $request->DiaChi,
                'NgaySinh' => $request->NgaySinh,
                'GioiTinh' => $request->GioiTinh,
                'TrangThai' => 1,
                'IDVaiTro' => $userRole->ID
            ]);

            // Tạo JWT token
            $token = JWTAuth::fromUser($user);
            
            // Lưu token vào database
            Token::createToken($user->ID, $token, Token::TYPE_JWT, config('jwt.ttl', 60));

            // Log activity 
            if (function_exists('activity_logger')) {
                activity_logger()->logUserAction(
                    $user->ID,
                    'Đăng ký tài khoản qua API',
                    null,
                    ['TenNguoiDung' => $user->TenNguoiDung, 'Email' => $user->Email]
                );
            }

            return $this->successResponse('Đăng ký thành công', [
                'user' => $this->formatUser($user),
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl', 60) * 60
            ], 201);

        } catch (\Exception $e) {
            return $this->errorResponse('Có lỗi xảy ra khi đăng ký', 500, ['detail' => $e->getMessage()]);
        }
    }

    /**
     * Đăng nhập
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|max:255'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Dữ liệu không hợp lệ', 422, $validator->errors());
        }

        $user = NguoiDung::where('Email', $request->email)->first();

        if (!$user) {
            $this->logFailedLogin('Đăng nhập thất bại - email không tồn tại', $request->email);
            return $this->errorResponse('Email không tồn tại', 401);
        }

        if (!$user->TrangThai) {
            $this->logFailedLogin('Đăng nhập thất bại - tài khoản bị khóa', $request->email, $user->ID);
            return $this->errorResponse('Tài khoản đã bị khóa', 403);
        }

        if (!Hash::check($request->password, $user->MatKhau)) {
            $this->logFailedLogin('Đăng nhập thất bại - sai mật khẩu', $request->email, $user->ID);
            return $this->errorResponse('Mật khẩu không đúng', 401);
        }

        try {
            $token = JWTAuth::fromUser($user);
            
            // Lưu token vào database
            Token::createToken($user->ID, $token, Token::TYPE_JWT, config('jwt.ttl', 60));

            // Đăng nhập session (cho web)
            Auth::login($user);
            $request->session()->regenerate();
            session(['jwt_token' => $token, 'user_id' => $user->ID]);
            
            // Load cart
            app(CartService::class)->count();

            // Log activity
            if (function_exists('activity_logger')) {
                activity_logger()->logUserAction(
                    $user->ID,
                    'Đăng nhập hệ thống qua API',
                    null,
                    [
                        'TenNguoiDung' => $user->TenNguoiDung,
                        'Email' => $user->Email,
                        'VaiTro' => $user->vaiTro->TenVaiTro ?? 'User'
                    ]
                );
            }

            // Xác định redirect URL theo vai trò
            $vaiTro = $user->vaiTro->TenVaiTro ?? 'User';
            $redirectUrl = match($vaiTro) {
                VaiTro::ADMIN => route('admin.dashboard'),
                VaiTro::PRODUCT_MANAGER => route('admin.products.index'),
                VaiTro::ORDER_MANAGER => route('admin.dashboard'),
                default => route('user.home')
            };

            return $this->successResponse('Đăng nhập thành công', [
                'user' => $this->formatUser($user),
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl', 60) * 60,
                'redirect_url' => $redirectUrl
            ]);

        } catch (JWTException $e) {
            return $this->errorResponse('Lỗi tạo token', 500);
        }
    }

    /**
     * Đăng xuất
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $token = JWTAuth::getToken();
            $user = null;
            
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (\Exception $e) {
                // Token không hợp lệ, tiếp tục logout session
            }
            
            if ($token) {
                try {
                    // Invalidate JWT
                    JWTAuth::invalidate($token);
                    
                    // Xóa token khỏi database
                    $userId = $user->ID ?? null;
                    Token::where('Token', $token->get())
                        ->where('IDNguoiDung', $userId)
                        ->delete();

                    // Thu hồi toàn bộ JWT còn hiệu lực của người dùng
                    if ($userId) {
                        Token::revokeUserTokens($userId, Token::TYPE_JWT);
                    }
                } catch (\Exception $e) {
                    // Bỏ qua lỗi JWT
                }
            }

            // Clear session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Log activity
            if (function_exists('activity_logger') && $user) {
                activity_logger()->logUserAction($user->ID, 'Đăng xuất hệ thống qua API');
            }

            return $this->successResponse('Đăng xuất thành công');

        } catch (\Exception $e) {
            // Vẫn clear session dù có lỗi
            Auth::logout();
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            return $this->successResponse('Đăng xuất thành công');
        }
    }

    /**
     * Refresh token
     * 
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            $user = JWTAuth::setToken($newToken)->authenticate();

            // Cập nhật token trong database
            Token::createToken($user->ID, $newToken, Token::TYPE_JWT, config('jwt.ttl', 60));

            // Cập nhật session
            session(['jwt_token' => $newToken]);

            return $this->successResponse('Token đã được làm mới', [
                'token' => $newToken,
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl', 60) * 60
            ]);

        } catch (JWTException $e) {
            return $this->errorResponse('Không thể làm mới token', 401);
        }
    }

    /**
     * Lấy thông tin user hiện tại
     * 
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return $this->errorResponse('Không tìm thấy người dùng', 404);
            }

            return $this->successResponse('Thông tin người dùng', [
                'user' => $this->formatUser($user)
            ]);

        } catch (JWTException $e) {
            return $this->errorResponse('Token không hợp lệ', 401);
        }
    }

    /**
     * Cập nhật thông tin profile
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            $validator = Validator::make($request->all(), [
                'TenNguoiDung' => 'sometimes|string|min:2|max:255',
                'SDT' => 'nullable|regex:/^(0|\+84)[0-9]{9,10}$/|unique:NguoiDung,SDT,' . $user->ID . ',ID',
                'DiaChi' => 'nullable|string|max:500',
                'NgaySinh' => 'nullable|date|before:today',
                'GioiTinh' => 'nullable|in:Nam,Nữ,Khác',
                'AnhDaiDien' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Dữ liệu không hợp lệ', 422, $validator->errors());
            }

            $oldData = $user->toArray();

            $user->update($request->only([
                'TenNguoiDung', 'SDT', 'DiaChi', 'NgaySinh', 'GioiTinh', 'AnhDaiDien'
            ]));

            // Log activity
            if (function_exists('activity_logger')) {
                activity_logger()->logUserAction(
                    $user->ID,
                    'Cập nhật thông tin cá nhân qua API',
                    json_encode($oldData),
                    $user->toArray()
                );
            }

            return $this->successResponse('Cập nhật thành công', [
                'user' => $this->formatUser($user->fresh())
            ]);

        } catch (JWTException $e) {
            return $this->errorResponse('Token không hợp lệ', 401);
        }
    }

    /**
     * Đổi mật khẩu
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|max:255|confirmed|different:current_password'
            ], [
                'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
                'new_password.required' => 'Vui lòng nhập mật khẩu mới',
                'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
                'new_password.confirmed' => 'Xác nhận mật khẩu không khớp',
                'new_password.different' => 'Mật khẩu mới phải khác mật khẩu hiện tại'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Dữ liệu không hợp lệ', 422, $validator->errors());
            }

            if (!Hash::check($request->current_password, $user->MatKhau)) {
                return $this->errorResponse('Mật khẩu hiện tại không đúng', 400);
            }

            $user->update(['MatKhau' => Hash::make($request->new_password)]);

            // Log activity
            if (function_exists('activity_logger')) {
                activity_logger()->logUserAction($user->ID, 'Đổi mật khẩu qua API');
            }

            return $this->successResponse('Đổi mật khẩu thành công');

        } catch (JWTException $e) {
            return $this->errorResponse('Token không hợp lệ', 401);
        }
    }

    /**
     * Quên mật khẩu - gửi email reset
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:NguoiDung,Email'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email không tồn tại trong hệ thống'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Dữ liệu không hợp lệ', 422, $validator->errors());
        }

        try {
            $user = NguoiDung::where('Email', $request->email)->first();
            
            // Tạo reset token
            $resetToken = Str::random(64);
            
            Token::createToken($user->ID, $resetToken, Token::TYPE_RESET_PASSWORD, 60); // 60 phút

            // TODO: Gửi email với link reset password
            // Mail::to($user->Email)->send(new ResetPasswordMail($resetToken));

            return $this->successResponse('Đã gửi email hướng dẫn đặt lại mật khẩu', [
                'reset_token' => $resetToken // Chỉ trả về trong development
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Có lỗi xảy ra', 500);
        }
    }

    /**
     * Reset mật khẩu
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'password' => 'required|string|min:6|max:255|confirmed'
        ], [
            'token.required' => 'Token không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Dữ liệu không hợp lệ', 422, $validator->errors());
        }

        try {
            $tokenRecord = Token::where('Token', $request->token)
                ->where('Loai', Token::TYPE_RESET_PASSWORD)
                ->where('HetHan', '>', now())
                ->first();

            if (!$tokenRecord) {
                return $this->errorResponse('Token không hợp lệ hoặc đã hết hạn', 400);
            }

            $user = NguoiDung::find($tokenRecord->IDNguoiDung);
            
            if (!$user) {
                return $this->errorResponse('Người dùng không tồn tại', 404);
            }

            $user->update(['MatKhau' => Hash::make($request->password)]);

            // Xóa token đã sử dụng
            $tokenRecord->delete();

            // Log activity
            if (function_exists('activity_logger')) {
                activity_logger()->logUserAction($user->ID, 'Đặt lại mật khẩu qua API');
            }

            return $this->successResponse('Đặt lại mật khẩu thành công');

        } catch (\Exception $e) {
            return $this->errorResponse('Có lỗi xảy ra', 500);
        }
    }

    /**
     * Format user data for response
     */
    protected function formatUser(NguoiDung $user): array
    {
        return [
            'id' => $user->ID,
            'ten' => $user->TenNguoiDung,
            'email' => $user->Email,
            'sdt' => $user->SDT,
            'dia_chi' => $user->DiaChi,
            'ngay_sinh' => $user->NgaySinh,
            'gioi_tinh' => $user->GioiTinh,
            'anh_dai_dien' => $user->AnhDaiDien,
            'vai_tro' => $user->vaiTro->TenVaiTro ?? 'User',
            'trang_thai' => $user->TrangThai,
            'ngay_tao' => $user->NgayTao
        ];
    }

    /**
     * Success response helper
     */
    protected function successResponse(string $message, array $data = [], int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Error response helper
     */
    protected function errorResponse(string $message, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Log failed login attempt
     */
    protected function logFailedLogin(string $action, string $email, ?int $userId = null): void
    {
        if (!function_exists('activity_logger')) {
            return;
        }

        if ($userId) {
            activity_logger()->logUserAction($userId, $action, null, ['email' => $email], 'Thất bại');
        } else {
            activity_logger()->logSystemAction($action, null, ['email' => $email], 'Thất bại');
        }
    }
}
