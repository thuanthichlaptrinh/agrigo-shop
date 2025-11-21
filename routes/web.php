<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Here is where you can register user-facing routes for your application.
|
*/

// Trang chủ
Route::get('/', function () {
    return view('user.home');
})->name('user.home');

/*
|--------------------------------------------------------------------------
| Include Route Files
|--------------------------------------------------------------------------
*/

// Authentication routes
require __DIR__.'/auth.php';

// Product routes
require __DIR__.'/product.php';

// Cart routes
require __DIR__.'/cart.php';

// User profile routes
require __DIR__.'/user.php';

// Admin routes
require __DIR__.'/admin.php';
