# 🌿 Hệ thống Quản lý Bán Nông Sản Organic

> Website thương mại điện tử bán nông sản hữu cơ được xây dựng bằng Laravel 11

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)

---

## 🎯 Giới thiệu

Hệ thống quản lý bán nông sản Organic là một website thương mại điện tử chuyên bán các sản phẩm nông sản hữu cơ, rau củ quả tươi sạch. Dự án được xây dựng với mục đích:

-   ✅ Cung cấp nền tảng mua bán nông sản trực tuyến
-   ✅ Quản lý sản phẩm, đơn hàng, khách hàng hiệu quả
-   ✅ Hỗ trợ nhiều phương thức thanh toán
-   ✅ Giao diện thân thiện, dễ sử dụng
-   ✅ Responsive trên mọi thiết bị

---

## 🛠️ Công nghệ sử dụng

### Backend
- Laravel, MySQL, JWT, ...

### Frontend
- Bootstrap, Blade Template, Remix Icon, JavaScript

---

## 📸 Demo

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
