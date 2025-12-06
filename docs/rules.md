# 📋 RULES - Hướng dẫn phát triển dự án Nông Sản (Bach Hoa Xanh Clone)

## 🎯 Tổng quan dự án

**Tên dự án:** Website bán nông sản trực tuyến
**Framework:** Laravel (PHP)  
**Database:** MySQL với 23 bảng cấu trúc tương tự như file database2.sql hoặc trong các models
**Ngôn ngữ:** Tiếng Việt (Vietnamese)

---

## 🗄️ Cấu trúc Database

### Danh sách các bảng chính:

| Bảng                | Mô tả                    | Số bản ghi |
| ------------------- | ------------------------ | ---------- |
| `nguoidung`         | Người dùng/Khách hàng    | 12         |
| `vaitro`            | Vai trò (Admin, User...) | 4          |
| `sanpham`           | Sản phẩm                 | 23         |
| `loaisanpham`       | Loại/Danh mục sản phẩm   | 19         |
| `danhmuc`           | Danh mục lớn             | 12         |
| `hinhanhsanpham`    | Hình ảnh sản phẩm        | 2          |
| `donhang`           | Đơn hàng                 | 11         |
| `chitietdonhang`    | Chi tiết đơn hàng        | 19         |
| `giohang`           | Giỏ hàng                 | 2          |
| `thanhtoan`         | Thanh toán               | 11         |
| `nhacungcap`        | Nhà cung cấp             | 6          |
| `khuyenmai`         | Khuyến mãi               | 3          |
| `sanphamkhuyenmai`  | Sản phẩm khuyến mãi      | 4          |
| `voucher`           | Mã giảm giá              | 0          |
| `danhgia`           | Đánh giá sản phẩm        | 1          |
| `baiviet`           | Bài viết/Tin tức         | 5          |
| `banner`            | Banner quảng cáo         | 4          |
| `thongbao`          | Thông báo                | 1          |
| `nhatky`            | Nhật ký hoạt động        | 22         |
| `hoatdongnguoidung` | Hoạt động người dùng     | 4          |
| `lienhe`            | Liên hệ/Hỗ trợ           | 0          |
| `token`             | Token xác thực           | 7          |

---

## ✅ Chức năng chính cần có

### 1. Trang chủ (Home page)

-   [ ] Giới thiệu tổng quan về cửa hàng
-   [ ] Hiển thị sản phẩm nổi bật, khuyến mãi, banner quảng cáo

### 2. Quản lý sản phẩm

-   [ ] Hiển thị danh sách sản phẩm (phân loại theo danh mục)
-   [ ] Chi tiết sản phẩm (tên, mô tả, giá, hình ảnh, đánh giá...)
-   [ ] Tìm kiếm và lọc sản phẩm theo giá, danh mục, tên, thương hiệu

### 3. Giỏ hàng (Shopping Cart)

-   [ ] Cho phép người dùng thêm, cập nhật, xóa sản phẩm
-   [ ] Tính tổng tiền đơn hàng

### 4. Thanh toán (Checkout)

-   [ ] Nhập thông tin giao hàng, phương thức thanh toán
-   [ ] Xác nhận đơn hàng

### 5. Tài khoản người dùng

-   [ ] Đăng ký, đăng nhập, quên mật khẩu
-   [ ] Quản lý thông tin cá nhân, lịch sử đơn hàng, đổi mật khẩu

### 6. Quản trị (Admin)

-   [ ] Đăng nhập quản trị
-   [ ] Quản lý sản phẩm (thêm, sửa, xóa)
-   [ ] Quản lý đơn hàng (xác nhận, xử lý, giao hàng)
-   [ ] Quản lý nhân viên, QL Khách hàng
-   [ ] Quản lý danh mục sản phẩm
-   [ ] Quản lý nội dung (banner, tin tức...)
-   [ ] Thống kê doanh thu theo nhiều tiêu chí: Loại SP, SP, Khách hàng...

### 7. Tìm kiếm và lọc

-   [ ] Tìm kiếm theo từ khóa
-   [ ] Lọc theo giá, danh mục, hãng sản xuất...

### 8. Đánh giá và bình luận

-   [ ] Khách hàng có thể đánh giá và để lại nhận xét cho sản phẩm

### 9. Hỗ trợ khách hàng

-   [ ] Chat trực tuyến, form liên hệ, câu hỏi thường gặp (FAQ)

### 10. Khuyến mãi và mã giảm giá

-   [ ] Áp dụng voucher, mã giảm giá trong giỏ hàng hoặc khi thanh toán

---

## 📊 Tiêu chí chấm điểm

