# Hệ Thống Xác Thực và Phân Quyền

## ✅ Đã Hoàn Thành

### 1. JWT Authentication

-   ✅ JWT Token được lưu trong database (bảng `Token`)
-   ✅ Token type: `jwt` đã được thêm vào ENUM
-   ✅ Tự động logout khi token hết hạn
-   ✅ Xóa token khỏi database khi logout

### 2. Phân Quyền (Authorization)

#### Vai Trò (Roles)

1. **Admin** - Toàn quyền quản trị
2. **User** - Người dùng thông thường
3. **ProductManager** - Quản lý sản phẩm
4. **OrderManager** - Quản lý đơn hàng

#### Middleware

-   **CheckAdmin** - Kiểm tra quyền Admin/Manager (cho khu vực admin)
-   **CheckUser** - Kiểm tra user đã đăng nhập
-   **JWTAuthenticate** - Xác thực JWT token

### 3. Luồng Đăng Nhập/Đăng Ký

#### Đăng Ký (Register)

```
1. User điền form đăng ký
2. Validation dữ liệu
3. Tạo tài khoản với vai trò "User"
4. Tự động đăng nhập (tạo JWT token)
5. Lưu token vào database (bảng Token, loại 'jwt')
6. Lưu token vào session
7. Redirect về trang user.home
```

#### Đăng Nhập (Login)

```
1. User nhập email/password
2. Kiểm tra tài khoản tồn tại
3. Kiểm tra tài khoản có bị khóa không (TrangThai = 0)
4. Kiểm tra mật khẩu
5. Tạo JWT token
6. Lưu token vào database (bảng Token, loại 'jwt')
7. Lưu token vào session
8. Redirect theo vai trò:
   - Admin → /admin/dashboard
   - ProductManager → /admin/products
   - OrderManager → /admin/dashboard
   - User → /
```

#### Đăng Xuất (Logout)

```
1. Invalidate JWT token
2. Xóa token khỏi database
3. Xóa session
4. Redirect về trang chủ
```

### 4. Bảo Mật Routes

#### Routes Admin (middleware: `admin`)

```php
/admin/*
```

**Cho phép**: Admin, ProductManager, OrderManager  
**Từ chối**: User hoặc chưa đăng nhập

#### Routes User (middleware: `user`)

```php
/user/*
/cart/*
/checkout/*
```

**Cho phép**: Tất cả user đã đăng nhập  
**Từ chối**: Chưa đăng nhập

#### Routes Public

```php
/
/products/*
/login
/register
```

**Cho phép**: Tất cả mọi người

### 5. Xử Lý Truy Cập Không Hợp Lệ

#### User cố truy cập Admin

```
1. Middleware CheckAdmin kiểm tra vai trò
2. Nếu không phải Admin/Manager
3. Redirect đến /unauthorized
4. Hiển thị trang "403 - Không có quyền truy cập"
5. Có 2 nút:
   - Về Trang Chủ
   - Đăng Nhập (nếu muốn đăng nhập tài khoản khác)
```

#### Token Hết Hạn

```
1. Middleware kiểm tra token
2. Token không hợp lệ/hết hạn
3. Xóa session
4. Redirect về /login
5. Thông báo: "Phiên đăng nhập đã hết hạn"
```

#### Tài Khoản Bị Khóa

```
1. Middleware kiểm tra TrangThai
2. Nếu TrangThai = 0
3. Xóa session
4. Redirect về /login
5. Thông báo: "Tài khoản đã bị khóa"
```

## 📁 Cấu Trúc Files

### Models

-   `app/Models/VaiTro.php` - Quản lý vai trò
-   `app/Models/NguoiDung.php` - User với JWT
-   `app/Models/Token.php` - Lưu trữ JWT tokens

### Controllers

-   `app/Http/Controllers/AuthController.php` - Xử lý authentication

### Middleware

-   `app/Http/Middleware/CheckAdmin.php` - Kiểm tra quyền admin
-   `app/Http/Middleware/CheckUser.php` - Kiểm tra user đã login
-   `app/Http/Middleware/JWTAuthenticate.php` - Xác thực JWT

### Views

-   `resources/views/auth/login.blade.php` - Form đăng nhập
-   `resources/views/auth/register.blade.php` - Form đăng ký
-   `resources/views/errors/unauthorized.blade.php` - Trang 403

### Routes

-   `routes/auth.php` - Routes authentication
-   `routes/admin.php` - Routes admin (middleware: admin)
-   `routes/user.php` - Routes user (middleware: user)
-   `routes/cart.php` - Routes cart/checkout (middleware: user)
-   `routes/web.php` - Routes public

