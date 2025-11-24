<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.4.0/remixicon.css" />
        <link rel="stylesheet" href="/template/admin/style.css" />
        <link rel="stylesheet" href="/template/admin/products.css" />
        <title>Quản lý bài viết - ADMIN</title>
        <style>
            :root {
                --primary-color: #435ebe;
                --bg-page: #f4f6fb;
                --text-muted: #6b7280;
            }

            body { background: var(--bg-page); font-family: 'Nunito', sans-serif; }

            .page-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 16px;
                flex-wrap: wrap;
                margin-bottom: 24px;
            }

            .page-header h1 { margin: 0; font-weight: 700; color: #111827; }

            .stat-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 18px;
                margin-bottom: 24px;
            }

            .stat-card {
                background: #fff;
                border-radius: 18px;
                padding: 16px 18px;
                box-shadow: 0 12px 24px rgba(15,23,42,0.08);
            }

            .stat-card span { color: var(--text-muted); font-size: 13px; }
            .stat-card h3 { margin: 6px 0 0; font-size: 28px; color: var(--primary-color); }

            .btn-primary-action {
                border: none;
                border-radius: 12px;
                padding: 11px 18px;
                color: #fff;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
                background: linear-gradient(135deg,#435ebe,#7786ff);
                box-shadow: 0 12px 20px rgba(67,94,190,0.22);
                text-decoration: none;
            }

            .filter-section { background: #fff; border-radius: 20px; padding: 22px; box-shadow: 0 15px 30px rgba(15,23,42,0.08); margin-bottom: 24px; }
            .filter-row { display: flex; flex-wrap: wrap; gap: 12px; }
            .filter-input, .filter-select {
                flex: 1;
                min-width: 180px;
                padding: 10px 13px;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                font-size: 14px;
            }

            .card { border: none; border-radius: 18px; overflow: hidden; box-shadow: 0 15px 30px rgba(15,23,42,0.1); background: #fff; }
            .card-header { padding: 20px 24px; border-bottom: 1px solid #f0f2f5; display: flex; justify-content: space-between; align-items: center; }
            .card-header h6 { margin: 0; font-weight: 700; }

            .authors-table { width: 100%; border-collapse: collapse; }
            .authors-table th { background: #f9fafb; padding: 14px 18px; font-size: 12px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; }
            .authors-table td { padding: 16px 18px; border-bottom: 1px solid #f1f2f6; font-size: 15px; vertical-align: middle; }

            .article-img {
                width: 60px;
                height: 40px;
                border-radius: 8px;
                object-fit: cover;
                margin-right: 12px;
                border: 1px solid #eef2ff;
                background: #f3f4ff;
            }

            .badge-status { padding: 6px 12px; border-radius: 30px; font-size: 12px; font-weight: 600; }
            .badge-active { background: rgba(34,197,94,0.15); color: #15803d; }
            .badge-inactive { background: rgba(248,113,113,0.15); color: #b91c1c; }

            .action-btn { width: 36px; height: 36px; border-radius: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; margin-right: 4px; cursor: pointer; transition: transform 0.2s ease; text-decoration: none; }
            .action-btn:hover { transform: translateY(-2px); }
            .view-btn { background: rgba(59,130,246,0.15); color: #2563eb; }
            .edit-btn { background: rgba(245,158,11,0.15); color: #d97706; }
            .delete-btn { background: rgba(239,68,68,0.15); color: #dc2626; }

            /* Modal Styles */
            .modal-content { border-radius: 18px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
            .modal-header { border-bottom: 1px solid #f0f2f5; padding: 20px 24px; }
            .modal-title { font-weight: 700; color: #111827; }
            .modal-body { padding: 24px; }
            .modal-footer { border-top: 1px solid #f0f2f5; padding: 20px 24px; }
            
            .form-label { font-weight: 600; color: #374151; margin-bottom: 8px; }
            .form-control, .form-select { padding: 12px 16px; border-radius: 10px; border: 1px solid #e5e7eb; font-size: 15px; }
            .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(67,94,190,0.1); }
            
            .preview-image { width: 100%; height: 200px; object-fit: cover; border-radius: 12px; border: 2px dashed #e5e7eb; display: flex; align-items: center; justify-content: center; background: #f9fafb; color: #9ca3af; margin-top: 10px; overflow: hidden; }
            .preview-image img { width: 100%; height: 100%; object-fit: cover; }
        </style>
    </head>
    <body>
        @php
            $sharedAlerts = collect([
                ['message' => session('status'), 'type' => session('status_type', 'info')],
                ['message' => session('success'), 'type' => 'success'],
                ['message' => session('error'), 'type' => 'error'],
                ['message' => session('warning'), 'type' => 'warning'],
            ])
                ->when($errors->any(), fn($collection) => $collection->merge(
                    collect($errors->all())->map(fn($error) => ['message' => $error, 'type' => 'error'])
                ))
                ->filter(fn ($alert) => filled($alert['message'] ?? null))
                ->values()
                ->all();
        @endphp

        <x-alert-stack :messages="$sharedAlerts" />
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <section id="content">
            <!-- Navbar -->
            @include('admin.partials.navbar')

            <!-- Main -->
            <main style="margin-top: 64px !important">
                <div class="page-header">
                    <div>
                        <h2>Quản lý bài viết</h2>
                        <p class="text-muted mb-0">Quản lý tin tức, bài viết blog</p>
                    </div>
                    <button type="button" class="btn-primary-action" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bx bx-plus"></i> Thêm bài viết
                    </button>
                </div>

                <!-- Stats -->
                <div class="stat-grid">
                    <div class="stat-card">
                        <span>Tổng bài viết</span>
                        <h3>{{ $stats['total'] }}</h3>
                    </div>
                    <div class="stat-card">
                        <span>Đang hiển thị</span>
                        <h3>{{ $stats['active'] }}</h3>
                    </div>
                    <div class="stat-card">
                        <span>Đang ẩn</span>
                        <h3>{{ $stats['inactive'] }}</h3>
                    </div>
                </div>

                <!-- Filter -->
                <div class="filter-section">
                    <form action="{{ route('admin.articles.index') }}" method="GET" class="filter-row">
                        <input type="text" name="search" class="filter-input" placeholder="Tìm kiếm theo tiêu đề..." value="{{ request('search') }}">
                        
                        <select name="category" class="filter-select">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->ID }}" {{ request('category') == $cat->ID ? 'selected' : '' }}>{{ $cat->TenDanhMuc }}</option>
                            @endforeach
                        </select>

                        <select name="status" class="filter-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>

                        <select name="sort_by" class="filter-select">
                            <option value="ID" {{ request('sort_by') == 'ID' ? 'selected' : '' }}>Sắp xếp theo ID</option>
                            <option value="TieuDe" {{ request('sort_by') == 'TieuDe' ? 'selected' : '' }}>Sắp xếp theo Tiêu đề</option>
                            <option value="LuotXem" {{ request('sort_by') == 'LuotXem' ? 'selected' : '' }}>Sắp xếp theo Lượt xem</option>
                            <option value="NgayTao" {{ request('sort_by') == 'NgayTao' ? 'selected' : '' }}>Sắp xếp theo Ngày tạo</option>
                        </select>

                        <select name="sort_direction" class="filter-select">
                            <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                            <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                        </select>

                        <button type="submit" class="btn-primary-action" style="padding: 10px 20px;">
                            <i class="bx bx-filter"></i> Lọc
                        </button>
                        
                        @if(request()->anyFilled(['search', 'status', 'category', 'sort_by']))
                            <a href="{{ route('admin.articles.index') }}" class="btn-secondary-action" style="text-decoration: none; padding: 10px 20px; background: #e5e7eb; color: #374151; box-shadow: none;">
                                <i class="bx bx-x"></i> Xóa lọc
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table -->
                <div class="card">
                    <div class="card-header">
                        <h6>Danh sách bài viết</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="authors-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hình ảnh</th>
                                    <th>Tiêu đề</th>
                                    <th>Danh mục</th>
                                    <th>Người đăng</th>
                                    <th>Lượt xem</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articles as $article)
                                    <tr>
                                        <td>#{{ $article->ID }}</td>
                                        <td>
                                            @if($article->HinhAnh)
                                                <img src="{{ asset($article->HinhAnh) }}" alt="" class="article-img">
                                            @else
                                                <div class="article-img d-flex align-items-center justify-content-center bg-light text-muted">
                                                    <i class="bx bx-image"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ Str::limit($article->TieuDe, 40) }}</div>
                                            <small class="text-muted">{{ Str::limit($article->MoTaNgan, 50) }}</small>
                                        </td>
                                        <td>
                                            @if($article->danhMuc)
                                                <span class="badge bg-light text-dark border">{{ $article->danhMuc->TenDanhMuc }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($article->nguoiDung)
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-semibold">{{ $article->nguoiDung->HoTen }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Unknown</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($article->LuotXem) }}</td>
                                        <td>
                                            @if($article->TrangThai)
                                                <span class="badge-status badge-active">Hiển thị</span>
                                            @else
                                                <span class="badge-status badge-inactive">Ẩn</span>
                                            @endif
                                        </td>
                                        <td>{{ $article->NgayTao ? $article->NgayTao->format('d/m/Y H:i') : '-' }}</td>
                                        <td class="text-end">
                                            <button type="button" class="action-btn view-btn" onclick="viewArticle({{ $article->ID }})" title="Xem chi tiết">
                                                <i class="bx bx-show"></i>
                                            </button>
                                            <button type="button" class="action-btn edit-btn" onclick="editArticle({{ $article->ID }})" title="Chỉnh sửa">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.articles.destroy', $article->ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn delete-btn" title="Xóa">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="bx bx-search fs-1 d-block mb-2"></i>
                                            Không tìm thấy bài viết nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                        <div class="text-muted small">
                            Hiển thị {{ $articles->firstItem() ?? 0 }} - {{ $articles->lastItem() ?? 0 }} trong tổng số {{ $articles->total() }} bài viết
                        </div>
                        <div>
                            {{ $articles->links('pagination.custom') }}
                        </div>
                    </div>
                </div>
            </main>
        </section>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm bài viết mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="TieuDe" required placeholder="Nhập tiêu đề bài viết...">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mô tả ngắn <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="MoTaNgan" rows="3" required placeholder="Tóm tắt nội dung..."></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                        <select class="form-select" name="IDDanhMuc" required>
                                            <option value="">-- Chọn danh mục --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->ID }}">{{ $cat->TenDanhMuc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select class="form-select" name="TrangThai" required>
                                            <option value="1">Hiển thị</option>
                                            <option value="0">Ẩn</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Hình ảnh</label>
                                        <input type="file" class="form-control" name="HinhAnh" accept="image/*" onchange="previewImage(this, 'createPreview')">
                                        <div class="preview-image" id="createPreview">
                                            <i class="bx bx-image fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nội dung chi tiết <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="NoiDung" rows="10" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                            <button type="submit" class="btn btn-primary">Lưu bài viết</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa bài viết</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_TieuDe" name="TieuDe" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mô tả ngắn <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="edit_MoTaNgan" name="MoTaNgan" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                        <select class="form-select" id="edit_IDDanhMuc" name="IDDanhMuc" required>
                                            <option value="">-- Chọn danh mục --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->ID }}">{{ $cat->TenDanhMuc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select class="form-select" id="edit_TrangThai" name="TrangThai" required>
                                            <option value="1">Hiển thị</option>
                                            <option value="0">Ẩn</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Hình ảnh</label>
                                        <input type="file" class="form-control" name="HinhAnh" accept="image/*" onchange="previewImage(this, 'editPreview')">
                                        <div class="preview-image" id="editPreview">
                                            <i class="bx bx-image fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nội dung chi tiết <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="edit_NoiDung" name="NoiDung" rows="10" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Modal -->
        <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chi tiết bài viết</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-12 text-center mb-3">
                                <img id="view_HinhAnh" src="" alt="" style="max-height: 300px; max-width: 100%; border-radius: 12px;">
                            </div>
                            <div class="col-md-12">
                                <h4 id="view_TieuDe" class="fw-bold mb-2"></h4>
                                <div class="d-flex gap-3 text-muted mb-3 small">
                                    <span><i class="bx bx-folder"></i> <span id="view_DanhMuc"></span></span>
                                    <span><i class="bx bx-user"></i> <span id="view_NguoiDung"></span></span>
                                    <span><i class="bx bx-calendar"></i> <span id="view_NgayTao"></span></span>
                                    <span><i class="bx bx-show"></i> <span id="view_LuotXem"></span> lượt xem</span>
                                </div>
                                <div class="p-3 bg-light rounded mb-3">
                                    <strong>Mô tả ngắn:</strong>
                                    <p id="view_MoTaNgan" class="mb-0 mt-1"></p>
                                </div>
                                <div>
                                    <strong>Nội dung:</strong>
                                    <div id="view_NoiDung" class="mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="/template/admin/script.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function previewImage(input, previewId) {
                const preview = document.getElementById(previewId);
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

            function editArticle(id) {
                fetch(`/admin/articles/${id}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('editForm').action = `/admin/articles/${id}`;
                        document.getElementById('edit_TieuDe').value = data.TieuDe;
                        document.getElementById('edit_MoTaNgan').value = data.MoTaNgan;
                        document.getElementById('edit_NoiDung').value = data.NoiDung;
                        document.getElementById('edit_IDDanhMuc').value = data.IDDanhMuc;
                        document.getElementById('edit_TrangThai').value = data.TrangThai ? 1 : 0;
                        
                        const preview = document.getElementById('editPreview');
                        if (data.HinhAnh) {
                            preview.innerHTML = `<img src="/${data.HinhAnh}" alt="Preview">`;
                        } else {
                            preview.innerHTML = '<i class="bx bx-image fs-1"></i>';
                        }
                        
                        new bootstrap.Modal(document.getElementById('editModal')).show();
                    });
            }

            function viewArticle(id) {
                fetch(`/admin/articles/${id}/edit`) // Reusing the same endpoint as it returns JSON
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('view_TieuDe').innerText = data.TieuDe;
                        document.getElementById('view_MoTaNgan').innerText = data.MoTaNgan;
                        document.getElementById('view_NoiDung').innerText = data.NoiDung;
                        document.getElementById('view_DanhMuc').innerText = data.danh_muc ? data.danh_muc.TenDanhMuc : '-';
                        document.getElementById('view_NguoiDung').innerText = data.nguoi_dung ? data.nguoi_dung.HoTen : 'Unknown';
                        document.getElementById('view_LuotXem').innerText = data.LuotXem;
                        document.getElementById('view_NgayTao').innerText = new Date(data.NgayTao).toLocaleDateString('vi-VN');
                        
                        const img = document.getElementById('view_HinhAnh');
                        if (data.HinhAnh) {
                            img.src = '/' + data.HinhAnh;
                            img.style.display = 'inline-block';
                        } else {
                            img.style.display = 'none';
                        }
                        
                        new bootstrap.Modal(document.getElementById('viewModal')).show();
                    });
            }
        </script>
    </body>
</html>
