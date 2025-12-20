<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
            'HinhAnh' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:HinhAnhSanPham,ID'],
        ];
    }

    public function messages(): array
    {
        return [
            'TenSanPham.required' => 'Tên sản phẩm không được bỏ trống',
            'Gia.required' => 'Giá bán không được bỏ trống',
            'Gia.numeric' => 'Giá bán phải là số',
            'SoLuongTon.required' => 'Số lượng tồn không được bỏ trống',
            'DonViTinh.required' => 'Đơn vị tính không được bỏ trống',
            'IDLoaiSP.required' => 'Vui lòng chọn loại sản phẩm',
            'HinhAnh.image' => 'File phải là hình ảnh',
            'HinhAnh.max' => 'Hình ảnh không được quá 4MB',
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
