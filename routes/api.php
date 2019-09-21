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
Route::get('/user', 'UserController@fetch');
// Update the current user
Route::patch('/user', 'UserController@store');
// Delete the current user
Route::delete('/user', 'UserController@destroy');

// Admin routes
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    // Fetch users
    Route::get('/users/{user?}', 'UserController@fetch');
    // Create a new user
    Route::post('/users', 'UserController@store');
    // Update a user
    Route::patch('/users/{user}', 'UserController@store');
    // Delete a user
    Route::delete('/users/{user}', 'UserController@destroy');

    // Trash routes
    Route::group(['prefix' => 'trash'], function () {
        // Get trashed users
        Route::get('/users/{trashedUser?}', 'UserTrashController@fetch');
        // Trash a user
        Route::put('/users/{user}', 'UserTrashController@store');
        // Restore a user
        Route::post('/users/{trashedUser}/restore', 'UserTrashController@restore');
        // Delete a trashed user
        Route::delete('/users/{trashedUser}', 'UserTrashController@destroy');
    });
});
