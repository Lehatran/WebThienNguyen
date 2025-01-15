<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PortBasedMiddleware;
use App\Http\Controllers\ProductController;



Route::middleware([PortBasedMiddleware::class])->group(function () {
    // Routes Authentication
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout']);
    // Routes quản lý hồ sơ User
    Route::middleware('auth')->prefix('user')->group(function () {
        Route::get('/edit-profile', [UserController::class, 'showEditProfileForm'])->name('edit.profile.form');
        Route::put('/edit-profile', [UserController::class, 'update'])->name('edit.profile');
        Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('change.password.form');
        Route::post('/change-password', [UserController::class, 'changePassword'])->name('change.password');
    });

    // Routes dành cho Admin
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'index'])->name('admin.users');
        Route::get('/users/{id}', [AdminController::class, 'show'])->name('admin.users.show');
    });
    Route::middleware(['auth'])->prefix('products')->group(function () {
    Route::get('navbar', [ProductController::class, 'showNavbarForm']);
    });
});