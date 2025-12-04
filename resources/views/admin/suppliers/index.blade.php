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
        <title>Quản lý nhà cung cấp - ADMIN</title>
        <style>
            :root {
                --primary-color: #0ea5e9;
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
            .stat-card h3 { margin: 6px 0 0; font-size: 26px; color: var(--primary-color); }

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

            .btn-primary-action { background: linear-gradient(135deg,#0ea5e9,#38bdf8); box-shadow: 0 12px 20px rgba(14,165,233,0.22); }
            .btn-secondary-action { background: linear-gradient(135deg,#6366f1,#8b5cf6); box-shadow: 0 12px 20px rgba(99,102,241,0.22); }

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

            .suppliers-table { width: 100%; border-collapse: collapse; }
            .suppliers-table th { background: #f9fafb; padding: 14px 18px; font-size: 12px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; }
            .suppliers-table td { padding: 16px 18px; border-bottom: 1px solid #f1f2f6; font-size: 15px; vertical-align: middle; }

            .supplier-chip {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                background: #e0f2fe;
                color: #0c4a6e;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 18px;
                margin-right: 12px;
            }

            .badge-status { padding: 6px 12px; border-radius: 30px; font-size: 12px; font-weight: 600; }
            .badge-with { background: rgba(34,197,94,0.15); color: #15803d; }
            .badge-without { background: rgba(248,113,113,0.15); color: #b91c1c; }

            .action-btn { width: 36px; height: 36px; border-radius: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; margin-right: 4px; cursor: pointer; transition: transform 0.2s ease; }
            .action-btn:hover { transform: translateY(-2px); }
            .info-link { background: rgba(59,130,246,0.15); color: #2563eb; }
            .edit-link { background: rgba(250,204,21,0.2); color: #b45309; }
            .delete-link { background: rgba(248,113,113,0.2); color: #b91c1c; }

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

            .modal { display: none; position: fixed; inset: 0; z-index: 1200; background: rgba(15,23,42,0.55); backdrop-filter: blur(3px); padding: 40px 20px; overflow-y: auto; }
            .modal.active { display: block; }
            .modal-content { background: #fff; border-radius: 22px; max-width: 650px; margin: auto; box-shadow: 0 35px 55px rgba(15,23,42,0.35); overflow: hidden; animation: modalShow 0.25s ease; }
            .modal-header { padding: 20px 28px; background: linear-gradient(135deg,#0ea5e9,#38bdf8); color: #fff; display: flex; justify-content: space-between; align-items: center; }
            .modal-body { padding: 22px 28px; }
            .modal-footer { padding: 16px 28px 24px; display: flex; justify-content: flex-end; gap: 12px; }
            .modal-close { font-size: 26px; cursor: pointer; }

            .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; }
            .form-group { margin-bottom: 16px; }
            .form-group label { font-weight: 600; display: block; margin-bottom: 6px; }

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
                        <h2>Quản lý nhà cung cấp</h2>
                        <p style="color: var(--text-muted); margin-top: 6px;">Quản lý thông tin liên hệ và sản phẩm liên kết.</p>
                    </div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn-primary-action" onclick="openCreateModal()">
                            <i class="fa-solid fa-plus"></i> Thêm 1 nhà cung cấp
                        </button>
                        <button type="button" class="btn-secondary-action" onclick="openBulkModal()">
                            <i class="fa-solid fa-layer-group"></i> Thêm nhiều
                        </button>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-card">
                        <span>Tổng số</span>
                        <h3>{{ number_format($stats['total'] ?? 0) }}</h3>
                    </div>
                    <div class="stat-card">
                        <span>Đang cấp sản phẩm</span>
                        <h3>{{ number_format($stats['with_products'] ?? 0) }}</h3>
                    </div>
                    <div class="stat-card">
                        <span>Chưa có sản phẩm</span>
                        <h3>{{ number_format($stats['without_products'] ?? 0) }}</h3>
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
                        <strong>Dòng bị bỏ qua:</strong> {{ implode(', ', session('skipped')) }}
                    </div>
                @endif

                <div class="filter-section">
                    <form action="{{ route('admin.suppliers.index') }}" method="GET">
                        <div class="filter-row">
                            <input type="text" name="search" class="filter-input" placeholder="Tìm theo tên, SĐT, email..." value="{{ request('search') }}">
                            <select name="product_status" class="filter-select">
                                <option value="">-- Sản phẩm liên kết --</option>
                                <option value="with" {{ request('product_status') === 'with' ? 'selected' : '' }}>Có sản phẩm</option>
                                <option value="without" {{ request('product_status') === 'without' ? 'selected' : '' }}>Chưa có</option>
                            </select>
                            <select name="sort_by" class="filter-select">
                                <option value="ID" {{ request('sort_by', 'ID') === 'ID' ? 'selected' : '' }}>Theo ID</option>
                                <option value="TenNhaCungCap" {{ request('sort_by') === 'TenNhaCungCap' ? 'selected' : '' }}>Theo tên</option>
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
                            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-light" style="border-radius: 10px;">Đặt lại</a>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6>Danh sách nhà cung cấp ({{ $suppliers->total() }})</h6>
                    </div>
                    <div class="table-wrapper">
                        <table class="suppliers-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Nhà cung cấp</th>
                                    <th>Liên hệ</th>
                                    <th>Địa chỉ</th>
                                    <th>Số sản phẩm</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $index => $supplier)
                                    <tr>
                                        <td>{{ $suppliers->firstItem() + $index }}</td>
                                        <td>
                                            <div style="display:flex; align-items:center;">
                                                <div class="supplier-chip">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($supplier->TenNhaCungCap, 0, 1)) }}</div>
                                                <div>
                                                    <strong>{{ $supplier->TenNhaCungCap }}</strong><br>
                                                    <small style="color: var(--text-muted);">Mã: #{{ $supplier->ID }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div><i class="fa-solid fa-phone"></i> {{ $supplier->SDT ?? 'Chưa có' }}</div>
                                            <div><i class="fa-solid fa-envelope"></i> {{ $supplier->Email ?? 'Chưa có' }}</div>
                                        </td>
                                        <td>{{ $supplier->DiaChi ?? '-' }}</td>
                                        <td>
                                            @if($supplier->san_pham_count > 0)
                                                <span class="badge-status badge-with">{{ $supplier->san_pham_count }} sản phẩm</span>
                                            @else
                                                <span class="badge-status badge-without">Chưa có</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="action-btn info-link" onclick="viewSupplier({{ $supplier->ID }})" title="Xem chi tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button type="button" class="action-btn edit-link" onclick="openEditModal({{ $supplier->ID }})" title="Chỉnh sửa">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button type="button" class="action-btn delete-link" onclick="deleteSupplier({{ $supplier->ID }})" title="Xóa">
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

                @if($suppliers->hasPages())
                    <div class="pagination-wrapper">
                        {{ $suppliers->links('vendor.pagination.admin-users') }}
                    </div>
                @endif
            </main>
        </section>

        {{-- Modal thêm 1 nhà cung cấp --}}
        <div class="modal" id="createModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Thêm nhà cung cấp</h3>
                    <span class="modal-close" onclick="closeCreateModal()">&times;</span>
                </div>
                <form action="{{ route('admin.suppliers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên nhà cung cấp <span class="text-danger">*</span></label>
                            <input type="text" name="TenNhaCungCap" class="form-control" value="{{ old('TenNhaCungCap') }}" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" name="SDT" class="form-control" value="{{ old('SDT') }}">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="Email" class="form-control" value="{{ old('Email') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <textarea name="DiaChi" class="form-control" rows="2">{{ old('DiaChi') }}</textarea>
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
                    <h3>Thêm nhiều nhà cung cấp</h3>
                    <span class="modal-close" onclick="closeBulkModal()">&times;</span>
                </div>
                <form action="{{ route('admin.suppliers.bulk-store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Danh sách (mỗi dòng 1 nhà cung cấp)</label>
                            <textarea name="SuppliersData" class="form-control" rows="8" placeholder="Tên | SĐT | Email | Địa chỉ" required>{{ old('SuppliersData') }}</textarea>
                        </div>
                        <small class="text-muted">Định dạng: Tên | SĐT | Email | Địa chỉ. Các trường sau có thể bỏ trống.</small>
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
                    <h3>Cập nhật nhà cung cấp</h3>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên nhà cung cấp <span class="text-danger">*</span></label>
                            <input type="text" name="TenNhaCungCap" id="edit_TenNhaCungCap" class="form-control" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" name="SDT" id="edit_SDT" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="Email" id="edit_Email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <textarea name="DiaChi" id="edit_DiaChi" class="form-control" rows="2"></textarea>
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
                    <h3>Thông tin nhà cung cấp</h3>
                    <span class="modal-close" onclick="closeViewModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <div style="text-align:center; margin-bottom: 18px;">
                        <div class="supplier-chip" style="margin: auto; font-size: 24px;" id="view_Initial">N</div>
                        <h4 id="view_TenNhaCungCap" style="margin-top: 10px;"></h4>
                    </div>
                    <p><strong>Mã:</strong> <span id="view_ID"></span></p>
                    <p><strong>Số điện thoại:</strong> <span id="view_SDT"></span></p>
                    <p><strong>Email:</strong> <span id="view_Email"></span></p>
                    <p><strong>Địa chỉ:</strong> <span id="view_DiaChi"></span></p>
                    <p><strong>Số sản phẩm:</strong> <span id="view_SoSP"></span></p>
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
            function closeEditModal() { closeModal('editModal'); }
            function closeViewModal() { closeModal('viewModal'); }

            function openEditModal(id) {
                fetch(`/admin/suppliers/${id}`, {
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
                    document.getElementById('edit_TenNhaCungCap').value = data.TenNhaCungCap || '';
                    document.getElementById('edit_SDT').value = data.SDT ?? '';
                    document.getElementById('edit_Email').value = data.Email ?? '';
                    document.getElementById('edit_DiaChi').value = data.DiaChi ?? '';
                    document.getElementById('editForm').action = `/admin/suppliers/${id}`;
                    openModal('editModal');
                })
                .catch(() => alert('Không thể tải thông tin nhà cung cấp.'));
            }

            function viewSupplier(id) {
                fetch(`/admin/suppliers/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('view_TenNhaCungCap').textContent = data.TenNhaCungCap || '-';
                    document.getElementById('view_Initial').textContent = (data.TenNhaCungCap || 'N').charAt(0).toUpperCase();
                    document.getElementById('view_ID').textContent = data.ID ? `#${data.ID}` : '-';
                    document.getElementById('view_SDT').textContent = data.SDT || 'Chưa có';
                    document.getElementById('view_Email').textContent = data.Email || 'Chưa có';
                    document.getElementById('view_DiaChi').textContent = data.DiaChi || '-';
                    document.getElementById('view_SoSP').textContent = data.san_pham_count ?? 0;
                    openModal('viewModal');
                })
                .catch(() => alert('Không thể tải thông tin nhà cung cấp.'));
            }

            function deleteSupplier(id) {
                if (!confirm('Xóa nhà cung cấp này? Hành động không thể hoàn tác.')) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/suppliers/${id}`;
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
        </script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="/template/admin/script.js"></script>
    </body>
</html>
