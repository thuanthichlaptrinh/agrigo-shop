# 📰 Hướng dẫn phần "Bài viết" (Articles Section)

## ✅ Đã hoàn thành

Tôi đã thêm phần "Bài viết" vào trang chủ (`home.blade.php`) nằm trên footer với layout đẹp mắt.

### 📦 Các file đã tạo/chỉnh sửa:

1. **resources/views/home.blade.php** - Thêm section bài viết
2. **resources/views/layouts/app.blade.php** - Thêm CSS link
3. **public/template/Assets/css/articles-section.css** - CSS cho section

---

## 🎨 Tính năng đã thêm

### Layout 2 cột:

**Cột 1 (50% - Bài viết lớn):**
- 1 bài viết lớn với grid hình ảnh 2x3
- Hình ảnh xếp theo grid:
  - Hàng 1: 3 ô nhỏ
  - Hàng 2: 1 ô nhỏ + 1 ô lớn (chiếm 2 cột)
- Tiêu đề và thời gian bên dưới

**Cột 2 (50% - 4 bài viết nhỏ):**
- 2 bài viết ngang (hình bên trái, text bên phải)
- 2 bài viết dọc nhỏ (hình trên, text dưới)

### Hiệu ứng:
- ✅ Hover: Card nổi lên
- ✅ Hover: Hình ảnh zoom nhẹ
- ✅ Hover: Tiêu đề đổi màu xanh
- ✅ Shadow tăng khi hover

---

## 📸 Hình ảnh cần thêm

Bạn cần thêm **10 hình ảnh** vào thư mục: `public/template/Assets/Images/`

### Bài viết 1 - Cá song biển (6 hình):
| File | Mô tả | Kích thước |
|------|-------|------------|
| `article-fish-1.jpg` | Cá song biển 1 | 200x140px |
| `article-fish-2.jpg` | Cá song biển 2 | 200x140px |
| `article-fish-3.jpg` | Cá song biển 3 | 200x140px |
| `article-fish-4.jpg` | Cá song biển 4 | 200x140px |
| `article-fish-5.jpg` | Cá song biển 5 (lớn) | 400x140px |

### Bài viết khác (4 hình):
| File | Mô tả | Kích thước |
|------|-------|------------|
| `article-goldfish.jpg` | Cá vàng dưới công | 180x130px |
| `article-cream.jpg` | Kem lạnh Carslam | 180x130px |
| `article-jelly.jpg` | Thạch rau câu | 200x140px |
| `article-movie.jpg` | Review phim | 200x140px |

---

## 💻 Cấu trúc Code

### HTML Structure:
```html
<div class="row">
    <!-- Cột 1: Bài viết lớn -->
    <div class="col-lg-6">
        <div class="article-card">
            <!-- Grid 2x3 hình ảnh -->
            <div class="article-images" style="display: grid">
                <div>Hình 1</div>
                <div>Hình 2</div>
                <div>Hình 3</div>
                <div>Hình 4</div>
                <div>Hình 5 (lớn)</div>
            </div>
            <!-- Tiêu đề -->
            <div>
                <h5>Tiêu đề bài viết</h5>
                <p>2 giờ trước</p>
            </div>
        </div>
    </div>
    
    <!-- Cột 2: 4 bài viết nhỏ -->
    <div class="col-lg-6">
        <!-- 2 bài ngang -->
        <div class="article-card-small">
            <img /> + <div>Text</div>
        </div>
        
        <!-- 2 bài dọc -->
        <div class="col-6">
            <div class="article-card-mini">
                <img />
                <div>Text</div>
            </div>
        </div>
    </div>
</div>
```

### CSS Classes:
- `.article-card` - Card bài viết lớn
- `.article-card-small` - Card bài viết ngang
- `.article-card-mini` - Card bài viết dọc nhỏ
- `.article-images` - Grid hình ảnh

---

## 🔧 Tùy chỉnh

### Thay đổi số bài viết:
```html
<!-- Thêm bài viết mới -->
<div class="col-12">
    <div class="article-card-small">
        <!-- Nội dung -->
    </div>
</div>
```

### Thay đổi layout grid hình:
```css
/* File: home.blade.php */
grid-template-columns: repeat(3, 1fr); /* 3 cột */
grid-template-rows: repeat(2, 1fr);    /* 2 hàng */
```

### Thay đổi màu hover:
```css
/* File: articles-section.css */
.article-card a:hover h5 {
    color: #007e42 !important; /* Màu xanh */
}
```

---

## 📱 Responsive

### Desktop (>= 992px):
- 2 cột: 50% - 50%
- Grid hình: 2x3
- Bài ngang: hình trái, text phải

