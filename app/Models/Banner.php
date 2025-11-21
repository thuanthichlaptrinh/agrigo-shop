<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'Banner';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'TieuDe',
        'HinhAnh',
        'LienKet',
        'ViTri',
        'ThuTu',
        'TrangThai'
    ];

    protected $casts = [
        'ThuTu' => 'integer',
        'TrangThai' => 'boolean'
    ];

    // Helper methods
    public function isActive()
    {
        return (bool) $this->TrangThai;
    }

    public static function getByViTri($viTri, $active = true)
    {
        $query = static::where('ViTri', $viTri)->orderBy('ThuTu');
        
        if ($active) {
            $query->where('TrangThai', 1);
        }
        
        return $query->get();
    }
}
