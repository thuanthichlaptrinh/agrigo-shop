<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý khuyến mãi - ADMIN</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome 6 Free CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <!-- Boxicons CDN (Backup) -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="/template/admin/style.css">
        <link rel="stylesheet" href="/template/admin/products.css">
        <style>
            :root { --primary-color:#0ea5e9; --bg-page:#f5f7fb; --text-muted:#6b7280; }
            body { background: var(--bg-page); font-family: 'Nunito', sans-serif; }
            .page-header { display:flex; justify-content:space-between; align-items: center; gap:16px; flex-wrap:wrap; margin-bottom:24px; }
            .stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px; margin-bottom:24px; }
            .stat-card { background:#fff; border-radius:20px; padding:16px 18px; box-shadow:0 12px 24px rgba(15,23,42,.08); }
            .stat-card span { font-size:13px; color:var(--text-muted); }
            .stat-card h3 { margin:6px 0 0; font-size:26px; color:#10b981; }
            .filter-section { background:#fff; border-radius:20px; padding:20px; box-shadow:0 12px 32px rgba(15,23,42,.08); margin-bottom:24px; }
            .filter-row { display:flex; flex-wrap:wrap; gap:12px; }
            .filter-input, .filter-select { flex:1; min-width:170px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; }
            .page-header .btn-primary-action { border:none; border-radius:12px; padding:11px 18px; font-weight:600; display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#0ea5e9,#38bdf8); color:#fff; box-shadow:0 12px 20px rgba(14,165,233,.22); transition:transform .2s ease, box-shadow .2s ease; }
            .page-header .btn-primary-action:hover { transform:translateY(-1px); box-shadow:0 16px 26px rgba(14,165,233,.3); }
            table { width:100%; border-collapse:collapse; }
            th { background:#f8fafc; padding:14px 16px; font-size:12px; text-transform:uppercase; color:#94a3b8; letter-spacing:.5px; }
            td { padding:16px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
            .badge-status { padding:6px 12px; border-radius:30px; font-size:12px; font-weight:600; }
            .badge-active { background:rgba(16,185,129,.15); color:#0f766e; }
            .badge-upcoming { background:rgba(59,130,246,.15); color:#1d4ed8; }
            .badge-expired { background:rgba(244,63,94,.15); color:#be123c; }
            .badge-inactive { background:rgba(148,163,184,.25); color:#475569; }
            .action-btn { width:36px; height:36px; border:none; border-radius:10px; margin-right:4px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; }
            .action-btn.info { background:rgba(59,130,246,.15); color:#1d4ed8; }
            .action-btn.edit { background:rgba(250,204,21,.2); color:#a16207; }
            .action-btn.delete { background:rgba(248,113,113,.2); color:#b91c1c; }
            .pagination-wrapper { margin-top:20px; display:flex; justify-content:center; }
            .modal { display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:1200; padding:40px 20px; overflow-y:auto; }
            .modal.active { display:block; }
            .modal-content { background:#fff; border-radius:24px; max-width:780px; margin:auto; box-shadow:0 30px 50px rgba(15,23,42,.3); overflow:hidden; }
            .modal-header { background:linear-gradient(135deg,#0ea5e9,#38bdf8); color:#fff; padding:20px 28px; display:flex; justify-content:space-between; align-items:center; }
            .modal-body { padding:24px 28px; }
            .modal-footer { padding:0 28px 24px; display:flex; justify-content:flex-end; gap:10px; padding-top: 20px !important; }
            .modal-close { font-size:26px; cursor:pointer; }
            .form-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
        </style>
    </head>
    <body>
        @include('admin.partials.sidebar')
        <section id="content">
            @include('admin.partials.navbar')
            <main>
                <div class="page-header">
                    <div>
                        <h2>Quản lý Khuyến mãi</h2>
                        <p style="color:var(--text-muted); margin-top:6px;">Theo dõi và cấu hình các chương trình giảm giá.</p>
                    </div>
                    <div>
                        <button class="btn-primary-action" onclick="openCreateModal()"><i class="fa-solid fa-plus"></i> Thêm khuyến mãi</button>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-card"><span>Tổng số</span><h3>{{ number_format($stats['total'] ?? 0) }}</h3></div>
                    <div class="stat-card"><span>Đang diễn ra</span><h3>{{ number_format($stats['active'] ?? 0) }}</h3></div>
                    <div class="stat-card"><span>Sắp diễn ra</span><h3>{{ number_format($stats['upcoming'] ?? 0) }}</h3></div>
                    <div class="stat-card"><span>Đã kết thúc</span><h3>{{ number_format($stats['expired'] ?? 0) }}</h3></div>
                </div>

                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                @if($errors->any())
                    <div class="alert alert-danger"><strong>Đã xảy ra lỗi:</strong><ul class="mt-2 mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <div class="filter-section">
                    <form method="GET" action="{{ route('admin.promotions.index') }}">
                        <div class="filter-row">
                            <input type="text" name="search" class="filter-input" placeholder="Tìm theo tên, mô tả..." value="{{ request('search') }}">
                            <select name="type" class="filter-select">
                                <option value="">-- Loại --</option>
                                <option value="Tiền mặt" {{ request('type')==='Tiền mặt' ? 'selected' : '' }}>Cố định (tiền)</option>
                                <option value="Phần trăm" {{ request('type')==='Phần trăm' ? 'selected' : '' }}>Phần trăm</option>
                            </select>
                            <select name="status" class="filter-select">
                                <option value="">-- Trạng thái --</option>
                                <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>Đang chạy</option>
                                <option value="upcoming" {{ request('status')==='upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                                <option value="expired" {{ request('status')==='expired' ? 'selected' : '' }}>Đã kết thúc</option>
                                <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>Tạm khóa</option>
                            </select>
                            <input type="date" name="start_from" class="filter-input" value="{{ request('start_from') }}" placeholder="Bắt đầu từ">
                            <input type="date" name="end_to" class="filter-input" value="{{ request('end_to') }}" placeholder="Kết thúc đến">
                            <select name="sort_by" class="filter-select">
                                <option value="ID" {{ request('sort_by','ID')==='ID' ? 'selected' : '' }}>Theo ID</option>
                                <option value="TenKhuyenMai" {{ request('sort_by')==='TenKhuyenMai' ? 'selected' : '' }}>Theo tên</option>
                                <option value="NgayBatDau" {{ request('sort_by')==='NgayBatDau' ? 'selected' : '' }}>Ngày bắt đầu</option>
                                <option value="NgayKetThuc" {{ request('sort_by')==='NgayKetThuc' ? 'selected' : '' }}>Ngày kết thúc</option>
                            </select>
                            <select name="sort_direction" class="filter-select">
                                <option value="desc" {{ request('sort_direction','desc')==='desc' ? 'selected' : '' }}>Giảm dần</option>
                                <option value="asc" {{ request('sort_direction')==='asc' ? 'selected' : '' }}>Tăng dần</option>
                            </select>
                            <select name="per_page" class="filter-select">
                                @foreach($perPageOptions as $option)
                                    <option value="{{ $option }}" {{ (int)request('per_page',10)===$option ? 'selected' : '' }}>{{ $option }} / trang</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary" style="border-radius:10px;">Lọc</button>
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-light" style="border-radius:10px;">Đặt lại</a>
                        </div>
                    </form>
                </div>

                <div class="card" style="border:none; border-radius:20px; box-shadow:0 12px 32px rgba(15,23,42,.08);">
                    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; background:#fff; border-bottom:1px solid #f1f5f9;">
                        <h6 class="mb-0">Danh sách khuyến mãi</h6>
                        <small>Hiển thị {{ $promotions->count() }} / {{ $promotions->total() }} chương trình</small>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tên chương trình</th>
                                    <th>Loại & Giá trị</th>
                                    <th>Thời gian</th>
                                    <th>Số sản phẩm</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promotions as $promo)
                                    @php
                                        $now = now();
                                        $isActive = $promo->TrangThai && $promo->NgayBatDau <= $now && $promo->NgayKetThuc >= $now;
                                        $isUpcoming = $promo->TrangThai && $promo->NgayBatDau > $now;
                                        $isExpired = $promo->NgayKetThuc < $now;
                                        $badge = $isActive ? 'badge-active' : ($isUpcoming ? 'badge-upcoming' : ($isExpired ? 'badge-expired' : 'badge-inactive'));
                                        $label = $isActive ? 'Đang chạy' : ($isUpcoming ? 'Sắp diễn ra' : ($isExpired ? 'Đã kết thúc' : 'Tạm khóa'));
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $promo->TenKhuyenMai }}</strong>
                                            <div style="color:var(--text-muted); font-size:13px;">Mã: #{{ $promo->ID }}</div>
                                        </td>
                                        <td>
                                            <div><strong>{{ $promo->LoaiKhuyenMai }}</strong></div>
                                            <div>
                                                @if($promo->LoaiKhuyenMai === 'Phần trăm')
                                                    Giảm {{ rtrim(rtrim(number_format($promo->GiaTriGiam, 2, '.', ''), '0'), '.') }}%
                                                @else
                                                    Giảm {{ number_format($promo->GiaTriGiam) }} đ
                                                @endif
                                            </div>
                                            @if($promo->GiamToiDa)
                                                <small class="text-muted">Tối đa {{ number_format($promo->GiamToiDa) }} đ</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>Bắt đầu: {{ optional($promo->NgayBatDau)->format('d/m/Y H:i') }}</div>
                                            <div>Kết thúc: {{ optional($promo->NgayKetThuc)->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td>{{ number_format($promo->san_pham_count) }}</td>
                                        <td><span class="badge-status {{ $badge }}">{{ $label }}</span></td>
                                        <td>
                                            <button class="action-btn info" onclick="viewPromotion({{ $promo->ID }})" title="Chi tiết"><i class="fa-solid fa-eye"></i></button>
                                            <button class="action-btn edit" onclick="openEditModal({{ $promo->ID }})" title="Chỉnh sửa"><i class="fa-solid fa-pen"></i></button>
                                            <button class="action-btn delete" onclick="deletePromotion({{ $promo->ID }})" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted);">Không có dữ liệu phù hợp.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($promotions->hasPages())
                    <div class="pagination-wrapper">
                        {{ $promotions->links('pagination::rounded') }}
                    </div>
                @endif
            </main>
        </section>

        {{-- Modal tạo --}}
        <div class="modal" id="createModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Thêm khuyến mãi</h3>
                    <span class="modal-close" onclick="closeCreateModal()">&times;</span>
                </div>
                <form method="POST" action="{{ route('admin.promotions.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="mb-3">
                                <label class="form-label">Tên chương trình *</label>
                                <input type="text" name="TenKhuyenMai" class="form-control" value="{{ old('TenKhuyenMai') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại *</label>
                                <select name="LoaiKhuyenMai" class="form-control" required>
                                    <option value="Tiền mặt">Cố định (tiền)</option>
                                    <option value="Phần trăm">Phần trăm</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="mb-3">
                                <label class="form-label">Giá trị giảm *</label>
                                <input type="number" name="GiaTriGiam" class="form-control" min="1" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giảm tối đa</label>
                                <input type="number" name="GiamToiDa" class="form-control" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="mb-3">
                                <label class="form-label">Ngày bắt đầu *</label>
                                <input type="datetime-local" name="NgayBatDau" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày kết thúc *</label>
                                <input type="datetime-local" name="NgayKetThuc" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái *</label>
                                <select name="TrangThai" class="form-control" required>
                                    <option value="1">Kích hoạt</option>
                                    <option value="0">Tạm khóa</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="MoTa" class="form-control" rows="3">{{ old('MoTa') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" onclick="closeCreateModal()">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal chỉnh sửa --}}
        <div class="modal" id="editModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Chỉnh sửa khuyến mãi</h3>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>
                <form method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="mb-3">
                                <label class="form-label">Tên chương trình *</label>
                                <input type="text" name="TenKhuyenMai" id="edit_TenKhuyenMai" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại *</label>
                                <select name="LoaiKhuyenMai" id="edit_LoaiKhuyenMai" class="form-control" required>
                                    <option value="Tiền mặt">Cố định (tiền)</option>
                                    <option value="Phần trăm">Phần trăm</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="mb-3">
                                <label class="form-label">Giá trị giảm *</label>
                                <input type="number" name="GiaTriGiam" id="edit_GiaTriGiam" class="form-control" min="1" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giảm tối đa</label>
                                <input type="number" name="GiamToiDa" id="edit_GiamToiDa" class="form-control" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="mb-3">
                                <label class="form-label">Ngày bắt đầu *</label>
                                <input type="datetime-local" name="NgayBatDau" id="edit_NgayBatDau" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày kết thúc *</label>
                                <input type="datetime-local" name="NgayKetThuc" id="edit_NgayKetThuc" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái *</label>
                                <select name="TrangThai" id="edit_TrangThai" class="form-control" required>
                                    <option value="1">Kích hoạt</option>
                                    <option value="0">Tạm khóa</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="MoTa" id="edit_MoTa" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" onclick="closeEditModal()">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal xem --}}
        <div class="modal" id="viewModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Chi tiết khuyến mãi</h3>
                    <span class="modal-close" onclick="closeViewModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p><strong>Tên:</strong> <span id="view_Ten"></span></p>
                    <p><strong>Mô tả:</strong> <span id="view_MoTa"></span></p>
                    <p><strong>Loại:</strong> <span id="view_Loai"></span></p>
                    <p><strong>Giá trị:</strong> <span id="view_GiaTri"></span></p>
                    <p><strong>Giảm tối đa:</strong> <span id="view_GiamToiDa"></span></p>
                    <p><strong>Thời gian:</strong> <span id="view_ThoiGian"></span></p>
                    <p><strong>Số sản phẩm áp dụng:</strong> <span id="view_SoSP"></span></p>
                    <p><strong>Trạng thái:</strong> <span id="view_TrangThai"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="closeViewModal()">Đóng</button>
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
            function openEditModal(id) {
                fetch(`/admin/promotions/${id}`, { headers: { 'Accept': 'application/json' }})
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('edit_TenKhuyenMai').value = data.TenKhuyenMai || '';
                        document.getElementById('edit_LoaiKhuyenMai').value = data.LoaiKhuyenMai || 'Tiền mặt';
                        document.getElementById('edit_GiaTriGiam').value = data.GiaTriGiam ?? '';
                        document.getElementById('edit_GiamToiDa').value = data.GiamToiDa ?? '';
                        document.getElementById('edit_NgayBatDau').value = data.NgayBatDau ? data.NgayBatDau.replace(' ', 'T').slice(0,16) : '';
                        document.getElementById('edit_NgayKetThuc').value = data.NgayKetThuc ? data.NgayKetThuc.replace(' ', 'T').slice(0,16) : '';
                        document.getElementById('edit_TrangThai').value = data.TrangThai ? '1' : '0';
                        document.getElementById('edit_MoTa').value = data.MoTa ?? '';
                        document.getElementById('editForm').action = `/admin/promotions/${id}`;
                        openModal('editModal');
                    })
                    .catch(() => alert('Không thể tải dữ liệu khuyến mãi.'));
            }
            function closeEditModal() { closeModal('editModal'); }
            function viewPromotion(id) {
                fetch(`/admin/promotions/${id}`, { headers: { 'Accept': 'application/json' }})
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('view_Ten').textContent = data.TenKhuyenMai || '';
                        document.getElementById('view_MoTa').textContent = data.MoTa || '-';
                        document.getElementById('view_Loai').textContent = data.LoaiKhuyenMai || '-';
                        document.getElementById('view_GiaTri').textContent = data.GiaTriGiam ?? '-';
                        document.getElementById('view_GiamToiDa').textContent = data.GiamToiDa ?? 'Không';
                        document.getElementById('view_ThoiGian').textContent = `${data.NgayBatDau ?? '-'} → ${data.NgayKetThuc ?? '-'}`;
                        document.getElementById('view_SoSP').textContent = data.san_pham_count ?? 0;
                        document.getElementById('view_TrangThai').textContent = data.TrangThai ? 'Đang bật' : 'Tạm khóa';
                        openModal('viewModal');
                    })
                    .catch(() => alert('Không thể tải dữ liệu khuyến mãi.'));
            }
            function closeViewModal() { closeModal('viewModal'); }
            function deletePromotion(id) {
                if (!confirm('Xóa khuyến mãi này?')) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/promotions/${id}`;
                form.style.display = 'none';
                form.innerHTML = `@csrf<input type="hidden" name="_method" value="DELETE">`;
                document.body.appendChild(form);
                form.submit();
            }
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', e => { if (e.target === modal) closeModal(modal.id); });
            });
        </script>
        <script src="/template/admin/script.js"></script>
    </body>
</html>
