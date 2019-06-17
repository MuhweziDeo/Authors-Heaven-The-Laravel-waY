<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleLike extends Model
{
    //
    protected $fillable = ['article_slug','user_uuid'];

    public function article()
    {
        return $this->belongsTo(\App\Models\Article::class, 'article_slug', 'slug');
    }

    public function likedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_uuid', 'uuid');
    }

    protected static function likeArticle(Array $data)
    {
        $article_disLike = ArticleDisLike::where('article_slug', $data['article_slug'])
                                    ->first();
        if ($article_disLike) {
            $article_disLike->delete();
        }
        return ArticleLike::create($data);
    }

    protected static function removeArticleLike($data)
    {
        return ArticleLike::where('article_slug', $data['article_slug'])
                            ->where('user_uuid', $data['user_uuid'])
                            ->delete();
    }
}
