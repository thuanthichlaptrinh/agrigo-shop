<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\Auth\JwtSessionManager;

class CheckUser
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
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại');
        }

        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}
