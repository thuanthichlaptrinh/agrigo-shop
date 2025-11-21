<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý vai trò - ADMIN</title>
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
            body { background:#f4f6fb; font-family:'Nunito',sans-serif; }
            .page-header { display:flex; justify-content:space-between; align-items: center; gap:16px; flex-wrap:wrap; gap:16px; margin-bottom:24px; }
            .stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px; margin-bottom:24px; }
            .stat-card { background:#fff; border-radius:18px; padding:16px; box-shadow:0 12px 22px rgba(15,23,42,.08); }
            .stat-card span { color:#6b7280; font-size:13px; }
            .stat-card h3 { margin:6px 0 0; color:#6366f1; }
            .filter-section { background:#fff; border-radius:20px; padding:20px; box-shadow:0 15px 30px rgba(15,23,42,.08); margin-bottom:24px; }
            .filter-row { display:flex; gap:12px; flex-wrap:wrap; }
            .filter-input, .filter-select { flex:1; min-width:180px; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; }
            .page-header .btn-primary-action { border:none; border-radius:12px; padding:11px 18px; font-weight:600; display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; box-shadow:0 12px 20px rgba(99,102,241,.26); transition:transform .2s ease, box-shadow .2s ease; }
            .page-header .btn-primary-action:hover { transform:translateY(-1px); box-shadow:0 16px 26px rgba(99,102,241,.32); }
            table { width:100%; border-collapse:collapse; }
            th { padding:14px 16px; background:#f8fafc; text-transform:uppercase; font-size:12px; color:#94a3b8; }
            td { padding:16px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
            .badge-used { background:rgba(16,185,129,.15); color:#0f766e; border-radius:999px; padding:6px 14px; font-size:12px; }
            .badge-empty { background:rgba(148,163,184,.2); color:#475569; border-radius:999px; padding:6px 14px; font-size:12px; }
            .action-btn { width:34px; height:34px; border:none; border-radius:10px; margin-right:4px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; }
            .action-btn.edit { background:rgba(250,204,21,.2); color:#a16207; }
            .action-btn.delete { background:rgba(248,113,113,.2); color:#b91c1c; }
            .modal { display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:1200; padding:40px 20px; overflow-y:auto; }
            .modal.active { display:block; }
            .modal-content { background:#fff; border-radius:20px; max-width:540px; margin:auto; box-shadow:0 30px 50px rgba(15,23,42,.35); }
            .modal-header { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; padding:18px 26px; display:flex; justify-content:space-between; align-items:center; }
            .modal-body { padding:22px 26px; }
            .modal-footer { padding:0 26px 22px; padding-top: 20px !important; display:flex; justify-content:flex-end; gap:10px; }
        </style>
    </head>
    <body>
        @include('admin.partials.sidebar')
        <section id="content">
            @include('admin.partials.navbar')
            <main>
                <div class="page-header">
                    <div>
                        <h2>Quản lý Vai trò</h2>
                        <p style="color:#6b7280; margin-top:6px;">Phân quyền truy cập cho đội ngũ quản trị.</p>
                    </div>
                    <div>
                        <button class="btn-primary-action btn-primary-action--purple" onclick="openCreateModal()"><i class="fa-solid fa-plus"></i> Thêm vai trò</button>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-card"><span>Tổng vai trò</span><h3>{{ number_format($stats['total'] ?? 0) }}</h3></div>
                    <div class="stat-card"><span>Đang sử dụng</span><h3>{{ number_format($stats['used'] ?? 0) }}</h3></div>
                    <div class="stat-card"><span>Chưa có người dùng</span><h3>{{ number_format($stats['empty'] ?? 0) }}</h3></div>
                </div>

                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                @if($errors->any())<div class="alert alert-danger"><strong>Đã xảy ra lỗi:</strong><ul class="mt-2 mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

                <div class="filter-section">
                    <form method="GET" action="{{ route('admin.roles.index') }}">
                        <div class="filter-row">
                            <input type="text" name="search" class="filter-input" placeholder="Tìm tên hoặc mô tả..." value="{{ request('search') }}">
                            <select name="sort_by" class="filter-select">
                                <option value="ID" {{ request('sort_by','ID')==='ID' ? 'selected' : '' }}>Theo ID</option>
                                <option value="TenVaiTro" {{ request('sort_by')==='TenVaiTro' ? 'selected' : '' }}>Theo tên</option>
                                <option value="nguoi_dung_count" {{ request('sort_by')==='nguoi_dung_count' ? 'selected' : '' }}>Theo số thành viên</option>
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
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-light" style="border-radius:10px;">Đặt lại</a>
                        </div>
                    </form>
                </div>

                <div class="card" style="border:none; border-radius:20px; box-shadow:0 12px 28px rgba(15,23,42,.08);">
                    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; background:#fff; border-bottom:1px solid #f1f5f9;">
                        <h6 class="mb-0">Danh sách vai trò</h6>
                        <small>Hiển thị {{ $roles->count() }} / {{ $roles->total() }}</small>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tên vai trò</th>
                                    <th>Mô tả</th>
                                    <th>Thành viên</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td><strong>{{ $role->TenVaiTro }}</strong><div style="color:#94a3b8; font-size:13px;">Mã: #{{ $role->ID }}</div></td>
                                        <td>{{ $role->MoTa ?? '-' }}</td>
                                        <td>{{ number_format($role->nguoi_dung_count) }}</td>
                                        <td>
                                            @if($role->nguoi_dung_count > 0)
                                                <span class="badge-used">Đang dùng</span>
                                            @else
                                                <span class="badge-empty">Chưa gán</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="action-btn edit" title="Chỉnh sửa" onclick="openEditModal({{ $role->ID }})"><i class="fa-solid fa-pen"></i></button>
                                            <button class="action-btn delete" title="Xóa" onclick="deleteRole({{ $role->ID }})"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" style="text-align:center; padding:40px; color:#94a3b8;">Không có dữ liệu.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($roles->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $roles->links('pagination::rounded') }}
                    </div>
                @endif
            </main>
        </section>

        {{-- Modal tạo --}}
        <div class="modal" id="createModal">
            <div class="modal-content neo-modal">
                <div class="modal-header">
                    <h3>Thêm vai trò</h3>
                    <span class="modal-close" onclick="closeCreateModal()">&times;</span>
                </div>
                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tên vai trò *</label>
                            <input type="text" name="TenVaiTro" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="MoTa" class="form-control" rows="3"></textarea>
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
                    <h3>Chỉnh sửa vai trò</h3>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>
                <form method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tên vai trò *</label>
                            <input type="text" name="TenVaiTro" id="edit_TenVaiTro" class="form-control" required>
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

        <script>
            function openModal(id){const modal=document.getElementById(id);if(!modal)return;modal.classList.add('active');document.body.style.overflow='hidden';}
            function closeModal(id){const modal=document.getElementById(id);if(!modal)return;modal.classList.remove('active');document.body.style.overflow='auto';const form=modal.querySelector('form');if(form)form.reset();}
            function openCreateModal(){openModal('createModal');}
            function closeCreateModal(){closeModal('createModal');}
            function openEditModal(id){fetch(`/admin/roles/${id}`,{headers:{'Accept':'application/json'}})
                .then(res=>res.json())
                .then(data=>{
                    document.getElementById('edit_TenVaiTro').value=data.TenVaiTro || '';
                    document.getElementById('edit_MoTa').value=data.MoTa ?? '';
                    document.getElementById('editForm').action=`/admin/roles/${id}`;
                    openModal('editModal');
                })
                .catch(()=>alert('Không thể tải dữ liệu vai trò.'));
            }
            function closeEditModal(){closeModal('editModal');}
            function deleteRole(id){if(!confirm('Xóa vai trò này?'))return;const form=document.createElement('form');form.method='POST';form.action=`/admin/roles/${id}`;form.style.display='none';form.innerHTML=`@csrf<input type="hidden" name="_method" value="DELETE">`;document.body.appendChild(form);form.submit();}
            document.querySelectorAll('.modal').forEach(modal=>modal.addEventListener('click',e=>{if(e.target===modal)closeModal(modal.id);}));
        </script>
        <script src="/template/admin/script.js"></script>
    </body>
</html>
