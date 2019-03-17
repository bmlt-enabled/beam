<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Cache;

Route::get('/', function () {
    return redirect('home');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin', 'AdminController@admin')
    ->middleware('is_admin')
    ->name('admin');
Route::get('/profile', 'ProfileController@index')->name('profile');
Route::post('/profile/save', 'ProfileController@save')->name('save_profile');
Route::post('/token', 'TokenController@generate')->name('token-generate');
Route::get('/profile/{id}', 'ProfileController@admin')->name('admin_profile');
Route::post('/profile/save/{id}', 'ProfileController@save_admin')->name('save_admin_profile');
Route::get('/posts','PostController@index')->name('posts');
Route::get('/settings','SettingsController@index')->name('settings');
Route::post('/posts/save','PostController@save')->name('posts-save');
Route::post('/posts/comment/save/{parent_id}', 'PostController@comment')->name('posts-comment-save');
Route::get('/flush', function() {
    Cache::forget('service_bodies');
});
