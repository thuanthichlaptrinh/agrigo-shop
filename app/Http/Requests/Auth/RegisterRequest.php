<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'TenNguoiDung' => ['required', 'string', 'min:2', 'max:255'],
            'Email' => ['required', 'email', 'max:255', 'unique:NguoiDung,Email'],
            'SDT' => ['nullable', 'regex:/^(0|\+84)[0-9]{9,10}$/', 'unique:NguoiDung,SDT'],
            'MatKhau' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
            'DiaChi' => ['nullable', 'string', 'max:500'],
            'NgaySinh' => ['nullable', 'date', 'before:today'],
            'GioiTinh' => ['nullable', 'in:Nam,Nữ,Khác'],
        ];
    }

    public function messages(): array
    {
        return [
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
            'NgaySinh.before' => 'Ngày sinh phải trước ngày hôm nay',
        ];
    }
}
