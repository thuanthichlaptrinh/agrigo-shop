# 🎁 Hướng dẫn phần "Gian hàng và ưu đãi từ hãng"

## ✅ Đã hoàn thành (Cập nhật mới)

Tôi đã thêm phần "Gian hàng và ưu đãi từ hãng" vào trang chủ (`home.blade.php`) theo đúng thiết kế trong hình:

### 📦 Các file đã tạo/chỉnh sửa:

1. **resources/views/home.blade.php** - Thêm section mới (4 banner ngang)
2. **resources/views/layouts/app.blade.php** - Thêm CSS link
3. **public/template/Assets/css/promo-section.css** - CSS cho section
4. **public/template/Assets/Images/promo-placeholder.txt** - Hướng dẫn thêm hình

---

## 🎨 Tính năng đã thêm (Theo thiết kế mới)

### 1. Header Section với Ngôi Sao ⭐
- Gradient màu cam-vàng (#ff9966 → #ffb84d)
- 2 ngôi sao vàng 2 bên tiêu đề
- Bo tròn 25px
- Shadow nhẹ
- Animation float cho background

### 2. Layout 4 Banner Ngang
- 4 cột đều nhau trên desktop
- 2 cột trên tablet
- 1 cột trên mobile
- Background màu kem (#fff5e6)
- Khoảng cách đều giữa các banner

### 3. Hiệu ứng Hover
- Card nổi lên khi hover
- Hình ảnh zoom in nhẹ
- Shadow tăng lên

### 4. Button "Xem thêm"
- Text đơn giản với icon mũi tên xuống
- Màu xám (#666)
- Không có viền

---

## 📸 Hình ảnh cần thêm (CẬP NHẬT)

Bạn cần thêm **4 hình ảnh** vào thư mục: `public/template/Assets/Images/`

| File | Mô tả | Kích thước |
|------|-------|------------|
| `promo-banner-1.jpg` | Tích lũy mua sắm nhận quà ưu đãi (Clear P.S, OMO) | 300x320px |
| `promo-banner-2.jpg` | Tích lũy mua sắm nhận phiếu (200K, 300K, 550K) | 300x320px |
| `promo-banner-3.jpg` | P&G Giặt Xả Gia Tốt (Downy, Ariel) | 300x320px |
| `promo-banner-4.jpg` | Cô Sprite Mát Lành Cực Đã | 300x320px |

### Cách thêm hình ảnh:

**Cách 1: Sử dụng hình từ template gốc**
```bash
# Copy hình từ template gốc vào thư mục Assets/Images
# Đổi tên thành promo-1.jpg, promo-2.jpg, ...
```

**Cách 2: Tải hình mới**
- Tải hình về máy
- Đặt tên theo quy ước: `promo-1.jpg` đến `promo-9.jpg`
- Copy vào `public/template/Assets/Images/`

**Cách 3: Sử dụng placeholder tạm thời**
```bash
# Tạo hình placeholder 400x280px với màu nền
# Hoặc dùng https://via.placeholder.com/400x280
```

---

## 🎯 Vị trí trong trang

Section "Gian hàng và ưu đãi" được đặt:
- ✅ Sau phần sản phẩm "Rau, củ, nấm"
- ✅ Trước footer
- ✅ Trong container chính

---

## 💻 Code Structure

### HTML Structure:
```html
<div class="row mt-4 bg-white mb-3">
    <!-- Header -->
    <div class="col-12 mb-3">
        <div class="gradient-header">
            <h4>GIAN HÀNG VÀ ƯU ĐÃI TỪ HÃNG</h4>
        </div>
    </div>
    
    <!-- Grid 9 banners -->
    <div class="col-12">
        <div class="row g-3">
            <!-- 9 promo cards -->
        </div>
    </div>
    
    <!-- View more button -->
    <div class="col-12 text-center mt-4">
        <a href="#" class="btn">Xem thêm</a>
    </div>
</div>
```

### CSS Classes:
- `.promo-card` - Card container
- `.promo-overlay` - Overlay gradient
- Hover effects tự động

---

## 🔧 Tùy chỉnh

### Thay đổi màu header:
```css
/* File: public/template/Assets/css/promo-section.css */
background: linear-gradient(90deg, #ff9966, #ff6b6b);
/* Đổi thành màu bạn muốn */
```

### Thay đổi số cột:
```html
<!-- File: resources/views/home.blade.php -->
<div class="col-lg-4 col-md-6 col-sm-12">
<!-- col-lg-4 = 3 cột desktop -->
<!-- col-lg-3 = 4 cột desktop -->
<!-- col-lg-6 = 2 cột desktop -->
```

### Thay đổi chiều cao hình:
```html
style="height: 280px; object-fit: cover;"
<!-- Đổi 280px thành giá trị khác -->
```

---

## 📱 Responsive

### Desktop (>= 992px):
- 3 cột
- Chiều cao hình: 280px

### Tablet (768px - 991px):
- 2 cột
- Chiều cao hình: 200px

### Mobile (< 768px):
- 1 cột
- Chiều cao hình: 180px

---

## 🚀 Test

### Kiểm tra hiển thị:
1. Mở trang chủ: `http://localhost:8000/`
2. Scroll xuống phần "Gian hàng và ưu đãi"
3. Kiểm tra:
   - ✅ Header hiển thị đúng
   - ✅ 9 banner xếp đều
   - ✅ Hover effect hoạt động
   - ✅ Responsive trên mobile

### Nếu hình không hiển thị:
```bash
# Kiểm tra đường dẫn
ls public/template/Assets/Images/promo-*.jpg

# Hoặc dùng hình placeholder tạm
# Thay đổi trong home.blade.php:
src="https://via.placeholder.com/400x280/ff6b6b/ffffff?text=Promo+1"
```

---

## 🎨 Màu sắc sử dụng

| Màu | Hex Code | Sử dụng |
|-----|----------|---------|
| Cam | `#ff9966` | Header gradient start |
| Đỏ | `#ff6b6b` | Header gradient end |
| Xanh | `#28a745` | Button outline |
| Đen | `rgba(0,0,0,0.7)` | Overlay |

---

## 📝 Ghi chú

1. **Hình ảnh**: Nếu chưa có hình, section vẫn hiển thị với icon broken image
2. **Link**: Tất cả link đang trỏ đến `#`, cần cập nhật sau
3. **Nội dung**: Có thể thay đổi text trong file `home.blade.php`
4. **Thêm banner**: Copy một block `.col-lg-4` và paste để thêm banner mới

---

## 🔗 Liên kết với Database

Để lấy dữ liệu từ database, bạn có thể:

### 1. Tạo Controller:
```php
// app/Http/Controllers/HomeController.php
public function index() {
    $promotions = Banner::where('ViTri', 'gian_hang_uu_dai')
                        ->where('TrangThai', true)
                        ->orderBy('ThuTu')
                        ->get();
    
    return view('home', compact('promotions'));
}
```

### 2. Update View:
```blade
@foreach($promotions as $promo)
<div class="col-lg-4 col-md-6 col-sm-12">
    <div class="promo-card">
        <a href="{{ $promo->LienKet }}">
            <img src="{{ asset($promo->HinhAnh) }}" />
            <div class="promo-overlay">
                <p>{{ $promo->TieuDe }}</p>
                <p>{{ $promo->MoTa }}</p>
            </div>
        </a>
    </div>
</div>
@endforeach
```

---

## ✅ Checklist hoàn thành

- [x] Tạo HTML structure
- [x] Tạo CSS với hover effects
- [x] Thêm responsive design
- [x] Tích hợp vào layout
- [x] Tạo hướng dẫn
- [ ] Thêm 9 hình ảnh (cần bạn làm)
- [ ] Cập nhật link thực tế (tùy chọn)
- [ ] Kết nối database (tùy chọn)

---

## 🎉 Kết quả

Sau khi thêm hình ảnh, bạn sẽ có:
- ✅ Section đẹp mắt với 9 banner
- ✅ Hiệu ứng hover mượt mà
- ✅ Responsive hoàn hảo
- ✅ Dễ dàng tùy chỉnh
- ✅ Sẵn sàng kết nối database

---

**Chúc bạn thành công!** 🚀


### Nội dung banner theo hình:

**Banner 1**: Tích lũy mua sắm nhận quà ưu đãi
- Sản phẩm: Clear P.S, OMO
- Text: "TÍCH LŨY MUA SẮM NHẬN QUÀ ƯU ĐÃI"
- Giảm giá: Hàng Bạc 30K, Hàng Vàng 50K, Hàng Kim Cương 80K

**Banner 2**: Tích lũy mua sắm nhận phiếu mua hàng
- Background: Xanh dương
- Text: "TÍCH LŨY MUA SẮM NHẬN PHIẾU MUA HÀNG"
- Mức tích lũy: 200K → 15K, 300K → 25K, 550K → 50K

**Banner 3**: P&G Giặt - Xả Gia Tốt
- Sản phẩm: Downy, Ariel
- Logo: P&G
- Text: "GIẶT - XẢ GIA TỐT"

**Banner 4**: Cô Sprite Mát Lành Cực Đã
- Sản phẩm: Sprite
- Background: Xanh lá nhạt
- Hình ảnh: Người uống Sprite
- Text: "CÔ SPRITE MÁT LÀNH CỰC ĐÃ"

---

## 💻 Code Structure (Mới)

### HTML Structure:
```html
<div class="row mt-4 mb-3">
    <!-- Header với ngôi sao -->
    <div class="col-12 mb-3 px-0">
        <div class="promo-header-wrapper">
            ⭐ GIAN HÀNG VÀ ƯU ĐÃI TỪ HÃNG ⭐
        </div>
    </div>
    
    <!-- 4 banners ngang -->
    <div class="col-12 px-0" style="background-color: #fff5e6">
        <div class="row g-2">
            <!-- 4 banner cards -->
        </div>
        
        <!-- View more -->
        <div class="text-center mt-3">
            Xem thêm 1 Ưu đãi từ hãng ↓
        </div>
    </div>
</div>
```

### CSS Classes:
- `.promo-header-wrapper` - Header container với animation
- `.promo-banner-card` - Banner card với hover effect
- Background: `#fff5e6` (màu kem nhạt)

---

## 🔧 Tùy chỉnh

### Thay đổi màu header:
```css
/* File: public/template/Assets/css/promo-section.css */
background: linear-gradient(90deg, #ff9966, #ffb84d);
/* Đổi thành màu bạn muốn */
```

### Thay đổi số banner:
```html
<!-- File: resources/views/home.blade.php -->
<div class="col-lg-3 col-md-6 col-sm-12">
<!-- col-lg-3 = 4 cột desktop (hiện tại) -->
<!-- col-lg-4 = 3 cột desktop -->
<!-- col-lg-6 = 2 cột desktop -->
```

### Thay đổi chiều cao hình:
```html
style="height: 320px; object-fit: cover;"
<!-- Đổi 320px thành giá trị khác -->
```

### Thay đổi background:
```html
style="background-color: #fff5e6"
<!-- Đổi màu nền -->
```

---

## 📱 Responsive

### Desktop (>= 992px):
- 4 cột ngang
- Chiều cao hình: 320px
- Header full size

### Tablet (768px - 991px):
- 2 cột
- Chiều cao hình: 280px
- Header size vừa

### Mobile (< 768px):
- 1 cột
- Chiều cao hình: 240px
- Header size nhỏ

---

## 🚀 Test

### Kiểm tra hiển thị:
1. Mở trang chủ: `http://localhost:8000/`
2. Scroll xuống phần "Gian hàng và ưu đãi"
3. Kiểm tra:
   - ✅ Header có 2 ngôi sao
   - ✅ 4 banner xếp ngang đều nhau
   - ✅ Background màu kem
   - ✅ Hover effect hoạt động
   - ✅ Responsive trên mobile

### Nếu hình không hiển thị:
```bash
# Kiểm tra đường dẫn
ls public/template/Assets/Images/promo-banner-*.jpg

# Hoặc dùng hình placeholder tạm
# Thay đổi trong home.blade.php:
src="https://via.placeholder.com/300x320/ff6b6b/ffffff?text=Banner+1"
```

---

## 🎨 Màu sắc sử dụng

| Màu | Hex Code | Sử dụng |
|-----|----------|---------|
| Cam | `#ff9966` | Header gradient start |
| Vàng | `#ffb84d` | Header gradient end |
| Vàng sao | `#fff700` | Icon ngôi sao |
| Kem | `#fff5e6` | Background section |
| Xám | `#666` | Text "Xem thêm" |

---

## 📝 So sánh với thiết kế cũ

| Tính năng | Cũ | Mới |
|-----------|-----|-----|
| Số banner | 9 banner (3x3) | 4 banner (1x4) |
| Header | Icon gift | 2 ngôi sao ⭐ |
| Layout | Grid 3 cột | 4 cột ngang |
| Background | Trắng | Màu kem (#fff5e6) |
| Overlay | Có text | Không có |
| Button | Bo tròn xanh | Text đơn giản |

---

## ✅ Checklist hoàn thành

- [x] Tạo HTML structure mới (4 banner)
- [x] Tạo CSS với hover effects
- [x] Thêm header với ngôi sao
- [x] Thêm background màu kem
- [x] Thêm responsive design
- [x] Tích hợp vào layout
- [x] Cập nhật hướng dẫn
- [ ] Thêm 4 hình ảnh (cần bạn làm)

---

## 🎉 Kết quả

Sau khi thêm hình ảnh, bạn sẽ có:
- ✅ Section giống hệt thiết kế trong hình
- ✅ Header với 2 ngôi sao vàng
- ✅ 4 banner lớn xếp ngang
- ✅ Background màu kem đẹp mắt
- ✅ Hiệu ứng hover mượt mà
- ✅ Responsive hoàn hảo

---

**Thiết kế mới đã hoàn thành theo đúng yêu cầu!** 🚀
