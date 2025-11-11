# Hướng dẫn tổ chức Routes trong Laravel

## 📁 Cấu trúc Routes đã chia

```
routes/
├── web.php          # Route chính + trang chủ + load các file route khác
├── auth.php         # Authentication (login, register, logout)
├── product.php      # Sản phẩm (danh sách, chi tiết, tìm kiếm)
├── cart.php         # Giỏ hàng & Thanh toán
├── user.php         # Profile user (cần đăng nhập)
├── api.php          # API routes (mặc định Laravel)
└── console.php      # Console commands (mặc định Laravel)
```

## 🎯 Lợi ích của việc chia Routes

1. **Dễ quản lý**: Mỗi file chứa routes liên quan đến 1 chức năng
2. **Dễ bảo trì**: Tìm và sửa route nhanh chóng
3. **Dễ phát triển**: Team có thể làm việc song song trên các file khác nhau
4. **Code sạch hơn**: Mỗi file ngắn gọn, dễ đọc

## 📝 Chi tiết các file Routes

### 1. **web.php** (File chính)

-   Trang chủ
-   Load tất cả các file route khác

```php
require __DIR__.'/auth.php';
require __DIR__.'/product.php';
require __DIR__.'/cart.php';
require __DIR__.'/user.php';
```

### 2. **auth.php** (Authentication)

Routes liên quan đến xác thực:

-   `GET /login` - Hiển thị form đăng nhập
-   `POST /login` - Xử lý đăng nhập
-   `GET /register` - Hiển thị form đăng ký
-   `POST /register` - Xử lý đăng ký
-   `POST /logout` - Đăng xuất

### 3. **product.php** (Sản phẩm)

Routes liên quan đến sản phẩm:

-   `GET /products` - Danh sách sản phẩm
-   `GET /product/{id}` - Chi tiết sản phẩm
-   `GET /search` - Tìm kiếm sản phẩm
-   `GET /category/{slug}` - Sản phẩm theo danh mục

### 4. **cart.php** (Giỏ hàng & Thanh toán)

Routes liên quan đến giỏ hàng:

-   `GET /cart` - Xem giỏ hàng
-   `POST /cart/add` - Thêm vào giỏ
-   `POST /cart/update` - Cập nhật số lượng
-   `DELETE /cart/remove/{id}` - Xóa sản phẩm
-   `DELETE /cart/clear` - Xóa toàn bộ
-   `GET /checkout` - Trang thanh toán
-   `POST /checkout` - Xử lý thanh toán

### 5. **user.php** (User Profile - Cần đăng nhập)

Routes cần authentication:

-   `GET /profile` - Xem thông tin
-   `PUT /profile` - Cập nhật thông tin
-   `POST /profile/avatar` - Đổi avatar
-   `GET /orders` - Danh sách đơn hàng
-   `GET /orders/{id}` - Chi tiết đơn hàng
-   `POST /orders/{id}/cancel` - Hủy đơn hàng
-   `GET /addresses` - Quản lý địa chỉ
-   `GET /wishlist` - Sản phẩm yêu thích
-   `GET /notifications` - Thông báo
-   `GET /change-password` - Đổi mật khẩu

## 🔄 Các cách chia Routes khác

### **Cách 2: Dùng Route Group với Prefix**

```php
// routes/web.php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
});

// URL: /admin/dashboard
// Route name: admin.dashboard
```

### **Cách 3: Dùng Controller**

Thay vì closure, dùng Controller (Recommended cho production):

```php
// routes/product.php
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product-detail');
```

### **Cách 4: Route Resource (cho CRUD)**

```php
// routes/web.php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

Tự động tạo 7 routes:

-   `GET /products` - index
-   `GET /products/create` - create
-   `POST /products` - store
-   `GET /products/{id}` - show
-   `GET /products/{id}/edit` - edit
-   `PUT/PATCH /products/{id}` - update
-   `DELETE /products/{id}` - destroy

### **Cách 5: Route Group với Middleware**

```php
// routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    require __DIR__.'/user.php';
});

// Hoặc
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::get('/orders', [OrderController::class, 'index']);
});
```

### **Cách 6: Chia theo Module (Advanced)**

```
routes/
├── web.php
├── api.php
└── modules/
    ├── product/
    │   ├── web.php
    │   └── api.php
    ├── user/
    │   ├── web.php
    │   └── api.php
    └── order/
        ├── web.php
        └── api.php
