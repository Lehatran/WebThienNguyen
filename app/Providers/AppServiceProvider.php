<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Middleware\AdminMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191); // Giới hạn độ dài mặc định
        // Đăng ký middleware với alias 'api.auth'
        Route::aliasMiddleware('api.auth', ApiAuthMiddleware::class);
        Route::aliasMiddleware('api.admin', AdminMiddleware::class);
    }

}