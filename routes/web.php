<?php

use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get('/', function () {
    return view('home');
})->name('home');

require __DIR__.'/auth.php';
require __DIR__.'/product.php';
require __DIR__.'/cart.php';
require __DIR__.'/user.php';
