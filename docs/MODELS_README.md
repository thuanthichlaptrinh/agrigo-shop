# Danh Sách Models - Database Vietnamese Structure

## ✅ Hoàn Thành

Đã tạo tất cả **21 Models** tương ứng với cấu trúc database2.sql:

### 1. VaiTro (Roles)

-   **File**: `app/Models/VaiTro.php`
-   **Bảng**: `VaiTro`
-   **Primary Key**: `ID`
-   **Timestamps**: Không
-   **Constants**: ADMIN, USER, PRODUCT_MANAGER, ORDER_MANAGER
-   **Relationships**:
    -   `nguoiDung()` - hasMany NguoiDung

### 2. NguoiDung (Users)

-   **File**: `app/Models/NguoiDung.php`
-   **Bảng**: `NguoiDung`
-   **Primary Key**: `ID`
-   **Timestamps**: `NgayTao`, `NgayCapNhat`
-   **Implements**: JWTSubject (JWT Authentication)
-   **Relationships**:
    -   `vaiTro()` - belongsTo VaiTro
    -   `tokens()` - hasMany Token
    -   `gioHang()` - hasMany GioHang
    -   `donHang()` - hasMany DonHang
    -   `danhGia()` - hasMany DanhGia
    -   `hoatDong()` - hasMany HoatDongNguoiDung
    -   `nhatKy()` - hasMany NhatKy
    -   `thongBao()` - hasMany ThongBao

### 3. Token (JWT Tokens)

-   **File**: `app/Models/Token.php`
-   **Bảng**: `Token`
-   **Primary Key**: `ID`
-   **Timestamps**: Không
-   **Relationships**:
    -   `nguoiDung()` - belongsTo NguoiDung
-   **Helper Methods**: createToken(), isExpired(), findValidToken(), revokeUserTokens()

### 4. DanhMuc (Categories)

-   **File**: `app/Models/DanhMuc.php`
-   **Bảng**: `DanhMuc`
-   **Primary Key**: `ID`
-   **Timestamps**: Không
-   **Relationships**:
    -   `loaiSanPham()` - hasMany LoaiSanPham

### 5. LoaiSanPham (Product Types)

-   **File**: `app/Models/LoaiSanPham.php`
-   **Bảng**: `LoaiSanPham`
-   **Primary Key**: `ID`
-   **Timestamps**: Không
-   **Relationships**:
    -   `danhMuc()` - belongsTo DanhMuc
    -   `sanPham()` - hasMany SanPham

### 6. NhaCungCap (Suppliers)

-   **File**: `app/Models/NhaCungCap.php`
-   **Bảng**: `NhaCungCap`
-   **Primary Key**: `ID`
-   **Timestamps**: Không
-   **Relationships**:
    -   `sanPham()` - hasMany SanPham

### 7. SanPham (Products)

-   **File**: `app/Models/SanPham.php`
-   **Bảng**: `SanPham`
-   **Primary Key**: `ID`
-   **Timestamps**: `NgayTao`, `NgayCapNhat`
-   **Relationships**:
    -   `loaiSanPham()` - belongsTo LoaiSanPham
    -   `nhaCungCap()` - belongsTo NhaCungCap
    -   `hinhAnh()` - hasMany HinhAnhSanPham
    -   `khuyenMai()` - belongsToMany KhuyenMai (through SanPhamKhuyenMai)
    -   `danhGia()` - hasMany DanhGia
    -   `gioHang()` - hasMany GioHang
    -   `chiTietDonHang()` - hasMany ChiTietDonHang
    -   `hoatDongNguoiDung()` - hasMany HoatDongNguoiDung

### 8. HinhAnhSanPham (Product Images)

-   **File**: `app/Models/HinhAnhSanPham.php`
-   **Bảng**: `HinhAnhSanPham`
-   **Primary Key**: `ID`
-   **Timestamps**: Không
-   **Relationships**:
    -   `sanPham()` - belongsTo SanPham

### 9. KhuyenMai (Promotions)

-   **File**: `app/Models/KhuyenMai.php`
-   **Bảng**: `KhuyenMai`
-   **Primary Key**: `ID`
-   **Timestamps**: `NgayTao`, `NgayCapNhat`
-   **Relationships**:
    -   `sanPham()` - belongsToMany SanPham (through SanPhamKhuyenMai)
-   **Helper Methods**: isActive()

### 10. SanPhamKhuyenMai (Product Promotions Pivot)

-   **File**: `app/Models/SanPhamKhuyenMai.php`
-   **Bảng**: `SanPhamKhuyenMai`
-   **Primary Key**: Composite (`IDSanPham`, `IDKhuyenMai`)
-   **Timestamps**: Không
-   **Note**: Pivot table với composite key

### 11. Voucher (Vouchers)

-   **File**: `app/Models/Voucher.php`
-   **Bảng**: `Voucher`
-   **Primary Key**: `ID`
-   **Timestamps**: Không
-   **Relationships**:
    -   `donHang()` - hasMany DonHang
-   **Helper Methods**: isAvailable(), canApply(), calculateDiscount()

### 12. GioHang (Shopping Cart)

-   **File**: `app/Models/GioHang.php`
-   **Bảng**: `GioHang`
-   **Primary Key**: Composite (`IDNguoiDung`, `IDSanPham`)
-   **Timestamps**: `NgayCapNhat` only
-   **Relationships**:
    -   `nguoiDung()` - belongsTo NguoiDung
    -   `sanPham()` - belongsTo SanPham
-   **Helper Methods**: getThanhTien()

### 13. DonHang (Orders)

