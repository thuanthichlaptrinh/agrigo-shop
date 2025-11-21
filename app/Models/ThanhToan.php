<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    protected $table = 'ThanhToan';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'IDDonHang',
        'SoTien',
        'PhuongThuc',
        'TrangThai',
        'NgayThanhToan'
    ];

    protected $casts = [
        'SoTien' => 'decimal:2',
        'NgayThanhToan' => 'datetime'
    ];

    // Relationships
    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'IDDonHang', 'ID');
    }

    // Helper methods
    public function isSuccess()
    {
        return $this->TrangThai === 'Thành công';
    }
}
