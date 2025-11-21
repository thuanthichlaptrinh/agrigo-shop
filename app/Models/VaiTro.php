<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaiTro extends Model
{
    protected $table = 'VaiTro';
    protected $primaryKey = 'ID';
    
    // Không sử dụng timestamps vì bảng database2.sql không có cột created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'TenVaiTro',
        'MoTa'
    ];

    // Relationships
    public function nguoiDung()
    {
        return $this->hasMany(NguoiDung::class, 'IDVaiTro', 'ID');
    }

    // Role constants
    const ADMIN = 'Admin';
    const USER = 'User';
    const PRODUCT_MANAGER = 'ProductManager';
    const ORDER_MANAGER = 'OrderManager';
}
