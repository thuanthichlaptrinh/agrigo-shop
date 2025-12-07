# DANH SÁCH CHỨC NĂNG DỰ ÁN NÔNG SẢN

## 📋 TỔNG QUAN DỰ ÁN

Dự án Website bán nông sản (lấy ý tưởng từ Bách Hóa Xanh) được phát triển bằng Laravel, tập trung vào trải nghiệm người dùng thân thiện, giúp khách hàng dễ dàng tiếp cận các sản phẩm thực phẩm sạch, tươi ngon. Website cung cấp đầy đủ các tính năng từ xem, tìm kiếm, lọc sản phẩm, thêm giỏ hàng đến thanh toán trực tuyến.

Đối với khách hàng, hệ thống cho phép xem các sản phẩm nổi bật, tìm kiếm theo danh mục (rau củ, trái cây, thịt cá...), lọc sản phẩm theo nhu cầu, thêm vào giỏ hàng và thanh toán tiện lợi. Khách hàng cũng có thể quản lý thông tin cá nhân và theo dõi đơn hàng.

Đối với quản trị viên (admin), hệ thống cung cấp các công cụ quản lý toàn diện (CRUD) cho sản phẩm, đơn hàng, người dùng, bài viết và các chương trình khuyến mãi.

---

## 🛠️ II. CHỨC NĂNG CHÍNH CỦA DỰ ÁN

### 1. Khách hàng

Một số chức năng chính triển khai dành cho khách hàng:

-   **Account**: Đăng ký, đăng nhập, quản lý thông tin cá nhân, đổi mật khẩu, quên mật khẩu.
-   **Giỏ hàng**: Thêm sản phẩm, sửa số lượng, xóa sản phẩm.
-   **Thanh toán**: Thanh toán trực tuyến, áp dụng mã giảm giá (Voucher).
-   **Sản phẩm**: Xem chi tiết, đánh giá, tìm kiếm, lọc sản phẩm theo danh mục (Rau, Củ, Quả, Thịt...).
-   **Chat box**: Tư vấn trực tuyến với nhân viên hoặc Chatbot AI.

### 2. Quản trị viên

Một số chức năng chính dành cho quản trị viên:

-   **Sản phẩm**: Thêm, sửa, xóa, tìm kiếm, lọc sản phẩm.
-   **Banner**: Thêm, sửa, xóa banner quảng cáo.
-   **Bài viết**: Thêm, sửa, xóa bài viết tin tức/blog.
-   **Khách hàng**: Xem danh sách, xóa, khóa/mở khóa tài khoản.
-   **Đơn hàng**: Xem chi tiết, cập nhật trạng thái (Duyệt/Hủy/Giao hàng).
-   **Trang chủ**: Quản lý hiển thị banner, sản phẩm nổi bật.
-   **Doanh thu**: Thống kê doanh thu, lợi nhuận, báo cáo tăng trưởng.

---

## 🌐 III. CHI TIẾT CHỨC NĂNG NGƯỜI DÙNG (USER)

### 1. **Trang chủ (Homepage)**

-   Hiển thị banner quảng cáo (Trang chủ & Sản phẩm)
-   Hiển thị sản phẩm Flash Sale (khuyến mãi hot)
-   Hiển thị sản phẩm giảm giá >= 50% (Brand Deals)
-   Hiển thị sản phẩm nổi bật (Favorite Products)
-   Hiển thị sản phẩm theo danh mục (Category Sections)
-   Hiển thị 5 bài viết mới nhất (Home Articles)
-   **Route:** `/` (name: `user.home`)

### 2. **Xác thực & Bảo mật (Authentication)**

#### Đăng nhập (Login)

-   Form đăng nhập với Email/SĐT và mật khẩu
-   **Route:** `GET/POST /login`

#### Đăng ký (Register)

-   Form đăng ký tài khoản mới
-   **Route:** `GET/POST /register`

#### Quên mật khẩu (Forgot Password)

-   Gửi email reset mật khẩu
-   **Route:** `GET/POST /forgot-password`

#### Đặt lại mật khẩu (Reset Password)

-   Đặt mật khẩu mới qua token
-   **Route:** `GET/POST /reset-password/{token}`

#### Đăng xuất (Logout)

-   **Route:** `POST /logout`

### 3. **Quản lý Sản phẩm (Products)**

#### Danh sách sản phẩm

-   Xem tất cả sản phẩm
-   Lọc và tìm kiếm sản phẩm
-   **Route:** `GET /products` (name: `user.products.index`)

#### Chi tiết sản phẩm

-   Xem thông tin chi tiết sản phẩm
-   Hiển thị hình ảnh, giá, mô tả, khuyến mãi
-   Xem đánh giá của khách hàng khác
-   **Route:** `GET /products/{id}` (name: `user.products.detail`)

#### Đánh giá sản phẩm

-   Viết đánh giá và chấm sao (yêu cầu đăng nhập)
-   **Route:** `POST /products/{id}/reviews` (name: `user.products.reviews.store`)

#### Tìm kiếm sản phẩm

-   Tìm kiếm theo từ khóa
-   **Route:** `GET /search` (name: `user.search`)

#### Lịch sử từ khóa tìm kiếm

-   API lấy các từ khóa đã tìm kiếm
-   **Route:** `GET /api/search-keywords`

#### Xem theo danh mục

-   Lọc sản phẩm theo danh mục
-   **Route:** `GET /category/{slug}` (name: `user.category`)

### 4. **Giỏ hàng (Shopping Cart)**

#### Xem giỏ hàng

-   Hiển thị danh sách sản phẩm trong giỏ
-   **Route:** `GET /cart` (name: `user.cart.index`)

#### Thêm vào giỏ hàng

-   **Route:** `POST /cart/add` (name: `user.cart.add`)

#### Cập nhật số lượng

