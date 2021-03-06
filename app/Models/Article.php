<?php

namespace App\Models;

use App\Models\User;
use App\Models\ArticleDisLike;
use App\Models\ArticleLike;
use App\Models\ArticleBookmark;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ArticleFavorite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Models\ArticleRating;

class Article extends Model
{
    //
    protected $fillable = ['title', 'description', 'author_uuid', 'body', 'slug'];
    
    protected $appends = ['isFavourite', 'averageRating', 'hasRated', 'currenUserRating', 'hasBookMarked'];

    public function setSlugAttribute($slug)
    {   
        $rand = Str::random();
        $this->attributes['slug'] = str_slug($slug . '-' . Carbon::now() . '-' . $rand);
    }

    public function getIsFavouriteAttribute()
    {
        //  refactor Move to controller
        if (request()->user) {
           return ArticleFavorite::checkIfHasFavourited(request()->user->uuid, $this->slug);
        }
        return false;
    }

    public function getHasRatedAttribute()
    {
        // refactor Move to controller
        if (request()->user) {
           return ArticleRating::checkIfUserHasRated(request()->user->uuid, $this->slug);
        }
        return false;
    }

    public function getCurrenUserRatingAttribute()
    {
        // refactor Move to controller
        if (request()->user) {
            $rating = ArticleRating::currentUserRating(request()->user->uuid, $this->slug);
           return $rating ? $rating->rating : null;
        }
        return null;
    }

    public function getHasBookMarkedAttribute()
    {
         // refactor Move to controller
        if (request()->user) {
            return ArticleBookmark::checkIfHasBookmarked(request()->user->uuid, $this->slug);
         }
         return false;
    }

    public function getAverageRatingAttribute()
    {
        return ArticleRating::getAverageRatings($this->slug);
    }

    protected static $rules = [
        'title' => ['string', 'required', 'min:5'],
        'description' => ['string', 'required'],
        'body'  =>  ['string', 'required']
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_uuid', 'uuid');
    }

    public function favourites()
    {
        return $this->hasMany(ArticleFavorite::class, 'article_slug', 'slug');
    }

    public function likes()
    {
        return $this->hasMany(ArticleLike::class, 'article_slug', 'slug');
    }

    public function disLikes()
    {
        return $this->hasMany(ArticleDisLike::class, 'article_slug', 'slug');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'article_slug', 'slug');
    }


    public static function validator(Array $data) 
    {
        return Validator::make($data, Article::$rules);
        
    }
    

    public static function findArticleBySlug(string $slug)
    {
        return Article::where('slug', $slug)->first();
    }

    protected static function checkIfcanFavouriteOrLike(Request $request, Article $article, $model)
    {

        $isAuthor = $request->user->uuid === $article->author_uuid;
        $isFavouritedorLiked = $model::where('article_slug', $article->slug)
                                        ->where('user_uuid', $request->user->uuid)->first();
        if ($isAuthor || $isFavouritedorLiked ) {
            return false;
        }
        return true;
        
    }


    protected static function checkIsOwner(Request $request, Article $article)
    {
        if ($article->author_uuid !== $request->user->uuid) {
            return false;
        }
        return true;
    }

    protected static function createArticle (Array $data)
    {
        $validator = Article::validator($data);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }
        return Article::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'body' => $data['body'],
            'slug' => $data['title'],
            'author_uuid' => $data['author_uuid']
        ]);
    }

    protected static function getAllArticles($limitStart, $limitEnd)
    {   
        $start = $limitStart ? $limitStart : 0;
        $end = $limitEnd ? $limitEnd : 10;
        $articles = Article::with('author.profile', 'likes.likedBy.profile', 
            'disLikes.disLikeBy.profile','favourites.favouriteBy.profile', 'comments.user.profile')
                    ->skip($start)->take($end)->orderBy('created_at', 'DESC')->get();
        return $articles;
    }

    public static function getSingleArticle($slug)
    {
        $article = Article::with('author.profile', 'likes.likedBy.profile', 'disLikes.disLikeBy.profile',
                                    'favourites.favouriteBy.profile', 'comments.user.profile')
                            ->where('slug', $slug)->first();
        return $article;

    }

    protected static function deleteArticle(string $slug)
    {
        return Article::where('slug', $slug)
                        ->first()
                        ->delete();
    }

    protected static function updateArticle(string $slug, Array $data)
    {
        if (count($data) === 0){
            return ['errors' => 'Please provide atleast one value to update'];
        }
        $validator = Validator::make($data,[
            'title' => ['string', 'min:5'],
            'description' => ['string'],
            'body'  =>  ['string'] ]);

        if($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
        return Article::where('slug', $slug)
                        ->first()
                        ->update($data);
    }



}
