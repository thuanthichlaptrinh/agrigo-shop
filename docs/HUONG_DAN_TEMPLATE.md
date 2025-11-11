# Hướng dẫn sử dụng Template trong Laravel

## ✅ Đã hoàn thành

Mình đã chuyển đổi TẤT CẢ các file HTML trong template thành view Laravel với cấu trúc hoàn chỉnh:

### 📁 Cấu trúc file đã tạo:

```
resources/views/
├── layouts/
│   └── app.blade.php              # Layout chính (head, body, scripts)
├── partials/
│   ├── header.blade.php           # Header + navbar + menu
│   └── footer.blade.php           # Footer thông tin liên hệ
├── auth/
│   ├── login.blade.php            # Trang đăng nhập
│   └── register.blade.php         # Trang đăng ký
├── home.blade.php                 # Trang chủ
├── products.blade.php             # Danh sách sản phẩm
├── product-detail.blade.php       # Chi tiết sản phẩm
├── cart.blade.php                 # Giỏ hàng
├── checkout.blade.php             # Thanh toán
└── profile.blade.php              # Thông tin cá nhân

routes/
└── web.php                        # Routes cho tất cả trang

public/template/                   # Template gốc (giữ nguyên)
```

## 🎯 Danh sách các trang đã tạo

### 1. **Trang chủ** (`home.blade.php`)

-   **Route**: `GET /`
-   **Tên route**: `home`
-   **Mô tả**: Trang chủ với banner, danh mục, sản phẩm khuyến mãi

### 2. **Đăng nhập** (`auth/login.blade.php`)

-   **Route**:
    -   `GET /login` - Hiển thị form
    -   `POST /login` - Xử lý đăng nhập
-   **Tên route**: `login`
-   **Form fields**: `phone`, `password`, `remember`

### 3. **Đăng ký** (`auth/register.blade.php`)

-   **Route**:
    -   `GET /register` - Hiển thị form
    -   `POST /register` - Xử lý đăng ký
-   **Tên route**: `register`
-   **Form fields**: `name`, `email`, `phone`, `address`, `password`, `password_confirmation`

### 4. **Danh sách sản phẩm** (`products.blade.php`)

-   **Route**: `GET /products`
-   **Tên route**: `products`
-   **Features**: Filter, sort, search sản phẩm

### 5. **Chi tiết sản phẩm** (`product-detail.blade.php`)

-   **Route**: `GET /product/{id}`
-   **Tên route**: `product-detail`
-   **Features**: Xem ảnh, thông tin, thêm vào giỏ

### 6. **Giỏ hàng** (`cart.blade.php`)

-   **Route**: `GET /cart`
-   **Tên route**: `cart`
-   **Features**: Quản lý số lượng, xóa sản phẩm, tính tổng

### 7. **Thanh toán** (`checkout.blade.php`)

-   **Route**:
    -   `GET /checkout` - Hiển thị trang xác nhận
    -   `POST /checkout` - Xử lý đặt hàng
-   **Tên route**: `checkout`
-   **Features**: Xem thông tin đơn hàng, xác nhận

### 8. **Thông tin cá nhân** (`profile.blade.php`)

-   **Route**:
    -   `GET /profile` - Hiển thị thông tin
    -   `PUT /profile` - Cập nhật thông tin
-   **Tên route**: `profile`, `profile.update`
-   **Features**: Cập nhật thông tin, xem lịch sử đơn hàng
-   **Middleware**: `auth` (yêu cầu đăng nhập)

## 🚀 Chạy project

```bash
# 1. Cài dependencies (nếu chưa)
composer install

# 2. Copy file env
cp .env.example .env

# 3. Generate app key
php artisan key:generate

# 4. Chạy server
php artisan serve
```

Truy cập các trang:

-   Trang chủ: `http://localhost:8000/`
-   Đăng nhập: `http://localhost:8000/login`
-   Đăng ký: `http://localhost:8000/register`
-   Sản phẩm: `http://localhost:8000/products`
-   Chi tiết SP: `http://localhost:8000/product/1`
-   Giỏ hàng: `http://localhost:8000/cart`
-   Thanh toán: `http://localhost:8000/checkout`
-   Profile: `http://localhost:8000/profile`

## 📝 Những điểm quan trọng

### Đường dẫn assets

-   ✅ Dùng: `{{ asset('template/Assets/...') }}`
-   ❌ Không dùng: `./Assets/...` (sai đường dẫn)

### URL routing

-   ✅ Dùng: `{{ route('home') }}` hoặc `{{ url('/') }}`
-   ❌ Không dùng: `<a href="index.html">` (sai cách)

### Blade syntax

-   `{{ $variable }}` - In biến (escaped)
-   `{!! $html !!}` - In HTML (không escaped)
-   `@if`, `@foreach`, `@for` - Control structures
-   `@extends`, `@section`, `@yield` - Layout inheritance
-   `@push`, `@stack` - Thêm CSS/JS riêng
-   `@auth`, `@guest` - Kiểm tra đăng nhập

## 🔧 Tùy chỉnh thêm

### 1. Thay đổi thông tin header

Sửa file: `resources/views/partials/header.blade.php`

### 2. Thay đổi footer

