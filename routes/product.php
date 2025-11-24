<?php

use App\Http\Controllers\User\ProductController;
use Illuminate\Support\Facades\Route;

// Product routes for users
Route::prefix('products')->name('user.products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductController::class, 'show'])->name('detail');
    Route::post('/{id}/reviews', [ProductController::class, 'storeReview'])
        ->middleware('auth')
        ->name('reviews.store');
});

// Search
Route::get('/search', function () {
    return redirect()->back();
})->name('user.search');

// Category
Route::get('/category/{slug}', function ($slug) {
    return view('user.products.index', ['category' => $slug]);
})->name('user.category');
