<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Listeners\OrderCreationListener;
use App\Models\User;
use App\Models\Guest;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductTrial;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
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

        //  $this->app->bind('path.public', function() {
        //     return realpath(base_path().'/public_html');
        // });
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
            'guest' => Guest::class,
            'product' => Product::class,
            'producttrial' => ProductTrial::class,
            'package' => Package::class
         ]);


     Event::listen(
        OrderCreated::class,
        [OrderCreationListener::class, 'handle']
    );

    
               
    }
  
   
}
