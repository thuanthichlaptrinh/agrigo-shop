<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GioHang extends Model
{
    protected $table = 'GioHang';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['IDNguoiDung', 'IDSanPham'];
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'IDNguoiDung',
        'IDSanPham',
        'SoLuong'
    ];

    protected $casts = [
        'SoLuong' => 'integer',
        'NgayCapNhat' => 'datetime'
    ];

    // Override getKeyName for composite key
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    // Relationships
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'IDNguoiDung', 'ID');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'IDSanPham', 'ID');
    }

    // Helper methods
    public function getThanhTien()
    {
        return $this->SoLuong * $this->sanPham->Gia;
    }
}
