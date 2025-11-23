<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\Auth\JwtSessionManager;

class JWTAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var JwtSessionManager $manager */
        $manager = app(JwtSessionManager::class);
        $user = $manager->resolveUser();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Phiên đăng nhập đã hết hạn');
        }

        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}
