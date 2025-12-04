<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link
            href="/template/Assets/vendor/boxicons/boxicons.min.css"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            href="/template/Assets/vendor/fontawesome/6.5.2/css/all.min.css"
            integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome 6 Free CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <!-- Boxicons CDN (Backup) -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="/template/admin/style.css" />
        <link rel="stylesheet" href="/template/admin/products.css" />
        <title>Quản lý loại sản phẩm - ADMIN</title>
        <style>
            :root {
                --primary-color: #435ebe;
                --primary-dark: #2d3f8f;
                --bg-page: #f5f7fb;
                --text-muted: #6b7280;
            }

            body { background: var(--bg-page); font-family: 'Nunito', sans-serif; }

            .page-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 16px;
                margin-bottom: 24px;
            }

            .page-header h1 { font-size: 26px; margin: 0; font-weight: 700; color: #111827; }

            .stat-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 18px;
                margin-bottom: 24px;
            }

            .stat-card {
                background: #fff;
                border-radius: 16px;
                padding: 16px 18px;
                box-shadow: 0 12px 20px rgba(15, 23, 42, 0.08);
            }

            .stat-card span { color: var(--text-muted); font-size: 13px; }
            .stat-card h3 { margin: 6px 0 0; font-size: 26px; color: var(--primary-color); }

            .filter-section { background: #fff; border-radius: 18px; padding: 22px; box-shadow: 0 15px 30px rgba(15, 23, 42, 0.08); margin-bottom: 24px; }
            .filter-row { display: flex; flex-wrap: wrap; gap: 12px; }
            .filter-input, .filter-select {
                flex: 1;
                min-width: 180px;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                padding: 10px 13px;
                font-size: 14px;
            }

            .btn-primary-action,
            .btn-secondary-action {
                border: none;
                border-radius: 12px;
                padding: 11px 16px;
                color: #fff;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
                text-decoration: none;
            }

            .btn-primary-action {
                background: linear-gradient(135deg, #435ebe, #7b8dff);
                box-shadow: 0 10px 20px rgba(67, 94, 190, 0.25);
            }

            .btn-secondary-action {
                background: linear-gradient(135deg, #0ea5e9, #38bdf8);
                box-shadow: 0 10px 20px rgba(14, 165, 233, 0.25);
            }

            .card { border: none; border-radius: 18px; overflow: hidden; box-shadow: 0 15px 30px rgba(15, 23, 42, 0.1); background: #fff; }
            .card-header { padding: 20px 24px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; }
            .card-header h6 { margin: 0; font-weight: 700; }

            .authors-table { width: 100%; border-collapse: collapse; }
            .authors-table th { background: #f9fafb; padding: 14px 18px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; }
            .authors-table td { padding: 16px 18px; border-bottom: 1px solid #f0f1f5; font-size: 15px; }

            .badge-status { padding: 6px 12px; border-radius: 30px; font-size: 12px; font-weight: 600; }
            .badge-active { background: rgba(34,197,94,0.12); color: #15803d; }
            .badge-inactive { background: rgba(248,113,113,0.12); color: #b91c1c; }

            .action-btn { width: 36px; height: 36px; border-radius: 10px; border: none; margin-right: 4px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s ease; }
            .action-btn:hover { transform: translateY(-2px); }
            .info-link { background: rgba(59,130,246,0.15); color: #2563eb; }
            .edit-link { background: rgba(250,204,21,0.2); color: #b45309; }
            .delete-link { background: rgba(248,113,113,0.15); color: #b91c1c; }

            .pagination-wrapper {
                margin: 28px 0 0;
                display: flex;
                justify-content: center;
            }

            .pagination-wrapper nav {
                display: inline-flex;
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.1);
                border-radius: 14px;
                padding: 4px;
                background: #fff;
            }

            .pagination-wrapper .pagination { margin: 0; gap: 6px; }

            .pagination-wrapper .page-item:first-child .page-link,
            .pagination-wrapper .page-item:last-child .page-link { border-radius: 10px; }

            .pagination-wrapper .page-link {
                border: 1px solid transparent;
                border-radius: 10px !important;
                padding: 8px 16px;
                color: #435ebe;
                font-weight: 600;
                transition: all 0.2s ease;
            }

            .pagination-wrapper .page-link:hover {
                background: rgba(67, 94, 190, 0.08);
                border-color: rgba(67, 94, 190, 0.2);
                color: #2b3f91;
            }

            .pagination-wrapper .page-item.active .page-link {
                background: linear-gradient(135deg, #435ebe 0%, #6f70f5 100%);
                color: #fff;
                border-color: transparent;
                box-shadow: 0 10px 20px rgba(67, 94, 190, 0.25);
            }

            .pagination-wrapper .page-item.disabled .page-link {
                color: #a0a7c4;
                background: transparent;
            }

            .modal { display: none; position: fixed; inset: 0; z-index: 1200; background: rgba(15,23,42,0.55); backdrop-filter: blur(4px); padding: 40px 20px; overflow-y: auto; }
            .modal.active { display: block; }
            .modal-content { background: #fff; border-radius: 20px; max-width: 640px; margin: auto; box-shadow: 0 35px 50px rgba(15,23,42,0.3); overflow: hidden; animation: modalShow 0.3s ease; }
            .modal-header { padding: 20px 28px; background: linear-gradient(135deg,#435ebe,#6f7be9); color: #fff; display:flex; justify-content: space-between; align-items:center; }
            .modal-body { padding: 22px 28px; }
            .modal-footer { padding: 16px 28px 24px; display: flex; justify-content: flex-end; gap: 12px; }
            .modal-close { font-size: 26px; cursor: pointer; }

            .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; }
            .form-group { margin-bottom: 16px; }
            .form-group label { font-weight: 600; margin-bottom: 6px; display: block; }

            .btn-submit { background: var(--primary-color); color: #fff; border: none; border-radius: 10px; padding: 10px 18px; font-weight: 600; }
            .btn-cancel { background: #e5e7eb; color: #111827; border: none; border-radius: 10px; padding: 10px 18px; font-weight: 600; }

            @keyframes modalShow { from { opacity: 0; transform: translateY(-15px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body>
        <!-- SIDEBAR -->
        @include('admin.partials.sidebar')
        <!-- SIDEBAR -->

        <!-- SIDEBAR -->
        
        <!-- NAVBAR -->
        <section id="content">
            <!-- NAVBAR -->
            @include('admin.partials.navbar')
            <!-- NAVBAR -->

            <!-- MAIN -->
            <main style="margin-top: 64px;">
                <div class="page-header">
                    <div>
                        <h2>Quản lý loại sản phẩm</h2>
                        <p style="color: var(--text-muted); margin-top: 6px;">Theo dõi và cập nhật nhóm sản phẩm theo danh mục.</p>
                    </div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn-primary-action" onclick="openCreateModal()">
                            <i class="fa-solid fa-plus"></i> Thêm 1 loại
                        </button>
                        <button type="button" class="btn-secondary-action" onclick="openBulkModal()">
                            <i class="fa-solid fa-layer-group"></i> Thêm nhiều
                        </button>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-card">
                        <span>Tổng số loại</span>
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
                    <form action="{{ route('admin.categories.index') }}" method="GET">
                        <div class="filter-row">
                            <input type="text" name="search" class="filter-input" placeholder="Tìm kiếm theo tên..." value="{{ request('search') }}">
                            <select name="parent" class="filter-select">
                                <option value="">-- Tất cả danh mục --</option>
                                @foreach($danhMucs as $parent)
                                    <option value="{{ $parent->ID }}" {{ (string)request('parent') === (string)$parent->ID ? 'selected' : '' }}>
                                        {{ $parent->TenDanhMuc }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="status" class="filter-select">
                                <option value="">-- Trạng thái --</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tạm khóa</option>
                            </select>
                            <select name="sort_by" class="filter-select">
                                <option value="ID" {{ request('sort_by', 'ID') === 'ID' ? 'selected' : '' }}>Theo ID</option>
                                <option value="TenLoai" {{ request('sort_by') === 'TenLoai' ? 'selected' : '' }}>Theo tên</option>
                                <option value="san_pham_count" {{ request('sort_by') === 'san_pham_count' ? 'selected' : '' }}>Theo số sản phẩm</option>
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
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light" style="border-radius: 10px;">Đặt lại</a>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6>Danh sách loại sản phẩm ({{ $productTypes->total() }})</h6>
                    </div>
                    <div class="table-wrapper">
                        <table class="authors-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên loại</th>
                                    <th>Danh mục gốc</th>
                                    <th>Số sản phẩm</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productTypes as $index => $type)
                                    <tr>
                                        <td>{{ $productTypes->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $type->TenLoai }}</strong><br>
                                            <small style="color: var(--text-muted);">Mã: #{{ $type->ID }}</small>
                                        </td>
                                        <td>{{ $type->danhMuc->TenDanhMuc ?? 'Chưa gán' }}</td>
                                        <td>{{ $type->san_pham_count }}</td>
                                        <td>
                                            @if($type->TrangThai)
                                                <span class="badge-status badge-active">Hoạt động</span>
                                            @else
                                                <span class="badge-status badge-inactive">Tạm khóa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="action-btn info-link" onclick="viewType({{ $type->ID }})" title="Xem chi tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button type="button" class="action-btn edit-link" onclick="openEditModal({{ $type->ID }})" title="Chỉnh sửa">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button type="button" class="action-btn delete-link" onclick="deleteType({{ $type->ID }})" title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                            <i class="fa-solid fa-folder-open" style="font-size: 40px; margin-bottom: 12px;"></i><br>
                                            Không có dữ liệu phù hợp.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($productTypes->hasPages())
                    <div class="pagination-wrapper">
                        {{ $productTypes->links('vendor.pagination.admin-users') }}
                    </div>
                @endif
            </main>
        </section>

        {{-- Modal Thêm 1 loại --}}
        <div class="modal" id="createModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Thêm loại sản phẩm</h3>
                    <span class="modal-close" onclick="closeCreateModal()">&times;</span>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên loại <span class="text-danger">*</span></label>
                            <input type="text" name="TenLoai" class="form-control" value="{{ old('TenLoai') }}" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Danh mục gốc <span class="text-danger">*</span></label>
                                <select name="IDDanhMuc" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($danhMucs as $parent)
                                        <option value="{{ $parent->ID }}" {{ old('IDDanhMuc') == $parent->ID ? 'selected' : '' }}>
                                            {{ $parent->TenDanhMuc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select name="TrangThai" class="form-select">
                                    <option value="1" {{ old('TrangThai', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ old('TrangThai') == '0' ? 'selected' : '' }}>Tạm khóa</option>
                                </select>
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
                    <h3>Thêm nhiều loại sản phẩm</h3>
                    <span class="modal-close" onclick="closeBulkModal()">&times;</span>
                </div>
                <form action="{{ route('admin.categories.bulk-store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Danh mục gốc <span class="text-danger">*</span></label>
                                <select name="IDDanhMuc" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($danhMucs as $parent)
                                        <option value="{{ $parent->ID }}" {{ old('IDDanhMuc') == $parent->ID ? 'selected' : '' }}>
                                            {{ $parent->TenDanhMuc }}
                                        </option>
                                    @endforeach
                                </select>
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
                            <label>Danh sách tên loại <span class="text-danger">*</span></label>
                            <textarea name="TenLoaiList" class="form-control" placeholder="Nhập mỗi tên trên một dòng" required>{{ old('TenLoaiList') }}</textarea>
                            <small style="color: var(--text-muted);">Hệ thống sẽ bỏ qua tên trùng lặp trong danh mục đã chọn.</small>
                        </div>
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
                    <h3>Cập nhật loại sản phẩm</h3>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên loại <span class="text-danger">*</span></label>
                            <input type="text" name="TenLoai" id="edit_TenLoai" class="form-control" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Danh mục gốc <span class="text-danger">*</span></label>
                                <select name="IDDanhMuc" id="edit_IDDanhMuc" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($danhMucs as $parent)
                                        <option value="{{ $parent->ID }}">{{ $parent->TenDanhMuc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select name="TrangThai" id="edit_TrangThai" class="form-select">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Tạm khóa</option>
                                </select>
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
                    <h3>Thông tin loại sản phẩm</h3>
                    <span class="modal-close" onclick="closeViewModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="detail-row" style="margin-bottom: 12px;">
                        <strong>Tên loại:</strong>
                        <p id="view_TenLoai" style="margin: 4px 0 0;"></p>
                    </div>
                    <div class="detail-row" style="margin-bottom: 12px;">
                        <strong>Danh mục:</strong>
                        <p id="view_DanhMuc" style="margin: 4px 0 0;"></p>
                    </div>
                    <div class="detail-row" style="margin-bottom: 12px;">
                        <strong>Trạng thái:</strong>
                        <p id="view_TrangThai" style="margin: 4px 0 0;"></p>
                    </div>
                    <div class="detail-row" style="margin-bottom: 12px;">
                        <strong>Số sản phẩm:</strong>
                        <p id="view_SoLuong" style="margin: 4px 0 0;"></p>
                    </div>
                    <div class="detail-row" style="margin-bottom: 12px;">
                        <strong>Mã loại:</strong>
                        <p id="view_ID" style="margin: 4px 0 0;"></p>
                    </div>
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
            }

            function openCreateModal() { openModal('createModal'); }
            function closeCreateModal() { closeModal('createModal'); }
            function openBulkModal() { openModal('bulkModal'); }
            function closeBulkModal() { closeModal('bulkModal'); }

            function openEditModal(id) {
                fetch(`/admin/categories/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Lỗi tải dữ liệu');
                    return response.json();
                })
                .then(data => {
                    document.getElementById('edit_TenLoai').value = data.TenLoai || '';
                    document.getElementById('edit_IDDanhMuc').value = data.IDDanhMuc || '';
                    document.getElementById('edit_TrangThai').value = data.TrangThai ?? '1';
                    document.getElementById('editForm').action = `/admin/categories/${id}`;
                    openModal('editModal');
                })
                .catch(() => alert('Không thể tải thông tin loại sản phẩm.'));
            }

            function closeEditModal() { closeModal('editModal'); }

            function viewType(id) {
                fetch(`/admin/categories/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('view_TenLoai').textContent = data.TenLoai || '-';
                    document.getElementById('view_DanhMuc').textContent = data.danh_muc || 'Chưa gán';
                    document.getElementById('view_TrangThai').textContent = data.TrangThai == 1 ? 'Hoạt động' : 'Tạm khóa';
                    document.getElementById('view_SoLuong').textContent = data.san_pham_count ?? 0;
                    document.getElementById('view_ID').textContent = data.ID ? `#${data.ID}` : '-';
                    openModal('viewModal');
                })
                .catch(() => alert('Không thể tải thông tin loại sản phẩm.'));
            }

            function closeViewModal() { closeModal('viewModal'); }

            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this.id);
                    }
                });
            });

            function deleteType(id) {
                if (!confirm('Bạn có chắc chắn muốn xóa loại sản phẩm này?')) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/categories/${id}`;
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
        </script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="/template/admin/script.js"></script>
    </body>
</html>
