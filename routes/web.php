<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProductsApiCpntroller;

Route::get('/', HomeController::class)->name('home');

Route::get('/contact' , function(){
    return view('web.pages.contact');
})->name('contact');

Route::view('about-us', 'web.pages.about-us')->name('about-us');

Route::get('/shop' , [ShopController::class, 'index'])->name('shop');
Route::get('api/products' , ProductsApiCpntroller::class);

Route::resource('products' , ProductController::class)->only(['show' , 'index']);

Route::prefix('cart')->group(function(){
     Route::get('' , [CartController::class , 'index'])->name('cart');
     Route::post('/add' , [CartController::class , 'add'])->name('cart.add');
     Route::patch('/update/{product_id}' , [CartController::class , 'update']);
     Route::delete('remove/{product_id}' , [CartController::class , 'remove']);
     Route::delete('clear' , [CartController::class , 'clear']);
});
Route::post('order' , [OrderController::class , 'store']);