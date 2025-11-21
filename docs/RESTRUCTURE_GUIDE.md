# Hướng dẫn Cấu trúc Mới - Organic Shop

## Cấu trúc thư mục Views

```
resources/views/
├── admin/                          # Views cho Admin
│   ├── layouts/
│   │   └── app.blade.php          # Layout chính cho admin
│   ├── partials/
│   │   ├── sidebar.blade.php      # Sidebar admin
│   │   └── navbar.blade.php       # Navbar admin
│   ├── dashboard.blade.php        # Trang tổng quan
│   ├── products/
│   │   ├── index.blade.php        # Danh sách sản phẩm
│   │   ├── create.blade.php       # Thêm sản phẩm
│   │   └── edit.blade.php         # Sửa sản phẩm
│   ├── users/
│   │   └── index.blade.php        # Quản lý người dùng
│   ├── categories/
│   │   └── index.blade.php        # Quản lý danh mục
│   └── orders/
│       └── index.blade.php        # Quản lý đơn hàng
│
├── user/                           # Views cho User
│   ├── layouts/
│   │   └── app.blade.php          # Layout chính cho user
│   ├── partials/
│   │   ├── header.blade.php       # Header
│   │   ├── footer.blade.php       # Footer
│   │   ├── sidebar.blade.php      # Sidebar
│   │   └── chatbot-widget.blade.php # Chatbot
│   ├── home.blade.php             # Trang chủ
│   ├── products/
│   │   ├── index.blade.php        # Danh sách sản phẩm
│   │   └── detail.blade.php       # Chi tiết sản phẩm
│   ├── cart/
│   │   ├── index.blade.php        # Giỏ hàng
│   │   └── checkout.blade.php     # Thanh toán
│   └── orders/
│       └── index.blade.php        # Đơn hàng của tôi
│
└── auth/                           # Views xác thực (chung)
    ├── login.blade.php
    └── register.blade.php
```

## Routes cần cập nhật

### Admin Routes (routes/web.php)
```php
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Products
    Route::resource('products', AdminProductController::class);
    
    // Users
    Route::resource('users', AdminUserController::class);
    
    // Categories
    Route::resource('categories', AdminCategoryController::class);
    
    // Orders
    Route::resource('orders', AdminOrderController::class);
    
    // Suppliers
    Route::resource('suppliers', AdminSupplierController::class);
});
```

### User Routes
```php
Route::name('user.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.detail');
    
    Route::middleware('auth')->group(function () {
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    });
});
```

## Cập nhật Controllers

### Cần tạo các controllers mới:
- `App\Http\Controllers\Admin\AdminController`
- `App\Http\Controllers\Admin\ProductController`
- `App\Http\Controllers\Admin\UserController`
- `App\Http\Controllers\Admin\CategoryController`
- `App\Http\Controllers\Admin\OrderController`
- `App\Http\Controllers\Admin\SupplierController`

### Cập nhật views trong controllers hiện tại:
- Thay `'home'` → `'user.home'`
- Thay `'products'` → `'user.products.index'`
- Thay `'product-detail'` → `'user.products.detail'`
- Thay `'cart'` → `'user.cart.index'`
- Thay `'checkout'` → `'user.cart.checkout'`
- Thay `'layouts.app'` → `'user.layouts.app'`

## Middleware cần tạo

### AdminMiddleware
```php
php artisan make:middleware AdminMiddleware
```

Kiểm tra role admin trong middleware này.

## Assets

### Admin Assets (đã có):
- `/public/template/Admin/style.css`
- `/public/template/Admin/products.css`
- `/public/template/Admin/script.js`

### User Assets (đã có):
- `/public/template/Assets/css/*`
- `/public/template/Assets/js/*`

## Lưu ý quan trọng

1. **Cập nhật tất cả `@extends` và `@include`** trong các file blade
2. **Cập nhật routes** trong file `routes/web.php`
3. **Tạo middleware** để phân quyền admin
4. **Cập nhật controllers** để trả về views mới
5. **Test kỹ** từng trang sau khi cập nhật

## Checklist

- [ ] Tạo admin controllers
- [ ] Tạo admin middleware
- [ ] Cập nhật routes
- [ ] Cập nhật @extends trong user views
- [ ] Cập nhật @include trong user views
- [ ] Test admin dashboard
- [ ] Test user pages
- [ ] Test authentication flow
