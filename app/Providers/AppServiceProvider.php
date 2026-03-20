<?php

namespace App\Providers;

use App\Events\OrderCancelled;
use App\Events\OrderCreated;
use App\Listeners\OrderCancellationListener;
use App\Listeners\OrderCreationListener;
use App\Models\Guest;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductTrial;
use App\Models\Routine;
use App\Models\User;
use App\Observers\ProductObserver;
use App\Observers\ProductTrialObserver;
use App\Observers\RoutineObserver;
use App\Policies\OrderPolicy;
use App\Services\CartService;
use Gate;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
        });

        Product::observe(ProductObserver::class);
        ProductTrial::observe(ProductTrialObserver::class);
        Routine::observe(RoutineObserver::class);

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
            'package' => Package::class,
        ]);

        Event::listen(
            OrderCreated::class,
            [OrderCreationListener::class, 'handle']
        );

        Event::listen(
            OrderCancelled::class,
            [OrderCancellationListener::class, 'handle']
        );

        Gate::policy(Order::class, OrderPolicy::class);
    }
}