| Tiêu chí                       | Trọng số | Mô tả                                                                                    |
| ------------------------------ | -------- | ---------------------------------------------------------------------------------------- |
| **Hoàn thiện chức năng chính** | 40%      | CRUD, auth, validation, pagination, search                                               |
| **Áp dụng công nghệ Laravel**  | 25%      | Eloquent ORM, Migration, Blade, Middleware                                               |
| **Thiết kế & trải nghiệm**     | 15%      | Giao diện đẹp, responsive, thân thiện                                                    |
| **Báo cáo & thuyết trình**     | 10%      | Báo cáo đầy đủ (ERD, Use Case, mô tả chức năng, ảnh chụp màn hình), thuyết trình rõ ràng |
| **Sáng tạo & mở rộng**         | 10%      | AJAX, API, tính năng nâng cao, UX cải tiến                                               |

---

## 🏗️ Cấu trúc thư mục Laravel

```
nongsan/
├── app/
│   ├── Http/Controllers/     # Controllers
│   │   ├── Admin/            # Admin controllers
│   │   └── ...
│   ├── Models/               # Eloquent Models (tiếng Việt)
│   └── Support/              # Helpers, Traits
├── resources/
│   └── views/
│       ├── admin/            # Admin views
│       │   ├── layouts/      # Admin layout chung
│       │   ├── partials/     # Sidebar, navbar
│       │   └── [module]/     # Views theo module
│       ├── user/             # User/Client views
│       └── components/       # Blade components (alert-stack...)
├── routes/
│   ├── admin.php             # Admin routes
│   ├── auth.php              # Authentication routes
│   ├── cart.php              # Cart routes
│   ├── product.php           # Product routes
│   └── web.php               # Main routes
├── public/
│   └── template/             # CSS, JS, images
└── docs/                     # Documentation
```

---

## 🎨 Quy tắc code

### Naming Convention (Tiếng Việt)

-   **Models:** Tên bảng tiếng Việt (NguoiDung, SanPham, DonHang...)
-   **Controllers:** PascalCase + Controller (NhatKyController, SanPhamController...)
-   **Views:** kebab-case hoặc snake_case (index.blade.php, create.blade.php...)
-   **Routes:** Tiếng Anh prefix + tiếng Việt resource (admin.logs, admin.products...)

### Database Columns (Tiếng Việt)

```
ID              - Khóa chính
TenSanPham      - Tên sản phẩm
GiaBan          - Giá bán
SoLuongTon      - Số lượng tồn
TrangThai       - Trạng thái
NgayTao         - Ngày tạo
NgayCapNhat     - Ngày cập nhật
...
```

### Components dùng chung

-   **Toast/Alert:** Sử dụng `<x-alert-stack :messages="$sharedAlerts" />` đã có sẵn trong `components/alert-stack.blade.php`, phân trang (vendor/pagnation)
-   **Layout Admin:** Extend từ `admin.layouts.app`
-   **Layout User:** Extend từ `user.layouts.app`

---

## 🔧 Công nghệ sử dụng

-   **Backend:** Laravel 10+ (PHP 8.1+)
-   **Frontend:** Blade, Bootstrap 5, FontAwesome 6
-   **Database:** MySQL 8.0
-   **Authentication:** JWT (tymon/jwt-auth)
-   **Build:** Vite

---

## 📝 Ghi chú khi chat

1. **Ngôn ngữ:** Ưu tiên tiếng Việt trong giao diện, code comments
2. **Toast notifications:** Sử dụng component `alert-stack` có sẵn thay vì tạo mới
3. **Admin layout:** Luôn extend `admin.layouts.app` cho các trang admin
4. **Responsive:** Đảm bảo giao diện hoạt động tốt trên mobile
5. **Validation:** Luôn validate input ở cả client và server
6. **Error handling:** Hiển thị lỗi thân thiện với người dùng

---

## 🚀 Các trang Admin đã hoàn thành

-   [x] Dashboard (`admin.dashboard`)
-   [x] Quản lý sản phẩm (`admin.products`)
-   [x] Quản lý người dùng (`admin.users`)
-   [x] Quản lý danh mục (`admin.categories`, `admin.catalog`)
-   [x] Quản lý đơn hàng (`admin.orders`)
-   [x] Quản lý nhà cung cấp (`admin.suppliers`)
-   [x] Quản lý khuyến mãi (`admin.promotions`)
-   [x] Quản lý voucher (`admin.vouchers`)
-   [x] Quản lý banner (`admin.banners`)
-   [x] Quản lý bài viết (`admin.articles`)
-   [x] Quản lý thông báo (`admin.notifications`)
-   [x] Quản lý nhật ký (`admin.logs`)
-   [x] Quản lý vai trò (`admin.roles`)

---

_Cập nhật lần cuối: 25/11/2025_
