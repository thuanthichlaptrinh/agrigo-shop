<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPhamKhuyenMai extends Model
{
    protected $table = 'SanPhamKhuyenMai';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['IDSanPham', 'IDKhuyenMai'];

    protected $fillable = [
        'IDSanPham',
        'IDKhuyenMai',
        'GhiChu',
        'NgayTao'
    ];

    protected $casts = [
        'NgayTao' => 'datetime'
    ];

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'IDSanPham', 'ID');
    }

    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'IDKhuyenMai', 'ID');
    }

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
}
