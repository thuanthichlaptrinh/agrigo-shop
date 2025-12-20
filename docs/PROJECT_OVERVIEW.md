# 🌿 TỔNG QUAN DỰ ÁN - HỆ THỐNG QUẢN LÝ BÁN NÔNG SẢN ORGANIC

> Tài liệu mô tả chi tiết về dự án website thương mại điện tử bán nông sản hữu cơ được xây dựng bằng Laravel 12

---

## 📌 THÔNG TIN CHUNG

| Thông tin         | Chi tiết                                               |
| ----------------- | ------------------------------------------------------ |
| **Tên dự án**     | Hệ thống Quản lý Bán Nông Sản Organic                  |
| **Framework**     | Laravel 12.x                                           |
| **Ngôn ngữ**      | PHP 8.2+                                               |
| **Database**      | MySQL 8.0+ / SQLite                                    |
| **Frontend**      | Blade Template, Bootstrap 5.3, JavaScript (Vanilla)   |
| **Authentication**| JWT Auth + Session Based                               |
| **Phiên bản**     | 2.0.0                                                  |

---

## 🎯 MỤC TIÊU DỰ ÁN

Dự án được xây dựng nhằm:

1. **Cung cấp nền tảng mua bán trực tuyến** cho các sản phẩm nông sản hữu cơ, rau củ quả tươi sạch
2. **Quản lý toàn diện** sản phẩm, đơn hàng, khách hàng và các hoạt động kinh doanh
3. **Hỗ trợ nhiều phương thức thanh toán** (COD, VNPay, MoMo, ZaloPay)
4. **Giao diện thân thiện**, responsive trên mọi thiết bị
5. **Tích hợp Chatbot AI** hỗ trợ khách hàng 24/7

---

## 🏗️ KIẾN TRÚC HỆ THỐNG

