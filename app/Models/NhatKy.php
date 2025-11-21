<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhatKy extends Model
{
    protected $table = 'NhatKy';
    protected $primaryKey = 'ID';
    const CREATED_AT = 'ThoiGian';
    const UPDATED_AT = null;

    protected $fillable = [
        'IDNguoiDung',
        'HanhDong',
        'Loai',
        'DuLieuCu',
        'DuLieuMoi',
        'DiaChiIP',
        'TrinhDuyet',
        'KetQua'
    ];

    protected $casts = [
        'ThoiGian' => 'datetime'
    ];

    // Relationships
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'IDNguoiDung', 'ID');
    }

    // Helper methods to log activities
    public static function log($idNguoiDung, $hanhDong, $loai = 'Người dùng', $duLieuCu = null, $duLieuMoi = null, $ketQua = 'Thành công')
    {
        return static::create([
            'IDNguoiDung' => $idNguoiDung,
            'HanhDong' => $hanhDong,
            'Loai' => $loai,
            'DuLieuCu' => is_array($duLieuCu) ? json_encode($duLieuCu) : $duLieuCu,
            'DuLieuMoi' => is_array($duLieuMoi) ? json_encode($duLieuMoi) : $duLieuMoi,
            'DiaChiIP' => request()->ip(),
            'TrinhDuyet' => request()->userAgent(),
            'KetQua' => $ketQua
        ]);
    }

    public static function logHeThong($hanhDong, $duLieuCu = null, $duLieuMoi = null, $ketQua = 'Thành công')
    {
        return static::log(null, $hanhDong, 'Hệ thống', $duLieuCu, $duLieuMoi, $ketQua);
    }

    public static function logQuanTri($idNguoiDung, $hanhDong, $duLieuCu = null, $duLieuMoi = null, $ketQua = 'Thành công')
    {
        return static::log($idNguoiDung, $hanhDong, 'Quản trị', $duLieuCu, $duLieuMoi, $ketQua);
    }
}
