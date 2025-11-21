---------------------------------------------------------------------------------------------
-- Tạo databse
---------------------------------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS QuanLyNongSan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE QuanLyNongSan;

-- 1. Vai trò
CREATE TABLE VaiTro (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenVaiTro VARCHAR(50) NOT NULL UNIQUE,
    MoTa VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Người dùng
CREATE TABLE NguoiDung (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenNguoiDung VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    SDT VARCHAR(15),
    MatKhau VARCHAR(255) NOT NULL,
    DiaChi VARCHAR(500),
    NgaySinh DATE,
    GioiTinh ENUM('Nam','Nữ','Khác'),
    HinhAnh VARCHAR(1000),
    TrangThai TINYINT(1),
    IDVaiTro INT NOT NULL, 
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (IDVaiTro) REFERENCES VaiTro(ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Token xác thực
CREATE TABLE Token (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT NOT NULL,
    Token VARCHAR(500) NOT NULL,
    Loai ENUM('reset_password','verify_email','remember_me') NOT NULL,
    HetHan DATETIME NOT NULL,
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE CASCADE,
    INDEX idx_token (Token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Danh mục
CREATE TABLE DanhMuc (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenDanhMuc VARCHAR(255) NOT NULL,
    HinhAnh VARCHAR(1000),
    ThuTu INT DEFAULT 0,
    TrangThai TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Loại sản phẩm
CREATE TABLE LoaiSanPham (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenLoai VARCHAR(255) NOT NULL,
    IDDanhMuc INT,
    TrangThai TINYINT(1) DEFAULT 1,
    FOREIGN KEY (IDDanhMuc) REFERENCES DanhMuc(ID) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Nhà cung cấp
CREATE TABLE NhaCungCap (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenNhaCungCap VARCHAR(255) NOT NULL,
    SDT VARCHAR(15),
    Email VARCHAR(255),
    DiaChi VARCHAR(500)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Sản phẩm (bỏ GiaGoc, HanSuDung đổi thành DATE)
CREATE TABLE SanPham (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenSanPham VARCHAR(255) NOT NULL,
    MoTa TEXT,
    Gia DECIMAL(18,2) NOT NULL,
    SoLuongTon INT DEFAULT 0,
    DonViTinh VARCHAR(30) DEFAULT 'kg',
    HinhAnh VARCHAR(1000),
    XuatXu VARCHAR(100),
    HanSuDung DATE, 
    LuotXem INT DEFAULT 0,
    LuotBan INT DEFAULT 0,
    DanhGiaTB DECIMAL(2,1) DEFAULT 0.0,
    NoiBat TINYINT(1) DEFAULT 0,
    TrangThai TINYINT(1) DEFAULT 1,
    IDLoaiSP INT,
    IDNhaCungCap INT,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (IDLoaiSP) REFERENCES LoaiSanPham(ID) ON DELETE SET NULL,
    FOREIGN KEY (IDNhaCungCap) REFERENCES NhaCungCap(ID) ON DELETE SET NULL,
    INDEX idx_ten (TenSanPham),
    INDEX idx_gia (Gia),
    INDEX idx_loai (IDLoaiSP),
    FULLTEXT INDEX ft_search (TenSanPham, MoTa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Hình ảnh sản phẩm
CREATE TABLE HinhAnhSanPham (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDSanPham INT NOT NULL,
    DuongDan VARCHAR(1000) NOT NULL,
    LaChinh TINYINT(1) DEFAULT 0,
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. BẢNG KHUYẾN MÃI
CREATE TABLE KhuyenMai (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenKhuyenMai VARCHAR(255) NOT NULL,
    MoTa VARCHAR(1000),
    LoaiKhuyenMai ENUM('Phần trăm', 'Tiền mặt') NOT NULL,
    GiaTriGiam DECIMAL(18,2) NOT NULL,
    GiamToiDa DECIMAL(18,2), -- Giảm tối đa (nếu là %)
    NgayBatDau DATETIME NOT NULL,
    NgayKetThuc DATETIME NOT NULL,
    TrangThai BOOLEAN DEFAULT TRUE,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. BẢNG SẢN PHẨM - KHUYẾN MÃI (Áp dụng KM)
CREATE TABLE SanPhamKhuyenMai (
    IDSanPham INT NOT NULL,
    IDKhuyenMai INT NOT NULL,
    GhiChu VARCHAR(500),
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (IDSanPham, IDKhuyenMai),
    CONSTRAINT FK_SanPhamKhuyenMai_SanPham FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_SanPhamKhuyenMai_KhuyenMai FOREIGN KEY (IDKhuyenMai) REFERENCES KhuyenMai(ID) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Voucher
CREATE TABLE Voucher (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    MaVoucher VARCHAR(50) UNIQUE NOT NULL,
    GiaTri DECIMAL(18,2) NOT NULL,
    Loai ENUM('Phần trăm','Tiền mặt'),
    GiamToiDa DECIMAL(18,2),
    DonToiThieu DECIMAL(18,2) DEFAULT 0,
    SoLuong INT DEFAULT 999999,
    DaDung INT DEFAULT 0,
    NgayKetThuc DATETIME NOT NULL,
    INDEX idx_ma (MaVoucher)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Giỏ hàng
CREATE TABLE GioHang (
    IDNguoiDung INT NOT NULL,
    IDSanPham INT NOT NULL,
    SoLuong INT DEFAULT 1,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (IDNguoiDung, IDSanPham),
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE CASCADE,
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Đơn hàng
CREATE TABLE DonHang (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    MaDonHang VARCHAR(50) UNIQUE NOT NULL,
    IDNguoiDung INT NULL,
    TenNguoiNhan VARCHAR(255) NOT NULL,
    SDT VARCHAR(15) NOT NULL,
    DiaChi VARCHAR(500) NOT NULL,
    PhuongThucTT ENUM('COD','VNPAY','Momo','Bank') DEFAULT 'COD',
    PhiVanChuyen DECIMAL(12,2) DEFAULT 0,
    GiamVoucher DECIMAL(12,2) DEFAULT 0,
    IDVoucher INT NULL,
    TongThanhToan DECIMAL(18,2) NOT NULL,
    TrangThai ENUM('Chờ xác nhận','Đã xác nhận','Đang giao','Đã giao','Đã hủy') DEFAULT 'Chờ xác nhận',
    GhiChu TEXT,
    NgayDat DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE SET NULL,
    FOREIGN KEY (IDVoucher) REFERENCES Voucher(ID),
    INDEX idx_trangthai (TrangThai),
    INDEX idx_ngay (NgayDat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Chi tiết đơn hàng
CREATE TABLE ChiTietDonHang (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDDonHang INT NOT NULL,
    IDSanPham INT NULL,
    TenSanPham VARCHAR(255) NOT NULL,
    SoLuong INT NOT NULL,
    DonGia DECIMAL(18,2) NOT NULL,
    ThanhTien DECIMAL(18,2) AS (SoLuong * DonGia) STORED,
    FOREIGN KEY (IDDonHang) REFERENCES DonHang(ID) ON DELETE CASCADE,
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Thanh toán (giữ lại vì có thể hoàn tiền nhiều lần)
CREATE TABLE ThanhToan (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDDonHang INT NOT NULL,
    SoTien DECIMAL(18,2) NOT NULL,
    PhuongThuc VARCHAR(50),
    TrangThai ENUM('Chờ','Thành công','Thất bại','Hoàn') DEFAULT 'Chờ',
    NgayThanhToan DATETIME,
    FOREIGN KEY (IDDonHang) REFERENCES DonHang(ID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Đánh giá (ảnh lưu chuỗi, chấp nhận được cho shop vừa-nhỏ)
CREATE TABLE DanhGia (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDSanPham INT NOT NULL,
    IDNguoiDung INT NOT NULL,
    SoSao TINYINT NOT NULL CHECK (SoSao BETWEEN 1 AND 5),
    NoiDung TEXT,
    HinhAnh TEXT, -- chuỗi JSON hoặc "," phân cách
    TrangThai ENUM('Chờ duyệt','Đã duyệt','Bị ẩn') DEFAULT 'Chờ duyệt',
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE CASCADE,
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE CASCADE,
    INDEX idx_sp (IDSanPham)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Hoạt động người dùng (gộp: yêu thích + tìm kiếm + xem)
CREATE TABLE HoatDongNguoiDung (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT NULL,
    Loai ENUM('Tìm kiếm','Yêu thích','Xem sản phẩm') NOT NULL,
    TuKhoa VARCHAR(255) NULL,
    IDSanPham INT NULL,
    Ngay DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE CASCADE,
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE CASCADE,
    UNIQUE uniq_like (IDNguoiDung, IDSanPham, Loai) -- tránh thích 2 lần
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Banner
CREATE TABLE Banner (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TieuDe VARCHAR(255),
    HinhAnh VARCHAR(1000) NOT NULL,
    LienKet VARCHAR(500),
    ViTri VARCHAR(50) DEFAULT 'Trang chủ',
    ThuTu INT DEFAULT 0,
    TrangThai TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. Liên hệ (hỗ trợ khách hàng)
CREATE TABLE LienHe (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    HoTen VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    SDT VARCHAR(15),
    TieuDe VARCHAR(255) NOT NULL,
    NoiDung TEXT NOT NULL,
    TrangThai ENUM('Mới','Đang xử lý','Hoàn thành') DEFAULT 'Mới',
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- INDEX ĐỂ TỐI ƯU QUERY
-- Index cho tìm kiếm sản phẩm
CREATE INDEX idx_sanpham_ten ON SanPham(TenSanPham);
CREATE INDEX idx_sanpham_gia ON SanPham(Gia);
CREATE INDEX idx_sanpham_trangthai ON SanPham(TrangThai);
CREATE INDEX idx_sanpham_loai ON SanPham(IDLoaiSP);
CREATE INDEX idx_sanpham_noibat ON SanPham(NoiBat);

-- Index cho đơn hàng
CREATE INDEX idx_donhang_nguoidung ON DonHang(IDNguoiDung);
CREATE INDEX idx_donhang_trangthai ON DonHang(TrangThai);
CREATE INDEX idx_donhang_ngaydat ON DonHang(NgayDat);
CREATE INDEX idx_donhang_ma ON DonHang(MaDonHang);

-- Index cho đánh giá
CREATE INDEX idx_danhgia_sanpham ON DanhGia(IDSanPham);
CREATE INDEX idx_danhgia_nguoidung ON DanhGia(IDNguoiDung);

-- Index cho giỏ hàng
CREATE INDEX idx_giohang_nguoidung ON GioHang(IDNguoiDung);

-- Index cho voucher
CREATE INDEX idx_voucher_ma ON Voucher(MaVoucher);

-- Index cho hoạt động người dùng
CREATE INDEX idx_hoatdong_loai_user ON HoatDongNguoiDung(Loai, IDNguoiDung);
CREATE INDEX idx_hoatdong_tukhoa ON HoatDongNguoiDung(TuKhoa);
CREATE INDEX idx_hoatdong_sanpham_user ON HoatDongNguoiDung(IDSanPham, IDNguoiDung);

---------------------------------------------------------------------------------------------
-- thêm dữ liệu
---------------------------------------------------------------------------------------------
-- -- Loại sản phẩm
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (1, N'Quà tặng')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (2, N'Trái cây & hoa')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (3, N'Thịt, cá, trứng, hải sản')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (4, N'Rau, củ, quả & nấm')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (5, N'Thực phẩm đông mát')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (7, N'Thực phẩm khô')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (8, N'Gia vị và thảo mộc')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (9, N'Bánh kẹo các loại')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (10, N'Thức uống các loại')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (11, N'Ngũ cốc & hạt')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (12, N'Thực phẩm bổ sung')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (13, N'Sữa các loại')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (14, N'Chăm sóc nhà & bếp')
-- INSERT [dbo].[LoaiSanPham] ([IDLoaiSP], [TenLoaiSP]) VALUES (15, N'Làm đẹp & chăm sốc cơ thể')

-- -- Nhà cung cấp
-- INSERT [dbo].[NhaCungCap] ([IDNhaCungCap], [TenNhaCungCap], [DiaChi], [SDT], [Email], [GhiChu]) VALUES (1, N'Trung Quốc', N'SƠN ĐÔNG', N'0123456789', N'tq@gmail.com', N'Sản phẩm đạt chất lượng')
-- INSERT [dbo].[NhaCungCap] ([IDNhaCungCap], [TenNhaCungCap], [DiaChi], [SDT], [Email], [GhiChu]) VALUES (2, N'Nam Mỹ', N'Số 5, Phạm Hùng, Mỹ Đình 2, Nam Từ Liêm, Hà Nội', N'0234295890', N'NM@gmail.com', N'Sản phẩm đạt chất lượng')
-- INSERT [dbo].[NhaCungCap] ([IDNhaCungCap], [TenNhaCungCap], [DiaChi], [SDT], [Email], [GhiChu]) VALUES (3, N'Nam Phi', N'92 Trần Nhật Duật - Hoàn Kiếm', N'0124295890', N'NP@gmail.com', N'Sản phẩm đạt chất lượng')
-- INSERT [dbo].[NhaCungCap] ([IDNhaCungCap], [TenNhaCungCap], [DiaChi], [SDT], [Email], [GhiChu]) VALUES (4, N'Úc', N'Payne Orchards 372 Bacchus Marsh Rd, Bacchus Marsh, VIC 3340', N'0974295890', N'Uc@gmail.com', N'Sản phẩm đạt chất lượng')
-- INSERT [dbo].[NhaCungCap] ([IDNhaCungCap], [TenNhaCungCap], [DiaChi], [SDT], [Email], [GhiChu]) VALUES (5, N'Việt Nam', N'Đại lộ Nguyễn Văn Linh, Khu Phố 6, Phường 7, Quận 8, TP. HCM.', N'0234567890', N'VN@gmail.com', N'Sản phẩm đạt chất lượng')
-- INSERT [dbo].[NhaCungCap] ([IDNhaCungCap], [TenNhaCungCap], [DiaChi], [SDT], [Email], [GhiChu]) VALUES (6, N'Đài Loan', N'Cửa hàng trái cây nhập khẩu cao cấp tại Gò Vấp TP.HCM', N'0971456789', N'DoaiLoan@gmail.com', N'Sản phẩm đạt chất lượng')
-- INSERT [dbo].[NhaCungCap] ([IDNhaCungCap], [TenNhaCungCap], [DiaChi], [SDT], [Email], [GhiChu]) VALUES (7, N'New Zealand', N'Cửa hàng trái cây nhập khẩu cao cấp tại Gò Vấp TP.HCM', N'0931456789', N'NewZealand@gmail.com', N'Sản phẩm đạt chất lượng')
-- INSERT [dbo].[NhaCungCap] ([IDNhaCungCap], [TenNhaCungCap], [DiaChi], [SDT], [Email], [GhiChu]) VALUES (8, N'Đức', N'Gofood Thủ Đức, 111 đường B Trưng Trắc, Hiệp Bình Chánh, Thủ Đức, TPHCM', N'0362223061', N'nhapkhauduc@gmail.com', N'Sản phẩm đạt chất lượng')