-   **Route:** `POST /cart/update` (name: `user.cart.update`)

#### Xóa sản phẩm khỏi giỏ

-   **Route:** `DELETE /cart/remove/{id}` (name: `user.cart.remove`)

#### Xóa toàn bộ giỏ hàng

-   **Route:** `DELETE /cart/clear` (name: `user.cart.clear`)

#### Đặt lại đơn hàng cũ

-   **Route:** `POST /cart/reorder` (name: `user.cart.reorder`)

### 5. **Thanh toán (Checkout)**

#### Trang thanh toán

-   **Route:** `GET /checkout` (name: `user.checkout.index`)

#### Xử lý thanh toán

-   **Route:** `POST /checkout` (name: `user.checkout.process`)

#### Trang thông tin thanh toán

-   **Route:** `GET /checkout/payment` (name: `user.checkout.payment`)

#### Áp dụng mã giảm giá (Voucher)

-   **Route:** `POST /checkout/voucher` (name: `user.checkout.voucher`)

#### Xác nhận đơn hàng

-   **Route:** `POST /checkout/confirm` (name: `user.checkout.confirm`)

#### Hủy đơn hàng

-   **Route:** `POST /checkout/cancel` (name: `user.checkout.cancel`)

#### Chỉnh sửa thông tin đơn hàng

-   **Route:** `GET /checkout/edit` (name: `user.checkout.edit`)
-   **Route:** `POST /checkout/edit` (name: `user.checkout.update`)

### 6. **Tài khoản & Hồ sơ (Profile & Account)**

_Yêu cầu đăng nhập và middleware 'user'_

#### Xem & Cập nhật hồ sơ

-   Xem thông tin cá nhân
-   **Route:** `GET /user/profile` (name: `user.profile`)
-   Cập nhật thông tin
-   **Route:** `PUT /user/profile` (name: `user.profile.update`)

#### Quản lý đơn hàng

-   Xem danh sách đơn hàng
-   **Route:** `GET /user/orders` (name: `user.orders.index`)
-   Xem chi tiết đơn hàng
-   **Route:** `GET /user/orders/{order}` (name: `user.orders.show`)
-   Hủy đơn hàng
-   **Route:** `POST /user/orders/{order}/cancel` (name: `user.orders.cancel`)

#### Quản lý địa chỉ

-   Danh sách địa chỉ
-   **Route:** `GET /user/addresses` (name: `user.addresses.index`)
-   Thêm địa chỉ mới
-   **Route:** `POST /user/addresses` (name: `user.addresses.store`)
-   Cập nhật địa chỉ
-   **Route:** `PUT /user/addresses/{id}` (name: `user.addresses.update`)
-   Xóa địa chỉ
-   **Route:** `DELETE /user/addresses/{id}` (name: `user.addresses.destroy`)

#### Danh sách yêu thích (Wishlist)

-   Xem sản phẩm yêu thích
-   **Route:** `GET /user/wishlist` (name: `user.wishlist.index`)
-   Thêm/Bỏ yêu thích (toggle)
-   **Route:** `POST /user/wishlist/toggle` (name: `user.wishlist.toggle`)
-   Thêm vào danh sách yêu thích
-   **Route:** `POST /user/wishlist/add/{productId}` (name: `user.wishlist.add`)
-   Xóa khỏi danh sách yêu thích
-   **Route:** `DELETE /user/wishlist/remove/{productId}` (name: `user.wishlist.remove`)

#### Thông báo (Notifications)

-   Xem danh sách thông báo
-   **Route:** `GET /user/notifications` (name: `user.notifications.index`)
-   Đánh dấu đã đọc
-   **Route:** `POST /user/notifications/{notification}/read` (name: `user.notifications.read`)
-   Đánh dấu tất cả đã đọc
-   **Route:** `POST /user/notifications/read-all` (name: `user.notifications.readAll`)

#### Đổi mật khẩu

-   Form đổi mật khẩu
-   **Route:** `GET /user/change-password` (name: `user.change-password`)
-   Xử lý đổi mật khẩu
-   **Route:** `POST /user/change-password` (name: `user.change-password.update`)

### 7. **Bài viết (Articles/Blog)**

#### Danh sách bài viết

-   Xem tất cả bài viết
-   **Route:** `GET /bai-viet` (name: `articles.index`)

#### Chi tiết bài viết

-   Đọc nội dung bài viết đầy đủ
-   **Route:** `GET /bai-viet/{slug}` (name: `articles.show`)

### 8. **Liên hệ (Contact)**

#### Xem trang liên hệ

-   **Route:** `GET /lien-he` (name: `user.contact.show`)

#### Gửi liên hệ

-   **Route:** `POST /lien-he` (name: `user.contact.submit`)

### 9. **Trang lỗi**

#### Không có quyền truy cập

-   **Route:** `GET /unauthorized` (name: `unauthorized`)

---

## 🔧 IV. CHI TIẾT CHỨC NĂNG QUẢN TRỊ (ADMIN)

_Tất cả route admin yêu cầu middleware 'admin'_

### 1. **Dashboard (Bảng điều khiển)**

-   Thống kê tổng quan:
    -   Tổng đơn hàng, doanh thu, sản phẩm, người dùng
    -   % tăng trưởng theo tháng
    -   Biểu đồ doanh thu 7 ngày gần nhất
    -   Top 5 sản phẩm bán chạy
    -   Thống kê trạng thái đơn hàng
    -   Cảnh báo: Sản phẩm sắp hết hàng, đơn chờ xác nhận, người dùng mới
-   **Route:** `GET /admin/dashboard` (name: `admin.dashboard`)

### 2. **Quản lý Sản phẩm (Products Management)**

