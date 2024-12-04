<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\StatisticsController;
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::get('HelloWorld', [HelloWorldController::class, 'HelloWorld'])->middleware('api.auth');
    Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('api.auth');
    Route::get('profile', [AuthController::class, 'profile'])->middleware('api.auth');
    Route::get('/categories', [CategoryController::class, 'getCategories'])->middleware('api.auth');
});

 // API lấy danh sách danh mục sản phẩm
 Route::get('/categories', [CategoryController::class, 'getCategories'])->name('api.categories.get');

 // API đếm số lượng sản phẩm theo loại
 Route::get('/products/count-by-category', [ProductsController::class, 'countProductsByCategory'])->name('api.products.count');

 //thống kê
 Route::get('statistics',[StatisticsController::class,'index']);

// API đếm số lượng sản phẩm theo tháng do người dùng nhập
Route::get('/products/count-by-month', [ProductsController::class, 'countProductsByMonth'])->name('api.products.count-by-month');

Route::get('/statistics/products-by-category', [StatisticsController::class, 'productByCategory'])->name('api.statistics.products-by-category');

Route::get('/products/count-by-category-and-month',[ProductsController::class, 'countByCategoryAndMonth']);
