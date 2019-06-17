<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class ArticleFavorite extends Model
{
    //
    protected $fillable = ['article_slug','user_uuid'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_slug', 'slug');
    }

    public function favouriteBy()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    protected static function checkIfcanFavourite(Request $request, Article $article)
    {

        $isAuthor = $request->user->uuid === $article->author_uuid;
        $isFavourited = ArticleFavorite::where('article_slug', $article->slug)
                                        ->where('user_uuid', $request->user->uuid)->first();
        if ($isAuthor || $isFavourited ){
            return false;
        }
        return true;
        
    }

    protected function favouriteArticle(Array $data)
    {

        return ArticleFavorite::create([
            'article_slug' => $data['slug'],
            'user_uuid' => $data['user_uuid']
        ]);
    }

    protected static function unFavouriteArticle(string $user_uuid, string $slug)
    {
        return ArticleFavorite::where('user_uuid', $user_uuid)
                                ->where('article_slug', $slug)
                                ->delete();
    }

   
}
