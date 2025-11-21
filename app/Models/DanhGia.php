<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    protected $table = 'DanhGia';
    protected $primaryKey = 'ID';
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = null;

    protected $fillable = [
        'IDSanPham',
        'IDNguoiDung',
        'SoSao',
        'NoiDung',
        'HinhAnh',
        'TrangThai'
    ];

    protected $casts = [
        'SoSao' => 'integer',
        'NgayTao' => 'datetime'
    ];

    // Relationships
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'IDSanPham', 'ID');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'IDNguoiDung', 'ID');
    }

    // Helper methods
    public function isApproved()
    {
        return $this->TrangThai === 'Đã duyệt';
    }
}
