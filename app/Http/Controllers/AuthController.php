<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use App\Models\VaiTro;
use App\Models\Token;
use App\Support\Cart\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm(Request $request)
    {
        // Xóa thông tin đăng nhập cũ khi vào trang login
        session()->forget(['jwt_token', 'user_id']);
        \Illuminate\Support\Facades\Auth::logout();
        
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập với JWT Token
     */
    public function login(Request $request)
    {
        // Validation với các quy tắc bảo mật
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
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        // Tìm user theo email
        $user = NguoiDung::where('Email', $request->email)->first();

        if (!$user) {
            activity_logger()->logSystemAction(
                'Đăng nhập thất bại - không tồn tại email',
                null,
                ['email' => $request->email],
                'Thất bại'
            );

            return back()->withErrors(['email' => 'Email không tồn tại'])->withInput($request->only('email'));
        }

        // Kiểm tra tài khoản có bị khóa không
        if (!$user->TrangThai) {
            activity_logger()->logUserAction(
                $user->ID,
                'Đăng nhập thất bại - tài khoản bị khóa',
                null,
                ['email' => $user->Email],
                'Thất bại'
            );

            return back()->withErrors(['email' => 'Tài khoản đã bị khóa'])->withInput($request->only('email'));
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($request->password, $user->MatKhau)) {
            activity_logger()->logUserAction(
                $user->ID,
                'Đăng nhập thất bại - sai mật khẩu',
                null,
                ['email' => $user->Email],
                'Thất bại'
            );

            return back()->withErrors(['password' => 'Mật khẩu không đúng'])->withInput($request->only('email'));
        }

        try {
            // Đăng nhập qua session guard để middleware auth nhận diện
            Auth::login($user);
            $request->session()->regenerate();

            // Tạo JWT token
            $token = JWTAuth::fromUser($user);
            
            // Lưu token vào database
            Token::createToken(
                $user->ID, 
                $token, 
                Token::TYPE_JWT, 
                config('jwt.ttl', 60) // Lấy từ config jwt
            );
            
            // Lưu token vào session
            session(['jwt_token' => $token]);
            session(['user_id' => $user->ID]);

            app(CartService::class)->count();

            activity_logger()->logUserAction(
                $user->ID,
                'Đăng nhập hệ thống',
                null,
                [
                    'TenNguoiDung' => $user->TenNguoiDung,
                    'Email' => $user->Email,
                    'VaiTro' => $user->vaiTro->TenVaiTro ?? 'User'
                ]
            );

            // Redirect theo vai trò
            $vaiTro = $user->vaiTro->TenVaiTro ?? 'User';
            
            if ($vaiTro === VaiTro::ADMIN) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
            } elseif ($vaiTro === VaiTro::PRODUCT_MANAGER) {
                return redirect()->route('admin.products.index')->with('success', 'Đăng nhập thành công!');
            } elseif ($vaiTro === VaiTro::ORDER_MANAGER) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
            }

            return redirect()->route('user.home')->with('success', 'Đăng nhập thành công!');

        } catch (JWTException $e) {
            activity_logger()->logSystemAction(
                'Đăng nhập thất bại - lỗi JWT',
                null,
                ['message' => $e->getMessage()],
                'Thất bại'
            );

            return back()->withErrors(['email' => 'Lỗi hệ thống'])->withInput($request->only('email'));
        }
    }

    // Hiển thị form đăng ký
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký với validation mạnh
     */
    public function register(Request $request)
    {
        // Validation chi tiết
        $validator = Validator::make($request->all(), [
            'TenNguoiDung' => [
                'required',
                'string',
                'min:2',
                'max:255'
            ],
            'Email' => [
                'required',
                'email',
                'max:255',
                'unique:NguoiDung,Email'
            ],
            'SDT' => [
                'nullable',
                'regex:/^(0|\+84)[0-9]{9,10}$/',
                'unique:NguoiDung,SDT'
            ],
            'MatKhau' => [
                'required',
                'string',
                'min:6',
                'max:255',
                'confirmed'
            ],
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
            return back()->withErrors($validator)->withInput($request->except('MatKhau', 'MatKhau_confirmation'));
        }

        try {
            // Lấy ID vai trò User
            $userRole = VaiTro::where('TenVaiTro', VaiTro::USER)->first();
            
            if (!$userRole) {
                return back()->withErrors(['email' => 'Lỗi hệ thống']);
            }

            // Tạo user mới
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

            activity_logger()->logUserAction(
                $user->ID,
                'Đăng ký tài khoản',
                null,
                [
                    'TenNguoiDung' => $user->TenNguoiDung,
                    'Email' => $user->Email
                ]
            );

            // Đăng nhập luôn sau khi đăng ký
            Auth::login($user);
            $request->session()->regenerate();

            // Tạo JWT token
            $token = JWTAuth::fromUser($user);
            
            // Lưu token vào database
            Token::createToken($user->ID, $token, Token::TYPE_JWT, config('jwt.ttl', 60));
            
            session(['jwt_token' => $token]);
            session(['user_id' => $user->ID]);

            app(CartService::class)->count();

            return redirect()->route('user.home')->with('success', 'Đăng ký thành công!');

        } catch (\Exception $e) {
            activity_logger()->logSystemAction(
                'Đăng ký thất bại',
                null,
                ['message' => $e->getMessage(), 'email' => $request->Email],
                'Thất bại'
            );

            return back()->withErrors(['email' => 'Có lỗi xảy ra'])->withInput($request->except('MatKhau', 'MatKhau_confirmation'));
        }
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        $currentUser = auth()->user();
        if (!$currentUser && function_exists('auth_user')) {
            $currentUser = auth_user();
        }
        $currentUserId = optional($currentUser)->ID;

        try {
            $token = session('jwt_token');
            $userId = session('user_id');
            
            // Invalidate JWT token
            if ($token) {
                try {
                    JWTAuth::setToken($token)->invalidate();
                } catch (\Exception $e) {
                    // Token đã hết hạn hoặc không hợp lệ
                }
                
                // Xóa token khỏi database
                Token::where('Token', $token)
                    ->where('IDNguoiDung', $userId)
                    ->delete();

                // Thu hồi toàn bộ JWT còn hiệu lực của người dùng
                if ($userId) {
                    Token::revokeUserTokens($userId, Token::TYPE_JWT);
                }
            }
            
            // Xóa Auth facade
            \Illuminate\Support\Facades\Auth::logout();
            
            // Lấy tên session và ID trước khi xóa
            $sessionName = $request->session()->getName();
            $sessionId = $request->session()->getId();
            
            if ($currentUserId) {
                activity_logger()->logUserAction($currentUserId, 'Đăng xuất hệ thống');
            }

            // Xóa tất cả session data (bao gồm flash messages)
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Xóa session file trong storage
            if ($sessionId) {
                $sessionPath = storage_path('framework/sessions/' . $sessionId);
                if (file_exists($sessionPath)) {
                    @unlink($sessionPath);
                }
            }
            
            // Xóa cookie session
            $cookie = \Cookie::forget($sessionName);

            return redirect()->route('login')->withCookie($cookie);
        } catch (\Exception $e) {
            if ($currentUserId) {
                activity_logger()->logUserAction(
                    $currentUserId,
                    'Đăng xuất hệ thống - lỗi',
                    null,
                    ['message' => $e->getMessage()],
                    'Thất bại'
                );
            } else {
                activity_logger()->logSystemAction(
                    'Đăng xuất hệ thống - lỗi',
                    null,
                    ['message' => $e->getMessage()],
                    'Thất bại'
                );
            }

            // Xóa session dù có lỗi
            $sessionName = $request->session()->getName();
            $sessionId = $request->session()->getId();
            
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Xóa session file trong storage
            if ($sessionId) {
                $sessionPath = storage_path('framework/sessions/' . $sessionId);
                if (file_exists($sessionPath)) {
                    @unlink($sessionPath);
                }
            }
            
            // Xóa cookie session
            $cookie = \Cookie::forget($sessionName);
            
            return redirect()->route('login')->withCookie($cookie);
        }
    }

    // Hiển thị form quên mật khẩu
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Xử lý quên mật khẩu (gửi email reset)
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:NguoiDung,Email'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email không tồn tại trong hệ thống'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = NguoiDung::where('Email', $request->email)->first();
        $token = Str::random(60);

        // Lưu token vào database
        Token::createToken($user->ID, $token, Token::TYPE_RESET_PASSWORD, 60);

        // Gửi email
        try {
            Mail::to($user->Email)->send(new ResetPasswordMail($user, $token));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Không thể gửi email. Vui lòng thử lại sau.']);
        }

        return back()->with('success', 'Link đặt lại mật khẩu đã được gửi đến email của bạn');
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Xử lý đặt lại mật khẩu
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:NguoiDung,Email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email không tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Kiểm tra token
        $user = NguoiDung::where('Email', $request->email)->first();
        
        $tokenRecord = Token::where('Token', $request->token)
            ->where('IDNguoiDung', $user->ID)
            ->where('Loai', Token::TYPE_RESET_PASSWORD)
            ->where('HetHan', '>', now())
            ->first();

        if (!$tokenRecord) {
            return back()->withErrors(['email' => 'Token không hợp lệ hoặc đã hết hạn']);
        }

        // Cập nhật mật khẩu
        $user->MatKhau = Hash::make($request->password);
        $user->save();

        // Xóa token đã sử dụng
        $tokenRecord->delete();

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công');
    }
}
