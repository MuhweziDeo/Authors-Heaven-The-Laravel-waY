<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleDisLike extends Model
{
    //
    protected $fillable = ['article_slug','user_uuid'];

    public function article()
    {
        return $this->belongsTo(\App\Models\Article::class, 'article_slug', 'slug');
    }

    public function disLikeBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_uuid', 'uuid');
    }

    protected static function disLikeArticle(Array $data)
    {
        $article_like = ArticleLike::where('article_slug', $data['article_slug'])
                                    ->first();
        if ($article_like) {
            $article_like->delete();
        }
        return ArticleDisLike::create($data);
    }
    
    protected static function deleteArticleDiskLike($data)
    {
        return ArticleDisLike::where('article_slug', $data['article_slug'])
                            ->where('user_uuid', $data['user_uuid'])
                            ->delete();
    }
}
