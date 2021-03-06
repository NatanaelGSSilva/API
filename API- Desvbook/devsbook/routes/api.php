<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
/*
Route::get('/404', 'AuthController@unauthorized')->name('login');

Route::post('/auth/login', 'AuthController@login');
Route::post('/auth/logout', 'AuthController@logout');
Route::post('/auth/refresh', 'AuthController@refresh');
*/

Route::post('/user', 'AuthController@create');
/*
Route::put('/user', 'AuthController@update');
Route::post('/user/avatar', 'UserController@updateAvatar');
Route::post('/user/cover', 'UserController@updateCover');

Route::get('/feed', 'FeedController@read');
Route::get('/user/feed', 'FeedController@userFeed');
Route::get('/user/{id}/feed', 'FeedController@userFeed');


Route::get('/user', 'UserController@read');
Route::get('user/{id}', 'UserController@read');

Route::post('/feed', 'FeedController@create');

Route::post('/post/{id}/like', 'PostController@like');
Route::post('/Post/{id}/comment', 'PostController@comment');

Route::get('/search', 'SearchController@search');
*/
