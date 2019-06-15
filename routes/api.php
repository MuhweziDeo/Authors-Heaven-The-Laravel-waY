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

// Profiles
Route::get('profiles', 'ProfileController@index');
Route::get('profiles/{username}','ProfileController@show');
Route::patch('profiles/{username}','ProfileController@update')->middleware('tokenAuthentication');