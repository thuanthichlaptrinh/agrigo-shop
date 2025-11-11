-- DATABASE BỔ SUNG: QUẢN LÝ BÁN NÔNG SẢN
-- File này chứa các bảng bổ sung và cải tiến cho database chính
-- Chạy file này SAU KHI đã chạy database.sql

USE QuanLyNongSan;

-- ============================================
-- PHẦN 1: CẢI TIẾN CÁC BẢNG ĐÃ CÓ
-- ============================================

-- 1.1. Thêm các trường cần thiết cho bảng NguoiDung (tương thích Laravel)
ALTER TABLE NguoiDung 
ADD COLUMN remember_token VARCHAR(100) AFTER MatKhau,
ADD COLUMN email_verified_at DATETIME AFTER Email;

-- 1.2. Thêm slug cho bảng SanPham (SEO friendly)
ALTER TABLE SanPham 
ADD COLUMN Slug VARCHAR(255) UNIQUE AFTER TenSanPham;

-- 1.3. Thêm slug cho bảng DanhMuc (SEO friendly)
ALTER TABLE DanhMuc 
ADD COLUMN Slug VARCHAR(255) UNIQUE AFTER TenDanhMuc;

-- 1.4. Thêm các trường cho bảng DonHang
ALTER TABLE DonHang 
ADD COLUMN MaVanDon VARCHAR(100) AFTER DiaChiGiaoHang,
ADD COLUMN DonViVanChuyen VARCHAR(100) AFTER MaVanDon;

-- ============================================
-- PHẦN 2: CÁC BẢNG BỔ SUNG MỚI
-- ============================================

