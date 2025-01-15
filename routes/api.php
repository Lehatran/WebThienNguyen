<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AddressController;
use App\Http\Middleware\PortBasedMiddleware;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;




Route::middleware([PortBasedMiddleware::class])->group(function () {


    Route::group([
        'middleware' => 'api',
        'prefix' => 'auth'
    ], function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::get('HelloWorld', [HelloWorldController::class, 'HelloWorld'])->middleware('api.auth');
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('refresh', [AuthController::class, 'refresh'])->middleware('api.auth');
        Route::get('profile', [AuthController::class, 'profile'])->middleware('api.auth');
        // Route::post('change-password', [AuthController::class, 'changePassword'])->middleware('api.auth');
    });

    // Các route cho Products trên cổng 8001
    Route::group(['middleware' => 'api.auth'], function () {
        Route::get('products/list', [ProductController::class, 'index'])->name('products.index');
        Route::post('products/create', [ProductController::class, 'store']);
        Route::get('products/index/{id}', [ProductController::class, 'show']);
        Route::put('products/update/{product}', [ProductController::class, 'update']);
        Route::delete('products/delete/{product}', [ProductController::class, 'destroy']);
    });

    Route::group(['middleware' => 'api.auth'], function () {
        Route::get('address/index/{id}', [AddressController::class, 'getAddressById']);
        Route::get('address/list', [AddressController::class, 'getListAddress']);
    });

    Route::group(['middleware' => 'api.auth'], function () {
        Route::get('category/index/{id}', [CategoryController::class, 'getCategoryById']);
        Route::get('category/list', [CategoryController::class, 'getCategories']);
    });

    // Routes dành cho Admin
    Route::middleware(['api.auth', 'api.admin'])->prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'index']); // Lấy danh sách users
        Route::get('/users/{id}', [AdminController::class, 'show']); // Xem thông tin chi tiết user
    });

    // Routes dành cho User quản lý tài khoản
    Route::middleware('auth:api')->prefix('user')->group(function () {
        Route::get('/detail-profile', [UserController::class, 'profile']); // Xem thông tin cá nhân
        Route::put('/update-profile', [UserController::class, 'update']); // Cập nhật thông tin cá nhân
        Route::put('/change-password', [UserController::class, 'changePassword']); // Đổi mật khẩu
    });


});

Route::group([
    'middleware' => 'api',
], function () {
 // API lấy danh sách danh mục sản phẩm
 Route::get('/categories', [CategoryController::class, 'getCategories'])->middleware('api.auth');

 // API đếm số lượng sản phẩm theo loại
 Route::get('/products/count-by-category', [ProductController::class, 'countProductsByCategory'])->middleware('api.auth');

 //thống kê số lượng loại sản phẩm
 Route::get('statistics',[StatisticsController::class,'index'])->middleware('api.auth');;

// API đếm số lượng sản phẩm theo tháng do người dùng nhập
Route::get('/products/count-by-month', [ProductController::class, 'countProductsByMonth'])->middleware('api.auth');

Route::get('/statistics/products-by-category', [StatisticsController::class, 'productByCategory'])->middleware('api.auth');

Route::get('/products/count-by-category-and-month',[ProductController::class, 'countByCategoryAndMonth'])->middleware('api.auth');;

});