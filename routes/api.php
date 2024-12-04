<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\PortBasedController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('HelloWorld', [HelloWorldController::class, 'HelloWorld']);

Route::post('/register', [AuthController::class, 'register']);

// Route cho đăng nhập, đăng xuất, và các chức năng xác thực
// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function () {
//     Route::post('login', [AuthController::class, 'login']) -> name('auth.login');
//     Route::get('HelloWorld', [HelloWorldController::class, 'HelloWorld'])->middleware('api.auth');
//     Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
//     Route::post('refresh', [AuthController::class, 'refresh'])->middleware('api.auth');
//     Route::get('profile', [AuthController::class, 'profile'])->middleware('api.auth');
// });

// Route::group(['middleware' => 'api.auth'], function () {
//     Route::get('products', [ProductController::class, 'index'])->name('products.index');
//     Route::post('products', [ProductController::class, 'store']);
//     Route::get('products/{id}', [ProductController::class, 'show']);
//     Route::put('products/{product}', [ProductController::class, 'update']);
//     Route::delete('products/{product}', [ProductController::class, 'destroy']);
// });



Route::middleware([PortBasedController::class])->group(function () {

    // Các route cho Auth trên cổng 8000
    Route::group([
        'middleware' => 'api',
        'prefix' => 'auth'
    ], function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::get('HelloWorld', [HelloWorldController::class, 'HelloWorld'])->middleware('api.auth');
        Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->middleware('api.auth');
        Route::get('profile', [AuthController::class, 'profile'])->middleware('api.auth');
    });

    // Các route cho Products trên cổng 8001
    Route::group(['middleware' => 'api.auth'], function () {
        Route::get('products/list', [ProductController::class, 'index'])->name('products.index');
        Route::post('products/create', [ProductController::class, 'store']);
        Route::get('products/index/{id}', [ProductController::class, 'show']);
        Route::put('products/update/{product}', [ProductController::class, 'update']);
        Route::delete('products/delete/{product}', [ProductController::class, 'destroy']);
    });

    Route::get('address/{id}', [ProductController::class, 'getAddress']);
});