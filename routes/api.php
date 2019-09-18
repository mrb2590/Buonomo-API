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

Route::get('/user', 'UserController@fetch');
Route::patch('/user', 'UserController@store');
Route::delete('/user', 'UserController@destroy');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/users/{user?}', 'UserController@fetch');
    Route::post('/users', 'UserController@store');
    Route::patch('/users/{user}', 'UserController@store');
    Route::delete('/users/{user}', 'UserController@destroy');
});