-   Danh sách sản phẩm: `GET /admin/products`
-   Thêm sản phẩm: `POST /admin/products`
-   Thêm nhiều sản phẩm (bulk): `POST /admin/products/bulk`
-   Xem chi tiết: `GET /admin/products/{id}`
-   Cập nhật sản phẩm: `PUT /admin/products/{id}`
-   Xóa sản phẩm: `DELETE /admin/products/{id}`
-   **Prefix:** `/admin/products` (name: `admin.products.*`)

### 3. **Quản lý Người dùng (Users Management)**

-   Danh sách người dùng: `GET /admin/users`
-   Tạo người dùng mới: `GET /admin/users/create`, `POST /admin/users`
-   Xem chi tiết: `GET /admin/users/{id}`
-   Chỉnh sửa: `GET /admin/users/{id}/edit`, `PUT /admin/users/{id}`
-   Bật/Tắt trạng thái: `PATCH /admin/users/{id}/toggle-status`
-   Xóa người dùng: `DELETE /admin/users/{id}`
-   **Prefix:** `/admin/users` (name: `admin.users.*`)

### 4. **Quản lý Loại sản phẩm (Categories Management)**

-   Danh sách loại sản phẩm: `GET /admin/categories`
-   Thêm loại: `POST /admin/categories`
-   Thêm nhiều loại (bulk): `POST /admin/categories/bulk`
-   Xem chi tiết: `GET /admin/categories/{id}`
-   Cập nhật: `PUT /admin/categories/{id}`
-   Xóa: `DELETE /admin/categories/{id}`
-   **Prefix:** `/admin/categories` (name: `admin.categories.*`)

### 5. **Quản lý Danh mục (Catalog Management)**

-   Danh sách danh mục: `GET /admin/catalog`
-   Thêm danh mục: `POST /admin/catalog`
-   Thêm nhiều danh mục (bulk): `POST /admin/catalog/bulk`
-   Xem chi tiết: `GET /admin/catalog/{id}`
-   Cập nhật: `PUT /admin/catalog/{id}`
-   Xóa: `DELETE /admin/catalog/{id}`
-   **Prefix:** `/admin/catalog` (name: `admin.catalog.*`)

### 6. **Quản lý Banner (Banners Management)**

-   Danh sách banner: `GET /admin/banners`
-   Thêm banner: `POST /admin/banners`
-   Xem chi tiết: `GET /admin/banners/{id}`
-   Cập nhật: `PUT /admin/banners/{id}`
-   Xóa: `DELETE /admin/banners/{id}`
-   Bật/Tắt trạng thái: `PATCH /admin/banners/{id}/toggle-status`
-   **Prefix:** `/admin/banners` (name: `admin.banners.*`)

### 7. **Quản lý Đơn hàng (Orders Management)**

-   Danh sách đơn hàng: `GET /admin/orders`
-   Thêm đơn hàng: `POST /admin/orders`
-   Thêm nhiều đơn (bulk): `POST /admin/orders/bulk`
-   Xem chi tiết: `GET /admin/orders/{id}`
-   Duyệt đơn hàng: `POST /admin/orders/{id}/approve`
-   Hủy đơn hàng: `POST /admin/orders/{id}/cancel`
-   Cập nhật: `PUT /admin/orders/{id}`
-   Xóa: `DELETE /admin/orders/{id}`
-   **Prefix:** `/admin/orders` (name: `admin.orders.*`)

### 8. **Quản lý Nhà cung cấp (Suppliers Management)**

-   Danh sách nhà cung cấp: `GET /admin/suppliers`
-   Thêm nhà cung cấp: `POST /admin/suppliers`
-   Thêm nhiều (bulk): `POST /admin/suppliers/bulk`
-   Xem chi tiết: `GET /admin/suppliers/{id}`
-   Cập nhật: `PUT /admin/suppliers/{id}`
-   Xóa: `DELETE /admin/suppliers/{id}`
-   **Prefix:** `/admin/suppliers` (name: `admin.suppliers.*`)

### 9. **Quản lý Khuyến mãi (Promotions Management)**

-   Danh sách khuyến mãi: `GET /admin/promotions`
-   Thêm khuyến mãi: `POST /admin/promotions`
-   Xem chi tiết: `GET /admin/promotions/{id}`
-   Cập nhật: `PUT /admin/promotions/{id}`
-   Xóa: `DELETE /admin/promotions/{id}`
-   **Prefix:** `/admin/promotions` (name: `admin.promotions.*`)

### 10. **Quản lý Sản phẩm - Khuyến mãi (Product Promotions)**

-   Danh sách liên kết SP-KM: `GET /admin/product-promotions`
-   Thêm liên kết: `POST /admin/product-promotions`
-   Thêm nhiều (bulk): `POST /admin/product-promotions/bulk`
-   Xem chi tiết: `GET /admin/product-promotions/{product}/{promotion}`
-   Cập nhật: `PUT /admin/product-promotions/{product}/{promotion}`
-   Xóa: `DELETE /admin/product-promotions/{product}/{promotion}`
-   **Prefix:** `/admin/product-promotions` (name: `admin.product-promotions.*`)

### 11. **Quản lý Voucher (Vouchers Management)**

-   Danh sách voucher: `GET /admin/vouchers`
-   Thêm voucher: `POST /admin/vouchers`
-   Thêm nhiều (bulk): `POST /admin/vouchers/bulk`
-   Xem chi tiết: `GET /admin/vouchers/{id}`
-   Cập nhật: `PUT /admin/vouchers/{id}`
-   Xóa: `DELETE /admin/vouchers/{id}`
-   **Prefix:** `/admin/vouchers` (name: `admin.vouchers.*`)

### 12. **Quản lý Thông báo (Notifications Management)**

-   Danh sách thông báo: `GET /admin/notifications`
-   Tạo thông báo: `POST /admin/notifications`
-   Xem chi tiết: `GET /admin/notifications/{id}`
-   Cập nhật: `PUT /admin/notifications/{id}`
-   Xóa: `DELETE /admin/notifications/{id}`
-   **Prefix:** `/admin/notifications` (name: `admin.notifications.*`)

