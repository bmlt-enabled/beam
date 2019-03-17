<?php

use Illuminate\Http\Request;
use App\User;
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

Route::get('/users/list', function (Request $request) {
    return User::all();
});

Route::post('/xposts/{beam_id}/save', 'XpostController@save')->name('xposts-save');
Route::post('/xposts/{beam_id}/comment/save/{parent_id}', 'XPostController@comment')->name('xposts-comment-save');