Sửa file: `resources/views/partials/footer.blade.php`

### 3. Thêm section mới vào trang

Sửa file view tương ứng trong `resources/views/`

### 4. Thêm CSS/JS riêng cho trang

Trong view bất kỳ:

```blade
@push('styles')
    <link rel="stylesheet" href="{{ asset('custom.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('custom.js') }}"></script>
@endpush
```

## 🎨 Làm việc với dữ liệu động

### Truyền dữ liệu từ Route/Controller

```php
// routes/web.php
Route::get('/products', function () {
    $products = [
        ['id' => 1, 'name' => 'Rau cải', 'price' => 15000],
        ['id' => 2, 'name' => 'Thịt heo', 'price' => 120000],
    ];

    return view('products', compact('products'));
});
```

### Hiển thị trong view

```blade
@foreach($products as $product)
    <div class="card">
        <h5>{{ $product['name'] }}</h5>
        <p>{{ number_format($product['price']) }}đ</p>
        <a href="{{ route('product-detail', $product['id']) }}">Xem chi tiết</a>
    </div>
@endforeach
```

## 🔐 Authentication

Các route trong `routes/web.php` đã được chia làm 2 nhóm:

### Public routes (không cần đăng nhập)

-   `/` - Trang chủ
-   `/login` - Đăng nhập
-   `/register` - Đăng ký
-   `/products` - Danh sách sản phẩm
-   `/product/{id}` - Chi tiết sản phẩm
-   `/cart` - Giỏ hàng

### Protected routes (cần đăng nhập)

-   `/profile` - Thông tin cá nhân
-   `/orders` - Đơn hàng của tôi
-   `/addresses` - Sổ địa chỉ
-   `/wishlist` - Yêu thích
-   `/notifications` - Thông báo
-   `/change-password` - Đổi mật khẩu

Để sử dụng authentication, bạn cần cài Laravel Auth:

```bash
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run dev
```

## ⚡ Tips

1. **Xóa cache view** khi sửa Blade:

    ```bash
    php artisan view:clear
    ```

2. **Kiểm tra route**:

    ```bash
    php artisan route:list
    ```

3. **Debug**:

    ```blade
    @dd($variable)  <!-- Dump and die -->
    @dump($variable) <!-- Dump only -->
    ```

4. **Kiểm tra user đăng nhập**:
    ```blade
    @auth
        <p>Xin chào {{ Auth::user()->name }}</p>
    @else
        <a href="{{ route('login') }}">Đăng nhập</a>
    @endauth
    ```

## 📚 Các bước tiếp theo

### 1. Tạo Controller cho từng chức năng

```bash
php artisan make:controller HomeController
php artisan make:controller ProductController
php artisan make:controller CartController
php artisan make:controller AuthController
```

### 2. Tạo Model và Migration cho database

```bash
php artisan make:model Product -m
php artisan make:model Order -m
php artisan make:model OrderItem -m
php artisan make:model Category -m
```

### 3. Cấu trúc database đề xuất

**Products table:**

-   id, name, description, price, old_price, image, stock, category_id, created_at, updated_at

**Orders table:**

-   id, user_id, receiver_name, phone, address, delivery_time, payment_method, total, status, created_at, updated_at

**Order_items table:**

-   id, order_id, product_id, quantity, price, created_at, updated_at

**Categories table:**

-   id, name, slug, image, created_at, updated_at

**Users table:**

-   id, name, email, phone, password, address, birthday, gender, avatar, created_at, updated_at

### 4. Implement chức năng

#### a. Authentication (Laravel Breeze hoặc Laravel UI)

```bash
composer require laravel/breeze --dev
php artisan breeze:install
```

#### b. Shopping Cart (sử dụng Session)

```php
// Thêm vào cart
Session::push('cart', ['product_id' => 1, 'quantity' => 2]);

// Lấy cart
$cart = Session::get('cart', []);
```

#### c. Upload ảnh sản phẩm

```php
$path = $request->file('image')->store('products', 'public');
```

### 5. API Routes (cho AJAX)

```php
// routes/api.php
Route::post('/cart/add', [CartController::class, 'add']);
Route::post('/cart/update', [CartController::class, 'update']);
Route::post('/cart/remove', [CartController::class, 'remove']);
```

## 🎉 Tổng kết

✅ **Đã tạo**: 8 views chính + 1 layout + 2 partials  
✅ **Routes**: 20+ routes đầy đủ  
✅ **Features**: Login, Register, Products, Cart, Checkout, Profile  
✅ **Assets**: Tất cả CSS/JS/Images đã được link đúng  
✅ **Responsive**: Giữ nguyên responsive design từ template gốc

## 📞 Next Steps

1. **Kết nối Database**: Tạo migrations và seeders
2. **Implement Logic**: Viết Controller và validation
3. **Authentication**: Cài Laravel Breeze/UI
4. **Shopping Cart**: Implement cart với session/database
5. **Payment Gateway**: Tích hợp VNPay, Momo, v.v.
6. **Admin Panel**: Tạo trang quản trị sản phẩm/đơn hàng

---

**Lưu ý**: Template gốc vẫn giữ nguyên trong `public/template/` để tham khảo.
