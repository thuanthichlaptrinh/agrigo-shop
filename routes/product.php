<?php

use Illuminate\Support\Facades\Route;

// Product routes for users
Route::prefix('products')->name('user.products.')->group(function () {
    Route::get('/', function () {
        return view('user.products.index');
    })->name('index');
    
    Route::get('/{id}', function ($id) {
        return view('user.products.detail', ['product' => ['id' => $id]]);
    })->name('detail');
});

// Search
Route::get('/search', function () {
    return redirect()->back();
})->name('user.search');

// Category
Route::get('/category/{slug}', function ($slug) {
    return view('user.products.index', ['category' => $slug]);
})->name('user.category');
