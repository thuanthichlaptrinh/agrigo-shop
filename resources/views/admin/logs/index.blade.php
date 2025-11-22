@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý nhật ký - ADMIN</title>
        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome 6 Free CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <!-- Boxicons CDN (Backup) -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link rel="stylesheet" href="/template/admin/style.css">
        <style>
            :root { --primary:#2563eb; --muted:#6b7280; }
            body { background:#f6f7fb; font-family:'Nunito',sans-serif; }
            .page-header { display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:24px; }
            .stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:24px; }
            .stat-card { background:#fff; border-radius:18px; padding:16px; box-shadow:0 14px 26px rgba(15,23,42,.08); }
            .stat-card span { color:var(--muted); font-size:13px; }
            .stat-card h3 { margin:6px 0 0; color:var(--primary); }
            .filter-section { background:#fff; border-radius:18px; padding:16px 18px; box-shadow:0 12px 24px rgba(15,23,42,.07); margin-bottom:24px; }
            .filter-row { display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; }
            .filter-row .filter-control { flex:1 1 180px; border:1px solid #e5e7eb; border-radius:10px; padding:8px 12px; min-height:42px; }
            .filter-actions { display:flex; gap:10px; flex-wrap:wrap; }
            .filter-actions .btn { border-radius:10px; padding:9px 18px; }
            table { width:100%; border-collapse:collapse; }
            th { padding:14px 16px; background:#f8fafc; text-transform:uppercase; font-size:12px; color:#94a3b8; }
            td { padding:16px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
            tr:hover { background:#fefefe; }
            .badge-success { background:rgba(16,185,129,.15); color:#0f766e; border-radius:999px; padding:6px 14px; font-size:12px; }
            .badge-danger { background:rgba(248,113,113,.15); color:#b91c1c; border-radius:999px; padding:6px 14px; font-size:12px; }
            .action-btn { width:34px; height:34px; border:none; border-radius:10px; margin-right:4px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; }
            .action-btn.info { background:rgba(59,130,246,.15); color:#1d4ed8; }
            .action-btn.edit { background:rgba(250,204,21,.2); color:#a16207; }
            .action-btn.delete { background:rgba(248,113,113,.2); color:#b91c1c; }
            .table-card { border:none; border-radius:20px; box-shadow:0 18px 35px rgba(15,23,42,.08); background:#fff; }
            .modal { display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:1200; padding:40px 20px; overflow-y:auto; }
            .modal.active { display:block; }
            .modal-content { background:#fff; border-radius:24px; max-width:760px; margin:auto; box-shadow:0 35px 55px rgba(15,23,42,.35); }
            .modal-header { background:linear-gradient(135deg,#2563eb,#3b82f6); color:#fff; padding:18px 26px; display:flex; justify-content:space-between; align-items:center; }
            .modal-body { padding:22px 26px; }
            .modal-footer { padding:0 26px 22px; display:flex; justify-content:flex-end; gap:12px; }
            .modal-close { cursor:pointer; font-size:26px; line-height:1; }
            .form-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
            .log-payload { font-size:12px; color:#475569; background:#f1f5f9; padding:10px; border-radius:12px; max-height:90px; overflow:auto; }
        </style>
    </head>
    <body>
        @include('admin.partials.sidebar')
        <section id="content">
            @include('admin.partials.navbar')
            <main style="margin-top: 64px;">
                <div class="page-header">
                    <div>
                        <h2>Quản lý nhật ký</h2>
                        <p style="color:var(--muted); margin-top:6px;">Theo dõi hoạt động người dùng và hệ thống.</p>
                    </div>
                    <div>
                        <button class="btn btn-primary btn-primary-action" onclick="openCreateModal()">
                            <i class="fa-solid fa-plus"></i>
                            Thêm nhật ký
                        </button>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-card">
                        <span>Tổng nhật ký</span>
                        <h3>{{ number_format($stats['total'] ?? 0) }}</h3>
                    </div>
                    <div class="stat-card">
                        <span>Thành công</span>
                        <h3>{{ number_format($stats['success'] ?? 0) }}</h3>
                    </div>
                    <div class="stat-card">
                        <span>Thất bại</span>
                        <h3>{{ number_format($stats['failed'] ?? 0) }}</h3>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Đã xảy ra lỗi:</strong>
                        <ul class="mt-2 mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="filter-section">
                    <form method="GET" action="{{ route('admin.logs.index') }}">
                        <div class="filter-row">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm hành động, dữ liệu..." class="filter-control">
                            <select name="user_id" class="filter-control">
                                <option value="">-- Người dùng --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->ID }}" {{ (string)request('user_id')===(string)$user->ID ? 'selected' : '' }}>{{ $user->TenNguoiDung }}</option>
                                @endforeach
                            </select>
                            <select name="type" class="filter-control">
                                <option value="">-- Loại --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type')===$type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            <select name="result" class="filter-control">
                                <option value="">-- Kết quả --</option>
                                @foreach($results as $result)
                                    <option value="{{ $result }}" {{ request('result')===$result ? 'selected' : '' }}>{{ $result }}</option>
                                @endforeach
                            </select>
                            <input type="date" name="date_from" class="filter-control" value="{{ request('date_from') }}" placeholder="Từ ngày">
                            <input type="date" name="date_to" class="filter-control" value="{{ request('date_to') }}" placeholder="Đến ngày">
                            <select name="sort_by" class="filter-control">
                                <option value="ThoiGian" {{ request('sort_by','ThoiGian')==='ThoiGian' ? 'selected' : '' }}>Theo thời gian</option>
                                <option value="ID" {{ request('sort_by')==='ID' ? 'selected' : '' }}>Theo ID</option>
                            </select>
                            <select name="sort_direction" class="filter-control">
                                <option value="desc" {{ request('sort_direction','desc')==='desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="asc" {{ request('sort_direction')==='asc' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                            <select name="per_page" class="filter-control">
                                @foreach($perPageOptions as $option)
                                    <option value="{{ $option }}" {{ (int)request('per_page',10)===$option ? 'selected' : '' }}>{{ $option }} / trang</option>
                                @endforeach
                            </select>
                            <div class="filter-actions">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                                <a href="{{ route('admin.logs.index') }}" class="btn btn-light">Đặt lại</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card table-card">
                    <div class="card-header" style="background:#fff; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                        <h6 class="mb-0">Danh sách nhật ký</h6>
                        <small>Hiển thị {{ $logs->count() }} / {{ $logs->total() }}</small>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Hành động</th>
                                    <th>Người dùng</th>
                                    <th>Loại</th>
                                    <th>Kết quả</th>
                                    <th>Dữ liệu mới</th>
                                    <th>IP</th>
                                    <th>Thời gian</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            <strong>{{ $log->HanhDong }}</strong>
                                            <div style="color:var(--muted); font-size:13px;">{{ Str::limit($log->DuLieuCu, 80) ?: '---' }}</div>
                                        </td>
                                        <td>{{ $log->nguoiDung->TenNguoiDung ?? 'Hệ thống' }}</td>
                                        <td>{{ $log->Loai }}</td>
                                        <td>
                                            <span class="{{ $log->KetQua === 'Thành công' ? 'badge-success' : 'badge-danger' }}">{{ $log->KetQua }}</span>
                                        </td>
                                        <td>
                                            <div class="log-payload">{{ Str::limit($log->DuLieuMoi, 120) ?: '---' }}</div>
                                        </td>
                                        <td>{{ $log->DiaChiIP ?? '-' }}</td>
                                        <td>{{ optional($log->ThoiGian)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <button class="action-btn info" onclick="viewLog({{ $log->ID }})" title="Chi tiết"><i class="fa-solid fa-eye"></i></button>
                                            <button class="action-btn edit" onclick="openEditModal({{ $log->ID }})" title="Chỉnh sửa"><i class="fa-solid fa-pen"></i></button>
                                            <button class="action-btn delete" onclick="deleteLog({{ $log->ID }})" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align:center; padding:40px; color:var(--muted);">Không có dữ liệu phù hợp.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($logs->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $logs->links('pagination::rounded') }}
                    </div>
                @endif
            </main>
        </section>

        {{-- Modal tạo --}}
        <div class="modal" id="createModal">
            <div class="modal-content neo-modal">
                <div class="modal-header">
                    <h3>Thêm nhật ký</h3>
                    <span class="modal-close" onclick="closeCreateModal()">&times;</span>
                </div>
                <form method="POST" action="{{ route('admin.logs.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid">
                            <div class="mb-3">
                                <label class="form-label">Người dùng</label>
                                <select name="IDNguoiDung" class="form-control">
                                    <option value="">-- Hệ thống --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->ID }}">{{ $user->TenNguoiDung }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại *</label>
                                <select name="Loai" class="form-control" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kết quả *</label>
                                <select name="KetQua" class="form-control" required>
                                    @foreach($results as $result)
                                        <option value="{{ $result }}">{{ $result }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thời gian</label>
                                <input type="datetime-local" name="ThoiGian" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hành động *</label>
                            <input type="text" name="HanhDong" class="form-control" required>
                        </div>
                        <div class="form-grid">
                            <div class="mb-3">
                                <label class="form-label">Dữ liệu cũ</label>
                                <textarea name="DuLieuCu" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dữ liệu mới</label>
                                <textarea name="DuLieuMoi" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ IP</label>
                                <input type="text" name="DiaChiIP" class="form-control" placeholder="Sẽ tự động lấy nếu bỏ trống">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trình duyệt</label>
                                <input type="text" name="TrinhDuyet" class="form-control" placeholder="Sẽ tự động lấy nếu bỏ trống">
                            </div>
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
            <div class="modal-content neo-modal">
                <div class="modal-header">
                    <h3>Chỉnh sửa nhật ký</h3>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>
                <form method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-grid">
                            <div class="mb-3">
                                <label class="form-label">Người dùng</label>
                                <select name="IDNguoiDung" id="edit_IDNguoiDung" class="form-control">
                                    <option value="">-- Hệ thống --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->ID }}">{{ $user->TenNguoiDung }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại *</label>
                                <select name="Loai" id="edit_Loai" class="form-control" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kết quả *</label>
                                <select name="KetQua" id="edit_KetQua" class="form-control" required>
                                    @foreach($results as $result)
                                        <option value="{{ $result }}">{{ $result }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thời gian</label>
                                <input type="datetime-local" name="ThoiGian" id="edit_ThoiGian" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hành động *</label>
                            <input type="text" name="HanhDong" id="edit_HanhDong" class="form-control" required>
                        </div>
                        <div class="form-grid">
                            <div class="mb-3">
                                <label class="form-label">Dữ liệu cũ</label>
                                <textarea name="DuLieuCu" id="edit_DuLieuCu" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dữ liệu mới</label>
                                <textarea name="DuLieuMoi" id="edit_DuLieuMoi" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ IP</label>
                                <input type="text" name="DiaChiIP" id="edit_DiaChiIP" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trình duyệt</label>
                                <input type="text" name="TrinhDuyet" id="edit_TrinhDuyet" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" onclick="closeEditModal()">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal xem chi tiết --}}
        <div class="modal" id="viewModal">
            <div class="modal-content neo-modal">
                <div class="modal-header">
                    <h3>Chi tiết nhật ký</h3>
                    <span class="modal-close" onclick="closeViewModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p><strong>Hành động:</strong> <span id="view_HanhDong"></span></p>
                    <p><strong>Người dùng:</strong> <span id="view_NguoiDung"></span></p>
                    <p><strong>Loại:</strong> <span id="view_Loai"></span></p>
                    <p><strong>Kết quả:</strong> <span id="view_KetQua"></span></p>
                    <p><strong>Thời gian:</strong> <span id="view_ThoiGian"></span></p>
                    <p><strong>Địa chỉ IP:</strong> <span id="view_DiaChiIP"></span></p>
                    <p><strong>Trình duyệt:</strong> <span id="view_TrinhDuyet"></span></p>
                    <p><strong>Dữ liệu cũ:</strong></p>
                    <pre id="view_DuLieuCu" class="log-payload"></pre>
                    <p><strong>Dữ liệu mới:</strong></p>
                    <pre id="view_DuLieuMoi" class="log-payload"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="closeViewModal()">Đóng</button>
                </div>
            </div>
        </div>

        <script>
            function openModal(id){const modal=document.getElementById(id);if(!modal)return;modal.classList.add('active');document.body.style.overflow='hidden';}
            function closeModal(id){const modal=document.getElementById(id);if(!modal)return;modal.classList.remove('active');document.body.style.overflow='auto';const form=modal.querySelector('form');if(form)form.reset();}

            function openCreateModal(){openModal('createModal');}
            function closeCreateModal(){closeModal('createModal');}

            function openEditModal(id){fetch(`/admin/logs/${id}`,{headers:{'Accept':'application/json'}})
                .then(res=>res.json())
                .then(data=>{
                    document.getElementById('edit_IDNguoiDung').value=data.IDNguoiDung ?? '';
                    document.getElementById('edit_Loai').value=data.Loai || '';
                    document.getElementById('edit_KetQua').value=data.KetQua || '';
                    document.getElementById('edit_HanhDong').value=data.HanhDong || '';
                    document.getElementById('edit_DuLieuCu').value=data.DuLieuCu || '';
                    document.getElementById('edit_DuLieuMoi').value=data.DuLieuMoi || '';
                    document.getElementById('edit_DiaChiIP').value=data.DiaChiIP || '';
                    document.getElementById('edit_TrinhDuyet').value=data.TrinhDuyet || '';
                    const timeInput=document.getElementById('edit_ThoiGian');
                    timeInput.value=data.ThoiGian ? formatDateTimeForInput(data.ThoiGian) : '';
                    document.getElementById('editForm').action=`/admin/logs/${id}`;
                    openModal('editModal');
                })
                .catch(()=>alert('Không thể tải dữ liệu nhật ký.'));
            }
            function closeEditModal(){closeModal('editModal');}

            function viewLog(id){fetch(`/admin/logs/${id}`,{headers:{'Accept':'application/json'}})
                .then(res=>res.json())
                .then(data=>{
                    document.getElementById('view_HanhDong').textContent=data.HanhDong || '';
                    document.getElementById('view_NguoiDung').textContent=(data.nguoi_dung?.TenNguoiDung)||'Hệ thống';
                    document.getElementById('view_Loai').textContent=data.Loai || '';
                    document.getElementById('view_KetQua').textContent=data.KetQua || '';
                    document.getElementById('view_ThoiGian').textContent=data.ThoiGian || '';
                    document.getElementById('view_DiaChiIP').textContent=data.DiaChiIP || '-';
                    document.getElementById('view_TrinhDuyet').textContent=data.TrinhDuyet || '-';
                    document.getElementById('view_DuLieuCu').textContent=data.DuLieuCu || '---';
                    document.getElementById('view_DuLieuMoi').textContent=data.DuLieuMoi || '---';
                    openModal('viewModal');
                })
                .catch(()=>alert('Không thể tải dữ liệu nhật ký.'));
            }
            function closeViewModal(){closeModal('viewModal');}

            function deleteLog(id){if(!confirm('Xóa nhật ký này?'))return;const form=document.createElement('form');form.method='POST';form.action=`/admin/logs/${id}`;form.style.display='none';form.innerHTML=`@csrf<input type="hidden" name="_method" value="DELETE">`;document.body.appendChild(form);form.submit();}

            document.querySelectorAll('.modal').forEach(modal=>modal.addEventListener('click',e=>{if(e.target===modal)closeModal(modal.id);}));

            function formatDateTimeForInput(value){const date=new Date(value);if(Number.isNaN(date.getTime()))return '';const year=date.getFullYear();const month=String(date.getMonth()+1).padStart(2,'0');const day=String(date.getDate()).padStart(2,'0');const hours=String(date.getHours()).padStart(2,'0');const minutes=String(date.getMinutes()).padStart(2,'0');return `${year}-${month}-${day}T${hours}:${minutes}`;}
        </script>
        <script src="/template/admin/script.js"></script>
    </body>
</html>
