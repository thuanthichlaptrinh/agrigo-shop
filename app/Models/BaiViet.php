<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    use HasFactory;

    protected $table = 'BaiViet';
    protected $primaryKey = 'ID';
    public $timestamps = false; // We use custom timestamps NgayTao, NgayCapNhat

    protected $fillable = [
        'TieuDe',
        'Slug',
        'NoiDung',
        'MoTaNgan',
        'HinhAnh',
        'IDNguoiDung',
        'IDDanhMuc',
        'TrangThai',
        'LuotXem',
        'NgayTao',
        'NgayCapNhat',
    ];

    protected $casts = [
        'TrangThai' => 'boolean',
        'NgayTao' => 'datetime',
        'NgayCapNhat' => 'datetime',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'IDNguoiDung', 'ID');
    }

    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'IDDanhMuc', 'ID');
    }
}
