<?php

namespace App\Http\Middleware;

use Closure;

class BookMarkExistenceMiddleware
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
        $bookmark = \App\Models\ArticleBookmark::findUserBookMark(request()->user->uuid, request()->slug);
        if (!$bookmark) {
            return response()->json([
                'message' => 'You have not bookmarked this article before',
                'success' => false
            ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }

        if($bookmark->user_uuid !== $request->user->uuid) {
            return response()->json([
                'message' => 'Permission Denied',
                'success' => false
            ], \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN);
        }
        $request->bookmark = $bookmark;
        return $next($request);
    }
}
