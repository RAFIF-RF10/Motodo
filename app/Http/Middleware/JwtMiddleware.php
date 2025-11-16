<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
     public function handle($request, Closure $next)
    {
        try {
            $token = $request->bearerToken() ?? session('jwt_token');

            if (!$token) {
                return response()->json(['error' => 'Token tidak ditemukan'], 401);
            }

            JWTAuth::setToken($token);
            $user = JWTAuth::authenticate();

            if ($user && !Auth::check()) {
                Auth::login($user);
            }

        } catch (Exception $e) {
            return response()->json(['error' => 'Token tidak valid atau kedaluwarsa'], 401);
        }

        return $next($request);
    }
}