### Mô hình MVC (Model-View-Controller)

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT                               │
│  (Browser / Mobile)                                         │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                      ROUTES                                 │
│  web.php | admin.php | auth.php | api.php | cart.php        │
│  product.php | user.php                                     │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                    MIDDLEWARE                               │
│  auth | admin | guest | jwt.auth | web                      │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                   CONTROLLERS                               │
│  ┌─────────────┐  ┌─────────────┐  ┌──────────────────┐     │
│  │ User/       │  │ Admin/      │  │ Api/             │     │
│  │ HomeController│ │ ProductController│ │ AuthController  │     │
│  │ CartController│ │ OrderController│  │ ChatController  │     │
│  │ CheckoutController│ │ UserController│ │ ChatbotController│   │
│  │ ProductController│ │ BannerController│ │               │     │
│  │ ProfileController│ │ etc...       │  │               │     │
│  └─────────────┘  └─────────────┘  └──────────────────┘     │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                      MODELS                                 │
│  NguoiDung | SanPham | DonHang | GioHang | DanhMuc |        │
│  ChiTietDonHang | Voucher | KhuyenMai | DanhGia | etc...    │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                     DATABASE                                │
│  MySQL / SQLite                                             │
│  19 bảng chính (đã tối ưu từ 39 bảng)                       │
└─────────────────────────────────────────────────────────────┘
```

### Phân tách Module User/Admin

| Module | Routes         | Views                   | Prefix   | Middleware |
| ------ | -------------- | ----------------------- | -------- | ---------- |
| User   | web.php, product.php, cart.php, user.php | resources/views/user/ | / | - |
| Admin  | admin.php      | resources/views/admin/  | /admin   | auth, admin |
| Auth   | auth.php       | resources/views/auth/   | /        | guest      |
| API    | api.php        | -                       | /api/v1  | jwt.auth   |

---

## 📂 CẤU TRÚC THƯ MỤC

```
nongsan/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # 17 Controllers quản trị
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── BannerController.php
│   │   │   │   ├── DanhMucController.php
│   │   │   │   ├── VoucherController.php
│   │   │   │   ├── KhuyenMaiController.php
│   │   │   │   └── ...
│   │   │   ├── User/               # 7 Controllers người dùng
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── CartController.php
│   │   │   │   ├── CheckoutController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   └── ...
│   │   │   ├── Api/                # 3 Controllers API
│   │   │   └── AuthController.php
│   │   └── Middleware/
│   ├── Models/                     # 25 Models Eloquent
│   │   ├── NguoiDung.php
│   │   ├── SanPham.php
│   │   ├── DonHang.php
│   │   ├── GioHang.php
│   │   ├── DanhMuc.php
│   │   └── ...
│   ├── Mail/
│   ├── Providers/
│   ├── Support/
│   └── helpers.php                 # Helper functions
├── database/
│   ├── database2.sql               # Schema SQL (19 bảng)
│   ├── migrations/
│   └── seeders/
├── docs/                           # Tài liệu
│   ├── AUTHENTICATION_SYSTEM.md
│   ├── DANH_SACH_CHUC_NANG.md
│   ├── DATABASE_README.md
│   ├── JWT_AUTH_GUIDE.md
│   ├── MODELS_README.md
│   ├── ROUTES_GUIDE.md
│   └── ...
├── public/
│   └── template/
│       ├── Admin/                  # Assets admin
│       └── Assets/                 # Assets user
│           ├── css/
│           ├── js/
│           └── Images/
├── resources/
│   └── views/
│       ├── admin/                  # Views quản trị
│       │   ├── layouts/
│       │   ├── partials/
│       │   ├── dashboard.blade.php
│       │   ├── products/
│       │   ├── orders/
│       │   └── ...
│       ├── user/                   # Views người dùng
│       │   ├── layouts/
│       │   ├── partials/
│       │   ├── home.blade.php
│       │   ├── products/
│       │   ├── cart/
│       │   └── ...
│       ├── auth/                   # Views xác thực
│       └── emails/
├── routes/
│   ├── web.php                     # Routes chính (172 dòng)
│   ├── admin.php                   # Routes admin (312 dòng)
│   ├── auth.php                    # Routes xác thực
│   ├── product.php                 # Routes sản phẩm
│   ├── cart.php                    # Routes giỏ hàng
│   ├── user.php                    # Routes profile
│   └── api.php                     # Routes API
└── storage/
```

---

## 💾 CƠ SỞ DỮ LIỆU

### Sơ đồ 19 bảng chính

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   VaiTro     │     │  DanhMuc     │     │ NhaCungCap   │
│   (Roles)    │     │  (Category)  │     │ (Suppliers)  │
└──────┬───────┘     └──────┬───────┘     └──────┬───────┘
       │                    │                    │
       ▼                    ▼                    │
┌──────────────┐     ┌──────────────┐            │
│  NguoiDung   │     │ LoaiSanPham  │            │
│   (Users)    │     │ (SubCategory)│            │
└──────┬───────┘     └──────┬───────┘            │
       │                    │                    │
       ▼                    ▼                    ▼
┌──────────────┐     ┌──────────────────────────────────┐
│    Token     │     │           SanPham               │
│  (Auth Token)│     │         (Products)              │
└──────────────┘     └──────────────┬──────────────────┘
                                    │
       ┌────────────────────────────┼────────────────────────────┐
       │                            │                            │
       ▼                            ▼                            ▼
┌──────────────┐     ┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│  GioHang     │     │ HinhAnhSanPham│    │   DanhGia    │     │ SanPhamKhuyenMai│
│   (Cart)     │     │ (ProductImages)│   │  (Reviews)   │     │ (ProductPromo)│
└──────────────┘     └──────────────┘     └──────────────┘     └───────┬──────┘
                                                                       │
       ┌───────────────────────────────────────────────────────────────┘
       ▼
┌──────────────┐     ┌──────────────┐
│  KhuyenMai   │     │   Voucher    │
│ (Promotions) │     │  (Coupons)   │
└──────────────┘     └──────┬───────┘
                            │
                            ▼
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   DonHang    │────▶│ChiTietDonHang│     │  ThanhToan   │
│   (Orders)   │     │(OrderDetails)│     │  (Payments)  │
└──────────────┘     └──────────────┘     └──────────────┘

┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   Banner     │     │   LienHe     │     │   BaiViet    │
│  (Banners)   │     │  (Contacts)  │     │  (Articles)  │
└──────────────┘     └──────────────┘     └──────────────┘

┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│ThongBao      │     │   NhatKy     │     │HoatDongNguoiDung│
│(Notifications)│    │(ActivityLog) │     │(UserActivity)│
└──────────────┘     └──────────────┘     └──────────────┘

┌──────────────┐     ┌──────────────┐
│CuocHoiThoai  │────▶│   TinNhan    │
│(Conversations)│    │  (Messages)  │
└──────────────┘     └──────────────┘
```

