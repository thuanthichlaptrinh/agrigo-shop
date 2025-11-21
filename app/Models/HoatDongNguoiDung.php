<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoatDongNguoiDung extends Model
{
    protected $table = 'HoatDongNguoiDung';
    protected $primaryKey = 'ID';
    const CREATED_AT = 'Ngay';
    const UPDATED_AT = null;

    protected $fillable = [
        'IDNguoiDung',
        'Loai',
        'TuKhoa',
        'IDSanPham'
    ];

    protected $casts = [
        'Ngay' => 'datetime'
    ];

    // Relationships
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'IDNguoiDung', 'ID');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'IDSanPham', 'ID');
    }

    // Helper methods to log activities
    public static function logTimKiem($idNguoiDung, $tuKhoa)
    {
        return static::create([
            'IDNguoiDung' => $idNguoiDung,
            'Loai' => 'Tìm kiếm',
            'TuKhoa' => $tuKhoa
        ]);
    }

    public static function logYeuThich($idNguoiDung, $idSanPham)
    {
        return static::updateOrCreate(
            [
                'IDNguoiDung' => $idNguoiDung,
                'IDSanPham' => $idSanPham,
                'Loai' => 'Yêu thích'
            ]
        );
    }

    public static function logXemSanPham($idNguoiDung, $idSanPham)
    {
        return static::create([
            'IDNguoiDung' => $idNguoiDung,
            'Loai' => 'Xem sản phẩm',
            'IDSanPham' => $idSanPham
        ]);
    }
}
