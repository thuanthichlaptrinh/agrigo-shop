<?php

use App\Models\SanPhamKhuyenMai;
use Illuminate\Support\Facades\Route;

// Product routes for users
Route::prefix('products')->name('user.products.')->group(function () {
    Route::get('/', function () {
        $now = now();
        $promotedProducts = SanPhamKhuyenMai::with(['sanPham', 'khuyenMai'])
            ->whereHas('sanPham', fn ($query) => $query->where('TrangThai', 1))
            ->whereHas('khuyenMai', function ($query) use ($now) {
                $query->where('TrangThai', 1)
                    ->where('NgayBatDau', '<=', $now)
                    ->where('NgayKetThuc', '>=', $now);
            })
            ->orderByDesc('NgayTao')
            ->get()
            ->map(fn ($pivot) => format_promoted_product($pivot))
            ->filter()
            ->values();

        return view('user.products.index', [
            'products' => $promotedProducts,
            'categoryName' => 'Sản phẩm khuyến mãi',
        ]);
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
