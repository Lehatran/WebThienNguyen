<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HelloWorldController;
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::get('HelloWorld', [HelloWorldController::class, 'HelloWorld'])->middleware('api.auth');
    Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('api.auth');
    Route::get('profile', [AuthController::class, 'profile'])->middleware('api.auth');
});
