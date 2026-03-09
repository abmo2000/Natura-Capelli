<?php

use App\Enums\OrderStatus;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\Web\Auth\GoogleAuthController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LocaleController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\PackagesApiController;
use App\Http\Controllers\Web\PackagesController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProductsApiCpntroller;
use App\Http\Controllers\Web\RoutineController;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\UserController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['locale'])->group(function () {
    Route::get('/', HomeController::class)->name('home');

    Route::view('/contact', 'web.pages.contact')->name('contact');
    Route::post('/contact-message', ContactController::class)->name('contact-message');

    Route::view('about-us', 'web.pages.about-us')->name('about-us');

    Route::resource('routines', RoutineController::class)->only('show', 'index');

    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::get('api/products/{is_trial}', ProductsApiCpntroller::class)->defaults('is_trial', false);
    Route::get('api/packages', PackagesApiController::class);

    Route::resource('products', ProductController::class)->only(['show', 'index']);
    Route::resource('packages', PackagesController::class)->only(['show']);
    Route::prefix('cart')->group(function () {
        Route::get('', [CartController::class, 'index'])->name('cart');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/update/{product_id}', [CartController::class, 'update']);
        Route::delete('remove/{product_id}', [CartController::class, 'remove']);
        Route::delete('clear', [CartController::class, 'clear']);
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('checkout', [OrderController::class, 'index'])->middleware('checkOut-checker')->name('checkout');
        Route::post('order', [OrderController::class, 'store'])->name('checkout');

        Route::get('/users/profile', [UserController::class, 'profile'])->name('users.profile');
        Route::put('/users/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');

        Route::patch('/users/orders/{order}/cancel', function (Request $request, Order $order) {
            $userOrder = $request->user()->orders()->whereKey($order->id)->firstOrFail();

            if ($userOrder->status !== OrderStatus::PENDING->value) {
                return back()->with('order_error', 'Only pending orders can be cancelled.');
            }

            $userOrder->update([
                'status' => OrderStatus::CANCELLED->value,
            ]);

            return back()->with('order_status', 'Order cancelled successfully.');
        })->name('users.orders.cancel');
    });

});

Route::get('locale/{locale}', LocaleController::class)->name('lang-switch');
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::get('queue', ArtisanController::class)->middleware('secure');
