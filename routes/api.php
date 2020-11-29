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
    Route::post('user/step', 'UserStepController@create');
    Route::delete('user/{id}', 'UserController@destroy');
    Route::post('user/process', 'UserController@addActiveProcess');

    // Processes section
    Route::get('processes', 'ProcessController@getAll');
    Route::post('process', 'ProcessController@create');
    Route::get('process/{processId}', 'ProcessController@getOne');
    Route::patch('process', 'ProcessController@update');
    Route::delete('process/{id}', 'ProcessController@destroy');
    Route::get('processes/user/{userId}', 'UserController@getActiveProcesses');
    Route::delete('process/{processId}/user/{userId}', 'UserController@deleteActiveProcess');

    // Steps section
    Route::get('step/{stepId}', 'StepController@getOne');
    Route::get('process/{processId}/steps', 'ProcessController@getSteps');
    Route::post('process/steps', 'ProcessController@addSteps');
    Route::patch('step', 'StepController@updateOne');
    Route::delete('step/{id}', 'StepController@deleteOne');
    Route::get('process/{processId}/student/steps', 'ProcessController@getStudentSteps');

    // Careers section
    Route::post('career', 'CareerController@create');

    // Admin areas section
    Route::post('area', 'AreaController@create');

    // Type users section
    Route::post('type-user', 'TypeUserController@create');
});

Route::group([
    'namespace' => 'App\Http\Controllers'
], function() {

    // Careers section
    Route::get('careers', 'CareerController@getAll');

    // Admiin area section
    Route::get('areas', 'AreaController@getAll');

    // Type users section
    Route::get('type-users', 'TypeUserController@getAll');
});
