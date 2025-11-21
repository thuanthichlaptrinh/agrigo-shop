<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HinhAnhSanPham extends Model
{
    protected $table = 'HinhAnhSanPham';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'IDSanPham',
        'DuongDan',
        'LaChinh'
    ];

    protected $casts = [
        'LaChinh' => 'boolean'
    ];

    // Relationships
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'IDSanPham', 'ID');
    }
}
