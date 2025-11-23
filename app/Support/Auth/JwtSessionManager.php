<?php

namespace App\Support\Auth;

use App\Models\NguoiDung;
use App\Models\Token;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JwtSessionManager
{
    /** @var int */
    protected $ttl;

    /** @var int */
    protected $threshold;

    public function __construct()
    {
        $this->ttl = (int) config('jwt.ttl', 60);
        $this->threshold = (int) config('jwt.auto_refresh_threshold', 5);
    }

    public function resolveUser(?string $token = null): ?NguoiDung
    {
        $token = $token ?? session('jwt_token');

        if (!$token) {
            $this->clearSession();
            return null;
        }

        try {
            JWTAuth::setToken($token);
            $payload = JWTAuth::getPayload();
            $user = JWTAuth::toUser();
        } catch (TokenExpiredException $e) {
            $refreshResult = $this->refreshToken($token);
            if (!$refreshResult) {
                return null;
            }

            [$token, $user, $payload] = $refreshResult;
        } catch (\Exception $e) {
            $this->clearSession();
            return null;
        }

        if (!$user || !$user->TrangThai) {
            $this->clearSession();
            return null;
        }

        $this->refreshIfAboutToExpire($token, $payload, $user);

        return $user;
    }

    protected function refreshIfAboutToExpire(string $token, $payload, NguoiDung $user): void
    {
        if ($this->threshold <= 0) {
            return;
        }

        $expiresAt = Carbon::createFromTimestamp((int) $payload->get('exp'));
        $thresholdMoment = now()->addMinutes($this->threshold);

        if ($expiresAt->lessThanOrEqualTo($thresholdMoment)) {
            $this->refreshToken($token, $user);
        }
    }

    protected function refreshToken(string $token, ?NguoiDung $knownUser = null): ?array
    {
        try {
            $newToken = JWTAuth::setToken($token)->refresh();
            JWTAuth::setToken($newToken);

            $user = $knownUser ?? JWTAuth::toUser();
            if (!$user) {
                $this->clearSession();
                return null;
            }

            $payload = JWTAuth::getPayload();

            session(['jwt_token' => $newToken, 'user_id' => $user->ID]);
            $this->persistToken($user->ID, $token, $newToken);

            return [$newToken, $user, $payload];
        } catch (\Exception $e) {
            $this->clearSession();
            return null;
        }
    }

    protected function persistToken(int $userId, string $oldToken, string $newToken): void
    {
        Token::where('IDNguoiDung', $userId)
            ->where('Loai', Token::TYPE_JWT)
            ->where('Token', $oldToken)
            ->delete();

        Token::createToken($userId, $newToken, Token::TYPE_JWT, $this->ttl);
    }

    protected function clearSession(): void
    {
        session()->forget(['jwt_token', 'user_id']);
    }
}
