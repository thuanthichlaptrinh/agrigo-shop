<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class NguoiDung extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'NguoiDung';
    protected $primaryKey = 'ID';
    
    protected $fillable = [
        'TenNguoiDung',
        'Email',
        'SDT',
        'MatKhau',
        'DiaChi',
        'NgaySinh',
        'GioiTinh',
        'HinhAnh',
        'TrangThai',
        'IDVaiTro'
    ];

    protected $hidden = [
        'MatKhau',
        'remember_token',
    ];

    protected $casts = [
        'NgaySinh' => 'date',
        'TrangThai' => 'boolean',
        'NgayTao' => 'datetime',
        'NgayCapNhat' => 'datetime',
    ];

    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    // Override password field for authentication
    public function getAuthPassword()
    {
        return $this->MatKhau;
    }

    // JWT Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'email' => $this->Email,
            'ten' => $this->TenNguoiDung,
            'vai_tro' => $this->vaiTro->TenVaiTro ?? 'User'
        ];
    }

    // Relationships
    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'IDVaiTro', 'ID');
    }

    public function tokens()
    {
        return $this->hasMany(Token::class, 'IDNguoiDung', 'ID');
    }

    public function donHang()
    {
        return $this->hasMany(DonHang::class, 'IDNguoiDung', 'ID');
    }

    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'IDNguoiDung', 'ID');
    }

    public function gioHang()
    {
        return $this->hasMany(GioHang::class, 'IDNguoiDung', 'ID');
    }

    public function hoatDong()
    {
        return $this->hasMany(HoatDongNguoiDung::class, 'IDNguoiDung', 'ID');
    }

    public function nhatKy()
    {
        return $this->hasMany(NhatKy::class, 'IDNguoiDung', 'ID');
    }

    public function thongBao()
    {
        return $this->hasMany(ThongBao::class, 'IDNguoiDung', 'ID');
    }

    // Helper methods for role checking
    public function isAdmin()
    {
        return $this->vaiTro && $this->vaiTro->TenVaiTro === VaiTro::ADMIN;
    }

    public function isProductManager()
    {
        return $this->vaiTro && $this->vaiTro->TenVaiTro === VaiTro::PRODUCT_MANAGER;
    }

    public function isOrderManager()
    {
        return $this->vaiTro && $this->vaiTro->TenVaiTro === VaiTro::ORDER_MANAGER;
    }

    public function hasRole($roleName)
    {
        return $this->vaiTro && $this->vaiTro->TenVaiTro === $roleName;
    }
}
