<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'Voucher';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'MaVoucher',
        'GiaTri',
        'Loai',
        'GiamToiDa',
        'DonToiThieu',
        'SoLuong',
        'DaDung',
        'NgayKetThuc'
    ];

    protected $casts = [
        'GiaTri' => 'decimal:2',
        'GiamToiDa' => 'decimal:2',
        'DonToiThieu' => 'decimal:2',
        'SoLuong' => 'integer',
        'DaDung' => 'integer',
        'NgayKetThuc' => 'datetime'
    ];

    // Relationships
    public function donHang()
    {
        return $this->hasMany(DonHang::class, 'IDVoucher', 'ID');
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->SoLuong > $this->DaDung && 
               now()->lessThan($this->NgayKetThuc);
    }

    public function canApply($tongTien)
    {
        return $this->isAvailable() && $tongTien >= $this->DonToiThieu;
    }

    public function calculateDiscount($tongTien)
    {
        if (!$this->canApply($tongTien)) {
            return 0;
        }

        if ($this->Loai === 'Phần trăm') {
            $giam = $tongTien * ($this->GiaTri / 100);
            return min($giam, $this->GiamToiDa ?? $giam);
        }

        return min($this->GiaTri, $tongTien);
    }
}
