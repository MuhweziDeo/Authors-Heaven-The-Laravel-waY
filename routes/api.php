<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('signup', 'Auth\RegisterController@register');
Route::put('verify/{token}/confirmation', 'Auth\RegisterController@emailConfirmation');
Route::post('login', 'Auth\LoginController@login');
Route::post('password-reset/request', 'Auth\ResetPasswordController@resetPasswordRequest');
Route::patch('password-reset/{token}/confirm','Auth\ResetPasswordController@resetPasswordConfirm');

// Profiles
Route::get('profiles', 'ProfileController@index');
Route::get('profiles/{username}','ProfileController@show');
Route::patch('profiles/{username}','ProfileController@update')->middleware('tokenAuthentication');

// Articles
Route::post('articles', 'ArticlesController@create')->middleware('tokenAuthentication');
Route::get('articles', 'ArticlesController@index');
Route::get('articles/{slug}', 'ArticlesController@show');
Route::delete('articles/{slug}', 'ArticlesController@destroy')->middleware('tokenAuthentication');
Route::patch('articles/{slug}', 'ArticlesController@update')->middleware('tokenAuthentication');
Route::put('articles/{slug}/favourite','ArticlesController@favouriteArticle')->middleware('tokenAuthentication');
Route::delete('articles/{slug}/favourite','ArticlesController@unfavouriteArticle')->middleware('tokenAuthentication');
Route::put('articles/{slug}/like','ArticlesController@likeArticle')->middleware('tokenAuthentication');
Route::delete('articles/{slug}/like','ArticlesController@unlikeArticle')->middleware('tokenAuthentication');
Route::put('articles/{slug}/unlike','ArticlesController@disLikeArticle')->middleware('tokenAuthentication');
Route::delete('articles/{slug}/unlike','ArticlesController@removeArticleDisLike')->middleware('tokenAuthentication');