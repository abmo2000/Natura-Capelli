<?php

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
use Illuminate\Support\Facades\Route;

Route::middleware(['locale', 'throttle:web'])->group(function () {

    // Public browsing routes
    Route::get('/', HomeController::class)->name('home');
    Route::view('/contact', 'web.pages.contact')->name('contact');
    Route::view('about-us', 'web.pages.about-us')->name('about-us');
    Route::resource('routines', RoutineController::class)->only('show', 'index');
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::resource('products', ProductController::class)->only(['show', 'index']);
    Route::resource('packages', PackagesController::class)->only(['show']);

    // API routes
    Route::get('api/products/{is_trial}', ProductsApiCpntroller::class)->defaults('is_trial', false);
    Route::get('api/packages', PackagesApiController::class);

});

// Auth routes — strict limit
Route::middleware(['locale', 'throttle:auth'])->group(function () {
    Route::post('/contact-message', ContactController::class)->name('contact-message');
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
});

// Cart routes — moderate limit
Route::middleware(['locale', 'throttle:checkout'])->prefix('cart')->group(function () {
    Route::get('', [CartController::class, 'index'])->name('cart');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/update/{product_id}', [CartController::class, 'update']);
    Route::delete('remove/{product_id}', [CartController::class, 'remove']);
    Route::delete('clear', [CartController::class, 'clear']);
});

// Checkout & order routes — strict limit
Route::middleware(['locale', 'auth', 'throttle:checkout'])->group(function () {
    Route::get('checkout', [OrderController::class, 'index'])->middleware('checkOut-checker')->name('checkout');
    Route::post('order', [OrderController::class, 'store'])->name('checkout');
});

// User profile routes
Route::middleware(['locale', 'auth', 'throttle:web'])->prefix('users')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');
    Route::patch('/orders/cancel', [OrderController::class, 'cancel'])->name('users.orders.cancel');
});

// Utility routes
Route::get('locale/{locale}', LocaleController::class)->name('lang-switch');
Route::get('queue', ArtisanController::class)->middleware('secure');
