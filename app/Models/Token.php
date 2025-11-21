<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Token extends Model
{
    protected $table = 'Token';
    protected $primaryKey = 'ID';
    
    public $timestamps = false;

    protected $fillable = [
        'IDNguoiDung',
        'Token',
        'Loai',
        'HetHan'
    ];

    protected $casts = [
        'HetHan' => 'datetime'
    ];

    // Relationship
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'IDNguoiDung', 'ID');
    }

    // Token types
    const TYPE_RESET_PASSWORD = 'reset_password';
    const TYPE_VERIFY_EMAIL = 'verify_email';
    const TYPE_REMEMBER_ME = 'remember_me';
    const TYPE_JWT = 'jwt';

    // Helper methods
    public static function createToken($userId, $token, $type, $expiresInMinutes = 60)
    {
        return self::create([
            'IDNguoiDung' => $userId,
            'Token' => $token,
            'Loai' => $type,
            'HetHan' => Carbon::now()->addMinutes($expiresInMinutes)
        ]);
    }

    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->HetHan);
    }

    public static function findValidToken($token, $type)
    {
        return self::where('Token', $token)
            ->where('Loai', $type)
            ->where('HetHan', '>', Carbon::now())
            ->first();
    }

    public static function revokeUserTokens($userId, $type = null)
    {
        $query = self::where('IDNguoiDung', $userId);
        
        if ($type) {
            $query->where('Loai', $type);
        }
        
        return $query->delete();
    }
}
