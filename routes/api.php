<?php

use App\Http\Controllers\HubController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SesController;
use App\Http\Controllers\UserController;

Route::get('/', function() {
    return response()->json([
        'status' => true,
        'message' => 'Application is running successfully.'
    ], 200);
});

Route::group(['prefix' => 'setup'], function() {

    Route::group(['prefix' => 'ses', 'controller' => SesController::class], function() {
        Route::get('/templates', 'listTemplates');
        Route::post('/templates', 'createTemplate');
        Route::delete('/templates', 'deleteTemplate');
    });
    
});

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/verify-email', 'verifyEmail');
    Route::get('/test', function() {
        return response()->json([
            'status' => true,
            'message' => 'You are now properly authenticated.'
        ], 200);
    })->middleware('auth:sanctum');
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {
    
    Route::group(['prefix' => 'users', 'controller' => UserController::class], function () {
        Route::get('/', 'index');
        Route::get('/{user}', 'show');
        Route::delete('/{user}', 'destroy');
    });

    Route::group(['prefix' => 'hubs', 'controller' => HubController::class], function () {
        Route::get('/', 'index');
        Route::get('/{hub}', 'show');
        Route::delete('/{hub}', 'destroy');
    });

});