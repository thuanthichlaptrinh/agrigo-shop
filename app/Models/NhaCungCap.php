<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model
{
    protected $table = 'NhaCungCap';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'TenNhaCungCap',
        'SDT',
        'Email',
        'DiaChi'
    ];

    // Relationships
    public function sanPham()
    {
        return $this->hasMany(SanPham::class, 'IDNhaCungCap', 'ID');
    }
}
