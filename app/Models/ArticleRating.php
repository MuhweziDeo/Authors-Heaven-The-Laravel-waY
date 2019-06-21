<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class ArticleRating extends Model
{
    //

    protected $fillable = ['rating', 'article_slug', 'user_uuid'];

    protected static $rules = [
        'rating' => ['integer' , 'required' , 'max:5']
    ];

    protected static function validator(Array $data)
    {
        return Validator::make($data, ArticleRating::$rules,);
        
    }

    public static function checkIfUserHasRated($user_uuid, $slug)
    {
        $hasRated = ArticleRating::where('user_uuid', $user_uuid)
                                ->where('article_slug', $slug)->first();
        if ($hasRated) {
            return true;
        }
        return false;
    }

    public static function currentUserRating($user_uuid, $slug)
    {
        return ArticleRating::where('user_uuid', $user_uuid)
                            ->where('article_slug', $slug)->first();

    }
    public static function getAverageRatings(string $slug)
    {
        $ratings = ArticleRating::where('article_slug', $slug)->get();

        $ratingsCollection = collect($ratings);
        $sum  = $ratingsCollection->map( function($rating) { 
                return $rating['rating'];
                })->sum();
        if(count($ratings) > 0) {
            $averageRatings = $sum/count($ratings);
            return $averageRatings;
        }
        return null;
        
    }

    protected static function rateArticle(Array $data)
    {
        $validator = ArticleRating::validator($data);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }

        return ArticleRating::create($data);
    }
}
