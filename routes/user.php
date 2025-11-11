<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::put('/profile', function () {
        return redirect()->route('profile');
    })->name('profile.update');

    Route::post('/profile/avatar', function () {
        return redirect()->route('profile');
    })->name('profile.avatar');

    // Orders
    Route::get('/orders', function () {
        return view('orders');
    })->name('orders');

    Route::get('/orders/{id}', function ($id) {
        return view('order-detail');
    })->name('orders.show');

    Route::post('/orders/{id}/cancel', function ($id) {
        return redirect()->route('orders');
    })->name('orders.cancel');

    // Addresses
    Route::get('/addresses', function () {
        return view('addresses');
    })->name('addresses');

    Route::post('/addresses', function () {
        return redirect()->route('addresses');
    })->name('addresses.store');

    Route::put('/addresses/{id}', function ($id) {
        return redirect()->route('addresses');
    })->name('addresses.update');

    Route::delete('/addresses/{id}', function ($id) {
        return redirect()->route('addresses');
    })->name('addresses.destroy');

    // Wishlist
    Route::get('/wishlist', function () {
        return view('wishlist');
    })->name('wishlist');

    Route::post('/wishlist/add/{productId}', function ($productId) {
        return redirect()->route('wishlist');
    })->name('wishlist.add');

    Route::delete('/wishlist/remove/{productId}', function ($productId) {
        return redirect()->route('wishlist');
    })->name('wishlist.remove');

    // Notifications
    Route::get('/notifications', function () {
        return view('notifications');
    })->name('notifications');

    Route::post('/notifications/{id}/read', function ($id) {
        return redirect()->route('notifications');
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        return redirect()->route('notifications');
    })->name('notifications.readAll');

    // Change Password
    Route::get('/change-password', function () {
        return view('change-password');
    })->name('change-password');

    Route::post('/change-password', function () {
        return redirect()->route('profile');
    })->name('change-password.update');
});
