<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/

// Cart routes for users
Route::prefix('cart')->name('user.cart.')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('user.cart.index');
    })->name('index');
    
    Route::post('/add', function () {
        return redirect()->route('user.cart.index');
    })->name('add');
    
    Route::post('/update', function () {
        return redirect()->route('user.cart.index');
    })->name('update');
    
    Route::delete('/remove/{id}', function ($id) {
        return redirect()->route('user.cart.index');
    })->name('remove');
    
    Route::delete('/clear', function () {
        return redirect()->route('user.cart.index');
    })->name('clear');
});

// Checkout routes
Route::prefix('checkout')->name('user.checkout.')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('user.cart.checkout');
    })->name('index');
    
    Route::post('/', function () {
        return redirect()->route('user.orders.index');
    })->name('process');
});
