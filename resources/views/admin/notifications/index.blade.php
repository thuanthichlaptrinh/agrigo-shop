<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý thông báo - ADMIN</title>
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
        <style>
            :root { --primary:#0ea5e9; --muted:#6b7280; }
            body { background:#f6f7fb; font-family:'Nunito',sans-serif; }
            .page-header { display: flex; justify-content:space-between; align-items: center; gap:16px; flex-wrap:wrap; margin-bottom:24px; }
            .stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:24px; }
            .page-header .btn-primary-action { border:none; border-radius:12px; padding:11px 18px; color:#fff; font-weight:600; display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#0ea5e9,#38bdf8); box-shadow:0 12px 20px rgba(14,165,233,.22); transition:transform .2s ease, box-shadow .2s ease; }
            .page-header .btn-primary-action:hover { transform:translateY(-1px); box-shadow:0 16px 26px rgba(14,165,233,.3); }
            .stat-card { background:#fff; border-radius:18px; padding:16px; box-shadow:0 10px 20px rgba(15,23,42,.08); }
            .stat-card span { color:var(--muted); font-size:13px; }
            .stat-card h3 { margin:6px 0 0; color:#0ea5e9; }
            .filter-section { background:#fff; border-radius:20px; padding:20px; box-shadow:0 15px 28px rgba(15,23,42,.08); margin-bottom:24px; }
            .filter-row { display:flex; flex-wrap:wrap; gap:12px; }
            .filter-input, .filter-select { flex:1; min-width:170px; border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; }
            table { width:100%; border-collapse:collapse; }
            th { padding:14px 16px; background:#f8fafc; text-transform:uppercase; font-size:12px; color:#94a3b8; }
            td { padding:16px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
            .badge-read { background:rgba(16,185,129,.15); color:#0f766e; border-radius:999px; padding:6px 14px; font-size:12px; }
            .badge-unread { background:rgba(248,113,113,.15); color:#b91c1c; border-radius:999px; padding:6px 14px; font-size:12px; }
            .action-btn { width:34px; height:34px; border:none; border-radius:10px; margin-right:4px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; }
            .action-btn.info { background:rgba(59,130,246,.15); color:#1d4ed8; }
            .action-btn.edit { background:rgba(250,204,21,.2); color:#a16207; }
            .action-btn.delete { background:rgba(248,113,113,.2); color:#b91c1c; }
            .modal { display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:1200; padding:40px 20px; overflow-y:auto; }
            .modal.active { display:block; }
            .modal-content { background:#fff; border-radius:24px; max-width:720px; margin:auto; box-shadow:0 35px 55px rgba(15,23,42,.35); }
            .modal-header { background:linear-gradient(135deg,#0ea5e9,#38bdf8); color:#fff; padding:18px 26px; display:flex; justify-content:space-between; align-items:center; padding-top: 20px !important;    }
            .modal-body { padding:22px 26px; }
            .modal-footer { padding:0 26px 22px; display:flex; justify-content:flex-end; gap:12px; padding-top: 20px !important; }
            .form-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }

            /* Pagination */
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
        </style>
    </head>
    <body>
        @include('admin.partials.sidebar')
        <section id="content">
            @include('admin.partials.navbar')
            <main style="margin-top: 64px;">
                <div class="page-header">
                    <div>
                        <h2>Quản lý Thông báo</h2>
                        <p style="color:var(--muted); margin-top:6px;">Gửi và theo dõi thông báo tới người dùng.</p>
                    </div>
                    <div>
                        <button class="btn-primary-action" onclick="openCreateModal()"><i class="fa-solid fa-paper-plane"></i> Gửi thông báo</button>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-card"><span>Tổng thông báo</span><h3>{{ number_format($stats['total'] ?? 0) }}</h3></div>
                    <div class="stat-card"><span>Đã đọc</span><h3>{{ number_format($stats['read'] ?? 0) }}</h3></div>
                    <div class="stat-card"><span>Chưa đọc</span><h3>{{ number_format($stats['unread'] ?? 0) }}</h3></div>
                </div>

                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if($errors->any())
                    <div class="alert alert-danger"><strong>Đã xảy ra lỗi:</strong><ul class="mt-2 mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <div class="filter-section">
                    <form method="GET" action="{{ route('admin.notifications.index') }}">
                        <div class="filter-row">
                            <input type="text" name="search" class="filter-input" placeholder="Tìm tiêu đề, nội dung..." value="{{ request('search') }}">
                            <select name="user_id" class="filter-select">
                                <option value="">-- Người nhận --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->ID }}" {{ (string)request('user_id')===(string)$user->ID ? 'selected' : '' }}>{{ $user->TenNguoiDung }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="type" class="filter-input" placeholder="Loại" value="{{ request('type') }}">
                            <select name="read_status" class="filter-select">
                                <option value="">-- Trạng thái đọc --</option>
                                <option value="unread" {{ request('read_status')==='unread' ? 'selected' : '' }}>Chưa đọc</option>
                                <option value="read" {{ request('read_status')==='read' ? 'selected' : '' }}>Đã đọc</option>
                            </select>
                            <select name="sort_by" class="filter-select">
                                <option value="NgayTao" {{ request('sort_by','NgayTao')==='NgayTao' ? 'selected' : '' }}>Theo thời gian</option>
                                <option value="ID" {{ request('sort_by')==='ID' ? 'selected' : '' }}>Theo ID</option>
                            </select>
                            <select name="sort_direction" class="filter-select">
                                <option value="desc" {{ request('sort_direction','desc')==='desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="asc" {{ request('sort_direction')==='asc' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                            <select name="per_page" class="filter-select">
                                @foreach($perPageOptions as $option)
                                    <option value="{{ $option }}" {{ (int)request('per_page',10)===$option ? 'selected' : '' }}>{{ $option }} / trang</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary" style="border-radius:10px;">Lọc</button>
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-light" style="border-radius:10px;">Đặt lại</a>
                        </div>
                    </form>
                </div>

                <div class="card" style="border:none; border-radius:20px; box-shadow:0 15px 30px rgba(15,23,42,.08);">
                    <div class="card-header" style="background:#fff; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                        <h6 class="mb-0">Danh sách thông báo</h6>
                        <small>Hiển thị {{ $notifications->count() }} / {{ $notifications->total() }}</small>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Người nhận</th>
                                    <th>Loại</th>
                                    <th>Đường dẫn</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->TieuDe }}</strong>
                                            <div style="color:var(--muted); font-size:13px;">{{ Str::limit($item->NoiDung, 70) }}</div>
                                        </td>
                                        <td>{{ $item->nguoiDung->TenNguoiDung ?? 'Tất cả / Hệ thống' }}</td>
                                        <td>{{ $item->Loai ?? '-' }}</td>
                                        <td><a href="{{ $item->LinkLienKet ?? '#' }}" target="_blank">{{ $item->LinkLienKet ? 'Mở liên kết' : '-' }}</a></td>
                                        <td>
                                            <span class="{{ $item->DaXem ? 'badge-read' : 'badge-unread' }}">{{ $item->DaXem ? 'Đã đọc' : 'Chưa đọc' }}</span>
                                        </td>
                                        <td>{{ optional($item->NgayTao)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <button class="action-btn info" onclick="viewNotification({{ $item->ID }})" title="Chi tiết"><i class="fa-solid fa-eye"></i></button>
                                            <button class="action-btn edit" onclick="openEditModal({{ $item->ID }})" title="Chỉnh sửa"><i class="fa-solid fa-pen"></i></button>
                                            <button class="action-btn delete" onclick="deleteNotification({{ $item->ID }})" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" style="text-align:center; padding:40px; color:var(--muted);">Không có dữ liệu phù hợp.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($notifications->hasPages())
                    <div class="pagination-wrapper mt-3">
                        {{ $notifications->links('vendor.pagination.admin-users') }}
                    </div>
                @endif
            </main>
        </section>

        {{-- Modal tạo --}}
        <div class="modal" id="createModal">
            <div class="modal-content neo-modal">
                <div class="modal-header">
                    <h3>Gửi thông báo</h3>
                    <span class="modal-close" onclick="closeCreateModal()">&times;</span>
                </div>
                <form method="POST" action="{{ route('admin.notifications.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="mb-3">
                                <label class="form-label">Người nhận</label>
                                <select name="IDNguoiDung" class="form-control">
                                    <option value="">-- Tất cả / Hệ thống --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->ID }}">{{ $user->TenNguoiDung }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại</label>
                                <input type="text" name="Loai" class="form-control" placeholder="Ví dụ: Đơn hàng, Khuyến mãi">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề *</label>
                            <input type="text" name="TieuDe" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung *</label>
                            <textarea name="NoiDung" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="mb-3">
                                <label class="form-label">Link liên kết</label>
                                <input type="text" name="LinkLienKet" class="form-control" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Đánh dấu đã đọc</label>
                                <select name="DaXem" class="form-control">
                                    <option value="0">Chưa đọc</option>
                                    <option value="1">Đã đọc</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" onclick="closeCreateModal()">Hủy</button>
                        <button type="submit" class="btn btn-primary">Gửi</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal chỉnh sửa --}}
        <div class="modal" id="editModal">
            <div class="modal-content neo-modal">
                <div class="modal-header">
                    <h3>Chỉnh sửa thông báo</h3>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>
                <form method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="mb-3">
                                <label class="form-label">Người nhận</label>
                                <select name="IDNguoiDung" id="edit_IDNguoiDung" class="form-control">
                                    <option value="">-- Tất cả / Hệ thống --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->ID }}">{{ $user->TenNguoiDung }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại</label>
                                <input type="text" name="Loai" id="edit_Loai" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề *</label>
                            <input type="text" name="TieuDe" id="edit_TieuDe" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung *</label>
                            <textarea name="NoiDung" id="edit_NoiDung" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="mb-3">
                                <label class="form-label">Link liên kết</label>
                                <input type="text" name="LinkLienKet" id="edit_LinkLienKet" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái đọc</label>
                                <select name="DaXem" id="edit_DaXem" class="form-control">
                                    <option value="0">Chưa đọc</option>
                                    <option value="1">Đã đọc</option>
                                </select>
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

        {{-- Modal xem --}}
        <div class="modal" id="viewModal">
            <div class="modal-content neo-modal">
                <div class="modal-header">
                    <h3>Chi tiết thông báo</h3>
                    <span class="modal-close" onclick="closeViewModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p><strong>Tiêu đề:</strong> <span id="view_TieuDe"></span></p>
                    <p><strong>Người nhận:</strong> <span id="view_NguoiNhan"></span></p>
                    <p><strong>Loại:</strong> <span id="view_Loai"></span></p>
                    <p><strong>Nội dung:</strong></p>
                    <p id="view_NoiDung" style="white-space:pre-line;"></p>
                    <p><strong>Link:</strong> <a href="#" id="view_Link" target="_blank" class="link-highlight">-</a></p>
                    <p><strong>Trạng thái:</strong> <span id="view_TrangThai"></span></p>
                    <p><strong>Gửi lúc:</strong> <span id="view_Ngay"></span></p>
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
            function openEditModal(id){fetch(`/admin/notifications/${id}`,{headers:{'Accept':'application/json'}})
                .then(res=>res.json())
                .then(data=>{
                    document.getElementById('edit_IDNguoiDung').value=data.IDNguoiDung ?? '';
                    document.getElementById('edit_Loai').value=data.Loai ?? '';
                    document.getElementById('edit_TieuDe').value=data.TieuDe || '';
                    document.getElementById('edit_NoiDung').value=data.NoiDung || '';
                    document.getElementById('edit_LinkLienKet').value=data.LinkLienKet ?? '';
                    document.getElementById('edit_DaXem').value=data.DaXem ? '1':'0';
                    document.getElementById('editForm').action=`/admin/notifications/${id}`;
                    openModal('editModal');
                })
                .catch(()=>alert('Không thể tải dữ liệu thông báo.'));
            }
            function closeEditModal(){closeModal('editModal');}
            function viewNotification(id){fetch(`/admin/notifications/${id}`,{headers:{'Accept':'application/json'}})
                .then(res=>res.json())
                .then(data=>{
                    document.getElementById('view_TieuDe').textContent=data.TieuDe || '';
                    document.getElementById('view_NguoiNhan').textContent=(data.nguoi_dung?.TenNguoiDung)||'Tất cả / Hệ thống';
                    document.getElementById('view_Loai').textContent=data.Loai || '-';
                    document.getElementById('view_NoiDung').textContent=data.NoiDung || '';
                    const link=document.getElementById('view_Link');
                    if(data.LinkLienKet){link.textContent=data.LinkLienKet;link.href=data.LinkLienKet;}else{link.textContent='-';link.href='#';}
                    document.getElementById('view_TrangThai').textContent=data.DaXem ? 'Đã đọc' : 'Chưa đọc';
                    document.getElementById('view_Ngay').textContent=data.NgayTao || '';
                    openModal('viewModal');
                })
                .catch(()=>alert('Không thể tải dữ liệu thông báo.'));
            }
            function closeViewModal(){closeModal('viewModal');}
            function deleteNotification(id){if(!confirm('Xóa thông báo này?'))return;const form=document.createElement('form');form.method='POST';form.action=`/admin/notifications/${id}`;form.style.display='none';form.innerHTML=`@csrf<input type="hidden" name="_method" value="DELETE">`;document.body.appendChild(form);form.submit();}
            document.querySelectorAll('.modal').forEach(modal=>modal.addEventListener('click',e=>{if(e.target===modal)closeModal(modal.id);}));
        </script>
        <script src="/template/admin/script.js"></script>
    </body>
</html>
