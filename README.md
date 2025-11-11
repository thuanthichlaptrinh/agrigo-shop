# 🌿 Hệ thống Quản lý Bán Nông Sản Organic

> Website thương mại điện tử bán nông sản hữu cơ được xây dựng bằng Laravel 11

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)

---

## 📋 Mục lục

- [Giới thiệu](#-giới-thiệu)
- [Tính năng](#-tính-năng)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [Cài đặt](#-cài-đặt)
- [Database](#-database)
- [Hướng dẫn sử dụng](#-hướng-dẫn-sử-dụng)
- [Screenshots](#-screenshots)
- [Tài liệu](#-tài-liệu)
- [Tác giả](#-tác-giả)

---

## 🎯 Giới thiệu

Hệ thống quản lý bán nông sản Organic là một website thương mại điện tử chuyên bán các sản phẩm nông sản hữu cơ, rau củ quả tươi sạch. Dự án được xây dựng với mục đích:

- ✅ Cung cấp nền tảng mua bán nông sản trực tuyến
- ✅ Quản lý sản phẩm, đơn hàng, khách hàng hiệu quả
- ✅ Hỗ trợ nhiều phương thức thanh toán
- ✅ Giao diện thân thiện, dễ sử dụng
- ✅ Responsive trên mọi thiết bị

---

## ✨ Tính năng

### 🛍️ Khách hàng (Customer)

#### 1. Trang chủ
- Banner quảng cáo với carousel
- Danh mục sản phẩm nổi bật
- Sản phẩm khuyến mãi sốc
- Gian hàng và ưu đãi từ hãng
- Bài viết/tin tức

#### 2. Quản lý sản phẩm
- Xem danh sách sản phẩm (có phân trang)
- Chi tiết sản phẩm (hình ảnh, mô tả, giá, đánh giá)
- Tìm kiếm và lọc sản phẩm
- Sản phẩm theo danh mục

#### 3. Giỏ hàng
- Thêm sản phẩm vào giỏ
- Cập nhật số lượng
- Xóa sản phẩm
- Tính tổng tiền tự động

#### 4. Thanh toán
- Nhập thông tin giao hàng
- Chọn phương thức thanh toán (COD, VNPay, MoMo, ZaloPay)
- Áp dụng voucher giảm giá
- Xác nhận đơn hàng

#### 5. Tài khoản
- Đăng ký/Đăng nhập
- Quên mật khẩu
- Xem thông tin cá nhân
- Lịch sử đơn hàng
- Quản lý địa chỉ giao hàng
- Sản phẩm yêu thích

#### 6. Đánh giá
- Đánh giá sản phẩm (1-5 sao)
- Viết nhận xét
- Upload hình ảnh
- Xem đánh giá của người khác

### 👨‍💼 Quản trị viên (Admin)

#### 1. Dashboard
- Thống kê tổng quan
- Doanh thu theo thời gian
- Đơn hàng mới
- Sản phẩm bán chạy

#### 2. Quản lý sản phẩm
- Thêm/Sửa/Xóa sản phẩm
- Quản lý danh mục
- Quản lý hình ảnh
- Quản lý tồn kho

#### 3. Quản lý đơn hàng
- Xem danh sách đơn hàng
- Xác nhận đơn hàng
- Cập nhật trạng thái
- In hóa đơn

#### 4. Quản lý khách hàng
- Xem danh sách khách hàng
- Xem lịch sử mua hàng
- Quản lý tài khoản

#### 5. Quản lý khuyến mãi
- Tạo chương trình khuyến mãi
- Tạo voucher giảm giá
- Áp dụng cho sản phẩm

#### 6. Quản lý nội dung
- Quản lý banner
- Quản lý bài viết
- Quản lý FAQ

#### 7. Báo cáo thống kê
- Doanh thu theo ngày/tháng/năm
- Sản phẩm bán chạy
- Khách hàng thân thiết
- Thống kê theo danh mục

---

## 🛠️ Công nghệ sử dụng

### Backend
- **Laravel 11** - PHP Framework
- **PHP 8.2+** - Programming Language
- **MySQL 8.0+** - Database
- **Composer** - Dependency Manager

### Frontend
- **Bootstrap 5.3** - CSS Framework
- **Blade Template** - Laravel Template Engine
- **Remix Icon** - Icon Library
- **JavaScript (Vanilla)** - Client-side scripting

### Tools & Libraries
- **Laravel Breeze** - Authentication (optional)
- **Laravel Debugbar** - Debugging
- **Intervention Image** - Image processing
- **Carbon** - Date/Time library

---

## 📁 Cấu trúc dự án

```
organic-shop/
├── app/
│   ├── Http/
│   │   └── Controllers/     # Controllers
│   ├── Models/              # Eloquent Models
│   └── ...
├── database/
│   ├── database.sql         # Database chính (24 bảng)
│   ├── database2.sql        # Database bổ sung (15 bảng)
│   ├── DATABASE_README.md   # Hướng dẫn database
│   └── SO_SANH_YEU_CAU.md  # So sánh với yêu cầu
├── docs/
│   ├── VIEWS_README.md              # Hướng dẫn views
│   ├── HUONG_DAN_GIAN_HANG_UU_DAI.md # Hướng dẫn gian hàng
│   └── HUONG_DAN_BAI_VIET.md        # Hướng dẫn bài viết
├── public/
│   └── template/
│       ├── Assets/
│       │   ├── css/         # CSS files
│       │   ├── js/          # JavaScript files
│       │   └── Images/      # Images
│       └── ...
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php        # Layout chính
│       ├── partials/
│       │   ├── header.blade.php     # Header
│       │   └── footer.blade.php     # Footer
│       ├── auth/
│       │   ├── login.blade.php      # Đăng nhập
│       │   └── register.blade.php   # Đăng ký
│       ├── home.blade.php           # Trang chủ
│       ├── products.blade.php       # Danh sách SP
│       ├── product-detail.blade.php # Chi tiết SP
│       ├── cart.blade.php           # Giỏ hàng
│       ├── checkout.blade.php       # Thanh toán
│       └── profile.blade.php        # Thông tin cá nhân
├── routes/
│   ├── web.php              # Routes chính
│   ├── auth.php             # Routes authentication
│   ├── product.php          # Routes sản phẩm
│   ├── cart.php             # Routes giỏ hàng
│   └── user.php             # Routes user
├── .env                     # Environment config
├── composer.json            # PHP dependencies
├── package.json             # Node dependencies
└── README.md               # File này
```

---

## 🚀 Cài đặt

### Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (optional)
- Web Server (Apache/Nginx)

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

- **Tổng số bảng**: 39 bảng
- **Database chính**: 24 bảng (database.sql)
- **Database bổ sung**: 15 bảng (database2.sql)
- **Triggers**: 7 triggers tự động
- **Stored Procedures**: 5 procedures
- **Views**: 3 views tối ưu
- **Indexes**: 25+ indexes

### Các bảng chính

| Bảng | Mô tả | Số cột |
|------|-------|--------|
| VaiTro | Vai trò người dùng | 5 |
| NguoiDung | Thông tin tài khoản | 15 |
| DanhMuc | Danh mục sản phẩm | 8 |
| SanPham | Sản phẩm | 23 |
| GioHang | Giỏ hàng | 7 |
| DonHang | Đơn hàng | 19 |
| ChiTietDonHang | Chi tiết đơn hàng | 7 |
| KhuyenMai | Khuyến mãi | 10 |
| Voucher | Mã giảm giá | 13 |
| DanhGia | Đánh giá sản phẩm | 10 |

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

- [DATABASE_README.md](database/DATABASE_README.md) - Hướng dẫn chi tiết database
- [SO_SANH_YEU_CAU.md](database/SO_SANH_YEU_CAU.md) - So sánh với yêu cầu

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

#### Đăng nhập Admin
```
URL: /admin/login
Email: admin@organic.vn
Password: admin123
```

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

### Trang chủ
- Banner carousel
- Danh mục sản phẩm
- Khuyến mãi sốc
- Gian hàng và ưu đãi
- Bài viết

### Sản phẩm
- Danh sách sản phẩm
- Chi tiết sản phẩm
- Đánh giá sản phẩm

### Giỏ hàng & Thanh toán
- Giỏ hàng
- Trang thanh toán
- Xác nhận đơn hàng

---

## 📚 Tài liệu

### Hướng dẫn chi tiết

- [VIEWS_README.md](docs/VIEWS_README.md) - Hướng dẫn về Views
- [HUONG_DAN_GIAN_HANG_UU_DAI.md](docs/HUONG_DAN_GIAN_HANG_UU_DAI.md) - Phần gian hàng và ưu đãi
- [HUONG_DAN_BAI_VIET.md](docs/HUONG_DAN_BAI_VIET.md) - Phần bài viết
- [DATABASE_README.md](database/DATABASE_README.md) - Database chi tiết

### API Documentation

Đang cập nhật...

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
- Kiểm tra thông tin trong file `.env`
- Đảm bảo MySQL đang chạy
- Kiểm tra username/password

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
- Email: thuanthichlaptrinh@gmail.com
- GitHub: [@thuanthichlaptrinh](https://github.com/thuanthichlaptrinh)

---

## 🙏 Lời cảm ơn

- Laravel Framework
- Bootstrap Team
- Remix Icon
- Tất cả contributors

---

## 📞 Liên hệ

Nếu có bất kỳ câu hỏi nào, vui lòng liên hệ:
- Email: thuanthichlaptrinh@gmail.com
- Website: https://organic.vn
- Facebook: https://facebook.com/thuanthichlaptrinh

---

<p align="center">Made with ❤️ by thuanthichlaptrinh</p>
<p align="center">© 2025 Organic Shop. All rights reserved.</p>
