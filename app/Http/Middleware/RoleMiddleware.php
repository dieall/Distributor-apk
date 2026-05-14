<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // Method HTTP yang aman / bersifat read-only
    private const READONLY_METHODS = ['GET', 'HEAD', 'OPTIONS'];

    // Route name yang diizinkan bagi Direktur meski bukan GET (hanya logout)
    private const DIREKTUR_ALLOWED_POST_ROUTES = ['logout'];

    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admin: akses penuh tanpa batasan
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Direktur: akses seperti admin, tapi HANYA read-only (GET/HEAD/OPTIONS)
        // Satu-satunya POST yang diizinkan adalah logout
        if ($user->isDirektur()) {
            $isReadonly = in_array($request->method(), self::READONLY_METHODS);
            $isAllowedPost = $request->routeIs(self::DIREKTUR_ALLOWED_POST_ROUTES);

            if (!$isReadonly && !$isAllowedPost) {
                abort(403, 'Direktur hanya dapat melihat data, tidak bisa melakukan perubahan.');
            }

            return $next($request);
        }

        // Direktur Sawit: akses seperti admin sawit, tapi HANYA read-only (GET/HEAD/OPTIONS)
        // Satu-satunya POST yang diizinkan adalah logout
        if ($user->isDirekturSawit()) {
            $isReadonly = in_array($request->method(), self::READONLY_METHODS);
            $isAllowedPost = $request->routeIs(self::DIREKTUR_ALLOWED_POST_ROUTES);

            if (!$isReadonly && !$isAllowedPost) {
                abort(403, 'Direktur Sawit hanya dapat melihat data, tidak bisa melakukan perubahan.');
            }

            return $next($request);
        }

        // Role lain: sesuaikan dengan daftar role yang diizinkan untuk route ini
        if (!in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