-- 2.1. BẢNG ĐỊA CHỈ GIAO HÀNG (Quản lý nhiều địa chỉ của user)
CREATE TABLE DiaChiGiaoHang (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT NOT NULL,
    TenNguoiNhan VARCHAR(255) NOT NULL,
    SDT VARCHAR(15) NOT NULL,
    DiaChi VARCHAR(500) NOT NULL,
    PhuongXa VARCHAR(100),
    QuanHuyen VARCHAR(100),
    TinhThanhPho VARCHAR(100),
    LaDiaChiMacDinh BOOLEAN DEFAULT FALSE,
    GhiChu VARCHAR(500),
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_DiaChiGiaoHang_NguoiDung FOREIGN KEY (IDNguoiDung) 
        REFERENCES NguoiDung(ID) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2.2. BẢNG LỊCH SỬ TRẠNG THÁI ĐƠN HÀNG (Tracking chi tiết)
CREATE TABLE LichSuDonHang (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDDonHang INT NOT NULL,
    TrangThaiCu VARCHAR(50),
    TrangThaiMoi VARCHAR(50) NOT NULL,
    IDNguoiThucHien INT, -- ID của admin/user thực hiện thay đổi
    GhiChu VARCHAR(500),
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_LichSuDonHang_DonHang FOREIGN KEY (IDDonHang) 
        REFERENCES DonHang(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_LichSuDonHang_NguoiThucHien FOREIGN KEY (IDNguoiThucHien) 
        REFERENCES NguoiDung(ID) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.3. BẢNG SẢN PHẨM YÊU THÍCH (Wishlist - Uncomment từ database.sql)
CREATE TABLE IF NOT EXISTS SanPhamYeuThich (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT NOT NULL,
    IDSanPham INT NOT NULL,
    NgayThem DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_SanPhamYeuThich_NguoiDung FOREIGN KEY (IDNguoiDung) 
        REFERENCES NguoiDung(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_SanPhamYeuThich_SanPham FOREIGN KEY (IDSanPham) 
        REFERENCES SanPham(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (IDNguoiDung, IDSanPham)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.4. BẢNG LỊCH SỬ TÌM KIẾM (Search History - Uncomment từ database.sql)
CREATE TABLE IF NOT EXISTS LichSuTimKiem (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT,
    TuKhoa VARCHAR(255) NOT NULL,
    SoLanTimKiem INT DEFAULT 1,
    NgayTimKiem DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_LichSuTimKiem_NguoiDung FOREIGN KEY (IDNguoiDung) 
        REFERENCES NguoiDung(ID) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.5. BẢNG TAGS SẢN PHẨM (Phân loại linh hoạt)
CREATE TABLE Tags (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenTag VARCHAR(100) NOT NULL UNIQUE,
    Slug VARCHAR(100) NOT NULL UNIQUE,
    MoTa VARCHAR(500),
    TrangThai BOOLEAN DEFAULT TRUE,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.6. BẢNG SẢN PHẨM - TAGS (Many-to-Many)
CREATE TABLE SanPhamTags (
    IDSanPham INT NOT NULL,
    IDTag INT NOT NULL,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (IDSanPham, IDTag),
    CONSTRAINT FK_SanPhamTags_SanPham FOREIGN KEY (IDSanPham) 
        REFERENCES SanPham(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_SanPhamTags_Tag FOREIGN KEY (IDTag) 
        REFERENCES Tags(ID) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.7. BẢNG CÂU HỎI THƯỜNG GẶP (FAQ)
CREATE TABLE FAQ (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    CauHoi VARCHAR(500) NOT NULL,
    CauTraLoi TEXT NOT NULL,
    DanhMuc VARCHAR(100), -- 'san_pham', 'van_chuyen', 'thanh_toan', 'tai_khoan'
    ThuTu INT DEFAULT 0,
    LuotXem INT DEFAULT 0,
    TrangThai BOOLEAN DEFAULT TRUE,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.8. BẢNG PHẢN HỒI LIÊN HỆ (Trả lời khách hàng)
CREATE TABLE PhanHoiLienHe (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDLienHe INT NOT NULL,
    IDNguoiTraLoi INT NOT NULL, -- Admin trả lời
    NoiDung VARCHAR(2000) NOT NULL,
    NgayTraLoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_PhanHoiLienHe_LienHe FOREIGN KEY (IDLienHe) 
        REFERENCES LienHe(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_PhanHoiLienHe_NguoiTraLoi FOREIGN KEY (IDNguoiTraLoi) 
        REFERENCES NguoiDung(ID) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.9. BẢNG LỊCH SỬ XEM SẢN PHẨM (Recently Viewed)
CREATE TABLE LichSuXemSanPham (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT,
    IDSanPham INT NOT NULL,
    NgayXem DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_LichSuXemSanPham_NguoiDung FOREIGN KEY (IDNguoiDung) 
        REFERENCES NguoiDung(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_LichSuXemSanPham_SanPham FOREIGN KEY (IDSanPham) 
        REFERENCES SanPham(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX idx_nguoidung_ngayxem (IDNguoiDung, NgayXem DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.10. BẢNG ĐÁNH GIÁ HỮU ÍCH (Like/Dislike đánh giá)
CREATE TABLE DanhGiaHuuIch (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDDanhGia INT NOT NULL,
    IDNguoiDung INT NOT NULL,
    LoaiPhanHoi ENUM('huu_ich', 'khong_huu_ich') NOT NULL,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_DanhGiaHuuIch_DanhGia FOREIGN KEY (IDDanhGia) 
        REFERENCES DanhGia(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_DanhGiaHuuIch_NguoiDung FOREIGN KEY (IDNguoiDung) 
        REFERENCES NguoiDung(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY unique_review_feedback (IDDanhGia, IDNguoiDung)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.11. BẢNG THỐNG KÊ TRUY CẬP (Analytics)
CREATE TABLE ThongKeTruyCap (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT,
    URL VARCHAR(500) NOT NULL,
    IPAddress VARCHAR(45),
    UserAgent VARCHAR(500),
    Referer VARCHAR(500),
    NgayTruyCap DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_ThongKeTruyCap_NguoiDung FOREIGN KEY (IDNguoiDung) 
        REFERENCES NguoiDung(ID) ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_ngaytruycap (NgayTruyCap),
    INDEX idx_url (URL(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.12. BẢNG CÀI ĐẶT HỆ THỐNG (System Settings)
CREATE TABLE CaiDatHeThong (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    KeyCaiDat VARCHAR(100) NOT NULL UNIQUE,
    GiaTri TEXT,
    MoTa VARCHAR(500),
    LoaiDuLieu ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.13. BẢNG NHẬT KÝ HOẠT ĐỘNG (Activity Log - cho Admin)
CREATE TABLE NhatKyHoatDong (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT,
    HanhDong VARCHAR(100) NOT NULL, -- 'create', 'update', 'delete', 'login', 'logout'
    DoiTuong VARCHAR(100), -- 'product', 'order', 'user', 'category'
    IDDoiTuong INT,
    DuLieuCu TEXT, -- JSON
    DuLieuMoi TEXT, -- JSON
    IPAddress VARCHAR(45),
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_NhatKyHoatDong_NguoiDung FOREIGN KEY (IDNguoiDung) 
        REFERENCES NguoiDung(ID) ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_nguoidung_ngaytao (IDNguoiDung, NgayTao DESC),
    INDEX idx_doituong (DoiTuong, IDDoiTuong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.14. BẢNG PHƯƠNG THỨC VẬN CHUYỂN
CREATE TABLE PhuongThucVanChuyen (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenPhuongThuc VARCHAR(255) NOT NULL,
    MaPhuongThuc VARCHAR(50) UNIQUE NOT NULL, -- 'ghn', 'ghtk', 'viettel_post'
    MoTa VARCHAR(500),
    PhiCoBan DECIMAL(18,2) DEFAULT 0,
    ThoiGianGiaoDuKien VARCHAR(100), -- '2-3 ngày', '3-5 ngày'
    Logo VARCHAR(1000),
    TrangThai BOOLEAN DEFAULT TRUE,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.15. BẢNG PHƯƠNG THỨC THANH TOÁN
CREATE TABLE PhuongThucThanhToan (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenPhuongThuc VARCHAR(255) NOT NULL,
    MaPhuongThuc VARCHAR(50) UNIQUE NOT NULL, -- 'cod', 'vnpay', 'momo', 'zalopay'
    MoTa VARCHAR(500),
    Logo VARCHAR(1000),
    PhiGiaoDich DECIMAL(18,2) DEFAULT 0, -- Phí giao dịch (nếu có)
    TrangThai BOOLEAN DEFAULT TRUE,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- PHẦN 3: INDEX BỔ SUNG ĐỂ TỐI ƯU QUERY
-- ============================================

-- Index cho bảng DiaChiGiaoHang
CREATE INDEX idx_diachi_nguoidung ON DiaChiGiaoHang(IDNguoiDung);
CREATE INDEX idx_diachi_macdinh ON DiaChiGiaoHang(IDNguoiDung, LaDiaChiMacDinh);

-- Index cho bảng LichSuDonHang
CREATE INDEX idx_lichsu_donhang ON LichSuDonHang(IDDonHang, NgayTao DESC);

-- Index cho bảng SanPhamYeuThich
CREATE INDEX idx_yeuthich_nguoidung ON SanPhamYeuThich(IDNguoiDung);
CREATE INDEX idx_yeuthich_sanpham ON SanPhamYeuThich(IDSanPham);

-- Index cho bảng LichSuTimKiem
CREATE INDEX idx_timkiem_tukho ON LichSuTimKiem(TuKhoa);
CREATE INDEX idx_timkiem_nguoidung ON LichSuTimKiem(IDNguoiDung);

-- Index cho bảng Tags
CREATE INDEX idx_tags_slug ON Tags(Slug);

-- Index cho bảng FAQ
CREATE INDEX idx_faq_danhmuc ON FAQ(DanhMuc, ThuTu);

-- Index cho bảng NguoiDung (bổ sung)
CREATE INDEX idx_nguoidung_email ON NguoiDung(Email);
CREATE INDEX idx_nguoidung_sdt ON NguoiDung(SDT);

-- Index cho bảng SanPham (bổ sung)
CREATE INDEX idx_sanpham_slug ON SanPham(Slug);
CREATE INDEX idx_sanpham_danhmuc ON SanPham(IDLoaiSP, TrangThai);

-- Index cho bảng DanhMuc (bổ sung)
CREATE INDEX idx_danhmuc_slug ON DanhMuc(Slug);

-- ============================================
-- PHẦN 4: DỮ LIỆU MẪU (SEED DATA)
-- ============================================

-- 4.1. Vai trò mặc định
INSERT INTO VaiTro (ID, TenVaiTro, MoTa) VALUES
(1, 'Admin', 'Quản trị viên hệ thống'),
(2, 'Khách hàng', 'Người dùng thông thường'),
(3, 'Nhân viên', 'Nhân viên quản lý đơn hàng');

-- 4.2. Phương thức vận chuyển
INSERT INTO PhuongThucVanChuyen (TenPhuongThuc, MaPhuongThuc, MoTa, PhiCoBan, ThoiGianGiaoDuKien) VALUES
('Giao hàng nhanh', 'ghn', 'Giao hàng nhanh toàn quốc', 30000, '2-3 ngày'),
('Giao hàng tiết kiệm', 'ghtk', 'Giao hàng tiết kiệm', 25000, '3-5 ngày'),
('Viettel Post', 'viettel_post', 'Bưu điện Viettel', 28000, '2-4 ngày'),
('Giao hàng nội thành', 'noi_thanh', 'Giao hàng trong nội thành', 20000, '1-2 ngày');

-- 4.3. Phương thức thanh toán
INSERT INTO PhuongThucThanhToan (TenPhuongThuc, MaPhuongThuc, MoTa, PhiGiaoDich) VALUES
('Tiền mặt (COD)', 'cod', 'Thanh toán khi nhận hàng', 0),
('VNPay', 'vnpay', 'Thanh toán qua VNPay', 0),
('MoMo', 'momo', 'Thanh toán qua ví MoMo', 0),
('ZaloPay', 'zalopay', 'Thanh toán qua ZaloPay', 0),
('Chuyển khoản ngân hàng', 'bank_transfer', 'Chuyển khoản trực tiếp', 0);

-- 4.4. Cài đặt hệ thống
INSERT INTO CaiDatHeThong (KeyCaiDat, GiaTri, MoTa, LoaiDuLieu) VALUES
('site_name', 'Cửa hàng Nông sản Organic', 'Tên website', 'string'),
('site_email', 'contact@organic.vn', 'Email liên hệ', 'string'),
('site_phone', '0123456789', 'Số điện thoại', 'string'),
('site_address', '123 Đường ABC, Quận 1, TP.HCM', 'Địa chỉ cửa hàng', 'string'),
('free_ship_amount', '500000', 'Miễn phí ship từ (VNĐ)', 'number'),
('min_order_amount', '50000', 'Đơn hàng tối thiểu (VNĐ)', 'number'),
('enable_review', 'true', 'Bật tính năng đánh giá', 'boolean'),
('enable_wishlist', 'true', 'Bật tính năng yêu thích', 'boolean'),
('products_per_page', '12', 'Số sản phẩm mỗi trang', 'number'),
('facebook_url', 'https://facebook.com/organic', 'Link Facebook', 'string'),
('instagram_url', 'https://instagram.com/organic', 'Link Instagram', 'string'),
('zalo_url', 'https://zalo.me/organic', 'Link Zalo', 'string');

-- 4.5. FAQ mẫu
INSERT INTO FAQ (CauHoi, CauTraLoi, DanhMuc, ThuTu) VALUES
('Làm thế nào để đặt hàng?', 'Bạn chọn sản phẩm, thêm vào giỏ hàng, sau đó tiến hành thanh toán và điền thông tin giao hàng.', 'san_pham', 1),
('Thời gian giao hàng là bao lâu?', 'Thời gian giao hàng từ 2-5 ngày tùy theo khu vực và phương thức vận chuyển bạn chọn.', 'van_chuyen', 2),
('Tôi có thể thanh toán bằng cách nào?', 'Chúng tôi hỗ trợ thanh toán COD, chuyển khoản, VNPay, MoMo và ZaloPay.', 'thanh_toan', 3),
('Làm sao để theo dõi đơn hàng?', 'Bạn đăng nhập vào tài khoản và vào mục "Đơn hàng của tôi" để xem chi tiết.', 'tai_khoan', 4),
('Chính sách đổi trả như thế nào?', 'Sản phẩm được đổi trả trong vòng 7 ngày nếu có lỗi từ nhà sản xuất.', 'san_pham', 5);

-- 4.6. Tags mẫu
INSERT INTO Tags (TenTag, Slug, MoTa) VALUES
('Organic', 'organic', 'Sản phẩm hữu cơ'),
('Fresh', 'fresh', 'Sản phẩm tươi mới'),
('Sale', 'sale', 'Đang giảm giá'),
('New', 'new', 'Sản phẩm mới'),
('Best Seller', 'best-seller', 'Bán chạy nhất'),
('Imported', 'imported', 'Nhập khẩu'),
('Local', 'local', 'Sản phẩm nội địa'),
('Premium', 'premium', 'Cao cấp');

-- ============================================
-- PHẦN 5: TRIGGER TỰ ĐỘNG
-- ============================================

-- 5.1. Trigger: Tự động cập nhật số lượng voucher đã dùng
DELIMITER $$
CREATE TRIGGER after_lichsuvoucher_insert
AFTER INSERT ON LichSuVoucher
FOR EACH ROW
BEGIN
    UPDATE Voucher 
    SET SoLuongDaDung = SoLuongDaDung + 1
    WHERE ID = NEW.IDVoucher;
END$$
DELIMITER ;

-- 5.2. Trigger: Tự động cập nhật lượt bán sản phẩm
DELIMITER $$
CREATE TRIGGER after_chitietdonhang_insert
AFTER INSERT ON ChiTietDonHang
FOR EACH ROW
BEGIN
    UPDATE SanPham 
    SET LuotBan = LuotBan + NEW.SoLuong,
        SoLuongTon = SoLuongTon - NEW.SoLuong
    WHERE ID = NEW.IDSanPham;
END$$
DELIMITER ;

-- 5.3. Trigger: Tự động cập nhật đánh giá trung bình sản phẩm
DELIMITER $$
CREATE TRIGGER after_danhgia_insert
AFTER INSERT ON DanhGia
FOR EACH ROW
BEGIN
    UPDATE SanPham 
    SET DanhGiaTrungBinh = (
        SELECT AVG(SoSao) 
        FROM DanhGia 
        WHERE IDSanPham = NEW.IDSanPham AND TrangThai = 'da_duyet'
    )
    WHERE ID = NEW.IDSanPham;
END$$
DELIMITER ;

-- 5.4. Trigger: Tự động cập nhật đánh giá trung bình khi update
DELIMITER $$
CREATE TRIGGER after_danhgia_update
AFTER UPDATE ON DanhGia
FOR EACH ROW
BEGIN
    UPDATE SanPham 
    SET DanhGiaTrungBinh = (
        SELECT AVG(SoSao) 
        FROM DanhGia 
        WHERE IDSanPham = NEW.IDSanPham AND TrangThai = 'da_duyet'
    )
    WHERE ID = NEW.IDSanPham;
END$$
DELIMITER ;

-- 5.5. Trigger: Tự động tạo slug từ tên sản phẩm (nếu chưa có)
DELIMITER $$
CREATE TRIGGER before_sanpham_insert
BEFORE INSERT ON SanPham
FOR EACH ROW
BEGIN
    IF NEW.Slug IS NULL OR NEW.Slug = '' THEN
        SET NEW.Slug = LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
            NEW.TenSanPham, ' ', '-'), 'đ', 'd'), 'Đ', 'D'), 'ă', 'a'), 'â', 'a'));
    END IF;
END$$
DELIMITER ;

-- 5.6. Trigger: Tự động tạo slug từ tên danh mục (nếu chưa có)
DELIMITER $$
CREATE TRIGGER before_danhmuc_insert
BEFORE INSERT ON DanhMuc
FOR EACH ROW
BEGIN
    IF NEW.Slug IS NULL OR NEW.Slug = '' THEN
        SET NEW.Slug = LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
            NEW.TenDanhMuc, ' ', '-'), 'đ', 'd'), 'Đ', 'D'), 'ă', 'a'), 'â', 'a'));
    END IF;
END$$
DELIMITER ;

-- 5.7. Trigger: Tự động ghi log khi thay đổi trạng thái đơn hàng
DELIMITER $$
CREATE TRIGGER after_donhang_update
AFTER UPDATE ON DonHang
FOR EACH ROW
BEGIN
    IF OLD.TrangThai != NEW.TrangThai THEN
        INSERT INTO LichSuDonHang (IDDonHang, TrangThaiCu, TrangThaiMoi, GhiChu)
        VALUES (NEW.ID, OLD.TrangThai, NEW.TrangThai, 'Tự động cập nhật');
    END IF;
END$$
DELIMITER ;

-- ============================================
-- PHẦN 6: STORED PROCEDURES (Thủ tục lưu trữ)
-- ============================================

-- 6.1. Procedure: Lấy sản phẩm bán chạy
DELIMITER $$
CREATE PROCEDURE sp_GetTopSellingProducts(IN limit_count INT)
BEGIN
    SELECT * FROM SanPham 
    WHERE TrangThai = TRUE
    ORDER BY LuotBan DESC, DanhGiaTrungBinh DESC
    LIMIT limit_count;
END$$
DELIMITER ;

-- 6.2. Procedure: Lấy sản phẩm mới nhất
DELIMITER $$
CREATE PROCEDURE sp_GetNewestProducts(IN limit_count INT)
BEGIN
    SELECT * FROM SanPham 
    WHERE TrangThai = TRUE
    ORDER BY NgayTao DESC
    LIMIT limit_count;
END$$
DELIMITER ;

-- 6.3. Procedure: Lấy sản phẩm có khuyến mãi
DELIMITER $$
CREATE PROCEDURE sp_GetPromotionProducts()
BEGIN
    SELECT DISTINCT sp.* 
    FROM SanPham sp
    INNER JOIN SanPhamKhuyenMai spkm ON sp.ID = spkm.IDSanPham
    INNER JOIN KhuyenMai km ON spkm.IDKhuyenMai = km.ID
    WHERE sp.TrangThai = TRUE 
    AND km.TrangThai = TRUE
    AND km.NgayBatDau <= NOW()
    AND km.NgayKetThuc >= NOW()
    ORDER BY sp.NgayTao DESC;
END$$
DELIMITER ;

-- 6.4. Procedure: Tính tổng doanh thu theo khoảng thời gian
DELIMITER $$
CREATE PROCEDURE sp_GetRevenue(IN start_date DATE, IN end_date DATE)
BEGIN
    SELECT 
        DATE(NgayDat) as Ngay,
        COUNT(*) as SoDonHang,
        SUM(TongThanhToan) as DoanhThu
    FROM DonHang
    WHERE NgayDat BETWEEN start_date AND end_date
    AND TrangThai IN ('da_xac_nhan', 'dang_giao', 'da_giao')
    GROUP BY DATE(NgayDat)
    ORDER BY Ngay DESC;
END$$
DELIMITER ;

-- 6.5. Procedure: Lấy thống kê tổng quan
DELIMITER $$
CREATE PROCEDURE sp_GetDashboardStats()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM NguoiDung WHERE IDVaiTro = 2) as TongKhachHang,
        (SELECT COUNT(*) FROM SanPham WHERE TrangThai = TRUE) as TongSanPham,
        (SELECT COUNT(*) FROM DonHang WHERE TrangThai = 'cho_xac_nhan') as DonHangChoXuLy,
        (SELECT SUM(TongThanhToan) FROM DonHang WHERE DATE(NgayDat) = CURDATE()) as DoanhThuHomNay,
        (SELECT SUM(TongThanhToan) FROM DonHang WHERE MONTH(NgayDat) = MONTH(CURDATE())) as DoanhThuThangNay;
END$$
DELIMITER ;

-- ============================================
-- PHẦN 7: VIEWS (Khung nhìn)
-- ============================================

-- 7.1. View: Sản phẩm với thông tin đầy đủ
CREATE OR REPLACE VIEW v_SanPhamDayDu AS
SELECT 
    sp.*,
    lsp.TenLoaiSP,
    dm.TenDanhMuc,
    ncc.TenNhaCungCap,
    (SELECT COUNT(*) FROM DanhGia WHERE IDSanPham = sp.ID AND TrangThai = 'da_duyet') as SoLuongDanhGia
FROM SanPham sp
LEFT JOIN LoaiSanPham lsp ON sp.IDLoaiSP = lsp.ID
LEFT JOIN DanhMuc dm ON lsp.IDDanhMuc = dm.ID
LEFT JOIN NhaCungCap ncc ON sp.IDNhaCungCap = ncc.ID;

-- 7.2. View: Đơn hàng với thông tin chi tiết
CREATE OR REPLACE VIEW v_DonHangChiTiet AS
SELECT 
    dh.*,
    nd.TenNguoiDung,
    nd.Email,
    nd.SDT as SDTNguoiDung,
    (SELECT COUNT(*) FROM ChiTietDonHang WHERE IDDonHang = dh.ID) as SoSanPham
FROM DonHang dh
LEFT JOIN NguoiDung nd ON dh.IDNguoiDung = nd.ID;

-- 7.3. View: Thống kê sản phẩm bán chạy
CREATE OR REPLACE VIEW v_SanPhamBanChay AS
SELECT 
    sp.ID,
    sp.TenSanPham,
    sp.Gia,
    sp.HinhAnh,
    sp.LuotBan,
    sp.DanhGiaTrungBinh,
    lsp.TenLoaiSP,
    dm.TenDanhMuc
FROM SanPham sp
LEFT JOIN LoaiSanPham lsp ON sp.IDLoaiSP = lsp.ID
LEFT JOIN DanhMuc dm ON lsp.IDDanhMuc = dm.ID
WHERE sp.TrangThai = TRUE
ORDER BY sp.LuotBan DESC, sp.DanhGiaTrungBinh DESC;

-- ============================================
-- PHẦN 8: GHI CHÚ VÀ HƯỚNG DẪN
-- ============================================

/*
HƯỚNG DẪN SỬ DỤNG:

1. Chạy file database.sql TRƯỚC
2. Chạy file database2.sql này SAU

3. Kiểm tra các bảng đã tạo:
   SHOW TABLES;

4. Kiểm tra triggers:
   SHOW TRIGGERS;

5. Kiểm tra stored procedures:
   SHOW PROCEDURE STATUS WHERE Db = 'QuanLyNongSan';

6. Kiểm tra views:
   SHOW FULL TABLES WHERE TABLE_TYPE LIKE 'VIEW';

7. Test stored procedure:
   CALL sp_GetTopSellingProducts(10);
   CALL sp_GetDashboardStats();

8. Test view:
   SELECT * FROM v_SanPhamDayDu LIMIT 10;

CÁC TÍNH NĂNG ĐÃ BỔ SUNG:
✅ Quản lý nhiều địa chỉ giao hàng
✅ Tracking chi tiết đơn hàng
✅ Wishlist (Yêu thích)
✅ Lịch sử tìm kiếm
✅ Tags cho sản phẩm
✅ FAQ (Câu hỏi thường gặp)
✅ Phản hồi liên hệ
✅ Lịch sử xem sản phẩm
✅ Đánh giá hữu ích
✅ Thống kê truy cập
✅ Cài đặt hệ thống
✅ Nhật ký hoạt động
✅ Phương thức vận chuyển
✅ Phương thức thanh toán
✅ Triggers tự động
✅ Stored Procedures
✅ Views tối ưu

TƯƠNG THÍCH LARAVEL:
✅ remember_token cho NguoiDung
✅ email_verified_at cho NguoiDung
✅ Slug cho SEO friendly URLs
✅ Soft deletes ready (có thể thêm deleted_at)
*/
