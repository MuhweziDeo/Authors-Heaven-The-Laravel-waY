<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\ArticleRating;
use App\Models\ArticleBookmark;
use App\Models\ArticleFavorite;
use \Symfony\Component\HttpFoundation\Response;

class ArticlesController extends Controller
{
    
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
    {   $start = request()->input('start');
        $end = request()->input('end');
        $articles = Article::getAllArticles($start, $end);
        return response()->json( [
            'data' => $articles,
            'limitStart' => $start,
            'limitEnd' => $end,
            'count' => count($articles) 
            ], Response::HTTP_OK); ;
    }

    protected function show($slug)
    {
        $article = Article::findArticleBySlug($slug);

        if (!$article) {
            return response()->json([
                'message' => 'Article not Found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'article' => Article::getSingleArticle($slug)
            ]);

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

    protected function favouriteArticle($slug)
    {
        $article = Article::findArticleBySlug($slug);
        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], Response::HTTP_NOT_FOUND);
        }
        $canFavourite = Article::checkIfcanFavouriteOrLike(request(), $article, ArticleFavorite::class);
        if (!$canFavourite) {
            return response()->json([
                'message' => 'You already liked this article or you authored it',
                'success' => false
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $data['slug'] = $slug;
        $data['user_uuid'] = request()->user->uuid;
        $favourite = ArticleFavorite::favouriteArticle($data); 
        return response()->json([
            'message' => 'article favourited successfully',
            'success' => true
        ], Response::HTTP_OK);
    }

    protected function unfavouriteArticle($slug)
    {
        $article = Article::findArticleBySlug($slug);
        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $hasNotFavourite = Article::checkIfcanFavouriteOrLike(request(), $article, ArticleFavorite::class);
        if ($hasNotFavourite) {
            return response()->json([
                'message' => 'You have not favourited this article before',
                'success' => false
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $unfavourite = ArticleFavorite::unfavouriteArticle(request()->user->uuid, $slug);
        return response()->json([
            'message' => 'You have unfavourited this article',
            'success' => false
        ], Response::HTTP_OK);

    }

    protected function likeArticle($slug)
    {
        $article = Article::findArticleBySlug($slug);
        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $canLike = Article::checkIfcanFavouriteOrLike(request(), $article, \App\Models\ArticleLike::class);
        if (!$canLike) {
            return response()->json([
                'message' => 'You have liked this article or you authored it',
                'success' => false
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $data['article_slug'] = $slug;
        $data['user_uuid'] = request()->user->uuid;
        $likeArticle = \App\Models\ArticleLike::likeArticle($data);
        return response()->json([
            'message' => 'You have sucessfully liked the article',
            'success' => true
        ], Response::HTTP_OK);
    }

    protected function unlikeArticle($slug)
    {
        $article = Article::findArticleBySlug($slug);
        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $canUnLike = Article::checkIfcanFavouriteOrLike(request(), $article, \App\Models\ArticleLike::class);
        if ($canUnLike) {
            return response()->json([
                'message' => 'You have not liked this article or you authored it',
                'success' => false
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $data['article_slug'] = $slug;
        $data['user_uuid'] = request()->user->uuid;
        $likeArticle = \App\Models\ArticleLike::removeArticleLike($data);
        return response()->json([
            'message' => 'You have sucessfully unliked the article',
            'success' => true
        ], Response::HTTP_OK);

    }

    protected function disLikeArticle($slug)
    {
        $article = Article::findArticleBySlug($slug);
        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $canDisLike = Article::checkIfcanFavouriteOrLike(request(), $article, \App\Models\ArticleDisLike::class);
        if (!$canDisLike) {
            return response()->json([
                'message' => 'You have already disLiked this article or you authored it',
                'success' => false
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $data['article_slug'] = $slug;
        $data['user_uuid'] = request()->user->uuid;
        $likeArticle = \App\Models\ArticleDisLike::disLikeArticle($data);
        return response()->json([
            'message' => 'You have sucessfully disLiked the article',
            'success' => true
        ], Response::HTTP_OK);
    }

    protected function removeArticleDisLike($slug)
    {
        $article = Article::findArticleBySlug($slug);
        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $isNotLikedArticle = Article::checkIfcanFavouriteOrLike(request(), $article, \App\Models\ArticleDisLike::class);
        if ($isNotLikedArticle) {
            return response()->json([
                'message' => 'You have not disLiked this article',
                'success' => false
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $data['article_slug'] = $slug;
        $data['user_uuid'] = request()->user->uuid;
        $likeArticle = \App\Models\ArticleDisLike::deleteArticleDiskLike($data);
        return response()->json([
            'message' => 'You have sucessfully removed your disLike on this article',
            'success' => true
        ], Response::HTTP_OK);

    }

    protected function rateArticle($slug)
    {
        $canRate  = Article::checkIfcanFavouriteOrLike(request(), request()->article, \App\Models\ArticleRating::class);
        if(!$canRate) {
            return response()->json([
                'message' => 'You already rated this article or you authored it',
                'success' => false
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $data['article_slug'] = $slug;
        $data['user_uuid'] = request()->user->uuid;
        $data['rating'] = request('rating');

        $rateArticle = ArticleRating::rateArticle($data);

        if ($rateArticle['errors']) {
            return response()->json([
                'erros' => $rateArticle['errors'] ,
                'success' => false
            ]);
        }
        return response()->json([
            'message' => 'Successfully rated article',
            'success' => true,
            'data' =>  $rateArticle
        ]);
    }

    protected function bookmarkArticle($slug)
    {
        $canBookMark = Article::checkIfcanFavouriteOrLike(request(),request()->article,
                                 \App\Models\ArticleBookmark::class);
        if(!$canBookMark) {
            return response()->json([
                'message' => 'You already bookmarked this article or you authored it',
                'success' => false
            ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_ACCEPTABLE);
        }
        $data['article_slug'] = $slug;
        $data['user_uuid'] = request()->user->uuid;
        $bookmarkArticle = ArticleBookmark::bookmarkArticle($data);
        return response()->json([
            'message' => 'Article bookmarked successfully',
            'success' => true
        ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }

    protected function unbookmarkArticle()
    {
        $unbookmark  = ArticleBookmark::deleteBookmark(request()->bookmark);
        if ($unbookmark) {
            return response()->json([
                'message' => 'Successfully unbookmarked article',
                'success' => true
            ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
        }
        return response()->json([
            'message' => 'unable to unbookmark article',
            'sucess' => false
        ], \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    
}
