<?php

use Illuminate\Support\Facades\Route;


Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::post('/cart/add', function () {
    return redirect()->route('cart');
})->name('cart.add');

Route::post('/cart/update', function () {
    return redirect()->route('cart');
})->name('cart.update');

Route::delete('/cart/remove/{id}', function ($id) {
    return redirect()->route('cart');
})->name('cart.remove');

Route::delete('/cart/clear', function () {
    return redirect()->route('cart');
})->name('cart.clear');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::post('/checkout', function () {
    return view('checkout');
})->name('checkout.process');
