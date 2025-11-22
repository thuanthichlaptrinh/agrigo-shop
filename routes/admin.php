<?php

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\LoaiSanPhamController;
use App\Http\Controllers\Admin\DanhMucController;
use App\Http\Controllers\Admin\NhaCungCapController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\NhatKyController;
use App\Http\Controllers\Admin\ThongBaoController;
use App\Http\Controllers\Admin\VaiTroController;
use App\Http\Controllers\Admin\ProductController;
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

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    
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
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::post('/bulk', [ProductController::class, 'bulkStore'])->name('bulk-store');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    });
    
    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
    });
    
    // Categories Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [LoaiSanPhamController::class, 'index'])->name('index');
        Route::post('/', [LoaiSanPhamController::class, 'store'])->name('store');
        Route::post('/bulk', [LoaiSanPhamController::class, 'bulkStore'])->name('bulk-store');
        Route::put('/{id}', [LoaiSanPhamController::class, 'update'])->name('update');
        Route::delete('/{id}', [LoaiSanPhamController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [LoaiSanPhamController::class, 'show'])->name('show');
    });

    // Catalog Management
    Route::prefix('catalog')->name('catalog.')->group(function () {
        Route::get('/', [DanhMucController::class, 'index'])->name('index');
        Route::post('/', [DanhMucController::class, 'store'])->name('store');
        Route::post('/bulk', [DanhMucController::class, 'bulkStore'])->name('bulk-store');
        Route::put('/{id}', [DanhMucController::class, 'update'])->name('update');
        Route::delete('/{id}', [DanhMucController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [DanhMucController::class, 'show'])->name('show');
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
        Route::get('/', [NhaCungCapController::class, 'index'])->name('index');
        Route::post('/', [NhaCungCapController::class, 'store'])->name('store');
        Route::post('/bulk', [NhaCungCapController::class, 'bulkStore'])->name('bulk-store');
        Route::put('/{id}', [NhaCungCapController::class, 'update'])->name('update');
        Route::delete('/{id}', [NhaCungCapController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [NhaCungCapController::class, 'show'])->name('show');
    });

    // Promotions Management
    Route::prefix('promotions')->name('promotions.')->group(function () {
        Route::get('/', [KhuyenMaiController::class, 'index'])->name('index');
        Route::post('/', [KhuyenMaiController::class, 'store'])->name('store');
        Route::put('/{id}', [KhuyenMaiController::class, 'update'])->name('update');
        Route::delete('/{id}', [KhuyenMaiController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [KhuyenMaiController::class, 'show'])->name('show');
    });

    // Notifications Management
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [ThongBaoController::class, 'index'])->name('index');
        Route::post('/', [ThongBaoController::class, 'store'])->name('store');
        Route::put('/{id}', [ThongBaoController::class, 'update'])->name('update');
        Route::delete('/{id}', [ThongBaoController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [ThongBaoController::class, 'show'])->name('show');
    });

    // Activity Logs Management
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [NhatKyController::class, 'index'])->name('index');
        Route::post('/', [NhatKyController::class, 'store'])->name('store');
        Route::put('/{id}', [NhatKyController::class, 'update'])->name('update');
        Route::delete('/{id}', [NhatKyController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [NhatKyController::class, 'show'])->name('show');
    });

    // Roles Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [VaiTroController::class, 'index'])->name('index');
        Route::post('/', [VaiTroController::class, 'store'])->name('store');
        Route::put('/{id}', [VaiTroController::class, 'update'])->name('update');
        Route::delete('/{id}', [VaiTroController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [VaiTroController::class, 'show'])->name('show');
    });

    // Vouchers Management
    Route::prefix('vouchers')->name('vouchers.')->group(function () {
        Route::get('/', [VoucherController::class, 'index'])->name('index');
        Route::post('/', [VoucherController::class, 'store'])->name('store');
        Route::post('/bulk', [VoucherController::class, 'bulkStore'])->name('bulk-store');
        Route::put('/{id}', [VoucherController::class, 'update'])->name('update');
        Route::delete('/{id}', [VoucherController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [VoucherController::class, 'show'])->name('show');
    });
    
    // Utility Routes
    Route::get('/search', function () {
        return redirect()->route('admin.dashboard');
    })->name('search');
    
    Route::get('/messages', function () {
        return redirect()->route('admin.dashboard');
    })->name('messages');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    
    Route::get('/settings', function () {
        return redirect()->route('admin.dashboard');
    })->name('settings');
});
