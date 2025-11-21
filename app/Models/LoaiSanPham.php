<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiSanPham extends Model
{
    protected $table = 'LoaiSanPham';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'TenLoai',
        'IDDanhMuc',
        'TrangThai'
    ];

    protected $casts = [
        'TrangThai' => 'boolean'
    ];

    // Relationships
    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'IDDanhMuc', 'ID');
    }

    public function sanPham()
    {
        return $this->hasMany(SanPham::class, 'IDLoaiSP', 'ID');
    }
}
