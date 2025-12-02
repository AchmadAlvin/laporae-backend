<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::apiResource('laporans', LaporanController::class);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'admin'
], function ($router) {
    Route::post('login', [App\Http\Controllers\AdminAuthController::class, 'login']);
    
    Route::group(['middleware' => 'auth:admin'], function () {
        Route::post('logout', [App\Http\Controllers\AdminAuthController::class, 'logout']);
        Route::post('refresh', [App\Http\Controllers\AdminAuthController::class, 'refresh']);
        Route::post('me', [App\Http\Controllers\AdminAuthController::class, 'me']);
        
        Route::get('laporans', [App\Http\Controllers\AdminLaporanController::class, 'index']);
        Route::get('laporans/{id}', [App\Http\Controllers\AdminLaporanController::class, 'show']);
        Route::put('laporans/{id}', [App\Http\Controllers\AdminLaporanController::class, 'update']);
        Route::delete('laporans/{id}', [App\Http\Controllers\AdminLaporanController::class, 'destroy']);
    });
});
