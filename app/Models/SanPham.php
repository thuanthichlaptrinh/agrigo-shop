<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    protected $table = 'SanPham';
    protected $primaryKey = 'ID';
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'TenSanPham',
        'MoTa',
        'Gia',
        'SoLuongTon',
        'DonViTinh',
        'HinhAnh',
        'XuatXu',
        'HanSuDung',
        'LuotXem',
        'LuotBan',
        'DanhGiaTB',
        'NoiBat',
        'TrangThai',
        'IDLoaiSP',
        'IDNhaCungCap'
    ];

    protected $casts = [
        'Gia' => 'decimal:2',
        'SoLuongTon' => 'integer',
        'LuotXem' => 'integer',
        'LuotBan' => 'integer',
        'DanhGiaTB' => 'decimal:1',
        'NoiBat' => 'boolean',
        'TrangThai' => 'boolean',
        'HanSuDung' => 'date',
        'NgayTao' => 'datetime',
        'NgayCapNhat' => 'datetime'
    ];

    // Relationships
    public function loaiSanPham()
    {
        return $this->belongsTo(LoaiSanPham::class, 'IDLoaiSP', 'ID');
    }

    public function nhaCungCap()
    {
        return $this->belongsTo(NhaCungCap::class, 'IDNhaCungCap', 'ID');
    }

    public function hinhAnh()
    {
        return $this->hasMany(HinhAnhSanPham::class, 'IDSanPham', 'ID');
    }

    public function khuyenMai()
    {
        return $this->belongsToMany(KhuyenMai::class, 'SanPhamKhuyenMai', 'IDSanPham', 'IDKhuyenMai')
                    ->withPivot('GhiChu', 'NgayTao');
    }

    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'IDSanPham', 'ID');
    }

    public function gioHang()
    {
        return $this->hasMany(GioHang::class, 'IDSanPham', 'ID');
    }

    public function chiTietDonHang()
    {
        return $this->hasMany(ChiTietDonHang::class, 'IDSanPham', 'ID');
    }

    public function hoatDongNguoiDung()
    {
        return $this->hasMany(HoatDongNguoiDung::class, 'IDSanPham', 'ID');
    }
}