### 13. **Quản lý Nhật ký (Activity Logs Management)**

-   Danh sách nhật ký: `GET /admin/logs`
-   Thêm nhật ký: `POST /admin/logs`
-   Xem chi tiết: `GET /admin/logs/{id}`
-   Cập nhật: `PUT /admin/logs/{id}`
-   Xóa: `DELETE /admin/logs/{id}`
-   **Prefix:** `/admin/logs` (name: `admin.logs.*`)

### 14. **Quản lý Vai trò (Roles Management)**

-   Danh sách vai trò: `GET /admin/roles`
-   Thêm vai trò: `POST /admin/roles`
-   Xem chi tiết: `GET /admin/roles/{id}`
-   Cập nhật: `PUT /admin/roles/{id}`
-   Xóa: `DELETE /admin/roles/{id}`
-   **Prefix:** `/admin/roles` (name: `admin.roles.*`)

### 15. **Quản lý Bài viết (Articles Management)**

-   Danh sách bài viết: `GET /admin/articles`
-   Trang tạo bài viết: `GET /admin/articles/create`
-   Thêm bài viết: `POST /admin/articles`
-   Trang chỉnh sửa: `GET /admin/articles/{id}/edit`
-   Cập nhật bài viết: `PUT /admin/articles/{id}`
-   Xóa bài viết: `DELETE /admin/articles/{id}`
-   **Prefix:** `/admin/articles` (name: `admin.articles.*`)

### 16. **Hệ thống Chat hỗ trợ khách hàng (Admin Chat Support)**

-   Trang quản lý chat: `GET /admin/chat`
-   Lấy danh sách cuộc hội thoại: `GET /admin/chat/conversations`
-   Lấy thống kê: `GET /admin/chat/conversations/stats` hoặc `GET /admin/chat/stats`
-   Lấy tin nhắn của cuộc hội thoại: `GET /admin/chat/conversations/{id}/messages` hoặc `GET /admin/chat/messages/{id}`
-   Phân công cuộc hội thoại: `POST /admin/chat/conversations/{id}/assign` hoặc `POST /admin/chat/assign/{id}`
-   Đóng cuộc hội thoại: `POST /admin/chat/conversations/{id}/close` hoặc `POST /admin/chat/close/{id}`
-   Gửi tin nhắn: `POST /admin/chat/messages/send` hoặc `POST /admin/chat/send`
-   **Prefix:** `/admin/chat` (name: `admin.chat.*`)

### 17. **Tiện ích Admin (Admin Utilities)**

-   Tìm kiếm: `GET /admin/search`
-   Tin nhắn: `GET /admin/messages` (redirect đến chat)
-   Hồ sơ Admin: `GET /admin/profile`
-   Cài đặt: `GET /admin/settings`

---

## 🔌 V. API (Application Programming Interface)

_Prefix: `/api/v1`_

### 1. **API Xác thực (Auth API)**

#### Công khai (Public - với middleware 'web')

-   Đăng ký: `POST /api/v1/auth/register`
-   Đăng nhập: `POST /api/v1/auth/login`
-   Quên mật khẩu: `POST /api/v1/auth/forgot-password`
-   Đặt lại mật khẩu: `POST /api/v1/auth/reset-password`
-   Đăng xuất: `POST /api/v1/auth/logout`

#### Bảo mật (Protected - yêu cầu JWT)

-   Làm mới token: `POST /api/v1/auth/refresh`
-   Lấy thông tin người dùng: `GET /api/v1/auth/me`
-   Cập nhật hồ sơ: `PUT /api/v1/auth/profile`
-   Đổi mật khẩu: `PUT /api/v1/auth/change-password`

### 2. **API Chatbot**

-   Truy vấn chatbot (không cần CSRF): `POST /api/v1/chatbot/query`
-   **Route name:** `api.v1.chatbot.query`

### 3. **API Chat với Admin**

_(Yêu cầu middleware 'web' - session based)_

-   Lấy hoặc tạo cuộc hội thoại: `POST /api/v1/chat/conversation`
-   Lấy tin nhắn: `GET /api/v1/chat/messages/{conversationId}`
-   Gửi tin nhắn: `POST /api/v1/chat/send`
-   Đóng cuộc hội thoại: `POST /api/v1/chat/close/{conversationId}`
-   **Prefix:** `/api/v1/chat` (name: `api.v1.chat.*`)

---

## 📊 VI. CƠ SỞ DỮ LIỆU (DATABASE MODELS)

Cơ sở dữ liệu được xây dựng để phục vụ cho trang web bán hàng nông sản với thương hiệu "Bách Hóa Xanh". Mục tiêu là quản lý đầy đủ các thông tin liên quan đến người dùng, sản phẩm, đơn hàng, kho hàng, đánh giá, mã giảm giá (voucher), và thanh toán.

### Danh sách các Model chính:

#### 6.1. Bảng NguoiDung (Người dùng)

Chứa thông tin người dùng hệ thống (bao gồm cả khách hàng và quản trị viên).

-   **ID**: Khóa chính.
-   **TenNguoiDung**, **Email**, **SDT**, **MatKhau**: Thông tin đăng nhập và liên hệ.
-   **DiaChi**, **NgaySinh**, **GioiTinh**, **HinhAnh**: Thông tin cá nhân.
-   **IDVaiTro**: Khóa ngoại liên kết bảng VaiTro (Phân quyền).
-   **TrangThai**: Trạng thái tài khoản (Kích hoạt/Khóa).

#### 6.2. Bảng SanPham (Sản phẩm)

Lưu trữ thông tin chi tiết về sản phẩm nông sản.

