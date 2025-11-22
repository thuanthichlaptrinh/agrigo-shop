<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="/template/admin/style.css" />
        <link rel="stylesheet" href="/template/admin/products.css" />
        <title>Quản lý danh mục - ADMIN</title>
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

            .btn-primary-action,
            .btn-secondary-action {
                border: none;
                border-radius: 12px;
                padding: 11px 18px;
                color: #fff;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
            }

            .btn-primary-action { background: linear-gradient(135deg,#435ebe,#7786ff); box-shadow: 0 12px 20px rgba(67,94,190,0.22); }
            .btn-secondary-action { background: linear-gradient(135deg,#0ea5e9,#38bdf8); box-shadow: 0 12px 20px rgba(14,165,233,0.25); }

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

            .category-avatar {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                object-fit: cover;
                margin-right: 12px;
                border: 1px solid #eef2ff;
                background: #f3f4ff;
            }

            .badge-status { padding: 6px 12px; border-radius: 30px; font-size: 12px; font-weight: 600; }
            .badge-active { background: rgba(34,197,94,0.15); color: #15803d; }
            .badge-inactive { background: rgba(248,113,113,0.15); color: #b91c1c; }

            .action-btn { width: 36px; height: 36px; border-radius: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; margin-right: 4px; cursor: pointer; transition: transform 0.2s ease; }
            .action-btn:hover { transform: translateY(-2px); }
            .info-link { background: rgba(59,130,246,0.15); color: #2563eb; }
            .edit-link { background: rgba(250,204,21,0.2); color: #b45309; }
            .delete-link { background: rgba(248,113,113,0.2); color: #b91c1c; }

            .pagination-wrapper { margin-top: 24px; display: flex; justify-content: center; }

            .modal { display: none; position: fixed; inset: 0; z-index: 1200; background: rgba(15,23,42,0.55); backdrop-filter: blur(3px); padding: 40px 20px; overflow-y: auto; }
            .modal.active { display: block; }
            .modal-content { background: #fff; border-radius: 22px; max-width: 680px; margin: auto; box-shadow: 0 35px 55px rgba(15,23,42,0.35); overflow: hidden; animation: modalShow 0.25s ease; }
            .modal-header { padding: 20px 28px; background: linear-gradient(135deg,#435ebe,#6f7de8); color: #fff; display: flex; justify-content: space-between; align-items: center; }
            .modal-body { padding: 22px 28px; }
            .modal-footer { padding: 16px 28px 24px; display: flex; justify-content: flex-end; gap: 12px; }
            .modal-close { font-size: 26px; cursor: pointer; }

            .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; }
            .form-group { margin-bottom: 16px; }
            .form-group label { font-weight: 600; display: block; margin-bottom: 6px; }

            .avatar-upload { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
            .avatar-preview { width: 90px; height: 90px; border-radius: 16px; object-fit: cover; border: 2px dashed #d4d7fb; background: #f4f6ff; }
            .upload-label { position: relative; overflow: hidden; border-radius: 10px; background: var(--primary-color); color: #fff; padding: 10px 16px; font-weight: 600; cursor: pointer; }
            .upload-label input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }

            .btn-submit { background: var(--primary-color); color: #fff; border: none; border-radius: 10px; padding: 10px 18px; font-weight: 600; }
            .btn-cancel { background: #e5e7eb; border: none; border-radius: 10px; padding: 10px 18px; font-weight: 600; }

            @keyframes modalShow { from { opacity: 0; transform: translateY(-15px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body>
        @include('admin.partials.sidebar')
        <section id="content">
            @include('admin.partials.navbar')
            <main style="margin-top: 64px;">
                <div class="page-header">
                    <div>
                        <h3>Quản lý Danh mục sản phẩm</h3>
                        <p style="color: var(--text-muted); margin-top: 6px;">Quản lý danh mục gốc và hình ảnh hiển thị.</p>
                    </div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn-primary-action" onclick="openCreateModal()">
                            <i class="fa-solid fa-plus"></i> Thêm 1 danh mục
                        </button>
                        <button type="button" class="btn-secondary-action" onclick="openBulkModal()">
                            <i class="fa-solid fa-layer-group"></i> Thêm nhiều
                        </button>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-card">
                        <span>Tổng số danh mục</span>
                        <h3>{{ number_format($stats['total'] ?? 0) }}</h3>
                    </div>
                    <div class="stat-card">
                        <span>Đang hoạt động</span>
                        <h3>{{ number_format($stats['active'] ?? 0) }}</h3>
                    </div>
                    <div class="stat-card">
                        <span>Tạm khóa</span>
                        <h3>{{ number_format($stats['inactive'] ?? 0) }}</h3>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Đã xảy ra lỗi:</strong>
                        <ul style="margin: 10px 0 0 18px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('skipped'))
                    <div class="alert alert-warning">
                        <strong>Tên bị bỏ qua:</strong> {{ implode(', ', session('skipped')) }}
                    </div>
                @endif

                <div class="filter-section">
                    <form action="{{ route('admin.catalog.index') }}" method="GET">
                        <div class="filter-row">
                            <input type="text" name="search" class="filter-input" placeholder="Tìm kiếm theo tên..." value="{{ request('search') }}">
                            <select name="status" class="filter-select">
                                <option value="">-- Trạng thái --</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tạm khóa</option>
                            </select>
                            <select name="sort_by" class="filter-select">
                                <option value="ID" {{ request('sort_by', 'ID') === 'ID' ? 'selected' : '' }}>Theo ID</option>
                                <option value="TenDanhMuc" {{ request('sort_by') === 'TenDanhMuc' ? 'selected' : '' }}>Theo tên</option>
                                <option value="ThuTu" {{ request('sort_by') === 'ThuTu' ? 'selected' : '' }}>Theo thứ tự</option>
                                <option value="loai_san_pham_count" {{ request('sort_by') === 'loai_san_pham_count' ? 'selected' : '' }}>Theo số loại</option>
                            </select>
                            <select name="sort_direction" class="filter-select">
                                <option value="desc" {{ request('sort_direction', 'desc') === 'desc' ? 'selected' : '' }}>Giảm dần</option>
                                <option value="asc" {{ request('sort_direction') === 'asc' ? 'selected' : '' }}>Tăng dần</option>
                            </select>
                            <select name="per_page" class="filter-select">
                                @foreach($perPageOptions as $option)
                                    <option value="{{ $option }}" {{ (int)request('per_page', 10) === $option ? 'selected' : '' }}>Hiển thị {{ $option }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-primary-action" style="padding: 10px 16px;">
                                <i class="fa-solid fa-filter"></i> Lọc
                            </button>
                            <a href="{{ route('admin.catalog.index') }}" class="btn btn-light" style="border-radius: 10px;">Đặt lại</a>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6>Danh sách danh mục ({{ $categories->total() }})</h6>
                    </div>
                    <div class="table-wrapper">
                        <table class="authors-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Danh mục</th>
                                    <th>Thứ tự</th>
                                    <th>Số loại sản phẩm</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                    <tr>
                                        <td>{{ $categories->firstItem() + $index }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center;">
                                                <img src="{{ $category->HinhAnh ? asset($category->HinhAnh) : asset('template/Assets/Images/default-avatar.png') }}" alt="{{ $category->TenDanhMuc }}" class="category-avatar" />
                                                <div>
                                                    <strong>{{ $category->TenDanhMuc }}</strong><br>
                                                    <small style="color: var(--text-muted);">Mã: #{{ $category->ID }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $category->ThuTu ?? '-' }}</td>
                                        <td>{{ $category->loai_san_pham_count }}</td>
                                        <td>
                                            @if($category->TrangThai)
                                                <span class="badge-status badge-active">Hoạt động</span>
                                            @else
                                                <span class="badge-status badge-inactive">Tạm khóa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="action-btn info-link" onclick="viewCategory({{ $category->ID }})" title="Xem chi tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button type="button" class="action-btn edit-link" onclick="openEditModal({{ $category->ID }})" title="Chỉnh sửa">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button type="button" class="action-btn delete-link" onclick="deleteCategory({{ $category->ID }})" title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align:center; padding: 40px; color: var(--text-muted);">
                                            <i class="fa-solid fa-folder-open" style="font-size: 40px; margin-bottom: 12px;"></i><br>
                                            Không có dữ liệu phù hợp.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($categories->hasPages())
                    <div class="pagination-wrapper">
                        {{ $categories->links('pagination::rounded') }}
                    </div>
                @endif
            </main>
        </section>

        {{-- Modal thêm 1 danh mục --}}
        <div class="modal" id="createModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Thêm danh mục</h3>
                    <span class="modal-close" onclick="closeCreateModal()">&times;</span>
                </div>
                <form action="{{ route('admin.catalog.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="TenDanhMuc" class="form-control" value="{{ old('TenDanhMuc') }}" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Thứ tự hiển thị</label>
                                <input type="number" name="ThuTu" class="form-control" value="{{ old('ThuTu') }}" min="0">
                            </div>
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select name="TrangThai" class="form-select">
                                    <option value="1" {{ old('TrangThai', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ old('TrangThai') == '0' ? 'selected' : '' }}>Tạm khóa</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Hình ảnh</label>
                            @php
                                $defaultCategory = asset('template/Assets/Images/default-avatar.png');
                            @endphp
                            <div class="avatar-upload">
                                <img src="{{ $defaultCategory }}" id="create_preview" class="avatar-preview" data-default="{{ $defaultCategory }}" alt="Preview">
                                <label class="upload-label">
                                    <i class="fa-solid fa-upload"></i> Chọn hình
                                    <input type="file" name="HinhAnh" id="create_input" accept="image/*">
                                </label>
                                <small class="text-muted">Hỗ trợ JPG, PNG, tối đa 2MB.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" onclick="closeCreateModal()">Hủy</button>
                        <button type="submit" class="btn-submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal thêm nhiều --}}
        <div class="modal" id="bulkModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Thêm nhiều danh mục</h3>
                    <span class="modal-close" onclick="closeBulkModal()">&times;</span>
                </div>
                <form action="{{ route('admin.catalog.bulk-store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Trạng thái mặc định</label>
                            <select name="TrangThai" class="form-select">
                                <option value="1" {{ old('TrangThai', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('TrangThai') == '0' ? 'selected' : '' }}>Tạm khóa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Danh sách tên danh mục <span class="text-danger">*</span></label>
                            <textarea name="TenDanhMucList" class="form-control" placeholder="Nhập mỗi tên trên một dòng" rows="6" required>{{ old('TenDanhMucList') }}</textarea>
                        </div>
                        <small class="text-muted">Hệ thống sẽ tự bỏ qua tên đã tồn tại.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" onclick="closeBulkModal()">Hủy</button>
                        <button type="submit" class="btn-submit">Thêm danh sách</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal chỉnh sửa --}}
        <div class="modal" id="editModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Cập nhật danh mục</h3>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="TenDanhMuc" id="edit_TenDanhMuc" class="form-control" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Thứ tự</label>
                                <input type="number" name="ThuTu" id="edit_ThuTu" class="form-control" min="0">
                            </div>
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select name="TrangThai" id="edit_TrangThai" class="form-select">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Tạm khóa</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Hình ảnh</label>
                            <div class="avatar-upload">
                                <img src="{{ $defaultCategory }}" id="edit_preview" class="avatar-preview" data-default="{{ $defaultCategory }}" alt="Preview">
                                <label class="upload-label">
                                    <i class="fa-solid fa-upload"></i> Chọn hình mới
                                    <input type="file" name="HinhAnh" id="edit_input" accept="image/*">
                                </label>
                                <small class="text-muted">Để trống nếu giữ hình cũ.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" onclick="closeEditModal()">Hủy</button>
                        <button type="submit" class="btn-submit">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal xem chi tiết --}}
        <div class="modal" id="viewModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Thông tin danh mục</h3>
                    <span class="modal-close" onclick="closeViewModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <div style="text-align:center; margin-bottom: 18px;">
                        <img src="{{ $defaultCategory }}" id="view_Image" class="avatar-preview" style="border-radius: 50%; width: 110px; height: 110px;" alt="Category" data-default="{{ $defaultCategory }}">
                    </div>
                    <p><strong>Tên danh mục:</strong> <span id="view_TenDanhMuc"></span></p>
                    <p><strong>Thứ tự:</strong> <span id="view_ThuTu"></span></p>
                    <p><strong>Trạng thái:</strong> <span id="view_TrangThai"></span></p>
                    <p><strong>Số loại sản phẩm:</strong> <span id="view_SoLoai"></span></p>
                    <p><strong>Mã danh mục:</strong> <span id="view_ID"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeViewModal()">Đóng</button>
                </div>
            </div>
        </div>

        <script>
            function openModal(id) {
                const modal = document.getElementById(id);
                if (!modal) return;
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeModal(id) {
                const modal = document.getElementById(id);
                if (!modal) return;
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
                const form = modal.querySelector('form');
                if (form) form.reset();
                modal.querySelectorAll('.avatar-preview').forEach(img => {
                    if (img.dataset.default) {
                        img.src = img.dataset.default;
                    }
                });
            }

            function openCreateModal() { openModal('createModal'); }
            function closeCreateModal() { closeModal('createModal'); }
            function openBulkModal() { openModal('bulkModal'); }
            function closeBulkModal() { closeModal('bulkModal'); }
            function closeEditModal() { closeModal('editModal'); }
            function closeViewModal() { closeModal('viewModal'); }

            function openEditModal(id) {
                fetch(`/admin/catalog/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Không thể tải dữ liệu');
                    return res.json();
                })
                .then(data => {
                    document.getElementById('edit_TenDanhMuc').value = data.TenDanhMuc || '';
                    document.getElementById('edit_ThuTu').value = data.ThuTu ?? '';
                    document.getElementById('edit_TrangThai').value = data.TrangThai ?? '1';
                    const preview = document.getElementById('edit_preview');
                    preview.src = data.HinhAnh || preview.dataset.default;
                    document.getElementById('editForm').action = `/admin/catalog/${id}`;
                    openModal('editModal');
                })
                .catch(() => alert('Không thể tải thông tin danh mục.'));
            }

            function viewCategory(id) {
                fetch(`/admin/catalog/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('view_TenDanhMuc').textContent = data.TenDanhMuc || '-';
                    document.getElementById('view_ThuTu').textContent = data.ThuTu ?? '-';
                    document.getElementById('view_TrangThai').textContent = data.TrangThai == 1 ? 'Hoạt động' : 'Tạm khóa';
                    document.getElementById('view_SoLoai').textContent = data.loai_san_pham_count ?? 0;
                    document.getElementById('view_ID').textContent = data.ID ? `#${data.ID}` : '-';
                    const preview = document.getElementById('view_Image');
                    preview.src = data.HinhAnh || preview.dataset.default;
                    openModal('viewModal');
                })
                .catch(() => alert('Không thể tải thông tin danh mục.'));
            }

            function deleteCategory(id) {
                if (!confirm('Xóa danh mục này? Hành động không thể hoàn tác.')) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/catalog/${id}`;
                form.style.display = 'none';

                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                form.appendChild(token);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            }

            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this.id);
                    }
                });
            });

            function bindPreview(inputId, previewId) {
                const input = document.getElementById(inputId);
                if (!input) return;
                input.addEventListener('change', function() {
                    const preview = document.getElementById(previewId);
                    if (!preview) return;
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = e => preview.src = e.target.result;
                        reader.readAsDataURL(this.files[0]);
                    } else if (preview.dataset.default) {
                        preview.src = preview.dataset.default;
                    }
                });
            }

            bindPreview('create_input', 'create_preview');
            bindPreview('edit_input', 'edit_preview');
        </script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="/template/admin/script.js"></script>
    </body>
</html>
