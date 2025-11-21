<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietDonHang extends Model
{
    protected $table = 'ChiTietDonHang';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'IDDonHang',
        'IDSanPham',
        'TenSanPham',
        'SoLuong',
        'DonGia'
    ];

    protected $casts = [
        'SoLuong' => 'integer',
        'DonGia' => 'decimal:2',
        'ThanhTien' => 'decimal:2'
    ];

    // Relationships
    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'IDDonHang', 'ID');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'IDSanPham', 'ID');
    }

    // ThanhTien is a computed column in database (SoLuong * DonGia)
    public function getThanhTienAttribute($value)
    {
        return $value ?? ($this->SoLuong * $this->DonGia);
    }
}