-   **ID**: Khóa chính.
-   **TenSanPham**: Tên sản phẩm.
-   **Gia**: Giá bán hiện tại.
-   **MoTa**: Mô tả chi tiết sản phẩm.
-   **SoLuongTon**: Số lượng còn trong kho.
-   **DonViTinh**: Đơn vị tính (kg, gói, hộp...).
-   **HinhAnh**: Đường dẫn ảnh sản phẩm.
-   **XuatXu**: Nguồn gốc xuất xứ.
-   **HanSuDung**: Hạn sử dụng.
-   **IDLoaiSP**: Khóa ngoại liên kết bảng LoaiSanPham.
-   **IDNhaCungCap**: Khóa ngoại liên kết bảng NhaCungCap.

#### 6.3. Bảng DonHang (Đơn hàng)

Quản lý thông tin đơn đặt hàng.

-   **ID**: Khóa chính.
-   **MaDonHang**: Mã đơn hàng (unique).
-   **IDNguoiDung**: Khóa ngoại liên kết người đặt.
-   **TenNguoiNhan**, **SDT**, **DiaChi**: Thông tin giao hàng.
-   **PhuongThucTT**: Phương thức thanh toán (COD, Online).
-   **TongThanhToan**: Tổng tiền phải trả.
-   **TrangThai**: Trạng thái đơn hàng (Chờ xác nhận, Đang giao, Đã giao...).
-   **IDVoucher**: Khóa ngoại mã giảm giá (nếu có).

#### 6.4. Bảng ChiTietDonHang (Chi tiết đơn hàng)

Lưu danh sách sản phẩm trong từng đơn hàng.

-   **ID**: Khóa chính.
-   **IDDonHang**: Khóa ngoại liên kết đơn hàng.
-   **IDSanPham**: Khóa ngoại liên kết sản phẩm.
-   **SoLuong**: Số lượng mua.
-   **DonGia**: Giá bán tại thời điểm đặt hàng.
-   **ThanhTien**: Thành tiền (Số lượng \* Đơn giá).

#### 6.5. Bảng DanhMuc (Danh mục)

Quản lý nhóm danh mục chính (Ví dụ: Rau củ, Trái cây, Thịt cá).

-   **ID**: Khóa chính.
-   **TenDanhMuc**: Tên danh mục.
-   **HinhAnh**: Ảnh đại diện danh mục.
-   **ThuTu**: Thứ tự hiển thị.

#### 6.6. Bảng LoaiSanPham (Loại sản phẩm)

Phân loại chi tiết hơn trong danh mục (Ví dụ: Rau ăn lá, Củ quả...).

-   **ID**: Khóa chính.
-   **TenLoai**: Tên loại sản phẩm.
-   **IDDanhMuc**: Khóa ngoại liên kết bảng DanhMuc.

#### 6.7. Bảng GioHang (Giỏ hàng)

Lưu sản phẩm khách hàng thêm vào giỏ (tạm thời).

-   **IDNguoiDung**, **IDSanPham**: Khóa chính kết hợp.
-   **SoLuong**: Số lượng sản phẩm.
-   **NgayCapNhat**: Thời gian cập nhật gần nhất.

#### 6.8. Bảng DanhGia (Đánh giá)

Lưu đánh giá và bình luận của khách hàng về sản phẩm.

-   **ID**: Khóa chính.
-   **IDSanPham**: Khóa ngoại sản phẩm được đánh giá.
-   **IDNguoiDung**: Khóa ngoại người đánh giá.
-   **SoSao**: Số sao đánh giá (1-5).
-   **NoiDung**: Nội dung bình luận.
-   **TrangThai**: Trạng thái duyệt.

#### 6.9. Bảng NhaCungCap (Nhà cung cấp)

Quản lý thông tin nhà cung cấp nông sản.

-   **ID**: Khóa chính.
-   **TenNhaCungCap**: Tên đơn vị cung cấp.
-   **SDT**, **Email**, **DiaChi**: Thông tin liên hệ.

#### 6.10. Bảng KhuyenMai (Khuyến mãi)

Quản lý các chương trình giảm giá.

-   **ID**: Khóa chính.
-   **TenKhuyenMai**: Tên chương trình.
-   **MoTa**: Mô tả.
-   **NgayBatDau**, **NgayKetThuc**: Thời gian áp dụng.
-   **TrangThai**: Trạng thái hoạt động.

#### 6.11. Bảng Voucher (Mã giảm giá)

Quản lý mã giảm giá cho đơn hàng.

-   **ID**: Khóa chính.
-   **MaVoucher**: Mã code nhập vào.
-   **GiamGia**: Số tiền hoặc phần trăm giảm.
-   **DonToiThieu**: Giá trị đơn hàng tối thiểu để áp dụng.
-   **SoLuong**: Số lượng mã phát hành.

#### 6.12. Bảng SanPhamKhuyenMai (Sản phẩm khuyến mãi)

Bảng trung gian liên kết sản phẩm và chương trình khuyến mãi (Quan hệ nhiều-nhiều).

-   **IDSanPham**, **IDKhuyenMai**: Khóa chính kết hợp.
-   **GhiChu**: Ghi chú thêm.
-   **NgayTao**: Ngày thêm vào chương trình.

#### 6.13. Bảng HinhAnhSanPham (Hình ảnh sản phẩm)

Lưu trữ nhiều hình ảnh cho một sản phẩm.

-   **ID**: Khóa chính.
-   **IDSanPham**: Khóa ngoại liên kết sản phẩm.
-   **DuongDan**: Đường dẫn file ảnh.
-   **LaChinh**: Đánh dấu là ảnh đại diện chính (Boolean).

#### 6.14. Bảng BaiViet (Bài viết)

Quản lý các bài viết tin tức, blog, hướng dẫn.