### Tablet (768px - 991px):
- 2 cột: 50% - 50%
- Grid hình: 2x3 (nhỏ hơn)
- Bài ngang: hình nhỏ hơn

### Mobile (< 768px):
- 1 cột: 100%
- Grid hình: 2x3 (chiều cao 300px)
- Bài ngang: hình trên, text dưới

---

## 🎯 Nội dung bài viết

### Bài 1: Cá song biển
- **Tiêu đề**: "Cá song biển có phải cá mú không? Các loại cá song biển phổ biến"
- **Thời gian**: 2 giờ trước
- **Hình**: 6 hình (grid 2x3)

### Bài 2: Cá vàng dưới công
- **Tiêu đề**: "Cá vàng dưới công là gì? Đặc điểm của cá vàng dưới công"
- **Thời gian**: 2 giờ trước
- **Layout**: Ngang (hình trái, text phải)

### Bài 3: Kem lạnh Carslam
- **Tiêu đề**: "Kem lạnh Carslam: Review, công dụng, cách sử dụng"
- **Thời gian**: 2 giờ trước
- **Layout**: Ngang (hình trái, text phải)

### Bài 4: Thạch rau câu
- **Tiêu đề**: "Tham khảo 2 cách làm dâu dứa dưỡng lỗ chỉ lỗ Tết Ôn ngon mát"
- **Thời gian**: 7 giờ trước
- **Layout**: Dọc (hình trên, text dưới)

### Bài 5: Review phim
- **Tiêu đề**: "Review phim Cách Em 1 Milimet – Phim Việt VTV đang hot"
- **Thời gian**: 7 giờ trước
- **Layout**: Dọc (hình trên, text dưới)

---

## 🚀 Test

### Kiểm tra hiển thị:
1. Mở trang chủ: `http://localhost:8000/`
2. Scroll xuống phần "Bài viết" (trên footer)
3. Kiểm tra:
   - ✅ Layout 2 cột đều nhau
   - ✅ Grid hình 2x3 hiển thị đúng
   - ✅ 4 bài viết nhỏ xếp đẹp
   - ✅ Hover effect hoạt động
   - ✅ Responsive trên mobile

### Nếu hình không hiển thị:
```bash
# Kiểm tra đường dẫn
ls public/template/Assets/Images/article-*.jpg

# Hoặc dùng hình placeholder tạm
# Thay đổi trong home.blade.php:
src="https://via.placeholder.com/200x140/4a90e2/ffffff?text=Article"
```

---

## 🎨 Màu sắc sử dụng

| Màu | Hex Code | Sử dụng |
|-----|----------|---------|
| Xanh | `#007e42` | Hover tiêu đề |
| Xám | `#333` | Tiêu đề |
| Xám nhạt | `#6c757d` | Thời gian |
| Trắng | `#ffffff` | Background card |

---

## 🔗 Liên kết với Database

Để lấy dữ liệu từ database, bạn có thể tạo bảng `BaiViet`:

### 1. Migration:
```php
Schema::create('bai_viet', function (Blueprint $table) {
    $table->id();
    $table->string('tieu_de');
    $table->text('noi_dung');
    $table->string('hinh_anh');
    $table->string('slug')->unique();
    $table->integer('luot_xem')->default(0);
    $table->boolean('trang_thai')->default(true);
    $table->timestamps();
});
```

### 2. Controller:
```php
public function index() {
    $articles = BaiViet::where('trang_thai', true)
                       ->orderBy('created_at', 'desc')
                       ->limit(5)
                       ->get();
    
    return view('home', compact('articles'));
}
```

### 3. View:
```blade
@foreach($articles as $article)
<div class="article-card-small">
    <img src="{{ asset($article->hinh_anh) }}" />
    <div>
        <h6>{{ $article->tieu_de }}</h6>
        <p>{{ $article->created_at->diffForHumans() }}</p>
    </div>
</div>
@endforeach
```

---

## ✅ Checklist hoàn thành

- [x] Tạo HTML structure (2 cột)
- [x] Tạo CSS với hover effects
- [x] Grid hình ảnh 2x3
- [x] 4 bài viết nhỏ
- [x] Responsive design
- [x] Tích hợp vào layout
- [x] Tạo hướng dẫn
- [ ] Thêm 10 hình ảnh (cần bạn làm)
- [ ] Kết nối database (tùy chọn)

---

## 🎉 Kết quả

Sau khi thêm hình ảnh, bạn sẽ có:
- ✅ Section bài viết đẹp mắt
- ✅ Layout 2 cột cân đối
- ✅ Grid hình ảnh độc đáo
- ✅ Hiệu ứng hover mượt mà
- ✅ Responsive hoàn hảo
- ✅ Sẵn sàng kết nối database

---

**Phần bài viết đã hoàn thành!** 🚀
