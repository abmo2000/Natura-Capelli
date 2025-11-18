<?php

use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/contact' , function(){
    return view('web.pages.contact');
})->name('contact');

Route::get('/shop' , function(){
     return view('web.pages.shop.index');
})->name('shop');

Route::get('home' , HomeController::class);
Route::get('/singel-product' , function(){
     return view('web.pages.shop.show');
})->name('show');


Route::resource('products' , ProductController::class)->only(['show' , 'index']);

Route::prefix('cart')->group(function(){
     Route::get('' , [CartController::class , 'index'])->name('cart');
     Route::post('/add' , [CartController::class , 'add'])->name('cart.add');
     Route::patch('/update/{product_id}' , [CartController::class , 'update']);
});
