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

// Fetch the current user
Route::get('/user', 'UserController@show');
Route::patch('/user', 'UserController@update');
Route::delete('/user', 'UserController@destroy');

// Fetch roles
Route::get('/roles', 'RoleController@index');
Route::get('/roles/{role}', 'RoleController@show');

// Fetch permissions
Route::get('/permissions', 'PermissionController@index');
Route::get('/permissions/{permission}', 'PermissionController@show');

// Admin routes
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    // Activity
    Route::get('/activity', 'ActivityController@index');
    Route::get('/activity/{activity}', 'ActivityController@show');
    Route::delete('/activity/{activity}', 'ActivityController@destroy');

    // Users
    Route::get('/users', 'UserController@index');
    Route::get('/users/{user}', 'UserController@show');
    Route::post('/users', 'UserController@store');
    Route::patch('/users/{user}', 'UserController@update');
    Route::delete('/users/{user}', 'UserController@destroy');

    // Roles
    Route::get('/roles', 'RoleController@index');
    Route::get('/roles/{role}', 'RoleController@show');
    Route::post('/roles', 'RoleController@store');
    Route::patch('/roles/{role}', 'RoleController@update');
    Route::delete('/roles/{role}', 'RoleController@destroy');

    // Permissions
    Route::get('/permissions', 'PermissionController@index');
    Route::get('/permissions/{permission}', 'PermissionController@show');

    // Role permissions
    Route::post('/roles/{role}/permissions/{permission}', 'PermissionRoleController@store');
    Route::delete('/roles/{role}/permissions/{permission}', 'PermissionRoleController@destroy');

    // User roles
    Route::post('/users/{user}/roles/{role}', 'RoleUserController@store');
    Route::delete('/users/{user}/roles/{role}', 'RoleUserController@destroy');

    // User permissions
    Route::post('/users/{user}/permissions/{permission}', 'PermissionUserController@store');
    Route::delete('/users/{user}/permissions/{permission}', 'PermissionUserController@destroy');

    // Trash routes
    Route::group(['prefix' => 'trash'], function () {
        // Trashed users
        Route::get('/users', 'UserTrashController@index');
        Route::get('/users/{trashedUser}', 'UserTrashController@show');
        Route::put('/users/{user}', 'UserTrashController@store');
        Route::post('/users/{trashedUser}/restore', 'UserTrashController@restore');
        Route::delete('/users/{trashedUser}', 'UserTrashController@destroy');
    });
});
