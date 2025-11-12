# 📂 Hướng dẫn sử dụng Sidebar Menu

## ✅ Đã hoàn thành

Sidebar menu đã được tách ra thành một partial riêng để dễ dàng sử dụng lại ở nhiều trang khác.

### 📦 File đã tạo:

**resources/views/partials/sidebar.blade.php** - Sidebar menu component

---

## 🎯 Cách sử dụng

### 1. Sử dụng trong view

Chỉ cần thêm một dòng `@include` vào view của bạn:

```blade
<div class="row">
    <!-- Sidebar Menu -->
    @include('partials.sidebar')
    
    <!-- Main Content -->
    <div class="col-10 content-body">
        <!-- Nội dung trang -->
    </div>
</div>
```

### 2. Các trang đã sử dụng sidebar

- ✅ `home.blade.php` - Trang chủ
- ✅ `products.blade.php` - Danh sách sản phẩm (cần cập nhật)
- ✅ `product-detail.blade.php` - Chi tiết sản phẩm (cần cập nhật)

---

## 📋 Danh sách menu

Sidebar hiện có 16 menu items:

1. Khuyến mãi sốc
2. Lương thực
3. Thịt, cá, trứng
4. Thủy sản tươi sống
5. Thủy sản chế biến
6. Rau, củ, nấm
7. Trái cây nhiệt đới
8. Trái cây ôn đới
9. Cà phê
10. Gia vị, nước chấm
11. Hạt dinh dưỡng
12. Sản phẩm từ dừa
13. Nông sản chế biến
14. Trà, mật ong
15. Thực phẩm đông mát
16. Xem 3.298 tại cửa hàng

---

## 🎨 Tính năng

### Icon mũi tên xuống
- Sử dụng Remix Icon: `ri-arrow-down-s-line`
- Màu xám (silver)
- Kích thước: 18px
- Vị trí: Float right

### Style
- Background: Trắng
- Font weight: 700 (Bold)
- Text transform: Uppercase
- Border bottom: Có viền dưới
- Padding: 11px 0

---

## 🔧 Tùy chỉnh

### Thay đổi menu items

Mở file `resources/views/partials/sidebar.blade.php` và chỉnh sửa:

```blade
<li class="nav-item">
    <a href="{{ route('category.show', 'rau-cu-nam') }}" class="nav-link border-b mx-3 p-0 text-dark fw-500 text-uperc fs-14-t" style="text-transform: uppercase; font-weight: 700; padding: 11px 0 !important;">
        Rau, củ, nấm 
        <i class="ri-arrow-down-s-line" style="float: right; color: silver; font-size: 18px;"></i>
    </a>
</li>
```

### Thay đổi icon

Thay thế class `ri-arrow-down-s-line` bằng icon khác từ Remix Icon:

```blade
<!-- Mũi tên phải -->
<i class="ri-arrow-right-s-line" style="..."></i>

<!-- Plus -->
<i class="ri-add-line" style="..."></i>

<!-- Chevron -->
<i class="ri-arrow-drop-down-line" style="..."></i>
```

### Thay đổi màu sắc

```blade
<!-- Màu xanh -->
<i class="ri-arrow-down-s-line" style="float: right; color: #007e42; font-size: 18px;"></i>

<!-- Màu đen -->
<i class="ri-arrow-down-s-line" style="float: right; color: #333; font-size: 18px;"></i>
```

---

## 🔗 Kết nối với Database

### Tạo Controller

```php
// app/Http/Controllers/CategoryController.php
public function index() {
    $categories = DanhMuc::where('TrangThai', true)
                         ->orderBy('ThuTu')
                         ->get();
    
    return view('home', compact('categories'));
}
```

### Update Sidebar với dữ liệu động

```blade
{{-- resources/views/partials/sidebar.blade.php --}}
<div class="col-2 slide-menu p-0">
    <ul class="nav flex-column bg-white">
        @foreach($categories ?? [] as $category)
        <li class="nav-item">
            <a href="{{ route('category.show', $category->Slug) }}" class="nav-link border-b mx-3 p-0 text-dark fw-500 text-uperc fs-14-t" style="text-transform: uppercase; font-weight: 700; padding: 11px 0 !important;">
                {{ $category->TenDanhMuc }}
                <i class="ri-arrow-down-s-line" style="float: right; color: silver; font-size: 18px;"></i>
            </a>
        </li>
        @endforeach
    </ul>
</div>
```

### Truyền dữ liệu từ Controller

