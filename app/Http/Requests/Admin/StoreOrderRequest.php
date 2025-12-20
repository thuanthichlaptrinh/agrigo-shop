<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'IDNguoiDung' => ['required', 'exists:NguoiDung,ID'],
            'TenNguoiNhan' => ['required', 'string', 'max:255'],
            'SDT' => ['required', 'regex:/^(0|\+84)[0-9]{9,10}$/'],
            'DiaChi' => ['required', 'string', 'max:500'],
            'PhuongThucTT' => ['required', 'string'],
            'PhiVanChuyen' => ['required', 'numeric', 'min:0'],
            'IDVoucher' => ['nullable', 'exists:Voucher,ID'],
            'GhiChu' => ['nullable', 'string', 'max:1000'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.IDSanPham' => ['required', 'exists:SanPham,ID'],
            'products.*.SoLuong' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'IDNguoiDung.required' => 'Vui lòng chọn khách hàng',
            'IDNguoiDung.exists' => 'Khách hàng không tồn tại',
            'TenNguoiNhan.required' => 'Vui lòng nhập tên người nhận',
            'SDT.required' => 'Vui lòng nhập số điện thoại',
            'SDT.regex' => 'Số điện thoại không hợp lệ (VD: 0912345678)',
            'DiaChi.required' => 'Vui lòng nhập địa chỉ',
            'PhuongThucTT.required' => 'Vui lòng chọn phương thức thanh toán',
            'PhiVanChuyen.required' => 'Vui lòng nhập phí vận chuyển',
            'PhiVanChuyen.min' => 'Phí vận chuyển không được âm',
            'products.required' => 'Vui lòng thêm ít nhất 1 sản phẩm',
            'products.min' => 'Vui lòng thêm ít nhất 1 sản phẩm',
            'products.*.IDSanPham.required' => 'Vui lòng chọn sản phẩm',
            'products.*.IDSanPham.exists' => 'Sản phẩm không tồn tại',
            'products.*.SoLuong.required' => 'Vui lòng nhập số lượng',
            'products.*.SoLuong.min' => 'Số lượng phải lớn hơn 0',
        ];
    }
}
