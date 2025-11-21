<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhuyenMai extends Model
{
    protected $table = 'KhuyenMai';
    protected $primaryKey = 'ID';
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'TenKhuyenMai',
        'MoTa',
        'LoaiKhuyenMai',
        'GiaTriGiam',
        'GiamToiDa',
        'NgayBatDau',
        'NgayKetThuc',
        'TrangThai'
    ];

    protected $casts = [
        'GiaTriGiam' => 'decimal:2',
        'GiamToiDa' => 'decimal:2',
        'TrangThai' => 'boolean',
        'NgayBatDau' => 'datetime',
        'NgayKetThuc' => 'datetime',
        'NgayTao' => 'datetime',
        'NgayCapNhat' => 'datetime'
    ];

    // Relationships
    public function sanPham()
    {
        return $this->belongsToMany(SanPham::class, 'SanPhamKhuyenMai', 'IDKhuyenMai', 'IDSanPham')
                    ->withPivot('GhiChu', 'NgayTao');
    }

    // Helper methods
    public function isActive()
    {
        return $this->TrangThai && 
               now()->between($this->NgayBatDau, $this->NgayKetThuc);
    }
}
