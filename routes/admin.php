<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'totalOrders' => 0,
            'totalRevenue' => 0,
            'totalProducts' => 0,
            'totalUsers' => 0,
            'recentOrders' => [],
            'chartData' => [],
            'chartLabels' => []
        ]);
    })->name('dashboard');
    
    // Products Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', function () {
            return view('admin.products.index', [
                'products' => [],
                'categories' => []
            ]);
        })->name('index');
        
        Route::get('/create', function () {
            return view('admin.products.create');
        })->name('create');
        
        Route::post('/', function () {
            return redirect()->route('admin.products.index');
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) {
            return view('admin.products.edit', ['id' => $id]);
        })->name('edit');
        
        Route::put('/{id}', function ($id) {
            return redirect()->route('admin.products.index');
        })->name('update');
        
        Route::delete('/{id}', function ($id) {
            return redirect()->route('admin.products.index');
        })->name('destroy');
    });
    
    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', function () {
            return view('admin.users.index');
        })->name('index');
        
        Route::get('/create', function () {
            return view('admin.users.create');
        })->name('create');
        
        Route::post('/', function () {
            return redirect()->route('admin.users.index');
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) {
            return view('admin.users.edit', ['id' => $id]);
        })->name('edit');
        
        Route::put('/{id}', function ($id) {
            return redirect()->route('admin.users.index');
        })->name('update');
        
        Route::delete('/{id}', function ($id) {
            return redirect()->route('admin.users.index');
        })->name('destroy');
    });
    
    // Categories Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', function () {
            return view('admin.categories.index');
        })->name('index');
        
        Route::get('/create', function () {
            return view('admin.categories.create');
        })->name('create');
        
        Route::post('/', function () {
            return redirect()->route('admin.categories.index');
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) {
            return view('admin.categories.edit', ['id' => $id]);
        })->name('edit');
        
        Route::put('/{id}', function ($id) {
            return redirect()->route('admin.categories.index');
        })->name('update');
        
        Route::delete('/{id}', function ($id) {
            return redirect()->route('admin.categories.index');
        })->name('destroy');
    });
    
    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', function () {
            return view('admin.orders.index');
        })->name('index');
        
        Route::get('/{id}', function ($id) {
            return view('admin.orders.show', ['id' => $id]);
        })->name('show');
        
        Route::put('/{id}/status', function ($id) {
            return redirect()->route('admin.orders.index');
        })->name('updateStatus');
        
        Route::delete('/{id}', function ($id) {
            return redirect()->route('admin.orders.index');
        })->name('destroy');
    });
    
    // Suppliers Management
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', function () {
            return view('admin.suppliers.index');
        })->name('index');
        
        Route::get('/create', function () {
            return view('admin.suppliers.create');
        })->name('create');
        
        Route::post('/', function () {
            return redirect()->route('admin.suppliers.index');
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) {
            return view('admin.suppliers.edit', ['id' => $id]);
        })->name('edit');
        
        Route::put('/{id}', function ($id) {
            return redirect()->route('admin.suppliers.index');
        })->name('update');
        
        Route::delete('/{id}', function ($id) {
            return redirect()->route('admin.suppliers.index');
        })->name('destroy');
    });
    
    // Utility Routes
    Route::get('/search', function () {
        return redirect()->route('admin.dashboard');
    })->name('search');
    
    Route::get('/notifications', function () {
        return redirect()->route('admin.dashboard');
    })->name('notifications');
    
    Route::get('/messages', function () {
        return redirect()->route('admin.dashboard');
    })->name('messages');
    
    Route::get('/profile', function () {
        return redirect()->route('admin.dashboard');
    })->name('profile');
    
    Route::get('/settings', function () {
        return redirect()->route('admin.dashboard');
    })->name('settings');
});
