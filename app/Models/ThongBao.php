<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    protected $table = 'ThongBao';
    protected $primaryKey = 'ID';
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = null;

    protected $fillable = [
        'IDNguoiDung',
        'TieuDe',
        'NoiDung',
        'Loai',
        'DaXem',
        'LinkLienKet'
    ];

    protected $casts = [
        'DaXem' => 'boolean',
        'NgayTao' => 'datetime'
    ];

    // Relationships
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'IDNguoiDung', 'ID');
    }

    // Helper methods
    public function markAsRead()
    {
        if (!$this->DaXem) {
            $this->DaXem = true;
            return $this->save();
        }
        return false;
    }

    public static function createForUser($idNguoiDung, $tieuDe, $noiDung, $loai = 'Khác', $linkLienKet = null)
    {
        return static::create([
            'IDNguoiDung' => $idNguoiDung,
            'TieuDe' => $tieuDe,
            'NoiDung' => $noiDung,
            'Loai' => $loai,
            'LinkLienKet' => $linkLienKet
        ]);
    }

    public static function getUnreadCount($idNguoiDung)
    {
        return static::where('IDNguoiDung', $idNguoiDung)
                    ->where('DaXem', 0)
                    ->count();
    }
}
