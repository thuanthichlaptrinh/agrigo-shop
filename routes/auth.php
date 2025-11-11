<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // Logic đăng nhập sẽ implement sau
    return redirect()->route('home');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    // Logic đăng ký sẽ implement sau
    return redirect()->route('login');
});

Route::post('/logout', function () {
    // Logic đăng xuất sẽ implement sau
    return redirect()->route('home');
})->name('logout');
