<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            abort(403, 'Silakan login terlebih dahulu');
        }

        if (!in_array(optional($request->user()->role)->name, $roles)) {

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak'], 403);
            }

            // Jika request web (HTML) - redirect dengan SweetAlert
            return redirect()->route('welcome')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini!');
        }

        return $next($request);
    }
}