### Bảng tóm tắt Models

| STT | Model                | Bảng DB            | Mô tả                            |
| --- | -------------------- | ------------------ | -------------------------------- |
| 1   | VaiTro               | VaiTro             | Vai trò người dùng (Admin/User)  |
| 2   | NguoiDung            | NguoiDung          | Thông tin tài khoản              |
| 3   | Token                | Token              | Token xác thực (reset password)  |
| 4   | DanhMuc              | DanhMuc            | Danh mục sản phẩm chính          |
| 5   | LoaiSanPham          | LoaiSanPham        | Loại sản phẩm (sub-category)     |
| 6   | NhaCungCap           | NhaCungCap         | Nhà cung cấp                     |
| 7   | SanPham              | SanPham            | Sản phẩm                         |
| 8   | HinhAnhSanPham       | HinhAnhSanPham     | Hình ảnh sản phẩm                |
| 9   | KhuyenMai            | KhuyenMai          | Chương trình khuyến mãi          |
| 10  | SanPhamKhuyenMai     | SanPhamKhuyenMai   | Liên kết SP-KM (pivot)           |
| 11  | Voucher              | Voucher            | Mã giảm giá                      |
| 12  | GioHang              | GioHang            | Giỏ hàng                         |
| 13  | DonHang              | DonHang            | Đơn hàng                         |
| 14  | ChiTietDonHang       | ChiTietDonHang     | Chi tiết đơn hàng                |
| 15  | ThanhToan            | ThanhToan          | Thanh toán                       |
| 16  | DanhGia              | DanhGia            | Đánh giá sản phẩm                |
| 17  | Banner               | Banner             | Banner quảng cáo                 |
| 18  | BaiViet              | BaiViet            | Bài viết/Blog                    |
| 19  | LienHe               | LienHe             | Liên hệ                          |
| 20  | ThongBao             | ThongBao           | Thông báo                        |
| 21  | NhatKy               | NhatKy             | Nhật ký hoạt động                |
| 22  | HoatDongNguoiDung    | HoatDongNguoiDung  | Hoạt động người dùng             |
| 23  | CuocHoiThoai         | CuocHoiThoai       | Cuộc hội thoại chat              |
| 24  | TinNhan              | TinNhan            | Tin nhắn chat                    |

---

## ✨ TÍNH NĂNG CHÍNH

### 🛍️ Khách hàng (User)

| Tính năng              | Route                  | Mô tả                                    |
| ---------------------- | ---------------------- | ---------------------------------------- |
| Trang chủ              | `/`                    | Banner, Flash Sale, Sản phẩm nổi bật     |
| Danh sách sản phẩm     | `/products`            | Xem, tìm kiếm, lọc sản phẩm             |
| Chi tiết sản phẩm      | `/products/{id}`       | Thông tin, hình ảnh, đánh giá           |
| Giỏ hàng               | `/cart`                | Thêm, sửa, xóa sản phẩm                 |
| Thanh toán             | `/checkout`            | COD, VNPay, MoMo, Voucher               |
| Tài khoản              | `/user/profile`        | Thông tin cá nhân, đơn hàng             |
| Đăng ký/Đăng nhập      | `/login`, `/register`  | Xác thực tài khoản                      |
| Quên mật khẩu          | `/forgot-password`     | Reset password qua email                |
| Đánh giá sản phẩm      | `/products/{id}/reviews` | Viết đánh giá, chấm sao              |
| Sản phẩm yêu thích     | `/user/wishlist`       | Danh sách yêu thích                     |
| Bài viết               | `/bai-viet`            | Tin tức, blog                           |
| Liên hệ                | `/lien-he`             | Form liên hệ                            |
| Chat hỗ trợ            | Widget                 | Chatbot AI + Chat với Admin             |

