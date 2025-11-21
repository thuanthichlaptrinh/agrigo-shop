CREATE DATABASE IF NOT EXISTS QuanLyNongSan_PHP_HUIT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE QuanLyNongSan_PHP_HUIT;

CREATE TABLE VaiTro (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenVaiTro VARCHAR(50) NOT NULL UNIQUE,
    MoTa VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
    FOREIGN KEY (IDVaiTro) REFERENCES VaiTro(ID),
    INDEX idx_email (Email),
    INDEX idx_trangthai (TrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Token (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT NOT NULL,
    Token VARCHAR(500) NOT NULL,
    Loai ENUM('reset_password','verify_email','remember_me') NOT NULL,
    HetHan DATETIME NOT NULL,
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE CASCADE,
    INDEX idx_token (Token),
    INDEX idx_user_token (IDNguoiDung)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE DanhMuc (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenDanhMuc VARCHAR(255) NOT NULL,
    HinhAnh VARCHAR(1000),
    ThuTu INT DEFAULT 0,
    TrangThai TINYINT(1) DEFAULT 1,
    INDEX idx_tendanhmuc (TenDanhMuc),
    INDEX idx_trangthai (TrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE LoaiSanPham (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenLoai VARCHAR(255) NOT NULL,
    IDDanhMuc INT,
    TrangThai TINYINT(1) DEFAULT 1,
    FOREIGN KEY (IDDanhMuc) REFERENCES DanhMuc(ID) ON DELETE SET NULL,
    INDEX idx_tenloai (TenLoai),
    INDEX idx_danhmuc (IDDanhMuc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE NhaCungCap (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenNhaCungCap VARCHAR(255) NOT NULL,
    SDT VARCHAR(15),
    Email VARCHAR(255),
    DiaChi VARCHAR(500),
    INDEX idx_nhacungcap_ten (TenNhaCungCap)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
    INDEX idx_trangthai (TrangThai),
    INDEX idx_loai (IDLoaiSP),
    INDEX idx_noibat (NoiBat),
    FULLTEXT INDEX ft_search (TenSanPham, MoTa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE HinhAnhSanPham (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDSanPham INT NOT NULL,
    DuongDan VARCHAR(1000) NOT NULL,
    LaChinh TINYINT(1) DEFAULT 0,
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE CASCADE,
    INDEX idx_sp (IDSanPham)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE KhuyenMai (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TenKhuyenMai VARCHAR(255) NOT NULL,
    MoTa VARCHAR(1000),
    LoaiKhuyenMai ENUM('Phần trăm', 'Tiền mặt') NOT NULL,
    GiaTriGiam DECIMAL(18,2) NOT NULL,
    GiamToiDa DECIMAL(18,2),
    NgayBatDau DATETIME NOT NULL,
    NgayKetThuc DATETIME NOT NULL,
    TrangThai BOOLEAN DEFAULT TRUE,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_trangthai (TrangThai),
    INDEX idx_ngay (NgayBatDau, NgayKetThuc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE SanPhamKhuyenMai (
    IDSanPham INT NOT NULL,
    IDKhuyenMai INT NOT NULL,
    GhiChu VARCHAR(500),
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (IDSanPham, IDKhuyenMai),
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE CASCADE,
    FOREIGN KEY (IDKhuyenMai) REFERENCES KhuyenMai(ID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE GioHang (
    IDNguoiDung INT NOT NULL,
    IDSanPham INT NOT NULL,
    SoLuong INT DEFAULT 1,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (IDNguoiDung, IDSanPham),
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE CASCADE,
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE CASCADE,
    INDEX idx_user (IDNguoiDung)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

    INDEX idx_user (IDNguoiDung),
    INDEX idx_trangthai (TrangThai),
    INDEX idx_ngaydat (NgayDat),
    INDEX idx_ma (MaDonHang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ChiTietDonHang (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDDonHang INT NOT NULL,
    IDSanPham INT NULL,
    TenSanPham VARCHAR(255) NOT NULL,
    SoLuong INT NOT NULL,
    DonGia DECIMAL(18,2) NOT NULL,
    ThanhTien DECIMAL(18,2) AS (SoLuong * DonGia) STORED,
    FOREIGN KEY (IDDonHang) REFERENCES DonHang(ID) ON DELETE CASCADE,
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE SET NULL,
    INDEX idx_donhang (IDDonHang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ThanhToan (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDDonHang INT NOT NULL,
    SoTien DECIMAL(18,2) NOT NULL,
    PhuongThuc VARCHAR(50),
    TrangThai ENUM('Chờ','Thành công','Thất bại','Hoàn') DEFAULT 'Chờ',
    NgayThanhToan DATETIME,
    FOREIGN KEY (IDDonHang) REFERENCES DonHang(ID) ON DELETE CASCADE,
    INDEX idx_trangthai (TrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE DanhGia (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDSanPham INT NOT NULL,
    IDNguoiDung INT NOT NULL,
    SoSao TINYINT NOT NULL CHECK (SoSao BETWEEN 1 AND 5),
    NoiDung TEXT,
    HinhAnh TEXT,
    TrangThai ENUM('Chờ duyệt','Đã duyệt','Bị ẩn') DEFAULT 'Chờ duyệt',
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE CASCADE,
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE CASCADE,

    INDEX idx_sp (IDSanPham),
    INDEX idx_user (IDNguoiDung)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE HoatDongNguoiDung (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT NULL,
    Loai ENUM('Tìm kiếm','Yêu thích','Xem sản phẩm') NOT NULL,
    TuKhoa VARCHAR(255) NULL,
    IDSanPham INT NULL,
    Ngay DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE CASCADE,
    FOREIGN KEY (IDSanPham) REFERENCES SanPham(ID) ON DELETE CASCADE,
    UNIQUE uniq_like (IDNguoiDung, IDSanPham, Loai),

    INDEX idx_loai_user (Loai, IDNguoiDung),
    INDEX idx_tukhoa (TuKhoa),
    INDEX idx_sp_user (IDSanPham, IDNguoiDung)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Banner (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TieuDe VARCHAR(255),
    HinhAnh VARCHAR(1000) NOT NULL,
    LienKet VARCHAR(500),
    ViTri VARCHAR(50) DEFAULT 'Trang chủ',
    ThuTu INT DEFAULT 0,
    TrangThai TINYINT(1) DEFAULT 1,
    INDEX idx_trangthai (TrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE LienHe (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    HoTen VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    SDT VARCHAR(15),
    TieuDe VARCHAR(255) NOT NULL,
    NoiDung TEXT NOT NULL,
    TrangThai ENUM('Mới','Đang xử lý','Hoàn thành') DEFAULT 'Mới',
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_trangthai (TrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE NhatKy (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT NULL,
    HanhDong VARCHAR(255) NOT NULL,
    Loai ENUM('Hệ thống','Quản trị','Người dùng') DEFAULT 'Người dùng',
    DuLieuCu TEXT NULL,
    DuLieuMoi TEXT NULL,
    DiaChiIP VARCHAR(100),
    TrinhDuyet VARCHAR(255),
    KetQua ENUM('Thành công','Thất bại') DEFAULT 'Thành công',
    ThoiGian DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE SET NULL,

    INDEX idx_user (IDNguoiDung),
    INDEX idx_hanhdong (HanhDong),
    INDEX idx_loai (Loai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ThongBao (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    IDNguoiDung INT NULL,
    TieuDe VARCHAR(255) NOT NULL,
    NoiDung TEXT NOT NULL,
    Loai ENUM('Hệ thống','Đơn hàng','Khuyến mãi','Tài khoản','Khác') DEFAULT 'Khác',
    DaXem TINYINT(1) DEFAULT 0,
    LinkLienKet VARCHAR(500),
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IDNguoiDung) REFERENCES NguoiDung(ID) ON DELETE CASCADE,

    INDEX idx_user (IDNguoiDung),
    INDEX idx_loai (Loai),
    INDEX idx_daxem (DaXem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
