<?php

namespace App\Providers;

use App\Models\ShoppingCart;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {
            $userId = auth()->id();
            $sessionId = session()->get('cart_session_id');
    
            $cartCount = ShoppingCart::when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }, function ($query) use ($sessionId) {
                $query->where('session_id', $sessionId);
            })->count();
    
            $view->with('cartCount', $cartCount);
        });
    }
}