### Database

-   Migration: `2025_11_21_164454_cap_nhat_bang_token_them_loai_jwt.php`
-   Thêm 'jwt' vào ENUM Loai trong bảng Token

## 🔐 Security Features

### JWT Token

-   TTL: 60 phút (config trong `config/jwt.php`)
-   Stored in database với thông tin:
    -   IDNguoiDung
    -   Token (JWT string)
    -   Loai: 'jwt'
    -   HetHan: Thời gian hết hạn

### Password

-   Minimum 6 ký tự
-   Hash bằng bcrypt
-   Validation khi đăng ký/đổi mật khẩu

### Session

-   Lưu jwt_token và user_id
-   Tự động xóa khi logout hoặc token không hợp lệ

### Database

-   Foreign keys với ON DELETE CASCADE
-   Index trên Email, Token
-   TrangThai để khóa tài khoản

## 🧪 Testing

### Test Đăng Ký

```
1. Truy cập /register
2. Điền thông tin hợp lệ
3. Click "Đăng ký"
4. Kiểm tra:
   ✅ Tài khoản được tạo trong bảng NguoiDung
   ✅ Token được lưu trong bảng Token (Loai = 'jwt')
   ✅ Redirect về trang chủ (/)
   ✅ Session có jwt_token và user_id
```

### Test Đăng Nhập User

```
1. Đăng nhập với tài khoản User
2. Kiểm tra redirect về /
3. Thử truy cập /admin/dashboard
4. Kiểm tra:
   ✅ Redirect về /unauthorized
   ✅ Hiển thị trang 403
```

### Test Đăng Nhập Admin

```
1. Đăng nhập với tài khoản Admin
2. Kiểm tra redirect về /admin/dashboard
3. Thử truy cập các trang admin khác
4. Kiểm tra:
   ✅ Truy cập thành công
   ✅ Không bị redirect
```

### Test Đăng Xuất

```
1. Đăng nhập
2. Click "Đăng xuất"
3. Kiểm tra:
   ✅ Token bị xóa khỏi database
   ✅ Session bị xóa
   ✅ Redirect về trang chủ
   ✅ Không thể truy cập routes protected
```

## 📝 API Endpoints

### Authentication

| Method | Endpoint  | Description     | Middleware |
| ------ | --------- | --------------- | ---------- |
| GET    | /login    | Form đăng nhập  | guest      |
| POST   | /login    | Xử lý đăng nhập | guest      |
| GET    | /register | Form đăng ký    | guest      |
| POST   | /register | Xử lý đăng ký   | guest      |
| POST   | /logout   | Đăng xuất       | -          |

### Admin

| Method | Endpoint         | Description  | Middleware |
| ------ | ---------------- | ------------ | ---------- |
| GET    | /admin/dashboard | Dashboard    | admin      |
| GET    | /admin/products  | Quản lý SP   | admin      |
| GET    | /admin/orders    | Quản lý ĐH   | admin      |
| GET    | /admin/users     | Quản lý User | admin      |

### User

| Method | Endpoint      | Description       | Middleware |
| ------ | ------------- | ----------------- | ---------- |
| GET    | /user/profile | Thông tin cá nhân | user       |
| GET    | /user/orders  | Đơn hàng          | user       |
| GET    | /cart         | Giỏ hàng          | user       |
| GET    | /checkout     | Thanh toán        | user       |

## 🎯 Các Tính Năng Bổ Sung

### Có thể thêm

-   [ ] Remember me (checkbox khi login)
-   [ ] Email verification
-   [ ] Password reset qua email
-   [ ] Two-factor authentication (2FA)
-   [ ] Login history
-   [ ] IP whitelist cho admin
-   [ ] Rate limiting cho login attempts
-   [ ] CAPTCHA sau nhiều lần login sai

### Database Token đã hỗ trợ

-   ✅ reset_password - Reset mật khẩu
-   ✅ verify_email - Xác thực email
-   ✅ remember_me - Nhớ đăng nhập
-   ✅ jwt - JWT authentication

## 🚀 Chạy Hệ Thống

```bash
# 1. Chạy migrations
php artisan migrate

# 2. Chạy seeders (tạo vai trò và admin)
php artisan db:seed

# 3. Start server
php artisan serve

# 4. Truy cập
http://localhost:8000
```

### Tài Khoản Test

Sau khi chạy seeders:

**Admin:**

-   Email: admin@organic.vn
-   Password: admin123

**Product Manager:**

-   Email: product@organic.vn
-   Password: product123

**Order Manager:**

-   Email: order@organic.vn
-   Password: order123