-   **ID**: Khóa chính.
-   **TieuDe**: Tiêu đề bài viết.
-   **Slug**: Đường dẫn thân thiện SEO.
-   **NoiDung**: Nội dung chi tiết (HTML).
-   **MoTaNgan**: Tóm tắt nội dung.
-   **HinhAnh**: Ảnh đại diện bài viết.
-   **IDNguoiDung**: Người viết bài.
-   **IDDanhMuc**: Danh mục bài viết.
-   **TrangThai**: Ẩn/Hiện.
-   **LuotXem**: Số lượt xem.

#### 6.15. Bảng Banner (Banner quảng cáo)

Quản lý các banner hiển thị trên trang chủ và các trang con.

-   **ID**: Khóa chính.
-   **TieuDe**: Tiêu đề banner.
-   **HinhAnh**: Đường dẫn ảnh.
-   **LienKet**: Link khi click vào banner.
-   **ViTri**: Vị trí hiển thị (Trang chủ, Sidebar...).
-   **ThuTu**: Thứ tự sắp xếp.
-   **TrangThai**: Ẩn/Hiện.

#### 6.16. Bảng LienHe (Liên hệ)

Lưu thông tin liên hệ từ khách hàng gửi qua form.

-   **ID**: Khóa chính.
-   **HoTen**, **Email**, **SDT**: Thông tin người gửi.
-   **TieuDe**: Tiêu đề liên hệ.
-   **NoiDung**: Nội dung tin nhắn.
-   **TrangThai**: Trạng thái xử lý (Mới, Đang xử lý, Hoàn thành).

#### 6.17. Bảng ThongBao (Thông báo)

Hệ thống thông báo cho người dùng.

-   **ID**: Khóa chính.
-   **IDNguoiDung**: Người nhận thông báo.
-   **TieuDe**: Tiêu đề thông báo.
-   **NoiDung**: Nội dung chi tiết.
-   **Loai**: Loại thông báo (Đơn hàng, Khuyến mãi...).
-   **DaXem**: Trạng thái đã đọc.
-   **LinkLienKet**: Link đích khi click vào.

#### 6.18. Bảng NhatKy (Nhật ký hoạt động)

Ghi lại lịch sử hoạt động của người dùng và hệ thống (Audit Log).

-   **ID**: Khóa chính.
-   **IDNguoiDung**: Người thực hiện.
-   **HanhDong**: Tên hành động (Login, Update, Delete...).
-   **Loai**: Loại đối tượng tác động.
-   **DuLieuCu**, **DuLieuMoi**: Dữ liệu trước và sau khi thay đổi.
-   **DiaChiIP**, **TrinhDuyet**: Thông tin thiết bị.
-   **KetQua**: Kết quả thực hiện.

#### 6.19. Bảng VaiTro (Vai trò)

Định nghĩa các vai trò trong hệ thống (RBAC).

-   **ID**: Khóa chính.
-   **TenVaiTro**: Tên vai trò (Admin, User, Manager...).
-   **MoTa**: Mô tả quyền hạn.

#### 6.20. Bảng ThanhToan (Thanh toán)

Lưu lịch sử giao dịch thanh toán của đơn hàng.

-   **ID**: Khóa chính.
-   **IDDonHang**: Khóa ngoại liên kết đơn hàng.
-   **SoTien**: Số tiền thanh toán.
-   **PhuongThuc**: Phương thức (Momo, VNPAY, COD...).
-   **TrangThai**: Trạng thái giao dịch.
-   **NgayThanhToan**: Thời gian giao dịch.

#### 6.21. Bảng CuocHoiThoai (Cuộc hội thoại)

Quản lý các phiên chat giữa khách hàng và admin/chatbot.

-   **ID**: Khóa chính.
-   **IDNguoiDung**: Khách hàng.
-   **IDAdmin**: Nhân viên hỗ trợ (nếu có).
-   **SessionID**: ID phiên (cho khách vãng lai).
-   **TieuDe**: Tiêu đề cuộc hội thoại.
-   **TrangThai**: Trạng thái (Mở, Đóng).

#### 6.22. Bảng TinNhan (Tin nhắn)

Chi tiết nội dung tin nhắn trong cuộc hội thoại.

-   **ID**: Khóa chính.
-   **IDCuocHoiThoai**: Khóa ngoại liên kết cuộc hội thoại.
-   **IDNguoiGui**: Người gửi tin.
-   **LoaiNguoiGui**: Loại người gửi (User, Admin, Bot).
-   **NoiDung**: Nội dung tin nhắn.
-   **HinhAnh**: Ảnh đính kèm (nếu có).
-   **DaXem**: Trạng thái đã xem.

#### 6.23. Bảng HoatDongNguoiDung (Hoạt động người dùng)

Lưu trữ hành vi người dùng để phân tích hoặc gợi ý.

-   **ID**: Khóa chính.
-   **IDNguoiDung**: Người dùng.
-   **Loai**: Loại hoạt động (Tìm kiếm, Xem sản phẩm...).
-   **TuKhoa**: Từ khóa tìm kiếm.
-   **IDSanPham**: Sản phẩm tương tác.

#### 6.24. Bảng Token

Quản lý các token xác thực và bảo mật.

-   **ID**: Khóa chính.
-   **IDNguoiDung**: Người sở hữu token.
-   **Token**: Chuỗi token.
-   **Loai**: Loại token (Reset Password, Verify Email...).
-   **HetHan**: Thời gian hết hạn.

---

## 🛠️ VII. CÔNG NGHỆ SỬ DỤNG

### Backend

-   **Framework:** Laravel 12.x
-   **PHP Version:** 8.2+
-   **Authentication:** JWT (JSON Web Token) + Session based
-   **Database:** MySQL/MariaDB
-   **ORM:** Eloquent

### Frontend

