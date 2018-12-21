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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'AuthController@login')->name('auth.login');
    Route::post('/register', 'AuthController@register')->name('auth.register');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/logout', 'AuthController@logout')->name('auth.logout');
        Route::get('/user', 'AuthController@user')->name('auth.user');
    });
});
