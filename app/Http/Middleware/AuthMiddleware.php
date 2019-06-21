<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $authorizationHeader = request()->header('authorization');
            if (!$authorizationHeader) {
                return $next($request);
            }
            $user = JWTAuth::parseToken()->authenticate();
            $request->user = $user;
            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e ) {
            return $next($request);
        }
        
       
    }
}
