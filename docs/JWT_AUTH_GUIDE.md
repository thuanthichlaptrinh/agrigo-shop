# 🔐 Hướng Dẫn Sử Dụng Hệ Thống Authentication với JWT Token

## ✅ Đã Hoàn Thành

### 1. **Cài đặt JWT Authentication**

-   ✅ Package `tymon/jwt-auth` đã được cài đặt
-   ✅ JWT secret key đã được generate
-   ✅ Config file `config/jwt.php` đã được publish

### 2. **Database & Models**

-   ✅ Model `VaiTro` với 4 vai trò:

    -   **Admin**: Quản trị viên - Toàn quyền hệ thống
    -   **User**: Người dùng - Khách hàng mua hàng
    -   **ProductManager**: Quản lý sản phẩm
    -   **OrderManager**: Quản lý đơn hàng

-   ✅ Model `NguoiDung` đã implement `JWTSubject`
    -   Tích hợp JWT token generation
    -   Helper methods: `isAdmin()`, `isProductManager()`, `isOrderManager()`, `hasRole()`

### 3. **Authentication Controller**

-   ✅ **Login** với JWT Token:

    -   Validation mạnh hơn
    -   Kiểm tra trạng thái tài khoản
    -   Tự động redirect theo vai trò
    -   Lưu JWT token vào session

-   ✅ **Register** với Validation cao:

    -   Họ tên: Chỉ chữ cái, min 2 ký tự
    -   Email: Validate RFC & DNS, unique
    -   Số điện thoại: Format VN (0912345678), unique
    -   Mật khẩu: Min 6 ký tự (có thể tăng lên 8 và thêm regex phức tạp)
    -   Tự động gán vai trò User
    -   Tự động login sau đăng ký

-   ✅ **Logout**:
    -   Invalidate JWT token
    -   Clear session

### 4. **Middleware Bảo Mật**

-   ✅ `JWTAuthenticate`: Xác thực JWT token từ session
-   ✅ `CheckAdmin`: Kiểm tra quyền Admin
-   ✅ `CheckUser`: Kiểm tra đăng nhập

### 5. **Seeder**

-   ✅ `VaiTroSeeder`: Tự động tạo 4 vai trò khi chạy ứng dụng

## 📝 Hướng Dẫn Sử Dụng

### Bước 1: Chạy Seeder tạo vai trò

```bash
php artisan db:seed --class=VaiTroSeeder
```

Hoặc chạy tất cả seeders:

```bash
php artisan db:seed
```

### Bước 2: Kiểm tra các vai trò đã tạo

Truy cập phpMyAdmin và kiểm tra bảng `VaiTro`, sẽ có 4 bản ghi:

| ID  | TenVaiTro      | MoTa                                                        |
| --- | -------------- | ----------------------------------------------------------- |
| 1   | Admin          | Quản trị viên - Có toàn quyền quản lý hệ thống              |
| 2   | User           | Người dùng - Khách hàng mua hàng                            |
| 3   | ProductManager | Quản lý sản phẩm - Quản lý danh mục, sản phẩm, nhà cung cấp |
| 4   | OrderManager   | Quản lý đơn hàng - Xử lý đơn hàng, giao hàng                |

### Bước 3: Đăng ký tài khoản

1. Truy cập: `http://localhost:8000/register`
2. Nhập thông tin (các trường bắt buộc: Họ tên, Email, Mật khẩu)
3. Tài khoản sẽ tự động được gán vai trò **User**

### Bước 4: Tạo tài khoản Admin (Thủ công trong phpMyAdmin)

```sql
INSERT INTO NguoiDung (TenNguoiDung, Email, SDT, MatKhau, TrangThai, IDVaiTro, NgayTao, NgayCapNhat)
VALUES (
    'Admin',
    'admin@organic.vn',
    '0912345678',
    '$2y$12$ZxLhVE4gF7PqE8tZQJqaKujFBhYm1vQk3wW.xJKvWxJqwN8mW9YHe', -- password: admin123
    1,
    1, -- ID vai trò Admin
    NOW(),
    NOW()
);
```

Hoặc tạo bằng PHP:

```php
use App\Models\NguoiDung;
use App\Models\VaiTro;
use Illuminate\Support\Facades\Hash;

$adminRole = VaiTro::where('TenVaiTro', 'Admin')->first();

NguoiDung::create([
    'TenNguoiDung' => 'Admin',
    'Email' => 'admin@organic.vn',
    'SDT' => '0912345678',
    'MatKhau' => Hash::make('admin123'),
    'TrangThai' => 1,
    'IDVaiTro' => $adminRole->ID
]);
```

### Bước 5: Đăng nhập

1. Truy cập: `http://localhost:8000/login`
2. Nhập Email và Mật khẩu
3. Hệ thống sẽ:
    - Tạo JWT token
    - Lưu token vào session
    - Redirect theo vai trò:
        - **Admin** → `/admin/dashboard`
        - **ProductManager** → `/admin/products`
        - **OrderManager** → `/admin/dashboard`
        - **User** → `/` (trang chủ)

