<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return $this->unauthorizedResponse('Người dùng không tồn tại');
            }

            if (!$user->TrangThai) {
                return $this->unauthorizedResponse('Tài khoản đã bị khóa');
            }

        } catch (TokenExpiredException $e) {
            return $this->unauthorizedResponse('Token đã hết hạn', 401, 'token_expired');
        } catch (TokenInvalidException $e) {
            return $this->unauthorizedResponse('Token không hợp lệ', 401, 'token_invalid');
        } catch (JWTException $e) {
            return $this->unauthorizedResponse('Token không được cung cấp', 401, 'token_absent');
        }

        return $next($request);
    }

    /**
     * Return unauthorized response
     */
    protected function unauthorizedResponse(string $message, int $code = 401, ?string $error = null)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, $code);
    }
}
