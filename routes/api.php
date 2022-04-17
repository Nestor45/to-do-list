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
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::post('tasks', 'TaskController@getTaskStatus'); // ENDPOINT API

Route::group(['middleware' => 'jwt.auth'], function ($router) {
    Route::post('task/create', 'TaskController@create');
    Route::post('tasks/ter', 'TaskController@getTaskStatusTer');
    Route::post('task/delete', 'TaskController@destroyStatus');
    Route::post('task/update', 'TaskController@updateStatus');
    Route::post('task/edit','TaskController@editTask');
});