-   **File**: `app/Models/DonHang.php`
-   **Bảng**: `DonHang`
-   **Primary Key**: `ID`
-   **Timestamps**: `NgayDat`, `NgayCapNhat`
-   **Relationships**:
    -   `nguoiDung()` - belongsTo NguoiDung
    -   `voucher()` - belongsTo Voucher
    -   `chiTiet()` - hasMany ChiTietDonHang
    -   `thanhToan()` - hasMany ThanhToan
-   **Helper Methods**: getTongTienHang(), canCancel()

### 14. ChiTietDonHang (Order Details)

-   **File**: `app/Models/ChiTietDonHang.php`
-   **Bảng**: `ChiTietDonHang`
-   **Primary Key**: `ID`
-   **Timestamps**: Không
-   **Relationships**:
    -   `donHang()` - belongsTo DonHang
    -   `sanPham()` - belongsTo SanPham
-   **Note**: `ThanhTien` là computed column (SoLuong \* DonGia)

### 15. ThanhToan (Payments)

-   **File**: `app/Models/ThanhToan.php`
-   **Bảng**: `ThanhToan`
-   **Primary Key**: `ID`
-   **Timestamps**: Không
-   **Relationships**:
    -   `donHang()` - belongsTo DonHang
-   **Helper Methods**: isSuccess()

### 16. DanhGia (Reviews)

-   **File**: `app/Models/DanhGia.php`
-   **Bảng**: `DanhGia`
-   **Primary Key**: `ID`
-   **Timestamps**: `NgayTao` only
-   **Relationships**:
    -   `sanPham()` - belongsTo SanPham
    -   `nguoiDung()` - belongsTo NguoiDung
-   **Helper Methods**: isApproved()

### 17. HoatDongNguoiDung (User Activities)

-   **File**: `app/Models/HoatDongNguoiDung.php`
-   **Bảng**: `HoatDongNguoiDung`
-   **Primary Key**: `ID`
-   **Timestamps**: `Ngay` only
-   **Relationships**:
    -   `nguoiDung()` - belongsTo NguoiDung
    -   `sanPham()` - belongsTo SanPham
-   **Helper Methods**: logTimKiem(), logYeuThich(), logXemSanPham()

### 18. Banner (Banners)

-   **File**: `app/Models/Banner.php`
-   **Bảng**: `Banner`
-   **Primary Key**: `ID`
-   **Timestamps**: Không
-   **Helper Methods**: isActive(), getByViTri()

### 19. LienHe (Contact Messages)

-   **File**: `app/Models/LienHe.php`
-   **Bảng**: `LienHe`
-   **Primary Key**: `ID`
-   **Timestamps**: `NgayTao` only
-   **Helper Methods**: isNew(), isProcessing(), isCompleted(), markAsProcessing(), markAsCompleted()

### 20. NhatKy (Activity Logs)

-   **File**: `app/Models/NhatKy.php`
-   **Bảng**: `NhatKy`
-   **Primary Key**: `ID`
-   **Timestamps**: `ThoiGian` only
-   **Relationships**:
    -   `nguoiDung()` - belongsTo NguoiDung
-   **Helper Methods**: log(), logHeThong(), logQuanTri()

### 21. ThongBao (Notifications)

-   **File**: `app/Models/ThongBao.php`
-   **Bảng**: `ThongBao`
-   **Primary Key**: `ID`
-   **Timestamps**: `NgayTao` only
-   **Relationships**:
    -   `nguoiDung()` - belongsTo NguoiDung
-   **Helper Methods**: markAsRead(), createForUser(), getUnreadCount()

---

## 📝 Đặc Điểm Models

### Timestamps Mapping

Các models có mapping timestamp tùy chỉnh:

-   **Full Timestamps**: `SanPham`, `KhuyenMai`, `DonHang`, `NguoiDung`
    -   `const CREATED_AT = 'NgayTao'`
    -   `const UPDATED_AT = 'NgayCapNhat'`
-   **Created Only**: `DanhGia`, `HoatDongNguoiDung`, `LienHe`, `NhatKy`, `ThongBao`
    -   `const CREATED_AT = 'Ngay'/'NgayTao'/'ThoiGian'`
    -   `const UPDATED_AT = null`
-   **Updated Only**: `GioHang`
    -   `const UPDATED_AT = 'NgayCapNhat'`
-   **No Timestamps**: Các models còn lại
    -   `public $timestamps = false`

### Composite Primary Keys

Hai models sử dụng composite primary key:

1. **SanPhamKhuyenMai**: `['IDSanPham', 'IDKhuyenMai']`
2. **GioHang**: `['IDNguoiDung', 'IDSanPham']`

### Computed Columns

-   **ChiTietDonHang**: `ThanhTien = SoLuong * DonGia` (STORED trong database)

### Vietnamese Field Names

Tất cả models đều sử dụng tên trường tiếng Việt theo database2.sql:

-   `TenNguoiDung`, `Email`, `MatKhau`
-   `TenSanPham`, `MoTa`, `Gia`, `SoLuongTon`
-   `NgayTao`, `NgayCapNhat`, `TrangThai`
-   etc.

---

## ✅ Trạng Thái

-   ✅ **21/21 Models** đã được tạo
-   ✅ Tất cả relationships đã được định nghĩa
-   ✅ Helper methods cho business logic đã được thêm
-   ✅ Casting types đã được cấu hình
-   ✅ Timestamps mapping đã được thiết lập
-   ✅ Composite keys đã được xử lý

## 🎯 Tiếp Theo

Bạn có thể:

1. Chạy seeders để tạo dữ liệu mẫu
2. Tạo Controllers cho CRUD operations
3. Tạo API Routes
4. Tạo Form Requests cho validation