## 🔒 Bảo Mật

### 1. **JWT Token**

-   Token được lưu trong session (server-side)
-   Tự động invalidate khi logout
-   Có thời gian hết hạn (config trong `config/jwt.php`)

### 2. **Validation Mạnh**

-   **Email**: Kiểm tra format RFC và DNS
-   **Số điện thoại**: Phải đúng format VN
-   **Mật khẩu**: Min 6 ký tự (có thể tăng lên 8+ và yêu cầu chữ hoa, số, ký tự đặc biệt)

### 3. **Middleware**

-   Tất cả routes admin được bảo vệ bởi middleware `admin`
-   Routes user được bảo vệ bởi middleware `user`
-   JWT token được verify mỗi request

## 📋 Routes Structure

### User Routes

```php
GET  /                    → Trang chủ
GET  /login              → Form đăng nhập
POST /login              → Xử lý đăng nhập
GET  /register           → Form đăng ký
POST /register           → Xử lý đăng ký
POST /logout             → Đăng xuất
```

### Admin Routes (Cần đăng nhập + Vai trò Admin)

```php
GET  /admin/dashboard     → Dashboard admin
GET  /admin/products      → Quản lý sản phẩm
GET  /admin/users         → Quản lý người dùng
GET  /admin/orders        → Quản lý đơn hàng
GET  /admin/categories    → Quản lý danh mục
```

## 🔑 Role-Based Access Control (RBAC)

### Kiểm tra vai trò trong Controller

```php
$user = JWTAuth::toUser(session('jwt_token'));

if ($user->isAdmin()) {
    // Admin logic
}

if ($user->isProductManager()) {
    // Product manager logic
}

if ($user->hasRole('OrderManager')) {
    // Order manager logic
}
```

### Kiểm tra vai trò trong Blade

```blade
@php
    $user = auth()->user();
@endphp

@if($user && $user->isAdmin())
    <!-- Admin content -->
@endif
```

## ⚙️ Config Files

### `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=QuanLyNongSan_PHP_HUIT
DB_USERNAME=root
DB_PASSWORD=your_password

JWT_SECRET=your_generated_secret_key
JWT_TTL=60  # Token expiration in minutes
```

### `config/jwt.php`

-   `ttl`: Thời gian sống của token (60 phút)
-   `refresh_ttl`: Thời gian có thể refresh token (2 tuần)
-   `algo`: Thuật toán mã hóa (HS256)

## 🚀 Testing

### Test đăng ký

```bash
# Truy cập form
http://localhost:8000/register

# Nhập:
- Họ tên: Nguyễn Văn A
- Email: test@gmail.com
- Số điện thoại: 0912345678
- Mật khẩu: 123456
- Xác nhận mật khẩu: 123456
```

### Test đăng nhập

```bash
# Đăng nhập User
Email: test@gmail.com
Password: 123456
→ Redirect: http://localhost:8000/

# Đăng nhập Admin
Email: admin@organic.vn
Password: admin123
→ Redirect: http://localhost:8000/admin/dashboard
```

## 📊 Database Schema

### Bảng VaiTro

```sql
ID    | TenVaiTro       | MoTa
------|-----------------|---------------------------
1     | Admin           | Quản trị viên...
2     | User            | Người dùng...
3     | ProductManager  | Quản lý sản phẩm...
4     | OrderManager    | Quản lý đơn hàng...
```

### Bảng NguoiDung

```sql
Columns:
- ID (PK)
- TenNguoiDung
- Email (UNIQUE)
- SDT (UNIQUE)
- MatKhau (HASHED)
- DiaChi
- NgaySinh
- GioiTinh
- HinhAnh
- TrangThai
- IDVaiTro (FK → VaiTro.ID)
- NgayTao
- NgayCapNhat
```

## 🐛 Troubleshooting

### Lỗi: "Token not found"

```bash
# Xóa session và đăng nhập lại
php artisan cache:clear
php artisan config:clear
```

### Lỗi: "SQLSTATE[42S02]: Base table or view not found"

```bash
# Kiểm tra database đã import database2.sql chưa
# Import lại:
mysql -u root -p QuanLyNongSan_PHP_HUIT < database/database2.sql
```

### Lỗi: "Class 'JWTAuth' not found"

```bash
composer dump-autoload
php artisan config:clear
```

## 📚 Tài Liệu Tham Khảo

-   [JWT-Auth Documentation](https://jwt-auth.readthedocs.io/)
-   [Laravel Authentication](https://laravel.com/docs/authentication)
-   [Laravel Middleware](https://laravel.com/docs/middleware)

---

**Version**: 2.0.0  
**Updated**: November 21, 2025  
**Author**: thuanthichlaptrinh
