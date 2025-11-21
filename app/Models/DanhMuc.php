<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    protected $table = 'DanhMuc';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'TenDanhMuc',
        'HinhAnh',
        'ThuTu',
        'TrangThai'
    ];

    protected $casts = [
        'ThuTu' => 'integer',
        'TrangThai' => 'boolean'
    ];

    // Relationships
    public function loaiSanPham()
    {
        return $this->hasMany(LoaiSanPham::class, 'IDDanhMuc', 'ID');
    }
}
