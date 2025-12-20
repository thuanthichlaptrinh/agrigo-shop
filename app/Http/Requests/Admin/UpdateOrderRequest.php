<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'TenNguoiNhan' => ['required', 'string', 'max:255'],
            'SDT' => ['required', 'regex:/^(0|\+84)[0-9]{9,10}$/'],
            'DiaChi' => ['required', 'string', 'max:500'],
            'TrangThai' => ['required', 'string', 'in:Chờ xác nhận,Đã xác nhận,Đang giao,Đã giao,Đã hủy'],
            'GhiChu' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'TenNguoiNhan.required' => 'Vui lòng nhập tên người nhận',
            'SDT.required' => 'Vui lòng nhập số điện thoại',
            'SDT.regex' => 'Số điện thoại không hợp lệ',
            'DiaChi.required' => 'Vui lòng nhập địa chỉ',
            'TrangThai.required' => 'Vui lòng chọn trạng thái',
            'TrangThai.in' => 'Trạng thái không hợp lệ',
        ];
    }
}
