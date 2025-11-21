# Hướng dẫn Quản lý Người dùng

## Tổng quan

Module quản lý người dùng cung cấp đầy đủ chức năng CRUD (Create, Read, Update, Delete) với giao diện modal hiện đại, sử dụng Bootstrap 5.3.0 và custom CSS.

## Tính năng

### 1. Danh sách người dùng

-   Hiển thị danh sách người dùng với phân trang (10 người/trang)
-   Avatar tự động từ ui-avatars.com
-   Badge màu sắc theo vai trò và trạng thái
-   Hover effect với animation mượt mà

### 2. Tìm kiếm & Lọc

-   **Tìm kiếm**: Theo tên, email, số điện thoại
-   **Lọc theo vai trò**: Admin, User, ProductManager, OrderManager
-   **Lọc theo trạng thái**: Hoạt động, Bị khóa
-   **Sắp xếp**: Theo ngày tạo

### 3. Thêm người dùng mới

-   Modal form với validation
-   Các trường bắt buộc: Tên, Email, Mật khẩu, Vai trò, Trạng thái
-   Các trường tùy chọn: SĐT, Địa chỉ, Ngày sinh
-   Animation mượt mà khi mở/đóng modal

### 4. Sửa thông tin người dùng

-   Load thông tin qua AJAX
-   Cho phép thay đổi mật khẩu (tùy chọn)
-   Validation đầy đủ
-   Cập nhật realtime

### 5. Xem chi tiết người dùng

-   Modal hiển thị đầy đủ thông tin
-   Định dạng dữ liệu đẹp mắt
-   Có thể click bên ngoài để đóng

### 6. Xóa người dùng

-   Xác nhận trước khi xóa
-   Không cho phép tự xóa chính mình
-   Xử lý an toàn với CSRF token

## UI/UX Design

### Colors & Gradients

-   **Primary**: Linear gradient từ #667eea đến #764ba2
-   **Success**: Linear gradient từ #28a745 đến #218838
-   **Warning**: Linear gradient từ #FFA500 đến #FF8C00
-   **Danger**: Linear gradient từ #dc3545 đến #c82333
-   **Info**: Linear gradient từ #17a2b8 đến #138496

### Animations

-   **Modal**: Fade in + Slide down effect (0.3s - 0.4s)
-   **Table Row**: Hover transform translateX(3px)
-   **Avatar**: Scale 1.1 on hover
-   **Buttons**: TranslateY(-2px) on hover
-   **Close Icon**: Rotate 90deg on hover

### Shadows

-   **Modal**: Box shadow 0 10px 40px rgba(0,0,0,0.3)
-   **Table**: Box shadow 0 2px 8px rgba(0,0,0,0.08)
-   **Badges**: Box shadow 0 2px 8px với màu tương ứng
-   **Buttons**: Box shadow 0 4px 12px khi hover

### Border Radius

-   **Modal**: 15px
-   **Form Controls**: 8px
-   **Badges**: 20px (pill shape)
-   **Buttons**: 8px
-   **Avatar**: 50% (circle)

## Routes

```php
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');           // Danh sách
    Route::post('/', [UserController::class, 'store'])->name('store');          // Thêm mới
    Route::get('/{id}', [UserController::class, 'show'])->name('show');         // Xem (AJAX)
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');    // Load form sửa (AJAX)
    Route::put('/{id}', [UserController::class, 'update'])->name('update');     // Cập nhật
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy'); // Xóa
});
```

## Database Schema

### Bảng: NguoiDung

-   **ID** (Primary Key)
-   **TenNguoiDung** (varchar)
-   **Email** (varchar, unique)
-   **MatKhau** (varchar, hashed)
-   **SDT** (varchar, nullable)
-   **DiaChi** (varchar, nullable)
-   **NgaySinh** (date, nullable)
-   **IDVaiTro** (Foreign Key -> VaiTro.IDVaiTro)
-   **TrangThai** (tinyint: 1=Active, 0=Locked)
-   **created_at** (timestamp)
-   **updated_at** (timestamp)

### Bảng: VaiTro

-   **IDVaiTro**: 1=Admin, 2=User, 3=ProductManager, 4=OrderManager
-   **TenVaiTro**: Tên vai trò tương ứng

## JavaScript Functions

### Modal Controls

```javascript
openCreateModal(); // Mở modal thêm mới
closeCreateModal(); // Đóng modal thêm mới
closeEditModal(); // Đóng modal sửa
closeViewModal(); // Đóng modal xem
```

### CRUD Operations