```

Load trong `RouteServiceProvider`:

```php
foreach (glob(base_path('routes/modules/*/web.php')) as $file) {
    require $file;
}
```

## ⚡ Best Practices

### 1. **Đặt tên Route**

```php
// ✅ Good - Dễ nhớ, có nghĩa
Route::get('/products', ...)->name('products');
Route::get('/product/{id}', ...)->name('product-detail');

// ❌ Bad - Không có tên hoặc khó hiểu
Route::get('/products', ...);
Route::get('/product/{id}', ...)->name('p.d');
```

### 2. **Nhóm Routes có cùng Middleware**

```php
// ✅ Good
Route::middleware('auth')->group(function () {
    Route::get('/profile', ...);
    Route::get('/orders', ...);
    Route::get('/wishlist', ...);
});

// ❌ Bad
Route::get('/profile', ...)->middleware('auth');
Route::get('/orders', ...)->middleware('auth');
Route::get('/wishlist', ...)->middleware('auth');
```

### 3. **Dùng Route Model Binding**

```php
// ✅ Good
Route::get('/product/{product}', function (Product $product) {
    return view('product-detail', compact('product'));
});

// ❌ Bad
Route::get('/product/{id}', function ($id) {
    $product = Product::findOrFail($id);
    return view('product-detail', compact('product'));
});
```

### 4. **Route Prefix và Name Prefix**

```php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', ...)->name('dashboard'); // admin.dashboard
    Route::get('/users', ...)->name('users');         // admin.users
});
```

### 5. **API Routes riêng biệt**

```php
// routes/api.php
Route::prefix('v1')->group(function () {
    Route::get('/products', [ApiProductController::class, 'index']);
    Route::get('/products/{id}', [ApiProductController::class, 'show']);
});

// URL: /api/v1/products
```

## 🛠️ Kiểm tra Routes

```bash
# Xem tất cả routes
php artisan route:list

# Lọc theo name
php artisan route:list --name=product

# Lọc theo path
php artisan route:list --path=api

# Lọc theo method
php artisan route:list --method=GET
```

## 🔍 Debug Routes

```bash
# Clear route cache
php artisan route:clear

# Cache routes (production)
php artisan route:cache
```

## 📊 So sánh các cách tổ chức

| Cách               | Ưu điểm             | Nhược điểm          | Khi nào dùng              |
| ------------------ | ------------------- | ------------------- | ------------------------- |
| **Chia file**      | Dễ quản lý, rõ ràng | Nhiều file          | Project trung bình/lớn    |
| **Route Group**    | Ngắn gọn, tập trung | Khó đọc nếu quá dài | Project nhỏ/vừa           |
| **Controller**     | Tách logic, dễ test | Phải tạo controller | Production, team          |
| **Route Resource** | Nhanh cho CRUD      | Ít linh hoạt        | CRUD đơn giản             |
| **Module**         | Cực kỳ tổ chức      | Phức tạp            | Project lớn, nhiều module |

## 💡 Gợi ý cho dự án của bạn

### Hiện tại (Small - Medium):

✅ **Chia theo file** (đã implement)

```
routes/web.php    → Trang chủ + load routes
routes/auth.php   → Authentication
routes/product.php → Sản phẩm
routes/cart.php   → Giỏ hàng
routes/user.php   → User profile
```

### Khi phát triển thêm:

🔄 **Thêm Controller**

```php
// routes/product.php
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{product}', [ProductController::class, 'show']);
```

### Khi có Admin panel:

➕ **Thêm routes/admin.php**

```php
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::resource('products', AdminProductController::class);
});
```

### Khi có API:

🔌 **Dùng routes/api.php**

```php
Route::prefix('v1')->group(function () {
    Route::apiResource('products', ApiProductController::class);
});
```

## 📚 Tài liệu tham khảo

-   [Laravel Routing Documentation](https://laravel.com/docs/routing)
-   [Route Model Binding](https://laravel.com/docs/routing#route-model-binding)
-   [Route Groups](https://laravel.com/docs/routing#route-groups)
-   [Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
