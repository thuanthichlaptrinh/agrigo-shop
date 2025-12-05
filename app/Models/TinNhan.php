<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinNhan extends Model
{
    use HasFactory;

    protected $table = 'TinNhan';
    protected $primaryKey = 'ID';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'IDCuocHoiThoai',
        'IDNguoiGui',
        'LoaiNguoiGui',
        'NoiDung',
        'HinhAnh',
        'DaXem',
        'ThoiGianGui'
    ];

    protected $casts = [
        'DaXem' => 'boolean',
        'ThoiGianGui' => 'datetime',
    ];

    // Relationships
    public function cuocHoiThoai()
    {
        return $this->belongsTo(CuocHoiThoai::class, 'IDCuocHoiThoai', 'ID');
    }

    public function nguoiGui()
    {
        return $this->belongsTo(NguoiDung::class, 'IDNguoiGui', 'ID');
    }

    // Scopes
    public function scopeChuaDoc($query)
    {
        return $query->where('DaXem', false);
    }

    public function scopeTuNguoiDung($query)
    {
        return $query->where('LoaiNguoiGui', 'NguoiDung');
    }

    public function scopeTuAdmin($query)
    {
        return $query->where('LoaiNguoiGui', 'Admin');
    }
}
