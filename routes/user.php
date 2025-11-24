<?php

use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Profile & Account Routes
|--------------------------------------------------------------------------
*/

Route::prefix('user')->name('user.')->middleware('user')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('user.profile', ['section' => 'orders']);
        })->name('index');

        Route::get('/{order}', [ProfileController::class, 'showOrder'])->name('show');
        Route::post('/{order}/cancel', [ProfileController::class, 'cancelOrder'])->name('cancel');
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
            return redirect()->route('user.profile', ['section' => 'wishlist']);
        })->name('index');

        Route::post('/add/{productId}', [ProfileController::class, 'addToWishlist'])->name('add');
        Route::delete('/remove/{productId}', [ProfileController::class, 'removeFromWishlist'])->name('remove');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('user.profile', ['section' => 'notifications']);
        })->name('index');

        Route::post('/{notification}/read', [ProfileController::class, 'markNotificationRead'])->name('read');
        Route::post('/read-all', [ProfileController::class, 'markAllNotificationsRead'])->name('readAll');
    });

    // Change Password
    Route::get('/change-password', function () {
        return redirect()->route('user.profile', ['section' => 'password']);
    })->name('change-password');

    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password.update');
});
