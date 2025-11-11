<?php

use Illuminate\Support\Facades\Route;

Route::get('/products', function () {
    return view('products');
})->name('products');

Route::get('/product/{id}', function ($id) {
    return view('product-detail', ['product' => ['id' => $id]]);
})->name('product-detail');

Route::get('/search', function () {
    return redirect()->back();
})->name('search');