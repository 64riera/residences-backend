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

Route::group([
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signUp');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group([
    'middleware' => ['auth:api'],
    'namespace' => 'App\Http\Controllers'
], function() {
    
    // Users section
    Route::get('users', 'UserController@index');
    Route::get('user/{id}', 'UserController@getOne');
    Route::patch('user', 'UserController@update');
    Route::delete('user/{id}', 'UserController@destroy');

    // Processes section
    Route::get('processes', 'ProcessController@getAll');
    Route::post('process', 'ProcessController@create');
    Route::patch('process', 'ProcessController@update');
    Route::delete('process/{id}', 'ProcessController@destroy');

    // Steps section
    Route::get('step/{stepId}', 'StepController@getOne');
    Route::get('process/{processId}/steps', 'ProcessController@getSteps');
    Route::post('process/steps', 'ProcessController@addSteps');
    Route::patch('step', 'StepController@updateOne');
});
