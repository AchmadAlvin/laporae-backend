<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminLaporanController;


Route::group([
    'middleware' => 'api',
    'prefix'     => 'auth',
], function () {
    Route::post('login',   [AuthController::class, 'login'])->name('login');
    Route::post('register',[AuthController::class, 'register']);
    Route::post('logout',  [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me',      [AuthController::class, 'me']);
});


Route::group([
    'middleware' => 'auth:api',
], function () {

    Route::apiResource('laporans', LaporanController::class);

});

Route::group([
    'middleware' => 'auth:admin',
    'prefix' => 'admin'
], function () {
    Route::get('laporans',        [AdminLaporanController::class, 'index']);
    Route::get('laporans/{id}',   [AdminLaporanController::class, 'show']);
    Route::put('laporans/{id}',   [AdminLaporanController::class, 'update']);
    Route::delete('laporans/{id}',[AdminLaporanController::class, 'destroy']);
});

