<?php

use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/

// Cart routes for users
Route::prefix('cart')->name('user.cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::post('/reorder', [CartController::class, 'reorder'])->name('reorder');
});

// Checkout routes
Route::prefix('checkout')->name('user.checkout.')->group(function () {
    Route::get('/payment', [CheckoutController::class, 'payment'])->name('payment');
    Route::post('/voucher', [CheckoutController::class, 'applyVoucher'])->name('voucher');
    Route::post('/confirm', [CheckoutController::class, 'confirm'])->name('confirm');
    Route::post('/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
    Route::get('/edit', [CheckoutController::class, 'edit'])->name('edit');
    Route::post('/edit', [CheckoutController::class, 'update'])->name('update');
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'process'])->name('process');
});