-   **Template Engine:** Blade
-   **CSS Framework:** Bootstrap 5
-   **JavaScript:** Vanilla JS / jQuery
-   **Icons:** Font Awesome, Bootstrap Icons

### Tính năng bổ sung

-   **File Upload:** Quản lý hình ảnh sản phẩm, avatar
-   **Email:** Gửi email xác thực, reset password
-   **Logging:** Ghi nhận hoạt động hệ thống
-   **Middleware:** Phân quyền admin/user, xác thực JWT
-   **Helper Functions:** Format giá, tính khuyến mãi, xử lý hình ảnh
-   **Chatbot:** Hỗ trợ tự động
-   **Real-time Chat:** Chat với admin

---

## 📂 VIII. CẤU TRÚC SOURCE CODE

Cấu trúc dự án sử dụng theo mô hình MVC (Model-View-Controller) điển hình của Laravel:

-   **Models** (`app/Models`): Chứa các lớp tương tác với cơ sở dữ liệu (Eloquent ORM).
-   **Views** (`resources/views`): Chứa giao diện người dùng (Blade templates).
-   **Controllers** (`app/Http/Controllers`): Xử lý logic nghiệp vụ và điều hướng.

Bên cạnh đó là các thành phần quan trọng khác:

-   **Core/Helpers** (`app/helpers.php`): Chứa các hàm hỗ trợ toàn cục (format tiền tệ, xử lý ảnh...).
-   **Middlewares** (`app/Http/Midsdleware`): Các lớp trung gian để xác thực và lọc request (Auth, Admin, JWT...).
-   **Config** (`config/`): Chứa các file cấu hình hệ thống (database, mail, services...).
-   **Database** (`database/`): Chứa Migrations (cấu trúc bảng) và Seeders (dữ liệu mẫu).
-   **Public** (`public/`): Chứa tài nguyên công khai như CSS, JS, Images, Fonts.
-   **Routes** (`routes/`): Định nghĩa các đường dẫn URL của ứng dụng.
-   **Vendor** (`vendor/`): Chứa các thư viện phụ thuộc được cài đặt qua Composer.
-   **.env**: Cấu hình môi trường (database credentials, app key...).

---

## 📁 IX. CẤU TRÚC ROUTES

```
├── web.php           - Routes người dùng chính
├── auth.php          - Routes xác thực (login, register, forgot password)
├── product.php       - Routes sản phẩm (danh sách, chi tiết, đánh giá)
├── cart.php          - Routes giỏ hàng & thanh toán
├── user.php          - Routes quản lý tài khoản người dùng
├── admin.php         - Routes quản trị viên (dashboard, CRUD tất cả)
└── api.php           - API routes (JWT auth, chatbot, chat)
```

---

## 🎯 X. MIDDLEWARE & BẢO MẬT

### Middleware có trong dự án:

1. **auth** - Yêu cầu đăng nhập
2. **guest** - Chỉ khách (chưa đăng nhập)
3. **admin** - Chỉ admin
4. **user** - Chỉ user thông thường
5. **jwt.auth** - Xác thực JWT token
6. **web** - Session based (cho web)

---

## 🛡️ XII. CHI TIẾT KỸ THUẬT BACKEND (YÊU CẦU PHI CHỨC NĂNG & BẢO MẬT)

### 1. Yêu cầu phi chức năng

#### 1.1. Phương pháp định tuyến (Routing)

**Mục đích:** Đưa người dùng đến đúng Controller và Action, tạo đường dẫn thân thiện (Clean URL).

Nếu không dùng định tuyến, URL sẽ lộ cấu trúc thư mục (ví dụ: `domain.com/controllers/ProductController.php?action=index`), gây mất thẩm mỹ và không tốt cho SEO.

Trong Laravel, các route được định nghĩa trong `routes/web.php` (cho web) và `routes/api.php` (cho API).
Ví dụ định nghĩa route:

```php
// Route hiển thị chi tiết sản phẩm
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('product.show');
```

Khi người dùng truy cập `/san-pham/ca-chua-sach`, Laravel sẽ tự động điều hướng đến phương thức `show` của `ProductController` và truyền tham số `slug = 'ca-chua-sach'`.

#### 1.2. Kỹ thuật AJAX

**Mục đích:** Tăng hiệu suất và trải nghiệm người dùng bằng cách tải dữ liệu nền mà không cần tải lại toàn bộ trang.

Ở một vài chức năng, cần lấy dữ liệu liên tục khi có sự tương tác của người dùng.
Ví dụ: Chức năng tìm kiếm, mỗi khi người dùng gõ thì kết quả sẽ hiện ra ngay không cần phải nhấn Enter hay reload trang.

Do tính chất của Form truyền thống, khi submit trang web sẽ bị reload. Vì vậy, khi dùng AJAX (thông qua `fetch` hoặc `axios`), ta có thể gửi dữ liệu đến server và nhận phản hồi JSON để cập nhật DOM.

Ví dụ code JS gọi AJAX tìm kiếm:

```javascript
axios.get("/api/search?keyword=" + keyword).then((response) => {
    // Cập nhật danh sách sản phẩm mà không reload trang
    renderProducts(response.data);
});
```

#### 1.3. JWT (JSON Web Token)

**Mục đích:** Xác thực người dùng cho các API, đảm bảo tính bảo mật và không trạng thái (stateless).

Dự án sử dụng thư viện `tymon/jwt-auth`.

**Quy trình hoạt động:**

1.  **Đăng nhập:** Client gửi username/password lên server.
2.  **Tạo Token:** Server kiểm tra, nếu đúng sẽ tạo ra chuỗi JWT (bao gồm Header, Payload, Signature).
3.  **Trả về:** Server trả token về cho Client.
4.  **Lưu trữ:** Client lưu token (thường vào LocalStorage hoặc Cookie).
5.  **Sử dụng:** Ở các request sau, Client gửi kèm token trong Header `Authorization: Bearer <token>`.
6.  **Xác thực:** Middleware `jwt.auth` trên server sẽ kiểm tra tính hợp lệ của token trước khi cho phép truy cập resource.

