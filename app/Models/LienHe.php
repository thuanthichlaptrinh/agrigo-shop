<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LienHe extends Model
{
    protected $table = 'LienHe';
    protected $primaryKey = 'ID';
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = null;

    protected $fillable = [
        'HoTen',
        'Email',
        'SDT',
        'TieuDe',
        'NoiDung',
        'TrangThai'
    ];

    protected $casts = [
        'NgayTao' => 'datetime'
    ];

    // Helper methods
    public function isNew()
    {
        return $this->TrangThai === 'Mới';
    }

    public function isProcessing()
    {
        return $this->TrangThai === 'Đang xử lý';
    }

    public function isCompleted()
    {
        return $this->TrangThai === 'Hoàn thành';
    }

    public function markAsProcessing()
    {
        $this->TrangThai = 'Đang xử lý';
        return $this->save();
    }

    public function markAsCompleted()
    {
        $this->TrangThai = 'Hoàn thành';
        return $this->save();
    }
}
