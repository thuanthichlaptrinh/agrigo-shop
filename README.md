# 🌿 Hệ thống Quản lý Bán Nông Sản Organic

> Website thương mại điện tử bán nông sản hữu cơ được xây dựng bằng Laravel 11

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)

---

## 📋 Mục lục

-   [Giới thiệu](#-giới-thiệu)
-   [Tính năng](#-tính-năng)
-   [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
-   [Cấu trúc dự án](#-cấu-trúc-dự-án)
-   [Cài đặt](#-cài-đặt)
-   [Database](#-database)
-   [Hướng dẫn sử dụng](#-hướng-dẫn-sử-dụng)
-   [Screenshots](#-screenshots)
-   [Tài liệu](#-tài-liệu)
-   [Tác giả](#-tác-giả)

---

## 🎯 Giới thiệu

Hệ thống quản lý bán nông sản Organic là một website thương mại điện tử chuyên bán các sản phẩm nông sản hữu cơ, rau củ quả tươi sạch. Dự án được xây dựng với mục đích:

-   ✅ Cung cấp nền tảng mua bán nông sản trực tuyến
-   ✅ Quản lý sản phẩm, đơn hàng, khách hàng hiệu quả
-   ✅ Hỗ trợ nhiều phương thức thanh toán
-   ✅ Giao diện thân thiện, dễ sử dụng
-   ✅ Responsive trên mọi thiết bị

---

## ✨ Tính năng

### 🛍️ Khách hàng (Customer)

#### 1. Trang chủ

-   Banner quảng cáo với carousel
-   Danh mục sản phẩm nổi bật
-   Sản phẩm khuyến mãi sốc
-   Gian hàng và ưu đãi từ hãng
-   Bài viết/tin tức

#### 2. Quản lý sản phẩm

-   Xem danh sách sản phẩm (có phân trang)
-   Chi tiết sản phẩm (hình ảnh, mô tả, giá, đánh giá)
-   Tìm kiếm và lọc sản phẩm
-   Sản phẩm theo danh mục

#### 3. Giỏ hàng

-   Thêm sản phẩm vào giỏ
-   Cập nhật số lượng
-   Xóa sản phẩm
-   Tính tổng tiền tự động

#### 4. Thanh toán

-   Nhập thông tin giao hàng
-   Chọn phương thức thanh toán (COD, VNPay, MoMo, ZaloPay)
-   Áp dụng voucher giảm giá
-   Xác nhận đơn hàng

#### 5. Tài khoản

-   Đăng ký/Đăng nhập
-   Quên mật khẩu
-   Xem thông tin cá nhân
-   Lịch sử đơn hàng
-   Quản lý địa chỉ giao hàng
-   Sản phẩm yêu thích

#### 6. Đánh giá

-   Đánh giá sản phẩm (1-5 sao)
-   Viết nhận xét
-   Upload hình ảnh
-   Xem đánh giá của người khác

#### 7. Chatbot hỗ trợ

-   Chat trực tuyến với AI
-   Trả lời tự động câu hỏi thường gặp
-   Hỗ trợ tìm kiếm sản phẩm
-   Liên hệ với Admin
-   Scroll to top button

### 👨‍💼 Quản trị viên (Admin)

> **URL Admin:** `/admin/dashboard`  
> **Middleware:** `auth` (cần đăng nhập)

#### 1. Dashboard

-   Thống kê tổng quan (đơn hàng, doanh thu, sản phẩm, người dùng)
-   Biểu đồ doanh thu theo thời gian
-   Đơn hàng gần đây
-   Sản phẩm bán chạy
-   Giao diện admin riêng biệt

#### 2. Quản lý sản phẩm

-   CRUD sản phẩm (Create, Read, Update, Delete)
-   Tìm kiếm và lọc theo danh mục
-   Quản lý hình ảnh sản phẩm
-   Quản lý tồn kho
-   Cập nhật trạng thái (hiển thị/ẩn)

#### 3. Quản lý đơn hàng

-   Xem danh sách đơn hàng với phân trang
-   Chi tiết đơn hàng
-   Cập nhật trạng thái (Chờ xác nhận → Đã xác nhận → Đang giao → Đã giao)
-   Hủy đơn hàng
-   In hóa đơn

#### 4. Quản lý người dùng

-   CRUD người dùng
-   Xem lịch sử mua hàng
-   Quản lý vai trò (Admin/User)
-   Khóa/Mở khóa tài khoản

#### 5. Quản lý danh mục

-   CRUD danh mục sản phẩm
-   Sắp xếp thứ tự hiển thị
-   Quản lý hình ảnh danh mục

#### 6. Quản lý nhà cung cấp

-   CRUD nhà cung cấp
-   Thông tin liên hệ
-   Sản phẩm theo nhà cung cấp

---

## 🏗️ Kiến trúc hệ thống

### Phân tách User/Admin

Dự án được tổ chức theo kiến trúc **phân tách rõ ràng** giữa User và Admin:

#### 🟢 User Module

-   **Routes**: `routes/web.php`, `routes/product.php`, `routes/cart.php`, `routes/user.php`
-   **Views**: `resources/views/user/`
-   **Assets**: `public/template/Assets/`
-   **Prefix**: Không có prefix (root level)
-   **Route Names**: `user.*` (ví dụ: `user.home`, `user.products.index`)

#### 🔴 Admin Module

-   **Routes**: `routes/admin.php`
-   **Views**: `resources/views/admin/`
-   **Assets**: `public/template/Admin/`
-   **Prefix**: `/admin`
-   **Route Names**: `admin.*` (ví dụ: `admin.dashboard`, `admin.products.index`)
-   **Middleware**: `auth` (cần đăng nhập)

#### 🔵 Shared Module

-   **Routes**: `routes/auth.php`
-   **Views**: `resources/views/auth/`
-   **Middleware**: `guest` (cho login/register)

### Route Structure

```php
// User Routes
GET  /                          → user.home
GET  /products                  → user.products.index
GET  /products/{id}             → user.products.detail
GET  /cart                      → user.cart.index
GET  /checkout                  → user.checkout.index
GET  /user/profile              → user.profile
GET  /user/orders               → user.orders.index

// Admin Routes (require auth)
GET  /admin/dashboard           → admin.dashboard
GET  /admin/products            → admin.products.index
POST /admin/products            → admin.products.store
GET  /admin/products/{id}/edit  → admin.products.edit
PUT  /admin/products/{id}       → admin.products.update
DELETE /admin/products/{id}     → admin.products.destroy
GET  /admin/users               → admin.users.index
GET  /admin/orders              → admin.orders.index
GET  /admin/categories          → admin.categories.index
GET  /admin/suppliers           → admin.suppliers.index
```

---

## 🛠️ Công nghệ sử dụng

### Backend

-   **Laravel 12** - PHP Framework
-   **PHP 8.2+** - Programming Language
-   **MySQL 8.0+** - Database
-   **Composer** - Dependency Manager

### Frontend

-   **Bootstrap 5.3** - CSS Framework
-   **Blade Template** - Laravel Template Engine
-   **Remix Icon** - Icon Library
-   **JavaScript (Vanilla)** - Client-side scripting
-   **Chatbot Widget** - AI-powered customer support
-   **Responsive Design** - Mobile-first approach

### Tools & Libraries

-   **JWT Auth** - Authentication
-   **Laravel Debugbar** - Debugging
-   **Intervention Image** - Image processing
-   **Carbon** - Date/Time library

---

## 📁 Cấu trúc dự án

```
organic-shop/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Admin Controllers
│   │   │   └── User/            # User Controllers
│   │   └── Middleware/
│   │       └── AdminMiddleware.php  # Admin authorization
│   ├── Models/                  # Eloquent Models
│   └── ...
├── database/
│   ├── database.sql             # Database chính (24 bảng)
│   ├── database2.sql            # Database tối ưu (19 bảng)
│   ├── DATABASE_README.md       # Hướng dẫn database
│   └── SO_SANH_YEU_CAU.md      # So sánh với yêu cầu
├── public/
│   └── template/
│       ├── Admin/               # Admin assets
│       │   ├── style.css
│       │   ├── products.css
│       │   └── script.js
│       └── Assets/              # User assets
│           ├── css/
│           │   ├── base.css
│           │   ├── style.css
│           │   ├── chatbot.css  # Chatbot widget
│           │   └── ...
│           ├── js/
│           │   ├── main.js
│           │   └── chatbot.js   # Chatbot functionality
│           └── Images/
├── resources/
│   └── views/
│       ├── admin/               # 🔴 Admin Views
│       │   ├── layouts/
│       │   │   └── app.blade.php        # Admin layout
│       │   ├── partials/
│       │   │   ├── sidebar.blade.php    # Admin sidebar
│       │   │   └── navbar.blade.php     # Admin navbar
│       │   ├── dashboard.blade.php      # Dashboard
│       │   ├── products/
│       │   │   └── index.blade.php      # Quản lý sản phẩm
│       │   ├── users/
│       │   │   └── index.blade.php      # Quản lý người dùng
│       │   ├── categories/
│       │   │   └── index.blade.php      # Quản lý danh mục
│       │   └── orders/
│       │       └── index.blade.php      # Quản lý đơn hàng
│       ├── user/                # 🟢 User Views
│       │   ├── layouts/
│       │   │   └── app.blade.php        # User layout
│       │   ├── partials/
│       │   │   ├── header.blade.php     # Header
│       │   │   ├── footer.blade.php     # Footer
│       │   │   ├── sidebar.blade.php    # Sidebar
│       │   │   └── chatbot-widget.blade.php # Chatbot
│       │   ├── home.blade.php           # Trang chủ
│       │   ├── profile.blade.php        # Thông tin cá nhân
│       │   ├── products/
│       │   │   ├── index.blade.php      # Danh sách SP
│       │   │   └── detail.blade.php     # Chi tiết SP
│       │   ├── cart/
│       │   │   ├── index.blade.php      # Giỏ hàng
│       │   │   └── checkout.blade.php   # Thanh toán
│       │   └── orders/
│       │       └── index.blade.php      # Đơn hàng của tôi
│       └── auth/                # 🔵 Authentication
│           ├── login.blade.php
│           └── register.blade.php
├── routes/
│   ├── web.php              # Routes chính (entry point)
│   ├── admin.php            # 🔴 Admin routes
│   ├── auth.php             # Authentication routes
│   ├── product.php          # User product routes
│   ├── cart.php             # Cart & checkout routes
│   └── user.php             # User profile routes
├── .env                     # Environment config
├── composer.json            # PHP dependencies
├── RESTRUCTURE_GUIDE.md     # Hướng dẫn cấu trúc mới
├── MIGRATION_COMPLETE.md    # Tài liệu migration
└── README.md               # File này
```

---

## 🚀 Cài đặt

### Yêu cầu hệ thống

-   PHP >= 8.2
-   Composer
-   MySQL >= 8.0
-   Node.js & NPM (optional)
-   Web Server (Apache/Nginx)

### Các bước cài đặt

#### 1. Clone repository

```bash
git clone https://github.com/your-username/organic-shop.git
cd organic-shop
```

#### 2. Cài đặt dependencies

```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt Node dependencies (optional)
npm install
```

#### 3. Cấu hình môi trường

```bash
# Copy file .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Cấu hình database

Mở file `.env` và cập nhật thông tin database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=QuanLyNongSan
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 5. Import database

```bash
# Tạo database
mysql -u root -p -e "CREATE DATABASE QuanLyNongSan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import database chính
mysql -u root -p QuanLyNongSan < database/database.sql

# Import database bổ sung
mysql -u root -p QuanLyNongSan < database/database2.sql
```

#### 6. Chạy server

```bash
# Chạy Laravel development server
php artisan serve

# Truy cập: http://localhost:8000
```

#### 7. Compile assets (optional)

```bash
# Development
npm run dev

# Production
npm run build
```

---

## 💾 Database

### Tổng quan

-   **Tổng số bảng**: 39 bảng
-   **Database chính**: 24 bảng (database.sql)
-   **Database bổ sung**: 15 bảng (database2.sql)
-   **Triggers**: 7 triggers tự động
-   **Stored Procedures**: 5 procedures
-   **Views**: 3 views tối ưu
-   **Indexes**: 25+ indexes

### Các bảng chính

| Bảng           | Mô tả               | Số cột |
| -------------- | ------------------- | ------ |
| VaiTro         | Vai trò người dùng  | 5      |
| NguoiDung      | Thông tin tài khoản | 15     |
| DanhMuc        | Danh mục sản phẩm   | 8      |
| SanPham        | Sản phẩm            | 23     |
| GioHang        | Giỏ hàng            | 7      |
| DonHang        | Đơn hàng            | 19     |
| ChiTietDonHang | Chi tiết đơn hàng   | 7      |
| KhuyenMai      | Khuyến mãi          | 10     |
| Voucher        | Mã giảm giá         | 13     |
| DanhGia        | Đánh giá sản phẩm   | 10     |

### Tính năng nâng cao

✅ Multi-address (Nhiều địa chỉ giao hàng)  
✅ Order tracking (Theo dõi đơn hàng)  
✅ Wishlist (Sản phẩm yêu thích)  
✅ Search history (Lịch sử tìm kiếm)  
✅ Product tags (Tags sản phẩm)  
✅ FAQ system (Hệ thống câu hỏi)  
✅ Review feedback (Đánh giá hữu ích)  
✅ Analytics (Thống kê truy cập)  
✅ Activity log (Nhật ký hoạt động)

### Xem chi tiết

-   [DATABASE_README.md](database/DATABASE_README.md) - Hướng dẫn chi tiết database
-   [SO_SANH_YEU_CAU.md](database/SO_SANH_YEU_CAU.md) - So sánh với yêu cầu

---

## 🎨 Tính năng đặc biệt

### 🤖 Chatbot Widget

Chatbot AI tích hợp sẵn với các tính năng:

-   ✅ Giao diện hiện đại với gradient màu xanh lá
-   ✅ Toggle mở/đóng mượt mà
-   ✅ Trả lời tự động dựa trên từ khóa
-   ✅ Quick reply buttons (nút trả lời nhanh)
-   ✅ Hiển thị trạng thái "We're online!"
-   ✅ Typing indicator (đang gõ...)
-   ✅ Lưu trạng thái chat (sessionStorage)
-   ✅ Nút "Chat với Admin" để liên hệ trực tiếp
-   ✅ Responsive trên mọi thiết bị
-   ✅ Có thể tái sử dụng trên nhiều trang

**Files:**

-   CSS: `public/template/Assets/css/chatbot.css`
-   JS: `public/template/Assets/js/chatbot.js`
-   View: `resources/views/user/partials/chatbot-widget.blade.php`

### 📜 Scroll to Top Button

-   ✅ Hiển thị khi cuộn xuống > 300px
-   ✅ Animation fade in/out mượt mà
-   ✅ Gradient màu xanh lá
-   ✅ Vị trí cố định phía trên chatbot toggle

### 🎯 Responsive Design

-   ✅ Mobile-first approach
-   ✅ Breakpoints: 320px, 768px, 1024px, 1920px
-   ✅ Touch-friendly buttons
-   ✅ Optimized images

---

## 📖 Hướng dẫn sử dụng

### Khách hàng

#### Đăng ký tài khoản

1. Truy cập `/register`
2. Điền thông tin: Tên, Email, SĐT, Mật khẩu
3. Nhấn "Đăng ký"

#### Mua hàng

1. Duyệt sản phẩm trên trang chủ hoặc danh mục
2. Nhấn "Thêm vào giỏ" trên sản phẩm muốn mua
3. Vào giỏ hàng, kiểm tra và cập nhật số lượng
4. Nhấn "Thanh toán"
5. Điền thông tin giao hàng
6. Chọn phương thức thanh toán
7. Xác nhận đơn hàng

#### Theo dõi đơn hàng

1. Đăng nhập
2. Vào "Tài khoản" > "Đơn hàng của tôi"
3. Xem chi tiết và trạng thái đơn hàng

### Quản trị viên

#### Truy cập Admin Panel

```
URL: http://localhost:8000/admin/dashboard
Yêu cầu: Đăng nhập với tài khoản có vai trò Admin
```

**Lưu ý:** Hiện tại chưa có middleware phân quyền, cần implement `AdminMiddleware` để kiểm tra vai trò.

#### Quản lý sản phẩm

1. Vào "Quản lý sản phẩm"
2. Nhấn "Thêm sản phẩm mới"
3. Điền thông tin và upload hình
4. Lưu sản phẩm

#### Xử lý đơn hàng

1. Vào "Quản lý đơn hàng"
2. Chọn đơn hàng cần xử lý
3. Cập nhật trạng thái:
    - Chờ xác nhận → Đã xác nhận
    - Đã xác nhận → Đang giao
    - Đang giao → Đã giao
4. Lưu thay đổi

---

## 📸 Screenshots

### Giao diện dự án

#### Đăng nhập

![alt text](<public/screenshots/Screenshot 2025-12-06 105150.png>)

#### Đăng ký

![alt text](<public/screenshots/Screenshot 2025-12-06 105224.png>)

#### Trang chủ

![alt text](<public/screenshots/Screenshot 2025-12-06 104504.png>)

#### Danh sách sản phẩm

![alt text](<public/screenshots/Screenshot 2025-12-06 125216.png>)

#### Chi tiết sản phẩm

![alt text](<public/screenshots/Screenshot 2025-12-06 130658.png>)

#### Giỏ hàng

![alt text](<public/screenshots/Screenshot 2025-12-06 105440.png>)

#### Thanh toán

![alt text](<public/screenshots/Screenshot 2025-12-06 130113.png>)

#### Chatbot

![alt text](<public/screenshots/Screenshot 2025-12-06 110422.png>)

#### Chat với Admin

![alt text](<public/screenshots/Screenshot 2025-12-06 110443.png>)

#### Admin Dashboard

#### Quản lý banner

![alt text](<public/screenshots/Screenshot 2025-12-06 132333.png>)

## ...

## 📚 Tài liệu

### Hướng dẫn chi tiết

-   [RESTRUCTURE_GUIDE.md](RESTRUCTURE_GUIDE.md) - Hướng dẫn cấu trúc User/Admin
-   [MIGRATION_COMPLETE.md](MIGRATION_COMPLETE.md) - Tài liệu migration và cập nhật
-   [DATABASE_README.md](database/DATABASE_README.md) - Database chi tiết
-   [SO_SANH_YEU_CAU.md](database/SO_SANH_YEU_CAU.md) - So sánh với yêu cầu

### Tính năng đặc biệt

-   **Chatbot Widget**: Xem code tại `public/template/Assets/js/chatbot.js`
-   **Scroll to Top**: Tích hợp trong chatbot.js
-   **Responsive Design**: Xem `public/template/Assets/css/chatbot.css`

### API Documentation

Đang cập nhật...

---

## 📝 TODO List

### Cần hoàn thiện

-   [x] Tạo `AdminMiddleware` để phân quyền admin
-   [x] Tạo các Controllers cho Admin module
-   [x] Tạo các Controllers cho User module
-   [x] Implement authentication (JWT Auth)
-   [ ] Tạo Seeders cho database
-   [ ] Implement payment gateways (VNPay, MoMo)
-   [ ] Tạo API endpoints
-   [ ] Viết Unit Tests
-   [ ] Tối ưu performance
-   [ ] SEO optimization

### Đã hoàn thành

-   [x] Tái cấu trúc views theo User/Admin
-   [x] Tách routes riêng biệt
-   [x] Tạo admin layout và partials
-   [x] Tạo user layout và partials
-   [x] Implement chatbot widget
-   [x] Scroll to top button
-   [x] Responsive design
-   [x] Database schema (19 bảng tối ưu)
-   [x] Cập nhật tất cả route names

---

## 🔧 Troubleshooting

### Lỗi thường gặp

#### 1. Lỗi "Class not found"

```bash
composer dump-autoload
```

#### 2. Lỗi "Permission denied"

```bash
chmod -R 775 storage bootstrap/cache
```

#### 3. Lỗi database connection

-   Kiểm tra thông tin trong file `.env`
-   Đảm bảo MySQL đang chạy
-   Kiểm tra username/password

#### 4. Lỗi "Mix manifest not found"

```bash
npm install
npm run dev
```

---

## 🤝 Đóng góp

Mọi đóng góp đều được chào đón! Vui lòng:

1. Fork repository
2. Tạo branch mới (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

---

## 📝 License

Dự án này được phát hành dưới giấy phép [MIT License](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Tác giả

**Tên của bạn**

-   Email: thuanthichlaptrinh@gmail.com
-   GitHub: [@thuanthichlaptrinh](https://github.com/thuanthichlaptrinh)

---

## 🙏 Lời cảm ơn

-   Laravel Framework
-   Bootstrap Team
-   Remix Icon
-   Tất cả contributors

---

## 📞 Liên hệ

Nếu có bất kỳ câu hỏi nào, vui lòng liên hệ:

-   Email: thuanthichlaptrinh@gmail.com
-   Website: https://organic.vn
-   Facebook: https://facebook.com/thuanthichlaptrinh

---

## 📋 Changelog

### Version 2.0.0 (2025-11-21)

-   ✨ Tái cấu trúc hoàn toàn theo kiến trúc User/Admin
-   ✨ Thêm Chatbot Widget với AI
-   ✨ Thêm Scroll to Top button
-   ✨ Tối ưu database (giảm từ 39 xuống 19 bảng)
-   ✨ Cập nhật toàn bộ routes và views
-   🐛 Sửa lỗi route names
-   📝 Cập nhật documentation

### Version 1.0.0 (2025-11-01)

-   🎉 Phiên bản đầu tiên
-   ✨ Tính năng cơ bản cho User và Admin
-   ✨ Database với 39 bảng

---

<p align="center">Made with ❤️ by thuanthichlaptrinh</p>
<p align="center">© 2025 Organic Shop. All rights reserved.</p>
<p align="center">
  <strong>Version 2.0.0</strong> | 
  <a href="RESTRUCTURE_GUIDE.md">Docs</a> | 
  <a href="MIGRATION_COMPLETE.md">Migration Guide</a>
</p>