```php
// routes/web.php
Route::get('/', [HomeController::class, 'index'])->name('home');

// app/Http/Controllers/HomeController.php
public function index() {
    $categories = DanhMuc::where('TrangThai', true)
                         ->orderBy('ThuTu')
                         ->limit(16)
                         ->get();
    
    return view('home', compact('categories'));
}
```

---

## 📱 Responsive

### Desktop (>= 992px)
- Width: col-2 (16.67%)
- Hiển thị đầy đủ

### Tablet (768px - 991px)
- Width: col-3 (25%)
- Hiển thị đầy đủ

### Mobile (< 768px)
- Có thể ẩn hoặc chuyển thành dropdown
- Cần thêm CSS responsive

### Thêm responsive cho mobile

```css
/* public/template/Assets/css/sidebar.css */
@media (max-width: 768px) {
    .slide-menu {
        display: none; /* Ẩn sidebar trên mobile */
    }
    
    /* Hoặc chuyển thành dropdown */
    .slide-menu {
        position: fixed;
        top: 0;
        left: -100%;
        width: 80%;
        height: 100vh;
        z-index: 1000;
        transition: left 0.3s ease;
    }
    
    .slide-menu.active {
        left: 0;
    }
}
```

---

## 🎯 Ví dụ sử dụng

### Trang chủ (home.blade.php)

```blade
@extends('layouts.app')

@section('content')
<div class="row">
    @include('partials.sidebar')
    
    <div class="col-10 content-body">
        <!-- Nội dung trang chủ -->
    </div>
</div>
@endsection
```

### Trang sản phẩm (products.blade.php)

```blade
@extends('layouts.app')

@section('content')
<div class="row">
    @include('partials.sidebar')
    
    <div class="col-10 content-body">
        <!-- Danh sách sản phẩm -->
    </div>
</div>
@endsection
```

### Trang chi tiết sản phẩm (product-detail.blade.php)

```blade
@extends('layouts.app')

@section('content')
<div class="row">
    @include('partials.sidebar')
    
    <div class="col-10 content-body">
        <!-- Chi tiết sản phẩm -->
    </div>
</div>
@endsection
```

---

## ✅ Lợi ích

1. **Tái sử dụng** - Chỉ cần viết một lần, dùng nhiều nơi
2. **Dễ bảo trì** - Sửa một chỗ, áp dụng toàn bộ
3. **Nhất quán** - Sidebar giống nhau trên mọi trang
4. **Linh hoạt** - Dễ dàng tùy chỉnh và mở rộng
5. **Clean code** - Code gọn gàng, dễ đọc

---

## 🔄 Migration từ code cũ

### Trước (code trực tiếp trong view):

```blade
<div class="col-2 slide-menu p-0">
    <ul class="nav flex-column bg-white">
        <li class="nav-item">
            <a href="#">Khuyến mãi sốc</a>
        </li>
        <!-- 15 items khác... -->
    </ul>
</div>
```

### Sau (sử dụng partial):

```blade
@include('partials.sidebar')
```

**Kết quả**: Giảm từ ~50 dòng code xuống còn 1 dòng! 🎉

---

## 📝 Checklist

- [x] Tạo file `partials/sidebar.blade.php`
- [x] Cập nhật `home.blade.php` sử dụng @include
- [x] Thay icon "V" bằng `ri-arrow-down-s-line`
- [ ] Cập nhật `products.blade.php` (cần làm)
- [ ] Cập nhật `product-detail.blade.php` (cần làm)
- [ ] Thêm responsive cho mobile (tùy chọn)
- [ ] Kết nối database (tùy chọn)

---

## 💡 Tips

1. **Truyền biến vào sidebar**:
```blade
@include('partials.sidebar', ['activeCategory' => 'rau-cu-nam'])
```

2. **Kiểm tra active menu**:
```blade
<a href="#" class="{{ $activeCategory === 'rau-cu-nam' ? 'active' : '' }}">
    Rau, củ, nấm
</a>
```

3. **Thêm submenu**:
```blade
<li class="nav-item">
    <a href="#" data-bs-toggle="collapse" data-bs-target="#submenu-rau">
        Rau, củ, nấm
        <i class="ri-arrow-down-s-line"></i>
    </a>
    <ul class="collapse" id="submenu-rau">
        <li><a href="#">Rau lá</a></li>
        <li><a href="#">Củ quả</a></li>
    </ul>
</li>
```

---

**Sidebar đã được tách thành công!** 🎉
