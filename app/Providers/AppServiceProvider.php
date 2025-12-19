<?php

namespace App\Providers;

use App\Models\Guest;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         View::composer('*', function ($view) {
            try {
                $cartService = app(CartService::class);
                $view->with([
                    'cartCount' => $cartService->getCount(),
                    'cartTotal' => $cartService->getTotal(),
                ]);
            } catch (\Exception $e) {
                $view->with([
                    'cartCount' => 0,
                    'cartTotal' => 0,
                ]);
            }
        });

         Relation::enforceMorphMap([
            'user' => User::class,
            'guest' => Guest::class
         ]);
    }


}