```javascript
editUser(id); // Load và hiển thị form sửa
viewUser(id); // Load và hiển thị thông tin chi tiết
deleteUser(id); // Xác nhận và xóa người dùng
```

### Event Listeners

```javascript
window.onclick; // Đóng modal khi click bên ngoài
```

## Validation Rules

### Thêm mới

-   **TenNguoiDung**: required, max:255
-   **Email**: required, email, unique:NguoiDung,Email
-   **MatKhau**: required, min:6
-   **SDT**: nullable, regex:/^[0-9]{10}$/
-   **IDVaiTro**: required, exists:VaiTro,IDVaiTro
-   **TrangThai**: required, in:0,1

### Cập nhật

-   **TenNguoiDung**: required, max:255
-   **Email**: required, email, unique:NguoiDung,Email,{id},ID
-   **MatKhau**: nullable, min:6 (nếu có thay đổi)
-   **SDT**: nullable, regex:/^[0-9]{10}$/
-   **IDVaiTro**: required, exists:VaiTro,IDVaiTro
-   **TrangThai**: required, in:0,1

## API Response Format

### GET /admin/users/{id} (Show)

```json
{
    "ID": 1,
    "TenNguoiDung": "Nguyễn Văn A",
    "Email": "admin@example.com",
    "SDT": "0123456789",
    "DiaChi": "TP.HCM",
    "NgaySinh": "1990-01-01",
    "IDVaiTro": 1,
    "TrangThai": 1,
    "created_at": "01/01/2024",
    "vai_tro": {
        "IDVaiTro": 1,
        "TenVaiTro": "Admin"
    }
}
```

### GET /admin/users/{id}/edit (Edit Form)

```json
{
    "ID": 1,
    "TenNguoiDung": "Nguyễn Văn A",
    "Email": "admin@example.com",
    "SDT": "0123456789",
    "DiaChi": "TP.HCM",
    "NgaySinh": "1990-01-01",
    "IDVaiTro": 1,
    "TrangThai": 1
}
```

## Bootstrap Components Used

-   **Modal**: Bootstrap modal structure
-   **Forms**: form-control, form-group
-   **Buttons**: btn classes
-   **Badges**: Custom badge styles with gradients
-   **Grid**: Grid layout cho form-row
-   **Icons**: BoxIcons (bx) và Font Awesome

## Custom CSS Features

1. **Backdrop Blur**: Modal background với blur effect
2. **Smooth Scrollbar**: Custom scrollbar cho modal body
3. **Gradient Headers**: Gradient background cho modal header
4. **Hover Transitions**: Smooth transitions trên tất cả interactive elements
5. **Shadow Effects**: Layered shadows tạo depth
6. **Responsive Design**: Grid tự động điều chỉnh theo màn hình

## Browser Compatibility

-   ✅ Chrome 90+
-   ✅ Firefox 88+
-   ✅ Safari 14+
-   ✅ Edge 90+

## Performance

-   **Modal Animation**: Hardware accelerated với transform
-   **AJAX Loading**: Async data fetching
-   **Image Optimization**: CDN avatars từ ui-avatars.com
-   **CSS Optimization**: Minimal reflows và repaints

## Security

-   ✅ CSRF Protection trên tất cả forms
-   ✅ Password hashing với bcrypt
-   ✅ Input validation cả client và server side
-   ✅ XSS protection với Laravel Blade escape
-   ✅ SQL Injection protection với Eloquent ORM

## Troubleshooting

### Modal không mở

-   Kiểm tra console log có lỗi JavaScript
-   Xác nhận Bootstrap JS đã load
-   Kiểm tra z-index của modal (hiện tại: 10000)

### Form không submit

-   Kiểm tra CSRF token
-   Xác nhận route đã đúng
-   Kiểm tra validation rules

### AJAX không hoạt động

-   Kiểm tra route trả về JSON
-   Xác nhận ID người dùng hợp lệ
-   Kiểm tra network tab trong DevTools

## Future Enhancements

-   [ ] Export users to Excel/CSV
-   [ ] Bulk actions (delete, status change)
-   [ ] Advanced search with more filters
-   [ ] User activity log
-   [ ] Profile image upload
-   [ ] Email verification
-   [ ] Password strength indicator
-   [ ] Role-based permissions management

## Change Log

### Version 1.0.0 (Current)

-   ✅ Complete CRUD functionality
-   ✅ Modal-based interface
-   ✅ Bootstrap 5.3.0 integration
-   ✅ Beautiful gradients and animations
-   ✅ AJAX data loading
-   ✅ Search and filter
-   ✅ Pagination
-   ✅ Responsive design
