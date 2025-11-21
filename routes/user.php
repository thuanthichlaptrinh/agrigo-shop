<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Profile & Account Routes
|--------------------------------------------------------------------------
*/

Route::prefix('user')->name('user.')->middleware(['auth', 'user'])->group(function () {
    
    // Profile
    Route::get('/profile', function () {
        return view('user.profile.index');
    })->name('profile');

    Route::put('/profile', function () {
        return redirect()->route('user.profile');
    })->name('profile.update');

    Route::post('/profile/avatar', function () {
        return redirect()->route('user.profile');
    })->name('profile.avatar');

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', function () {
            return view('user.orders.index');
        })->name('index');

        Route::get('/{id}', function ($id) {
            return view('user.orders.detail', ['id' => $id]);
        })->name('show');

        Route::post('/{id}/cancel', function ($id) {
            return redirect()->route('user.orders.index');
        })->name('cancel');
    });

    // Addresses
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', function () {
            return view('user.addresses.index');
        })->name('index');

        Route::post('/', function () {
            return redirect()->route('user.addresses.index');
        })->name('store');

        Route::put('/{id}', function ($id) {
            return redirect()->route('user.addresses.index');
        })->name('update');

        Route::delete('/{id}', function ($id) {
            return redirect()->route('user.addresses.index');
        })->name('destroy');
    });

    // Wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', function () {
            return view('user.wishlist.index');
        })->name('index');

        Route::post('/add/{productId}', function ($productId) {
            return redirect()->route('user.wishlist.index');
        })->name('add');

        Route::delete('/remove/{productId}', function ($productId) {
            return redirect()->route('user.wishlist.index');
        })->name('remove');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', function () {
            return view('user.notifications.index');
        })->name('index');

        Route::post('/{id}/read', function ($id) {
            return redirect()->route('user.notifications.index');
        })->name('read');

        Route::post('/read-all', function () {
            return redirect()->route('user.notifications.index');
        })->name('readAll');
    });

    // Change Password
    Route::get('/change-password', function () {
        return view('user.profile.change-password');
    })->name('change-password');

    Route::post('/change-password', function () {
        return redirect()->route('user.profile');
    })->name('change-password.update');
});