### 👨‍💼 Quản trị viên (Admin)

| Tính năng              | Route                  | Mô tả                                    |
| ---------------------- | ---------------------- | ---------------------------------------- |
| Dashboard              | `/admin/dashboard`     | Thống kê, biểu đồ, cảnh báo             |
| Quản lý sản phẩm       | `/admin/products`      | CRUD sản phẩm, bulk import              |
| Quản lý đơn hàng       | `/admin/orders`        | Xem, duyệt, hủy, cập nhật trạng thái    |
| Quản lý người dùng     | `/admin/users`         | CRUD, khóa/mở tài khoản                 |
| Quản lý danh mục       | `/admin/catalog`       | CRUD danh mục                           |
| Quản lý loại SP        | `/admin/categories`    | CRUD loại sản phẩm                      |
| Quản lý nhà cung cấp   | `/admin/suppliers`     | CRUD nhà cung cấp                       |
| Quản lý khuyến mãi     | `/admin/promotions`    | CRUD chương trình khuyến mãi            |
| Quản lý voucher        | `/admin/vouchers`      | CRUD mã giảm giá                        |
| Quản lý banner         | `/admin/banners`       | CRUD banner quảng cáo                   |
| Quản lý bài viết       | `/admin/articles`      | CRUD bài viết/blog                      |
| Quản lý vai trò        | `/admin/roles`         | CRUD vai trò                            |
| Nhật ký hệ thống       | `/admin/logs`          | Xem lịch sử hoạt động                   |
| Thông báo              | `/admin/notifications` | Gửi thông báo đến người dùng            |
| Chat hỗ trợ            | `/admin/chat`          | Trả lời chat từ khách hàng              |

### 🔌 API (RESTful)

| Endpoint                   | Method | Mô tả                         |
| -------------------------- | ------ | ----------------------------- |
| `/api/v1/auth/register`    | POST   | Đăng ký                       |
| `/api/v1/auth/login`       | POST   | Đăng nhập                     |
| `/api/v1/auth/logout`      | POST   | Đăng xuất                     |
| `/api/v1/auth/me`          | GET    | Lấy thông tin user            |
| `/api/v1/auth/refresh`     | POST   | Làm mới token                 |
| `/api/v1/chatbot/query`    | POST   | Gửi câu hỏi cho chatbot       |
| `/api/v1/chat/conversation`| POST   | Tạo/lấy cuộc hội thoại        |
| `/api/v1/chat/send`        | POST   | Gửi tin nhắn                  |

---

## 🛠️ CÔNG NGHỆ SỬ DỤNG

### Backend Stack

| Công nghệ        | Phiên bản | Vai trò                          |
| ---------------- | --------- | -------------------------------- |
| Laravel          | 12.x      | PHP Framework chính              |
| PHP              | 8.2+      | Ngôn ngữ lập trình               |
| MySQL/SQLite     | 8.0+      | Cơ sở dữ liệu                    |
| JWT Auth         | *         | Xác thực API                     |
| Eloquent ORM     | -         | Tương tác database               |
| Blade Template   | -         | Template engine                  |

### Frontend Stack

| Công nghệ        | Phiên bản | Vai trò                          |
| ---------------- | --------- | -------------------------------- |
| Bootstrap        | 5.3       | CSS Framework                    |
| JavaScript       | ES6+      | Client-side scripting            |
| Remix Icon       | -         | Icon library                     |
| Vite             | -         | Build tool                       |

### Development Tools

| Công nghệ        | Vai trò                          |
| ---------------- | -------------------------------- |
| Composer         | PHP dependency management        |
| NPM              | Node dependency management       |
| Laravel Debugbar | Debugging                        |
| PHPUnit          | Testing                          |
| Pint             | Code formatting                  |

---

## 🚀 HƯỚNG DẪN CÀI ĐẶT

### Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- MySQL >= 8.0 hoặc SQLite
- Node.js & NPM

### Các bước cài đặt

```bash
# 1. Clone repository
git clone <repository-url>
cd nongsan

# 2. Cài đặt PHP dependencies
composer install

# 3. Cài đặt Node dependencies
npm install

# 4. Copy file môi trường
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Cấu hình database trong .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=QuanLyNongSan
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Import database
mysql -u root -p QuanLyNongSan < database/database2.sql

# 8. Chạy server
php artisan serve

# 9. Build assets (optional)
npm run dev
```

### Script tiện lợi (composer.json)

```bash
# Setup đầy đủ
composer run setup

# Chạy development server (server + queue + logs + vite)
composer run dev

# Chạy tests
composer run test
```

---

## 🔐 XÁC THỰC & PHÂN QUYỀN

### Phương thức xác thực

1. **Session-based Authentication**: Cho web routes (User/Admin)
2. **JWT Authentication**: Cho API routes

### Middleware

| Middleware | Mô tả                                    |
| ---------- | ---------------------------------------- |
| `auth`     | Yêu cầu đăng nhập                       |
| `guest`    | Chỉ cho khách (chưa đăng nhập)          |
| `admin`    | Yêu cầu vai trò Admin                   |
| `user`     | Yêu cầu vai trò User                    |
| `jwt.auth` | Xác thực JWT token                      |

### Vai trò người dùng

| Vai trò | IDVaiTro | Quyền hạn                            |
| ------- | -------- | ------------------------------------ |
| Admin   | 1        | Toàn quyền quản trị hệ thống         |
| User    | 2        | Mua hàng, quản lý tài khoản cá nhân  |

---

## 📚 TÀI LIỆU LIÊN QUAN

Các file tài liệu có trong thư mục `/docs`:

| File                          | Nội dung                              |
| ----------------------------- | ------------------------------------- |
| AUTHENTICATION_SYSTEM.md      | Chi tiết hệ thống xác thực            |
| DANH_SACH_CHUC_NANG.md       | Danh sách đầy đủ các chức năng        |
| DATABASE_README.md            | Hướng dẫn database                    |
| JWT_AUTH_GUIDE.md             | Hướng dẫn JWT Authentication          |
| MODELS_README.md              | Chi tiết các Models                   |
| ROUTES_GUIDE.md               | Hướng dẫn Routes                      |
| HUONG_DAN_TEMPLATE.md         | Hướng dẫn sử dụng template            |
| MIGRATION_COMPLETE.md         | Tài liệu migration                    |
| USER_MANAGEMENT_GUIDE.md      | Hướng dẫn quản lý người dùng          |

---

## 📈 THỐNG KÊ DỰ ÁN

| Metric                 | Số lượng |
| ---------------------- | -------- |
| Controllers (Admin)    | 17       |
| Controllers (User)     | 7        |
| Controllers (API)      | 3        |
| Models                 | 25       |
| Database Tables        | 19       |
| Route Files            | 9        |
| Views Directories      | 8        |
| Documentation Files    | 17+      |

---

## 👨‍💻 TÁC GIẢ

**thuanthichlaptrinh**

- Email: thuanthichlaptrinh@gmail.com
- GitHub: [@thuanthichlaptrinh](https://github.com/thuanthichlaptrinh)

---

## 📝 CHANGELOG

### Version 2.0.0 (2025-11-21)

- ✨ Tái cấu trúc hoàn toàn theo kiến trúc User/Admin
- ✨ Thêm Chatbot Widget với AI
- ✨ Thêm Scroll to Top button
- ✨ Tối ưu database (giảm từ 39 xuống 19 bảng)
- ✨ Cập nhật toàn bộ routes và views
- 🐛 Sửa lỗi route names
- 📝 Cập nhật documentation

### Version 1.0.0 (2025-11-01)

- 🎉 Phiên bản đầu tiên
- ✨ Tính năng cơ bản cho User và Admin
- ✨ Database với 39 bảng

---

> 📅 Tài liệu được tạo: 2025-12-20
> 
> © 2025 Organic Shop. All rights reserved.
