<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\VaiTro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng với tìm kiếm, lọc, phân trang
     */
    public function index(Request $request)
    {
        $query = NguoiDung::with('vaiTro');

        // Tìm kiếm theo tên, email, số điện thoại
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('TenNguoiDung', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%")
                  ->orWhere('SDT', 'like', "%{$search}%");
            });
        }

        // Lọc theo vai trò
        if ($request->filled('role')) {
            $query->where('IDVaiTro', $request->role);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('TrangThai', $request->status);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'ID');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $users = $query->paginate(8)->withQueryString();
        $roles = VaiTro::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Hiển thị form thêm người dùng
     */
    public function create()
    {
        $roles = VaiTro::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Lưu người dùng mới
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'TenNguoiDung' => 'required|string|min:2|max:255',
                'Email' => 'required|email|unique:NguoiDung,Email|max:255',
                'MatKhau' => 'required|string|min:6|max:255',
                'SDT' => 'nullable|regex:/^[0-9]{10,11}$/|unique:NguoiDung,SDT',
                'DiaChi' => 'nullable|string|max:500',
                'NgaySinh' => 'nullable|date|before:today',
                'IDVaiTro' => 'required|exists:VaiTro,ID',
                'TrangThai' => 'required|in:0,1',
                'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ], [
                'TenNguoiDung.required' => 'Vui lòng nhập tên người dùng',
                'TenNguoiDung.min' => 'Tên phải có ít nhất 2 ký tự',
                'Email.required' => 'Vui lòng nhập email',
                'Email.email' => 'Email không hợp lệ',
                'Email.unique' => 'Email đã tồn tại',
                'MatKhau.required' => 'Vui lòng nhập mật khẩu',
                'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
                'SDT.regex' => 'Số điện thoại phải có 10-11 chữ số',
                'SDT.unique' => 'Số điện thoại đã tồn tại',
                'NgaySinh.before' => 'Ngày sinh phải trước hôm nay',
                'IDVaiTro.required' => 'Vui lòng chọn vai trò',
                'IDVaiTro.exists' => 'Vai trò không hợp lệ',
                'TrangThai.required' => 'Vui lòng chọn trạng thái'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $avatarPath = $this->uploadAvatar($request);

            $user = NguoiDung::create([
                'TenNguoiDung' => $request->TenNguoiDung,
                'Email' => $request->Email,
                'MatKhau' => Hash::make($request->MatKhau),
                'SDT' => $request->SDT,
                'DiaChi' => $request->DiaChi,
                'NgaySinh' => $request->NgaySinh,
                'IDVaiTro' => $request->IDVaiTro,
                'TrangThai' => $request->TrangThai,
                'HinhAnh' => $avatarPath
            ]);

            $user->load('vaiTro');

            activity_logger()->logAdminAction(
                $this->currentAdminId(),
                'Tạo người dùng',
                null,
                [
                    'user_id' => $user->ID,
                    'TenNguoiDung' => $user->TenNguoiDung,
                    'Email' => $user->Email,
                    'VaiTro' => optional($user->vaiTro)->TenVaiTro,
                    'TrangThai' => $user->TrangThai ? 'Hoạt động' : 'Khóa'
                ]
            );

            return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công!');
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Lấy thông tin người dùng (AJAX)
     */
    public function show($id)
    {
        $user = NguoiDung::with('vaiTro')->findOrFail($id);
        return response()->json([
            'ID' => $user->ID,
            'TenNguoiDung' => $user->TenNguoiDung,
            'Email' => $user->Email,
            'SDT' => $user->SDT,
            'DiaChi' => $user->DiaChi,
            'NgaySinh' => $user->NgaySinh ? $user->NgaySinh->format('d/m/Y') : null,
            'IDVaiTro' => $user->IDVaiTro,
            'TrangThai' => $user->TrangThai,
            'vai_tro' => $user->vaiTro,
            'created_at' => $user->created_at ? $user->created_at->toIso8601String() : null,
            'avatar_url' => $user->HinhAnh ? asset($user->HinhAnh) : null
        ]);
    }

    /**
     * Hiển thị form sửa người dùng (AJAX)
     */
    public function edit($id)
    {
        $user = NguoiDung::findOrFail($id);
        
        // Nếu request là AJAX, trả về JSON
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'ID' => $user->ID,
                'TenNguoiDung' => $user->TenNguoiDung,
                'Email' => $user->Email,
                'SDT' => $user->SDT,
                'DiaChi' => $user->DiaChi,
                'NgaySinh' => $user->NgaySinh ? $user->NgaySinh->format('Y-m-d') : null,
                'IDVaiTro' => (int)$user->IDVaiTro,
                'TrangThai' => (int)$user->TrangThai,
                'avatar_url' => $user->HinhAnh ? asset($user->HinhAnh) : null
            ]);
        }
        
        // Nếu không phải AJAX, trả về view (backward compatibility)
        $roles = VaiTro::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Cập nhật người dùng
     */
    public function update(Request $request, $id)
    {
        $user = NguoiDung::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'TenNguoiDung' => 'required|string|min:2|max:255',
            'Email' => 'required|email|max:255|unique:NguoiDung,Email,' . $id . ',ID',
            'MatKhau' => 'nullable|string|min:6|max:255',
            'SDT' => 'nullable|regex:/^[0-9]{10,11}$/|unique:NguoiDung,SDT,' . $id . ',ID',
            'DiaChi' => 'nullable|string|max:500',
            'NgaySinh' => 'nullable|date|before:today',
            'IDVaiTro' => 'required|exists:VaiTro,ID',
            'TrangThai' => 'required|boolean',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ], [
            'TenNguoiDung.required' => 'Vui lòng nhập tên người dùng',
            'TenNguoiDung.min' => 'Tên phải có ít nhất 2 ký tự',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không hợp lệ',
            'Email.unique' => 'Email đã tồn tại',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'SDT.regex' => 'Số điện thoại phải có 10-11 chữ số',
            'SDT.unique' => 'Số điện thoại đã tồn tại',
            'NgaySinh.before' => 'Ngày sinh phải trước hôm nay',
            'IDVaiTro.required' => 'Vui lòng chọn vai trò',
            'IDVaiTro.exists' => 'Vai trò không hợp lệ',
            'TrangThai.required' => 'Vui lòng chọn trạng thái'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'TenNguoiDung' => $request->TenNguoiDung,
            'Email' => $request->Email,
            'SDT' => $request->SDT,
            'DiaChi' => $request->DiaChi,
            'NgaySinh' => $request->NgaySinh,
            'IDVaiTro' => $request->IDVaiTro,
            'TrangThai' => $request->TrangThai
        ];

        $before = $user->only(['TenNguoiDung', 'Email', 'SDT', 'DiaChi', 'NgaySinh', 'IDVaiTro', 'TrangThai']);

        // Chỉ cập nhật mật khẩu nếu có nhập
        if ($request->filled('MatKhau')) {
            $data['MatKhau'] = Hash::make($request->MatKhau);
        }

        if ($request->hasFile('HinhAnh')) {
            $data['HinhAnh'] = $this->uploadAvatar($request, $user->HinhAnh);
        }

        $user->update($data);
        $user->refresh()->load('vaiTro');

        activity_logger()->logAdminAction(
            $this->currentAdminId(),
            'Cập nhật người dùng',
            array_merge(['user_id' => $user->ID], $before),
            array_merge([
                'user_id' => $user->ID
            ], $user->only(['TenNguoiDung', 'Email', 'SDT', 'DiaChi', 'NgaySinh', 'IDVaiTro', 'TrangThai']))
        );

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    /**
     * Khóa hoặc mở khóa người dùng nhanh.
     */
    public function toggleStatus($id)
    {
        $user = NguoiDung::findOrFail($id);

        if ($user->ID == auth_user()->ID) {
            return back()->with('error', 'Không thể khóa tài khoản của chính bạn!');
        }

        $oldStatus = $user->TrangThai;
        $user->TrangThai = $user->TrangThai ? 0 : 1;
        $user->save();

        $action = $user->TrangThai ? 'Mở khóa tài khoản' : 'Khóa tài khoản';
        activity_logger()->logAdminAction(
            $this->currentAdminId(),
            $action,
            ['user_id' => $user->ID, 'TrangThai' => $oldStatus],
            ['user_id' => $user->ID, 'TrangThai' => $user->TrangThai]
        );

        $message = $user->TrangThai ? 'Mở khóa tài khoản thành công!' : 'Khóa tài khoản thành công!';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'TrangThai' => $user->TrangThai
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    /**
     * Upload avatar to public/uploads/avatars and return relative path.
     */
    protected function uploadAvatar(Request $request, ?string $oldPath = null)
    {
        if (!$request->hasFile('HinhAnh')) {
            return $oldPath;
        }

        $file = $request->file('HinhAnh');
        $destination = public_path('uploads/avatars');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        if ($oldPath && File::exists(public_path($oldPath))) {
            File::delete(public_path($oldPath));
        }

        $filename = 'avatar_' . now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/avatars/' . $filename;
    }

    /**
     * Xóa người dùng
     */
    public function destroy($id)
    {
        $user = NguoiDung::findOrFail($id);
        
        // Không cho xóa chính mình
        if ($user->ID == auth_user()->ID) {
            return back()->with('error', 'Không thể xóa tài khoản của chính bạn!');
        }

        $snapshot = $user->only(['TenNguoiDung', 'Email', 'SDT', 'DiaChi', 'NgaySinh', 'IDVaiTro', 'TrangThai']);
        $user->delete();

        activity_logger()->logAdminAction(
            $this->currentAdminId(),
            'Xóa người dùng',
            array_merge(['user_id' => $user->ID], $snapshot),
            null
        );

        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công!');
    }

    protected function currentAdminId(): ?int
    {
        $user = function_exists('auth_user') ? auth_user() : auth()->user();
        return optional($user)->ID;
    }
}
