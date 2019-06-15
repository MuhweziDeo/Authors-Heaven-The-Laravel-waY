<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Illuminate\Http\Response;

class TokenAuthentication
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
            $prefix = explode(' ',$authorizationHeader)[0];
            if ($prefix !== 'Bearer') {
                return response()->json([
                    'message' => 'missing Bearer prefix',
                    'action' => 'Please pass in token in format Bearer token'
                ], Response::HTTP_BAD_REQUEST);
            }
            if (!$authorizationHeader) {
                return response()->json([
                    'message' => 'Missing authorization header',
                    'success' => 'false'
                ], Response::HTTP_UNAUTHORIZED);
            }
            $user = JWTAuth::parseToken()->authenticate();
            $request->user = $user;
            return $next($request);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e ) {
            return response()->json([
                'message' => 'We could not complete the request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
}
