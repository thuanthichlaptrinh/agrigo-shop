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
        <title>Quản lý voucher - ADMIN</title>
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
            .stat-card h3 { margin: 6px 0 0; font-size: 26px; color: #14b8a6; }

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
            .btn-secondary-action { background: linear-gradient(135deg,#f97316,#fb923c); box-shadow: 0 12px 20px rgba(249,115,22,0.22); }

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

            table { width: 100%; border-collapse: collapse; }
            th { background: #f9fafb; padding: 14px 18px; font-size: 12px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; }
            td { padding: 16px 18px; border-bottom: 1px solid #f1f2f6; font-size: 15px; vertical-align: middle; }

            .badge-status { padding: 6px 12px; border-radius: 30px; font-size: 12px; font-weight: 600; }
            .badge-active { background: rgba(34,197,94,0.15); color: #15803d; }
            .badge-expired { background: rgba(248,113,113,0.15); color: #b91c1c; }
            .badge-out { background: rgba(245,158,11,0.15); color: #b45309; }

            .action-btn { width: 36px; height: 36px; border-radius: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; margin-right: 4px; cursor: pointer; transition: transform 0.2s ease; }
            .action-btn:hover { transform: translateY(-2px); }
            .info-link { background: rgba(59,130,246,0.15); color: #2563eb; }
            .edit-link { background: rgba(250,204,21,0.2); color: #b45309; }
            .delete-link { background: rgba(248,113,113,0.2); color: #b91c1c; }

            .pagination-wrapper { margin-top: 24px; display: flex; justify-content: center; }

            .modal { display: none; position: fixed; inset: 0; z-index: 1200; background: rgba(15,23,42,0.55); backdrop-filter: blur(3px); padding: 40px 20px; overflow-y: auto; }
            .modal.active { display: block; }
            .modal-content { background: #fff; border-radius: 22px; max-width: 720px; margin: auto; box-shadow: 0 35px 55px rgba(15,23,42,0.35); overflow: hidden; animation: modalShow 0.25s ease; }
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
            <main>
                <div class="page-header">
                    <div>
                        <h2>Quản lý Voucher</h2>
                        <p style="color: var(--text-muted); margin-top: 6px;">Quản lý chiến dịch giảm giá và điều kiện áp dụng.</p>
                    </div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn-primary-action" onclick="openCreateModal()">
                            <i class="fa-solid fa-plus"></i> Thêm voucher
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
                        <span>Đang hoạt động</span>
                        <h3>{{ number_format($stats['active'] ?? 0) }}</h3>
                    </div>
                    <div class="stat-card">
                        <span>Đã hết hạn</span>
                        <h3>{{ number_format($stats['expired'] ?? 0) }}</h3>
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
                    <form action="{{ route('admin.vouchers.index') }}" method="GET">
                        <div class="filter-row">
                            <input type="text" name="search" class="filter-input" placeholder="Tìm theo mã voucher..." value="{{ request('search') }}">
                            <select name="type" class="filter-select">
                                <option value="">-- Loại --</option>
                                <option value="Cố định" {{ request('type') === 'Cố định' ? 'selected' : '' }}>Cố định</option>
                                <option value="Phần trăm" {{ request('type') === 'Phần trăm' ? 'selected' : '' }}>Phần trăm</option>
                            </select>
                            <select name="status" class="filter-select">
                                <option value="">-- Trạng thái --</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                <option value="out" {{ request('status') === 'out' ? 'selected' : '' }}>Hết lượt</option>
                            </select>
                            <input type="date" name="valid_to" class="filter-input" value="{{ request('valid_to') }}" placeholder="Hết hạn trước">
                            <select name="sort_by" class="filter-select">
                                <option value="ID" {{ request('sort_by', 'ID') === 'ID' ? 'selected' : '' }}>Theo ID</option>
                                <option value="MaVoucher" {{ request('sort_by') === 'MaVoucher' ? 'selected' : '' }}>Theo mã</option>
                                <option value="GiaTri" {{ request('sort_by') === 'GiaTri' ? 'selected' : '' }}>Theo giá trị</option>
                                <option value="SoLuong" {{ request('sort_by') === 'SoLuong' ? 'selected' : '' }}>Theo số lượng</option>
                                <option value="NgayKetThuc" {{ request('sort_by') === 'NgayKetThuc' ? 'selected' : '' }}>Theo ngày hết hạn</option>
                            </select>
                            <select name="sort_direction" class="filter-select">
                                <option value="desc" {{ request('sort_direction', 'desc') === 'desc' ? 'selected' : '' }}>Giảm dần</option>
                                <option value="asc" {{ request('sort_direction') === 'asc' ? 'selected' : '' }}>Tăng dần</option>
                            </select>
                            <select name="per_page" class="filter-select">
                                @foreach($perPageOptions as $option)
                                    <option value="{{ $option }}" {{ (int)request('per_page', 10) === $option ? 'selected' : '' }}>{{ $option }} / trang</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Lọc</button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light" style="border-radius: 10px;">Đặt lại</a>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6>Danh sách voucher</h6>
                        <small style="color: var(--text-muted);">Hiển thị {{ $vouchers->count() }} / {{ $vouchers->total() }} voucher</small>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã</th>
                                    <th>Loại & Giá trị</th>
                                    <th>Điều kiện</th>
                                    <th>Số lượt</th>
                                    <th>Hết hạn</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vouchers as $voucher)
                                    @php
                                        $isExpired = $voucher->NgayKetThuc && \Carbon\Carbon::parse($voucher->NgayKetThuc)->isPast();
                                        $isOut = $voucher->DaDung >= $voucher->SoLuong;
                                        $statusLabel = $isOut ? 'Hết lượt' : ($isExpired ? 'Hết hạn' : 'Đang chạy');
                                        $statusClass = $isOut ? 'badge-out' : ($isExpired ? 'badge-expired' : 'badge-active');
                                        $expireDate = $voucher->NgayKetThuc ? \Carbon\Carbon::parse($voucher->NgayKetThuc)->format('d/m/Y') : '-';
                                    @endphp
                                    <tr>
                                        <td style="font-weight: 600;">#{{ $voucher->MaVoucher }}</td>
                                        <td>
                                            <div><strong>{{ $voucher->Loai }}</strong></div>
                                            <div>
                                                @if($voucher->Loai === 'Phần trăm')
                                                    Giảm {{ rtrim(rtrim(number_format($voucher->GiaTri, 2, '.', ''), '0'), '.') }}%
                                                @else
                                                    Giảm {{ number_format($voucher->GiaTri) }} đ
                                                @endif
                                            </div>
                                            @if($voucher->GiamToiDa)
                                                <small class="text-muted">Tối đa: {{ number_format($voucher->GiamToiDa) }} đ</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>Đơn tối thiểu: {{ $voucher->DonToiThieu ? number_format($voucher->DonToiThieu) . ' đ' : 'Không' }}</div>
                                        </td>
                                        <td>
                                            <strong>{{ $voucher->DaDung }}/{{ $voucher->SoLuong }}</strong>
                                            <div class="progress" style="height: 6px; margin-top: 6px; background: #e5e7eb;">
                                                @php $usage = $voucher->SoLuong > 0 ? ($voucher->DaDung / $voucher->SoLuong) * 100 : 0; @endphp
                                                <div style="width: {{ min(100, $usage) }}%; background: #0ea5e9; height: 100%; border-radius: 999px;"></div>
                                            </div>
                                        </td>
                                        <td>{{ $expireDate }}</td>
                                        <td><span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                        <td>
                                            <button type="button" class="action-btn info-link" onclick="viewVoucher({{ $voucher->ID }})" title="Xem chi tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button type="button" class="action-btn edit-link" onclick="openEditModal({{ $voucher->ID }})" title="Chỉnh sửa">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button type="button" class="action-btn delete-link" onclick="deleteVoucher({{ $voucher->ID }})" title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align:center; padding: 40px; color: var(--text-muted);">
                                            <i class="fa-solid fa-folder-open" style="font-size: 40px; margin-bottom: 12px;"></i><br>
                                            Không có dữ liệu phù hợp.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($vouchers->hasPages())
                    <div class="pagination-wrapper">
                        {{ $vouchers->links('pagination::rounded') }}
                    </div>
                @endif
            </main>
        </section>

        {{-- Modal thêm voucher --}}
        <div class="modal" id="createModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Thêm voucher</h3>
                    <span class="modal-close" onclick="closeCreateModal()">&times;</span>
                </div>
                <form action="{{ route('admin.vouchers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Mã voucher <span class="text-danger">*</span></label>
                                <input type="text" name="MaVoucher" class="form-control" value="{{ old('MaVoucher') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Loại <span class="text-danger">*</span></label>
                                <select name="Loai" class="form-control" required>
                                    <option value="Cố định">Cố định</option>
                                    <option value="Phần trăm">Phần trăm</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Giá trị <span class="text-danger">*</span></label>
                                <input type="number" name="GiaTri" class="form-control" step="0.01" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Giảm tối đa</label>
                                <input type="number" name="GiamToiDa" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Đơn tối thiểu</label>
                                <input type="number" name="DonToiThieu" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label>Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="SoLuong" class="form-control" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" name="NgayKetThuc" class="form-control" required>
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

        {{-- Modal chỉnh sửa voucher --}}
        <div class="modal" id="editModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Chỉnh sửa voucher</h3>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Mã voucher <span class="text-danger">*</span></label>
                                <input type="text" name="MaVoucher" id="edit_MaVoucher" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Loại <span class="text-danger">*</span></label>
                                <select name="Loai" id="edit_Loai" class="form-control" required>
                                    <option value="Cố định">Cố định</option>
                                    <option value="Phần trăm">Phần trăm</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Giá trị <span class="text-danger">*</span></label>
                                <input type="number" name="GiaTri" id="edit_GiaTri" class="form-control" step="0.01" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Giảm tối đa</label>
                                <input type="number" name="GiamToiDa" id="edit_GiamToiDa" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Đơn tối thiểu</label>
                                <input type="number" name="DonToiThieu" id="edit_DonToiThieu" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label>Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="SoLuong" id="edit_SoLuong" class="form-control" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Đã dùng</label>
                                <input type="number" name="DaDung" id="edit_DaDung" class="form-control" min="0">
                            </div>
                            <div class="form-group">
                                <label>Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" name="NgayKetThuc" id="edit_NgayKetThuc" class="form-control" required>
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

        {{-- Modal thêm nhanh nhiều voucher --}}
        <div class="modal" id="bulkModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Nhập danh sách voucher</h3>
                    <span class="modal-close" onclick="closeBulkModal()">&times;</span>
                </div>
                <form action="{{ route('admin.vouchers.bulk-store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Nhập mỗi dòng một voucher theo định dạng: <strong>MÃ|LOẠI|GIÁ TRỊ|SỐ LƯỢNG|YYYY-MM-DD|ĐƠN TỐI THIỂU|GIẢM TỐI ĐA</strong></p>
                        <p class="text-muted" style="font-size: 14px;">Ví dụ: <code>SALE50|Phần trăm|50|100|2025-12-31|300000|50000</code></p>
                        <textarea name="VoucherData" class="form-control" rows="8" placeholder="SALE10|Cố định|10000|50|2025-05-01|"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" onclick="closeBulkModal()">Hủy</button>
                        <button type="submit" class="btn-submit">Nhập</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal xem chi tiết voucher --}}
        <div class="modal" id="viewModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Chi tiết voucher</h3>
                    <span class="modal-close" onclick="closeViewModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p><strong>Mã:</strong> <span id="view_MaVoucher"></span></p>
                    <p><strong>Loại:</strong> <span id="view_Loai"></span></p>
                    <p><strong>Giá trị:</strong> <span id="view_GiaTri"></span></p>
                    <p><strong>Giảm tối đa:</strong> <span id="view_GiamToiDa"></span></p>
                    <p><strong>Đơn tối thiểu:</strong> <span id="view_DonToiThieu"></span></p>
                    <p><strong>Số lượt:</strong> <span id="view_SoLuong"></span></p>
                    <p><strong>Đã dùng:</strong> <span id="view_DaDung"></span></p>
                    <p><strong>Ngày hết hạn:</strong> <span id="view_NgayKetThuc"></span></p>
                    <p><strong>Số đơn đã áp dụng:</strong> <span id="view_OrderCount"></span></p>
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
                fetch(`/admin/vouchers/${id}`, {
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
                    document.getElementById('edit_MaVoucher').value = data.MaVoucher || '';
                    document.getElementById('edit_Loai').value = data.Loai || 'Cố định';
                    document.getElementById('edit_GiaTri').value = data.GiaTri ?? '';
                    document.getElementById('edit_GiamToiDa').value = data.GiamToiDa ?? '';
                    document.getElementById('edit_DonToiThieu').value = data.DonToiThieu ?? '';
                    document.getElementById('edit_SoLuong').value = data.SoLuong ?? '';
                    document.getElementById('edit_DaDung').value = data.DaDung ?? 0;
                    document.getElementById('edit_NgayKetThuc').value = data.NgayKetThuc || '';
                    document.getElementById('editForm').action = `/admin/vouchers/${id}`;
                    openModal('editModal');
                })
                .catch(() => alert('Không thể tải thông tin voucher.'));
            }

            function viewVoucher(id) {
                fetch(`/admin/vouchers/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('view_MaVoucher').textContent = data.MaVoucher || '-';
                    document.getElementById('view_Loai').textContent = data.Loai || '-';
                    document.getElementById('view_GiaTri').textContent = data.Loai === 'Phần trăm'
                        ? `${data.GiaTri}%`
                        : `${Number(data.GiaTri).toLocaleString('vi-VN')} đ`;
                    document.getElementById('view_GiamToiDa').textContent = data.GiamToiDa ? `${Number(data.GiamToiDa).toLocaleString('vi-VN')} đ` : 'Không';
                    document.getElementById('view_DonToiThieu').textContent = data.DonToiThieu ? `${Number(data.DonToiThieu).toLocaleString('vi-VN')} đ` : 'Không';
                    document.getElementById('view_SoLuong').textContent = data.SoLuong ?? '-';
                    document.getElementById('view_DaDung').textContent = data.DaDung ?? 0;
                    document.getElementById('view_NgayKetThuc').textContent = data.NgayKetThuc || '-';
                    document.getElementById('view_OrderCount').textContent = data.don_hang_count ?? 0;
                    openModal('viewModal');
                })
                .catch(() => alert('Không thể tải thông tin voucher.'));
            }

            function deleteVoucher(id) {
                if (!confirm('Xóa voucher này? Hành động không thể hoàn tác.')) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/vouchers/${id}`;
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
