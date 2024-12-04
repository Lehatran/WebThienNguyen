<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HelloWorldController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route HelloWorld


// Route cho đăng nhập, đăng xuất, và các chức năng xác thực
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

// Routes dành cho Admin
Route::middleware(['api.auth', 'api.admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'index']); // Lấy danh sách users
    Route::get('/users/{id}', [AdminController::class, 'show']); // Xem thông tin chi tiết user
});

// Routes dành cho User quản lý tài khoản
Route::middleware('api.auth')->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'profile']); // Xem thông tin cá nhân
    Route::put('/', [UserController::class, 'update']); // Cập nhật thông tin cá nhân
    Route::put('/change-password', [UserController::class, 'changePassword']); // Đổi mật khẩu
});


