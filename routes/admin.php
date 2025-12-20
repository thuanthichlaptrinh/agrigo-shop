<?php

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\LoaiSanPhamController;
use App\Http\Controllers\Admin\DanhMucController;
use App\Http\Controllers\Admin\NhaCungCapController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\ProductPromotionController;
use App\Http\Controllers\Admin\NhatKyController;
use App\Http\Controllers\Admin\ThongBaoController;
use App\Http\Controllers\Admin\VaiTroController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\AdminChatController;
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
        // Tổng số liệu cơ bản
        $totalOrders = \App\Models\DonHang::count();
        $totalRevenue = \App\Models\DonHang::whereIn('TrangThai', ['Đã giao', 'Đang giao'])->sum('TongThanhToan');
        $totalProducts = \App\Models\SanPham::count();
        $totalUsers = \App\Models\NguoiDung::count();
        
        // Tính % tăng trưởng so với tháng trước
        $lastMonthOrders = \App\Models\DonHang::whereMonth('NgayDat', now()->subMonth()->month)
            ->whereYear('NgayDat', now()->subMonth()->year)
            ->count();
        $currentMonthOrders = \App\Models\DonHang::whereMonth('NgayDat', now()->month)
            ->whereYear('NgayDat', now()->year)
            ->count();
        $orderGrowth = $lastMonthOrders > 0 ? round((($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100) : 0;
        
        $lastMonthRevenue = \App\Models\DonHang::whereMonth('NgayDat', now()->subMonth()->month)
            ->whereYear('NgayDat', now()->subMonth()->year)
            ->whereIn('TrangThai', ['Đã giao', 'Đang giao'])
            ->sum('TongThanhToan');
        $currentMonthRevenue = \App\Models\DonHang::whereMonth('NgayDat', now()->month)
            ->whereYear('NgayDat', now()->year)
            ->whereIn('TrangThai', ['Đã giao', 'Đang giao'])
            ->sum('TongThanhToan');
        $revenueGrowth = $lastMonthRevenue > 0 ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100) : 0;
        
        // Tính tăng trưởng người dùng (sử dụng NgayTao thay vì created_at)
        $lastMonthUsers = \App\Models\NguoiDung::whereMonth('NgayTao', now()->subMonth()->month)
            ->whereYear('NgayTao', now()->subMonth()->year)
            ->count();
        $currentMonthUsers = \App\Models\NguoiDung::whereMonth('NgayTao', now()->month)
            ->whereYear('NgayTao', now()->year)
            ->count();
        $userGrowth = $lastMonthUsers > 0 ? round((($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100) : 0;
        
        // Recent orders (last 10)
        $recentOrders = \App\Models\DonHang::with('nguoiDung')
            ->latest('NgayDat')
            ->limit(10)
            ->get();
        
        // Chart data for last 7 days
        $chartData = [];
        $chartLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d/m');
            
            $dayRevenue = \App\Models\DonHang::whereDate('NgayDat', $date)
                ->whereIn('TrangThai', ['Đã giao', 'Đang giao'])
                ->sum('TongThanhToan');
            
            $chartData[] = (int) $dayRevenue;
        }
        
        // Top 5 sản phẩm bán chạy
        $topProducts = \App\Models\ChiTietDonHang::select('IDSanPham', \DB::raw('SUM(SoLuong) as total_sold'), \DB::raw('SUM(SoLuong * DonGia) as total_revenue'))
            ->with('sanPham')
            ->groupBy('IDSanPham')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->sanPham->TenSanPham ?? 'N/A',
                    'sold' => $item->total_sold,
                    'revenue' => $item->total_revenue
                ];
            });
        
        // Thống kê trạng thái đơn hàng
        $orderStatusStats = [
            'delivered' => \App\Models\DonHang::where('TrangThai', 'Đã giao')->count(),
            'shipping' => \App\Models\DonHang::where('TrangThai', 'Đang giao')->count(),
            'pending' => \App\Models\DonHang::where('TrangThai', 'Chờ xác nhận')->count(),
            'cancelled' => \App\Models\DonHang::where('TrangThai', 'Đã hủy')->count(),
        ];
        
        // Thống kê cảnh báo
        $lowStockProducts = \App\Models\SanPham::where('SoLuongTon', '<', 10)->count();
        $pendingOrders = \App\Models\DonHang::where('TrangThai', 'Chờ xác nhận')->count();
        $todayNewUsers = \App\Models\NguoiDung::whereDate('NgayTao', today())->count();
        
        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'orderGrowth' => $orderGrowth,
            'revenueGrowth' => $revenueGrowth,
            'userGrowth' => $userGrowth,
            'recentOrders' => $recentOrders,
            'chartData' => $chartData,
            'chartLabels' => $chartLabels,
            'topProducts' => $topProducts,
            'orderStatusStats' => $orderStatusStats,
            'lowStockProducts' => $lowStockProducts,
            'pendingOrders' => $pendingOrders,
            'todayNewUsers' => $todayNewUsers,
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

    // Chat với khách hàng
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [AdminChatController::class, 'index'])->name('index');
        Route::get('/conversations', [AdminChatController::class, 'getConversations'])->name('conversations');
        Route::get('/conversations/stats', [AdminChatController::class, 'getStats'])->name('stats');
        Route::get('/conversations/{id}/messages', [AdminChatController::class, 'getMessages'])->name('messages');
        Route::post('/conversations/{id}/assign', [AdminChatController::class, 'assignConversation'])->name('assign');
        Route::post('/conversations/{id}/close', [AdminChatController::class, 'closeConversation'])->name('close');
        Route::post('/messages/send', [AdminChatController::class, 'sendMessage'])->name('send');
        Route::post('/typing', [AdminChatController::class, 'typing'])->name('typing');
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
    
    // Banners Management
    Route::prefix('banners')->name('banners.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\BannerController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [\App\Http\Controllers\Admin\BannerController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\OrderController::class, 'store'])->name('store');
        Route::post('/bulk', [\App\Http\Controllers\Admin\OrderController::class, 'bulkStore'])->name('bulk-store');
        Route::post('/{id}/approve', [\App\Http\Controllers\Admin\OrderController::class, 'approve'])->name('approve');
        Route::post('/{id}/cancel', [\App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('cancel');
        Route::put('/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('show');
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

    // Product Promotions (Product - Promotion pivot)
    Route::prefix('product-promotions')->name('product-promotions.')->group(function () {
        Route::get('/', [ProductPromotionController::class, 'index'])->name('index');
        Route::post('/', [ProductPromotionController::class, 'store'])->name('store');
        Route::post('/bulk', [ProductPromotionController::class, 'bulkStore'])->name('bulk-store');
        Route::get('/{product}/{promotion}', [ProductPromotionController::class, 'show'])->name('show');
        Route::put('/{product}/{promotion}', [ProductPromotionController::class, 'update'])->name('update');
        Route::delete('/{product}/{promotion}', [ProductPromotionController::class, 'destroy'])->name('destroy');
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
    
    // Articles Management
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [BaiVietController::class, 'index'])->name('index');
        Route::get('/create', [BaiVietController::class, 'create'])->name('create');
        Route::post('/', [BaiVietController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BaiVietController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BaiVietController::class, 'update'])->name('update');
        Route::delete('/{id}', [BaiVietController::class, 'destroy'])->name('destroy');
    });

    // Chat Management - Đã được định nghĩa ở trên (xem phần "Chat với khách hàng")

    // Utility Routes
    Route::get('/search', function () {
        return redirect()->route('admin.dashboard');
    })->name('search');
    
    Route::get('/messages', function () {
        return redirect()->route('admin.chat.index');
    })->name('messages');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    
    Route::get('/settings', function () {
        return redirect()->route('admin.dashboard');
    })->name('settings');
});
