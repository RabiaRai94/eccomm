<?php

namespace App\Providers;

use App\Models\ShoppingCart;
use Illuminate\Support\Facades\Schema;
use App\Observers\ShoppingCartObserver;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);
        ShoppingCart::observe(ShoppingCartObserver::class);
    }
}
