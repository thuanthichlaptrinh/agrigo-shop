<?php

use App\Http\Controllers\User\ArticleController;
use App\Models\Banner;
use App\Models\BaiViet;
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

    $productBanners = Banner::where('ViTri', 'Sản phẩm')
        ->where('TrangThai', 1)
        ->orderBy('ThuTu')
        ->get();

    $now = now();
    $activePromotionQuery = fn ($query) => $query->where('TrangThai', 1)
        ->where('NgayBatDau', '<=', $now)
        ->where('NgayKetThuc', '>=', $now);

    $flashSaleProducts = SanPhamKhuyenMai::with(['sanPham', 'khuyenMai'])
        ->whereHas('sanPham', fn ($query) => $query->where('TrangThai', 1))
        ->whereHas('khuyenMai', $activePromotionQuery)
        ->orderByDesc('NgayTao')
        ->limit(4)
        ->get()
        ->map(fn ($pivot) => format_promoted_product($pivot))
        ->filter(fn ($product) => $product && ($product['is_percent'] ?? false))
        ->values();

    $brandDealProducts = SanPhamKhuyenMai::with(['sanPham', 'khuyenMai'])
        ->whereHas('sanPham', fn ($query) => $query->where('TrangThai', 1))
        ->whereHas('khuyenMai', $activePromotionQuery)
        ->orderByDesc('NgayTao')
        ->get()
        ->map(fn ($pivot) => format_promoted_product($pivot))
        ->filter(fn ($product) => $product && ($product['is_percent'] ?? false) && (($product['discount_percent'] ?? 0) >= 50))
        ->take(4)
        ->values();

    $activePromotionConstraint = function ($query) use ($now) {
        $query->where('KhuyenMai.TrangThai', 1)
            ->where('KhuyenMai.NgayBatDau', '<=', $now)
            ->where('KhuyenMai.NgayKetThuc', '>=', $now);
    };

    $formatProduct = function (SanPham $product) {
        $promotion = $product->khuyenMai->first();
        $pricing = $promotion ? calculate_promotion_pricing($product, $promotion) : null;

        return [
            'id' => $product->ID,
            'name' => $product->TenSanPham,
            'final_price' => $pricing['final_price'] ?? (float) ($product->Gia ?? 0),
            'original_price' => (float) ($product->Gia ?? 0),
            'unit' => $product->DonViTinh ?? 'Gói',
            'image' => $product->HinhAnh,
            'discount_percent' => $pricing['discount_percent'] ?? 0,
            'has_discount' => (bool) $pricing,
        ];
    };

    $favoriteProducts = SanPham::with(['khuyenMai' => $activePromotionConstraint])
        ->where('TrangThai', 1)
        ->where('NoiBat', 1)
        ->orderByDesc('LuotBan')
        ->limit(8)
        ->get()
        ->map($formatProduct);

    $categoryProductSections = SanPham::with(['khuyenMai' => $activePromotionConstraint])
        ->select([
            'SanPham.ID',
            'SanPham.TenSanPham',
            'SanPham.Gia',
            'SanPham.DonViTinh',
            'SanPham.HinhAnh',
            'SanPham.LuotBan',
            'SanPham.NgayTao',
            'DanhMuc.ID as category_id',
            'DanhMuc.TenDanhMuc as category_name',
            'DanhMuc.ThuTu as category_order',
        ])
        ->join('LoaiSanPham', 'SanPham.IDLoaiSP', '=', 'LoaiSanPham.ID')
        ->join('DanhMuc', 'LoaiSanPham.IDDanhMuc', '=', 'DanhMuc.ID')
        ->where('SanPham.TrangThai', 1)
        ->where('LoaiSanPham.TrangThai', 1)
        ->where('DanhMuc.TrangThai', 1)
        ->orderBy('DanhMuc.ThuTu')
        ->orderBy('DanhMuc.TenDanhMuc')
        ->orderByDesc('SanPham.LuotBan')
        ->orderByDesc('SanPham.NgayTao')
        ->get()
        ->groupBy('category_id')
        ->sortBy(fn ($products) => $products->first()->category_order ?? 0)
        ->map(function ($products) use ($formatProduct) {
            $first = $products->first();

            return [
                'id' => $first->category_id,
                'name' => $first->category_name,
                'products' => $products->take(4)->map($formatProduct)->values(),
            ];
        })
        ->values();

    $homeArticles = BaiViet::with(['nguoiDung'])
        ->where('TrangThai', 1)
        ->orderByDesc('NgayTao')
        ->limit(5)
        ->get();

    return view('user.home', compact(
        'homeBanners',
        'productBanners',
        'flashSaleProducts',
        'brandDealProducts',
        'favoriteProducts',
        'categoryProductSections',
        'homeArticles'
    ));
})->name('user.home');

// Bài viết - trang danh sách & chi tiết
Route::get('/bai-viet', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/bai-viet/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Liên hệ
Route::get('/lien-he', [\App\Http\Controllers\User\ContactController::class, 'show'])->name('user.contact.show');
Route::post('/lien-he', [\App\Http\Controllers\User\ContactController::class, 'store'])->name('user.contact.submit');

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
