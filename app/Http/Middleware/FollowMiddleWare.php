<?php

namespace App\Http\Middleware;

use Closure;

class FollowMiddleWare
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
        
        $followee = \App\Models\User::findUserByUuid($request->uuid);
        $action = $request->isMethod('post') ? 'follow': 'unfollow';
        
        if (!$followee) {
            return response()->json([
                'message' => "User you are trying to $action a user that doesnot exist",
                'success' => false
            ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }

        if ($request->isMethod('post')) {
            $canFollow = \App\Models\UserFollow::checkIfCanFollow($request->user->uuid, $followee->uuid);
            if (!$canFollow) {
                return response()->json([
                    'message' => 'You have already followed user or you cant not follow user self',
                    'success' => false
                ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_ACCEPTABLE);
            }
            return $next($request);
        }
        
        return $next($request);
    }
}
