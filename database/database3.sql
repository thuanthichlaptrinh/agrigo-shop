-- DATABASE: AGRICULTURAL SALES MANAGEMENT (Laravel Standardized - Vietnamese ENUM)
CREATE DATABASE IF NOT EXISTS agricultural_sales
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE agricultural_sales;

-- 1. ROLES (Vai trò)
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL, -- quản trị viên, khách hàng, nhân viên
    description VARCHAR(255),
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. USERS (Người dùng)
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(15),
    password VARCHAR(255) NOT NULL,
    address VARCHAR(500),
    birthday DATE,
    gender ENUM('nam', 'nữ', 'khác'),
    avatar VARCHAR(1000),
    status BOOLEAN DEFAULT TRUE, -- Hoạt động/Không hoạt động
    role_id BIGINT UNSIGNED,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. USER TOKENS (Xác thực: reset password, verify email)
CREATE TABLE user_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    token VARCHAR(500) NOT NULL,
    type ENUM('đặt_lại_mật_khẩu', 'xác_thực_email', 'làm_mới_token') NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tokens_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. CATEGORIES (Danh mục)
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(500),
    image VARCHAR(1000),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. SUB_CATEGORIES (Loại sản phẩm)
CREATE TABLE sub_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(500),
    category_id BIGINT UNSIGNED,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_sub_categories_category FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. SUPPLIERS (Nhà cung cấp)
