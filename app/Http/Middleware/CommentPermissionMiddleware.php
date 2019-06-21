<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Comment;

class CommentPermissionMiddleware
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
        $comment_id = request()->id;
        $comment = Comment::findCommentById($comment_id);
        if (!$comment) {
            return response()->json([
                'message' => 'Comment Not Found',
                'success' => false
            ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }

        if ($comment->user_uuid !== $request->user->uuid) {
            return response()->json([
                'message' => 'Permission Denied',
                'success' => false
            ], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        }
        $request->comment = $comment;
        return $next($request);
    }
}
