<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class ArticlesController extends Controller
{
    //

    protected function create()
    {   
        $data = request()->all();
        $data['author_uuid'] = request()->user->uuid;
        $article = Article::createArticle($data);
        if ($article['errors']) {
            return response()->json([
                'errors' => $article['errors']
            ],Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'data' => $article,
            'message' => 'Article created successfully',
            'success' => true
        ], Response::HTTP_CREATED);
    }

    protected function index()
    {   
        return response()->json(Article::getAllArticles(), Response::HTTP_OK); ;
    }

    protected function destroy(Request $request, $slug)
    {
        $article = Article::findArticleBySlug($slug);

        if (!$article) {
            return response()->json([
                'message' => 'Article not Found'], Response::HTTP_NOT_FOUND);
        }

        $isOwner = Article::checkIsOwner($request, $article);

        if (!$isOwner) {
            return response()->json([
                'message' => 'Permission denied',
                'success' => false
            ], Response::HTTP_FORBIDDEN);
        }

        Article::deleteArticle($slug);
        return response()->json(['message' => 'Article deleted successfully','success' => true], 
            Response::HTTP_OK);
    }

    protected function update(Request $request, $slug)
    {
        $article = Article::findArticleBySlug($slug);

        if (!$article) {
            return response()->json([
                'message' => 'Article not Found'], Response::HTTP_NOT_FOUND);
        }

        $isOwner = Article::checkIsOwner($request, $article);

        if (!$isOwner) {
            return response()->json([
                'message' => 'Permission denied',
                'success' => false
            ], Response::HTTP_FORBIDDEN);
        }
        $updateArticle = Article::updateArticle($slug,request()->all());
        if($updateArticle['errors']){
            return response()->json([
                'errors' => $updateArticle['errors'],
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'message' => 'Article updated successfully',
            'success' => $updateArticle
        ],Response::HTTP_OK);
    }

    
}