#### 1.4. Quản lý hình ảnh (Laravel Storage)

**Mục đích:** Giảm dung lượng dự án, tăng hiệu suất, quản lý file tập trung.

Thay vì lưu trực tiếp vào thư mục code, Laravel sử dụng `Storage` facade để quản lý file.

-   Sử dụng `php artisan storage:link` để tạo liên kết từ `public/storage` sang `storage/app/public`.
-   Tên file được hash (mã hóa) để tránh trùng lặp: `md5(time() . $originalName) . '.' . $extension`.

Ví dụ code lưu ảnh:

```php
if ($request->hasFile('hinh_anh')) {
    $path = $request->file('hinh_anh')->store('uploads/products', 'public');
    $product->image = $path;
}
```

#### 1.5. Gửi Mail xác thực

**Mục đích:** Tăng cường bảo mật, định danh người dùng hợp lệ.

Để đăng ký tài khoản, người dùng phải nhập email. Server sẽ gửi một email chứa đường link xác thực.
Sử dụng `Illuminate\Support\Facades\Mail` và `Mailable` class của Laravel.

Quy trình:

1.  Người dùng đăng ký -> Tạo tài khoản với trạng thái "Chưa kích hoạt".
2.  Hệ thống tạo token xác thực và gửi email chứa link: `domain.com/verify-email?token=xyz`.
3.  Người dùng click link -> Server kiểm tra token -> Kích hoạt tài khoản.

### 2. Bảo mật

#### 2.1. Token & Cookie

Token được lưu vào Cookie cần được cấu hình bảo mật để tránh bị đánh cắp (XSS).
Cấu hình Cookie trong Laravel (`config/session.php`):

-   **HttpOnly**: `true` (JavaScript không thể đọc được cookie, chống XSS đọc trộm session).
-   **Secure**: `true` (Chỉ gửi qua HTTPS).
-   **SameSite**: `lax` hoặc `strict` (Chống CSRF).

#### 2.2. Chống SQL Injection

**Vấn đề:** Kẻ tấn công nhập `admin' OR '1'='1` vào ô đăng nhập để vượt qua xác thực.
`SELECT * FROM users WHERE user = 'admin' OR '1'='1'` (Luôn đúng).

**Giải pháp:** Laravel sử dụng **PDO Parameter Binding** (Prepare Statement).
Câu truy vấn được tách biệt với dữ liệu:

```php
// Code an toàn trong Laravel
$user = User::where('email', $request->email)->first();
// Tương đương SQL: select * from users where email = ?
```

Dữ liệu nhập vào sẽ được coi là chuỗi văn bản thuần túy, không phải mã lệnh SQL thực thi.

#### 2.3. Chống XSS (Cross-Site Scripting)

**Vấn đề:** Hacker chèn đoạn script `<script>alert(document.cookie)</script>` vào bình luận. Khi người khác xem bình luận, script sẽ chạy và lấy cắp cookie.

**Giải pháp:** Template Engine **Blade** tự động Escape dữ liệu.

-   Sử dụng `{{ $comment }}`: Laravel sẽ chuyển đổi ký tự đặc biệt thành HTML Entities.
    -   `<` thành `&lt;`
    -   `>` thành `&gt;`
-   Kết quả hiển thị trên trình duyệt: `&lt;script&gt;alert(...)&lt;/script&gt;` (Chỉ là văn bản, không chạy được).

#### 2.4. Chống CSRF (Cross-Site Request Forgery)

**Vấn đề:** Hacker lừa người dùng click vào link lạ, link này âm thầm gửi request (ví dụ: đổi mật khẩu) đến server mà người dùng không biết.

**Giải pháp:** Sử dụng CSRF Token.

-   Mỗi phiên làm việc (session) có một token bí mật duy nhất.
-   Mọi form POST đều phải gửi kèm token này (`@csrf`).
-   Middleware `VerifyCsrfToken` sẽ kiểm tra: Nếu request không có token hoặc token không khớp với session -> Từ chối (Lỗi 419).

Ví dụ trong Blade View:

```html
<form method="POST" action="/profile">
    @csrf
    <!-- Tự động sinh ra input hidden chứa token -->
    ...
</form>
```

---

## 📝 XIII. TÀI LIỆU THAM KHẢO

Xem thêm trong thư mục `/docs`:

-   `AUTHENTICATION_SYSTEM.md` - Hệ thống xác thực
-   `DATABASE_README.md` - Cơ sở dữ liệu
-   `JWT_AUTH_GUIDE.md` - Hướng dẫn JWT
-   `MODELS_README.md` - Mô tả các Model
-   `ROUTES_GUIDE.md` - Hướng dẫn Routes
-   `USER_MANAGEMENT_GUIDE.md` - Quản lý người dùng
-   `VIEWS_README.md` - Giao diện Views
-   Và nhiều tài liệu khác...

---

## ✅ TỔNG KẾT

**Tổng số chức năng:** 100+ features
**Tổng số routes:** 150+ routes
**Tổng số models:** 25 models
**Tổng số controllers:** 20+ controllers

Dự án được xây dựng hoàn chỉnh với đầy đủ chức năng của một website thương mại điện tử bán nông sản, bao gồm:

-   ✅ Quản lý sản phẩm & danh mục
-   ✅ Giỏ hàng & thanh toán
-   ✅ Khuyến mãi & voucher
-   ✅ Đánh giá & bài viết
-   ✅ Chat hỗ trợ & chatbot
-   ✅ Quản trị toàn diện
-   ✅ API đầy đủ
-   ✅ Bảo mật JWT + Session

---

_Tài liệu được tạo tự động ngày: 06/12/2025_
