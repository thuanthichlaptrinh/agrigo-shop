<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuocHoiThoai extends Model
{
    use HasFactory;

    protected $table = 'CuocHoiThoai';
    protected $primaryKey = 'ID';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'IDNguoiDung',
        'SessionID',
        'TieuDe',
        'TrangThai',
        'IDAdmin',
        'LanHoatDongCuoi'
    ];

    protected $casts = [
        'LanHoatDongCuoi' => 'datetime',
    ];

    // Relationships
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'IDNguoiDung', 'ID');
    }

    public function admin()
    {
        return $this->belongsTo(NguoiDung::class, 'IDAdmin', 'ID');
    }

    public function tinNhan()
    {
        return $this->hasMany(TinNhan::class, 'IDCuocHoiThoai', 'ID');
    }

    public function tinNhanMoiNhat()
    {
        return $this->hasOne(TinNhan::class, 'IDCuocHoiThoai', 'ID')->latest('ThoiGianGui');
    }

    public function tinNhanChuaDoc()
    {
        return $this->hasMany(TinNhan::class, 'IDCuocHoiThoai', 'ID')->where('DaXem', false);
    }

    // Scopes
    public function scopeMo($query)
    {
        return $query->where('TrangThai', 'Mở');
    }

    public function scopeCho($query)
    {
        return $query->where('TrangThai', 'Chờ');
    }

    public function scopeCuaAdmin($query, $adminId)
    {
        return $query->where('IDAdmin', $adminId);
    }
}
