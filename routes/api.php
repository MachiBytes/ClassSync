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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/test', function() {
        return response()->json([
            'status' => true,
            'message' => 'Application is running successfully. You are now properly authenticated.'
        ], 200);
    });
});

Route::prefix('v1')->group(function () {

    // /v1/auth/...
    Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function() {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::get('/verify-email', 'verifyEmail');
    });

    // /v1/users/...
    Route::group(['prefix' => 'users', 'controller' => UserController::class], function () {
        Route::get('/', 'index');
        Route::get('/{user}', 'show');
        Route::post('/', 'store');
        Route::delete('/{user}', 'destroy');
    });

    // /v1/hubs/...
    Route::group(['prefix' => 'hubs', 'controller' => HubController::class], function () {
        Route::get('/', 'index');
        Route::get('/{hub}', 'show');
        Route::delete('/{hub}', 'destroy');
    });

    Route::group(['prefix' => 'ses', 'controller' => SesController::class], function() {
        Route::get('/templates', 'listTemplates');
        Route::post('/templates', 'createTemplate');
        Route::delete('/templates', 'deleteTemplate');
    });
});