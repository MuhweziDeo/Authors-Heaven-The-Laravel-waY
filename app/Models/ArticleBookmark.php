<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleBookmark extends Model
{
    //
    protected $fillable = ['article_slug', 'user_uuid'];

    protected static function checkIfHasBookmarked($user_uuid, $slug)
    {
        $hasBookMarked = ArticleBookmark::where('user_uuid', $user_uuid)
                                ->where('article_slug', $slug)->first();
        if ($hasBookMarked) {
            return true;
        }
        return false;
    }

    protected function bookMarkArticle(Array $data)
    {
        return ArticleBookmark::create($data);
    }

    protected static function findUserBookMark($user_uuid, $slug)
    {
        return ArticleBookmark::where('user_uuid', $user_uuid)
                            ->where('article_slug', $slug)->first();
    }

    protected static function deleteBookmark(ArticleBookmark $bookmark)
    {
        return $bookmark->delete();
    }


}
