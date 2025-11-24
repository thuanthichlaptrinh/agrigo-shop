<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/template/admin/style.css">
    <title>Thêm bài viết mới - ADMIN</title>
    <style>
        :root {
            --primary-color: #435ebe;
            --bg-page: #f4f6fb;
        }
        body { background: var(--bg-page); font-family: 'Nunito', sans-serif; }
        
        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-weight: 700; color: #111827; margin: 0; }
        
        .card { border: none; border-radius: 18px; box-shadow: 0 15px 30px rgba(15,23,42,0.1); background: #fff; overflow: hidden; }
        .card-body { padding: 30px; }
        
        .form-label { font-weight: 600; color: #374151; margin-bottom: 8px; }
        .form-control, .form-select {
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67,94,190,0.1);
        }
        
        .btn-submit {
            background: linear-gradient(135deg,#435ebe,#7786ff);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            color: #fff;
            font-weight: 600;
            box-shadow: 0 12px 20px rgba(67,94,190,0.22);
            transition: all 0.3s ease;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 15px 25px rgba(67,94,190,0.3); color: #fff; }
        
        .btn-cancel {
            background: #f3f4f6;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            color: #4b5563;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
        }
        .btn-cancel:hover { background: #e5e7eb; color: #374151; }

        .preview-image {
            width: 100%;
            max-width: 300px;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px dashed #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            color: #9ca3af;
            margin-top: 10px;
            overflow: hidden;
        }
        .preview-image img { width: 100%; height: 100%; object-fit: cover; }
    </style>
</head>
<body>
    @include('admin.partials.sidebar')

    <section id="content">
        @include('admin.partials.navbar')

        <main>
            <div class="page-header">
                <h1>Thêm bài viết mới</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mt-2">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}" class="text-decoration-none">Bài viết</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                    </ol>
                </nav>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="TieuDe" class="form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('TieuDe') is-invalid @enderror" id="TieuDe" name="TieuDe" value="{{ old('TieuDe') }}" placeholder="Nhập tiêu đề bài viết...">
                                            @error('TieuDe')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="MoTaNgan" class="form-label">Mô tả ngắn <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('MoTaNgan') is-invalid @enderror" id="MoTaNgan" name="MoTaNgan" rows="3" placeholder="Tóm tắt nội dung bài viết...">{{ old('MoTaNgan') }}</textarea>
                                            @error('MoTaNgan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="IDDanhMuc" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                            <select class="form-select @error('IDDanhMuc') is-invalid @enderror" id="IDDanhMuc" name="IDDanhMuc">
                                                <option value="">-- Chọn danh mục --</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->ID }}" {{ old('IDDanhMuc') == $cat->ID ? 'selected' : '' }}>{{ $cat->TenDanhMuc }}</option>
                                                @endforeach
                                            </select>
                                            @error('IDDanhMuc')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="TrangThai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                            <select class="form-select @error('TrangThai') is-invalid @enderror" id="TrangThai" name="TrangThai">
                                                <option value="1" {{ old('TrangThai') == '1' ? 'selected' : '' }}>Hiển thị</option>
                                                <option value="0" {{ old('TrangThai') == '0' ? 'selected' : '' }}>Ẩn</option>
                                            </select>
                                            @error('TrangThai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="HinhAnh" class="form-label">Hình ảnh đại diện</label>
                                            <input type="file" class="form-control @error('HinhAnh') is-invalid @enderror" id="HinhAnh" name="HinhAnh" accept="image/*" onchange="previewImage(this)">
                                            <div class="preview-image" id="imagePreview">
                                                <i class="bx bx-image fs-1"></i>
                                            </div>
                                            @error('HinhAnh')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="NoiDung" class="form-label">Nội dung chi tiết <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('NoiDung') is-invalid @enderror" id="NoiDung" name="NoiDung" rows="10">{{ old('NoiDung') }}</textarea>
                                    @error('NoiDung')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('admin.articles.index') }}" class="btn-cancel">Hủy bỏ</a>
                                    <button type="submit" class="btn-submit">
                                        <i class="bx bx-save me-2"></i> Lưu bài viết
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <script src="/template/admin/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '<i class="bx bx-image fs-1"></i>';
            }
        }
    </script>
</body>
</html>
