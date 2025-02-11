<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Coba untuk mengautentikasi pengguna melalui token JWT
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            // Token telah kadaluarsa
            return response()->json(['error' => 'Token is expired'], 401);
        } catch (TokenInvalidException $e) {
            // Token tidak valid
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            // Token tidak ditemukan atau masalah lainnya
            return response()->json(['error' => 'Token is not provided'], 401);
        }

        // Jika token valid, lanjutkan request
        return $next($request);
    }
}
