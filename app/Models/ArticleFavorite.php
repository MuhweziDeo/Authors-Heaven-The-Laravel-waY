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

    public static function checkIfHasFavourited($user_uuid, $slug)
    {
        $hasFavourited = ArticleFavorite::where('article_slug', $slug)
                                            ->where('user_uuid', $user_uuid)
                                            ->first();
        if ($hasFavourited) {
            return true;
        }

        return false;

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
