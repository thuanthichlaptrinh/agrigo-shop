<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Middleware đã kiểm tra quyền admin
    }

    public function rules(): array
    {
        return [
            'TenSanPham' => ['required', 'string', 'max:255'],
            'MoTa' => ['nullable', 'string'],
            'Gia' => ['required', 'numeric', 'min:0'],
            'SoLuongTon' => ['required', 'integer', 'min:0'],
            'DonViTinh' => ['required', 'string', 'max:30'],
            'XuatXu' => ['nullable', 'string', 'max:100'],
            'HanSuDung' => ['nullable', 'date'],
            'NoiBat' => ['sometimes', 'boolean'],
            'TrangThai' => ['sometimes', 'boolean'],
            'IDLoaiSP' => ['required', 'exists:LoaiSanPham,ID'],
            'IDNhaCungCap' => ['nullable', 'exists:NhaCungCap,ID'],
            'HinhAnh' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'TenSanPham.required' => 'Tên sản phẩm không được bỏ trống',
            'TenSanPham.max' => 'Tên sản phẩm không được quá 255 ký tự',
            'Gia.required' => 'Giá bán không được bỏ trống',
            'Gia.numeric' => 'Giá bán phải là số',
            'Gia.min' => 'Giá bán không được âm',
            'SoLuongTon.required' => 'Số lượng tồn không được bỏ trống',
            'SoLuongTon.integer' => 'Số lượng tồn phải là số nguyên',
            'SoLuongTon.min' => 'Số lượng tồn không được âm',
            'DonViTinh.required' => 'Đơn vị tính không được bỏ trống',
            'IDLoaiSP.required' => 'Vui lòng chọn loại sản phẩm',
            'IDLoaiSP.exists' => 'Loại sản phẩm không tồn tại',
            'IDNhaCungCap.exists' => 'Nhà cung cấp không tồn tại',
            'HinhAnh.required' => 'Vui lòng chọn hình ảnh cho sản phẩm',
            'HinhAnh.image' => 'File phải là hình ảnh',
            'HinhAnh.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
            'HinhAnh.max' => 'Hình ảnh không được quá 4MB',
            'gallery.*.image' => 'File gallery phải là hình ảnh',
            'gallery.*.mimes' => 'Hình ảnh gallery phải có định dạng: jpeg, png, jpg, gif, webp',
            'gallery.*.max' => 'Hình ảnh gallery không được quá 4MB',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'NoiBat' => $this->boolean('NoiBat'),
            'TrangThai' => $this->boolean('TrangThai'),
        ]);
    }
}