CREATE TABLE suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(500),
    phone VARCHAR(15),
    email VARCHAR(255),
    contact_person VARCHAR(255),
    note VARCHAR(1000),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. PRODUCTS (Sản phẩm)
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE, -- Slug cho SEO
    description TEXT,
    detail_description LONGTEXT,
    price DECIMAL(18,2) NOT NULL, -- Giá bán
    original_price DECIMAL(18,2), -- Giá gốc (để so sánh)
    stock_quantity INT DEFAULT 0,
    unit VARCHAR(30) NOT NULL, -- kg, hộp, v.v.
    image VARCHAR(1000), -- Ảnh đại diện chính
    origin VARCHAR(100), -- Xuất xứ
    shelf_life INT, -- Hạn sử dụng (ngày)
    weight VARCHAR(50),
    brand VARCHAR(255),
    view_count INT DEFAULT 0,
    sold_count INT DEFAULT 0,
    rating_average DECIMAL(2,1) DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    sub_category_id BIGINT UNSIGNED,
    supplier_id BIGINT UNSIGNED,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_sub_category FOREIGN KEY (sub_category_id) REFERENCES sub_categories(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_products_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. PRODUCT IMAGES (Ảnh chi tiết sản phẩm)
CREATE TABLE product_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(1000) NOT NULL,
    sort_order INT DEFAULT 0,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. PROMOTIONS (Khuyến mãi)
CREATE TABLE promotions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(1000),
    type ENUM('phần_trăm', 'số_tiền_cố_định') NOT NULL,
    discount_value DECIMAL(18,2) NOT NULL,
    max_discount DECIMAL(18,2), -- Số tiền giảm tối đa nếu là phần trăm
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. PRODUCT_PROMOTIONS (Sản phẩm áp dụng khuyến mãi)
CREATE TABLE product_promotions (
    product_id BIGINT UNSIGNED NOT NULL,
    promotion_id BIGINT UNSIGNED NOT NULL,
    note VARCHAR(500),
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (product_id, promotion_id),
    CONSTRAINT fk_prod_promo_product FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_prod_promo_promotion FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. VOUCHERS (Mã giảm giá)
CREATE TABLE vouchers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    discount_type ENUM('phần_trăm', 'số_tiền_cố_định') NOT NULL,
    discount_value DECIMAL(18,2) NOT NULL,
    max_discount DECIMAL(18,2),
    min_order_value DECIMAL(18,2) DEFAULT 0,
    quantity INT NOT NULL,
    used_quantity INT DEFAULT 0,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. VOUCHER USAGE HISTORY (Lịch sử sử dụng voucher)
CREATE TABLE voucher_usage (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    voucher_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    order_id BIGINT UNSIGNED, -- Sẽ được liên kết sau khi tạo đơn hàng
    discount_amount DECIMAL(18,2),
    used_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_voucher_usage_voucher FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_voucher_usage_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. CART ITEMS (Giỏ hàng)
CREATE TABLE cart_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price_at_add DECIMAL(18,2), -- Giá khi thêm vào giỏ (theo dõi tùy chọn)
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_cart_product FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. ORDERS (Đơn hàng)
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(50) UNIQUE NOT NULL,
    user_id BIGINT UNSIGNED,
    recipient_name VARCHAR(255) NOT NULL,
    recipient_phone VARCHAR(15) NOT NULL,
    shipping_address VARCHAR(500) NOT NULL,
    delivery_time VARCHAR(100),
    payment_method ENUM('tiền_mặt', 'vnpay', 'momo', 'zalopay', 'chuyển_khoản') DEFAULT 'tiền_mặt',
    shipping_method ENUM('giao_hàng_nhanh', 'giao_hàng_tiết_kiệm', 'viettel_post', 'nội_bộ') DEFAULT 'giao_hàng_tiết_kiệm',
    total_amount DECIMAL(18,2) NOT NULL, -- Tổng tiền hàng
    shipping_fee DECIMAL(18,2) DEFAULT 0,
    voucher_discount DECIMAL(18,2) DEFAULT 0,
    voucher_id BIGINT UNSIGNED,
    final_amount DECIMAL(18,2) NOT NULL, -- Tổng thanh toán
    status ENUM('chờ_xử_lý', 'đã_xác_nhận', 'đang_giao', 'đã_giao', 'đã_hủy') DEFAULT 'chờ_xử_lý',
    note VARCHAR(1000),
    order_date TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP NULL,
    shipped_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_orders_voucher FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. ORDER ITEMS (Chi tiết đơn hàng)
CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED,
    product_name VARCHAR(255) NOT NULL, -- Lưu tên sản phẩm tại thời điểm đặt
    quantity INT NOT NULL,
    unit_price DECIMAL(18,2) NOT NULL, -- Lưu giá tại thời điểm đặt
    total_price DECIMAL(18,2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. PAYMENTS (Thanh toán)
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    transaction_id VARCHAR(100),
    payment_method VARCHAR(50) NOT NULL,
    amount DECIMAL(18,2) NOT NULL,
    status ENUM('chờ_thanh_toán', 'đã_thanh_toán', 'thất_bại', 'đã_hoàn_tiền') DEFAULT 'chờ_thanh_toán',
    payment_details TEXT, -- JSON response từ cổng thanh toán
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. REVIEWS (Đánh giá)
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    order_id BIGINT UNSIGNED,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment VARCHAR(2000),
    images VARCHAR(1000), -- Đường dẫn ảnh phân cách bằng dấu phẩy
    status ENUM('chờ_duyệt', 'đã_duyệt', 'đã_ẩn') DEFAULT 'chờ_duyệt',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_product FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_reviews_order FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. REVIEW REPLIES (Phản hồi đánh giá)
CREATE TABLE review_replies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    review_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL, -- Quản trị viên/Nhân viên phản hồi
    content VARCHAR(2000) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_replies_review FOREIGN KEY (review_id) REFERENCES reviews(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_replies_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. USER ACTIVITIES (Hoạt động: Yêu thích, Xem, Tìm kiếm)
CREATE TABLE user_activities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL, -- Cho phép khách
    activity_type ENUM('tìm_kiếm', 'yêu_thích', 'xem_sản_phẩm') NOT NULL,
    keyword VARCHAR(255), -- Cho tìm kiếm
    product_id BIGINT UNSIGNED NULL, -- Cho yêu thích/xem
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_wishlist (user_id, product_id, activity_type), -- Ngăn trùng lặp yêu thích
    CONSTRAINT fk_activities_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_activities_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. BANNERS (Quảng cáo)
CREATE TABLE banners (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(500),
    image VARCHAR(1000) NOT NULL,
    link VARCHAR(500),
    position ENUM('trang_chủ_chính', 'trang_chủ_phụ', 'trang_sản_phẩm', 'thanh_bên') DEFAULT 'trang_chủ_chính',
    sort_order INT DEFAULT 0,
    start_date DATETIME,
    end_date DATETIME,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. NOTIFICATIONS (Thông báo)
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    title VARCHAR(255) NOT NULL,
    content VARCHAR(1000) NOT NULL,
    type ENUM('đơn_hàng', 'khuyến_mãi', 'hệ_thống', 'khác') DEFAULT 'hệ_thống',
    link VARCHAR(500),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. CONTACTS (Liên hệ)
CREATE TABLE contacts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    title VARCHAR(255) NOT NULL,
    content VARCHAR(2000) NOT NULL,
    status ENUM('mới', 'đang_xử_lý', 'đã_xử_lý', 'đã_đóng') DEFAULT 'mới',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_contacts_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- INDEXES FOR OPTIMIZATION (Chỉ mục để tối ưu hóa)
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_products_price ON products(price);
CREATE INDEX idx_products_status ON products(is_active);
CREATE INDEX idx_products_category ON products(sub_category_id);
CREATE INDEX idx_products_featured ON products(is_featured);

CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_date ON orders(order_date);
CREATE INDEX idx_orders_code ON orders(order_code);

CREATE INDEX idx_reviews_product ON reviews(product_id);
CREATE INDEX idx_reviews_user ON reviews(user_id);

CREATE INDEX idx_cart_user ON cart_items(user_id);

CREATE INDEX idx_vouchers_code ON vouchers(code);
CREATE INDEX idx_vouchers_status ON vouchers(is_active);

CREATE INDEX idx_activities_type_user ON user_activities(activity_type, user_id);
CREATE INDEX idx_activities_keyword ON user_activities(keyword);
CREAT