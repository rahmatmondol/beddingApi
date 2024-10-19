<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        //
        Schema::defaultStringLength(191);

        // Routes without any prefix
        Route::middleware('web')
            ->group(base_path('routes/user.php'));

        // Routes with 'admin' prefix
        Route::middleware('web')
            ->prefix('admin')
            ->group(base_path('routes/admin.php'));
    }
}
