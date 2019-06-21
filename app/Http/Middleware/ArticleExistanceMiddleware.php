<?php

namespace App\Http\Middleware;

use Closure;

class ArticleExistanceMiddleware
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
        $slug = $request->slug;
        $article = \App\Models\Article::findArticleBySlug($slug);
        if (!$article) {
            return response()->json([
                'message' => 'Article Not Found',
                'success' => false
            ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }
        $request->article = $article;
        return $next($request);
    }
}
