<?php

use App\Models\Banner;
use App\Models\SanPham;
use App\Models\SanPhamKhuyenMai;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Here is where you can register user-facing routes for your application.
|
*/

// Trang chủ
Route::get('/', function () {
    $homeBanners = Banner::where('ViTri', 'Trang chủ')
        ->where('TrangThai', 1)
        ->orderBy('ThuTu')
        ->get();

    $now = now();
    $flashSaleProducts = SanPhamKhuyenMai::with(['sanPham', 'khuyenMai'])
        ->whereHas('sanPham', fn ($query) => $query->where('TrangThai', 1))
        ->whereHas('khuyenMai', function ($query) use ($now) {
            $query->where('TrangThai', 1)
                ->where('NgayBatDau', '<=', $now)
                ->where('NgayKetThuc', '>=', $now);
        })
        ->orderByDesc('NgayTao')
        ->limit(4)
        ->get()
        ->map(fn ($pivot) => format_promoted_product($pivot))
        ->filter(fn ($product) => $product && ($product['is_percent'] ?? false))
        ->values();

    $favoriteProducts = SanPham::where('TrangThai', 1)
        ->where('NoiBat', 1)
        ->orderByDesc('LuotBan')
        ->limit(8)
        ->get()
        ->map(fn ($product) => [
            'id' => $product->ID,
            'name' => $product->TenSanPham,
            'price' => (float) ($product->Gia ?? 0),
            'unit' => $product->DonViTinh ?? 'Gói',
            'image' => $product->HinhAnh,
        ]);

    $regularProducts = SanPham::where('TrangThai', 1)
        ->where(function ($query) {
            $query->whereNull('NoiBat')->orWhere('NoiBat', 0);
        })
        ->orderByDesc('NgayTao')
        ->limit(12)
        ->get()
        ->map(fn ($product) => [
            'id' => $product->ID,
            'name' => $product->TenSanPham,
            'price' => (float) ($product->Gia ?? 0),
            'unit' => $product->DonViTinh ?? 'Gói',
            'image' => $product->HinhAnh,
        ]);

    return view('user.home', compact('homeBanners', 'flashSaleProducts', 'favoriteProducts', 'regularProducts'));
})->name('user.home');

// Trang không có quyền truy cập
Route::get('/unauthorized', function () {
    return view('errors.unauthorized');
})->name('unauthorized');

/*
|--------------------------------------------------------------------------
| Include Route Files
|--------------------------------------------------------------------------
*/
    
// Authentication routes
require __DIR__.'/auth.php';

// Product routes
require __DIR__.'/product.php';

// Cart routes
require __DIR__.'/cart.php';

// User profile routes
require __DIR__.'/user.php';

// Admin routes
require __DIR__.'/admin.php';
