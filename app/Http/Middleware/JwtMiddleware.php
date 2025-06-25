<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
            $user = JWTAuth::parseToken()->authenticate();
            
        } catch (JWTException $e) {
            if ($e instanceof TokenExpiredException) {
                return response()->json(['error' => 'JWT token expired!!'], 401);
            } else if ($e instanceof TokenInvalidException) {
                return response()->json(['error' => 'Invalid JWT token!'], 401);
            } else if ($e instanceof TokenBlacklistedException) {
                return response()->json(['error' => 'JWT token has been blacklisted!'], 401);
            } else if ($e instanceof JWTException) {
                return response()->json(['error' => 'Unauthorized. JWT token is required!'], 401);
            } else {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return $next($request);
    }
}
