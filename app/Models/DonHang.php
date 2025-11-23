<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    protected $table = 'DonHang';
    protected $primaryKey = 'ID';
    const CREATED_AT = 'NgayDat';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'MaDonHang',
        'IDNguoiDung',
        'TenNguoiNhan',
        'SDT',
        'DiaChi',
        'PhuongThucTT',
        'PhiVanChuyen',
        'GiamVoucher',
        'IDVoucher',
        'TongThanhToan',
        'TrangThai',
        'GhiChu'
    ];

    protected $casts = [
        'PhiVanChuyen' => 'decimal:2',
        'GiamVoucher' => 'decimal:2',
        'TongThanhToan' => 'decimal:2',
        'NgayDat' => 'datetime',
        'NgayCapNhat' => 'datetime'
    ];

    // Relationships
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'IDNguoiDung', 'ID');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'IDVoucher', 'ID');
    }

    public function chiTiet()
    {
        return $this->hasMany(ChiTietDonHang::class, 'IDDonHang', 'ID');
    }

    public function thanhToan()
    {
        return $this->hasMany(ThanhToan::class, 'IDDonHang', 'ID');
    }

    // Helper methods
    public function getTongTienHang()
    {
        return $this->chiTiet->sum('ThanhTien');
    }

    public function canCancel()
    {
        return in_array($this->TrangThai, ['Chờ xác nhận', 'Đã xác nhận']);
    }

    public function restoreStock(): void
    {
        $this->loadMissing('chiTiet.sanPham');

        foreach ($this->chiTiet as $detail) {
            if ($detail->sanPham) {
                $detail->sanPham->increment('SoLuongTon', $detail->SoLuong);
            }
        }
    }
}
