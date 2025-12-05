<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome 6 Free CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <!-- Boxicons CDN (Backup) -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
        <link rel="stylesheet" href="/template/admin/style.css" />
        <link rel="stylesheet" href="/template/admin/products.css" />
        <title>Quản lý người dùng - ADMIN</title>
        <style>
            :root {
                --primary-color: #435ebe;
                --secondary-color: #6c757d;
                --success-color: #198754;
                --danger-color: #dc3545;
                --warning-color: #ffc107;
                --info-color: #0dcaf0;
                --light-bg: #f2f7ff;
            }
            
            body {
                background-color: var(--light-bg);
                font-family: 'Nunito', sans-serif;
            }

            /* Card Styling */
            .card {
                border: none;
                border-radius: 15px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.05);
                background: #fff;
                overflow: hidden;
                margin-bottom: 30px;
            }

            .card-header {
                background: #fff;
                padding: 20px 25px;
                border-bottom: 1px solid #f0f0f0;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .card-header h6 {
                font-size: 18px;
                font-weight: 700;
                color: #25396f;
                margin: 0;
            }

            /* Filter Section */
            .filter-section {
                background: #fff;
                padding: 25px;
                border-radius: 15px;
                margin-bottom: 25px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            }

            .filter-form .filter-row {
                display: flex;
                gap: 15px;
                align-items: center;
                flex-wrap: wrap;
            }

            .filter-input, .filter-select {
                padding: 12px 15px;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                font-size: 14px;
                flex: 1;
                min-width: 200px;
                transition: all 0.3s;
            }

            .filter-input:focus, .filter-select:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(67, 94, 190, 0.1);
                outline: none;
            }

            /* Buttons */
            .btn-filter, .btn-reset, .btn-add {
                padding: 12px 20px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s;
                text-decoration: none;
            }

            .btn-filter {
                background: var(--primary-color);
                color: white;
            }
            .btn-filter:hover {
                background: #2d4294;
                transform: translateY(-2px);
            }

            .btn-reset {
                background: #f0f0f0;
                color: #333;
            }
            .btn-reset:hover {
                background: #e0e0e0;
            }

            .btn-add {
                background: var(--success-color);
                color: white;
                box-shadow: 0 4px 10px rgba(25, 135, 84, 0.2);
            }
            .btn-add:hover {
                background: #146c43;
                transform: translateY(-2px);
                box-shadow: 0 6px 15px rgba(25, 135, 84, 0.3);
            }

            /* Table Styling */
            .table-wrapper {
                overflow-x: auto;
            }

            .authors-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .authors-table th {
                background: #f8f9fa;
                color: #25396f;
                font-weight: 700;
                padding: 15px 20px;
                text-align: left;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border-bottom: 2px solid #f0f0f0;
            }

            .authors-table td {
                padding: 15px 20px;
                vertical-align: middle;
                border-bottom: 1px solid #f0f0f0;
                color: #555;
                font-size: 14px;
            }

            .authors-table tr:hover td {
                background-color: #fcfcfc;
            }

            .authors-table tr:last-child td {
                border-bottom: none;
            }

            /* User Info in Table */
            .author-info {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .avatar {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                object-fit: cover;
                border: 2px solid #fff;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .avatar-preview {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid #f6f7ff;
                box-shadow: 0 4px 14px rgba(67, 94, 190, 0.2);
            }

            .avatar-upload {
                display: flex;
                align-items: center;
                gap: 18px;
                flex-wrap: wrap;
            }

            .upload-label {
                position: relative;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 18px;
                border-radius: 10px;
                background: #435ebe;
                color: #fff;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 10px 20px rgba(67, 94, 190, 0.2);
            }

            .upload-label input {
                position: absolute;
                inset: 0;
                opacity: 0;
                cursor: pointer;
            }

            .avatar-hint {
                font-size: 12px;
                color: #8c8c8c;
            }

            .detail-avatar {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                object-fit: cover;
                border: 4px solid #eef2ff;
                box-shadow: 0 15px 30px rgba(67, 94, 190, 0.2);
                margin: 0 auto 20px;
                display: block;
            }

            .form-group.full-width {
                grid-column: 1 / -1;
            }

            .author-info h6 {
                margin: 0;
                font-size: 15px;
                font-weight: 600;
                color: #333;
            }

            .author-info .email {
                font-size: 12px;
                color: #888;
            }

            /* Badges */
            .badge {
                padding: 6px 12px;
                border-radius: 30px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .badge.admin { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
            .badge.manager { background: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
            .badge.user { background: rgba(67, 94, 190, 0.1); color: #435ebe; }
            
            .badge.status-active { background: rgba(25, 135, 84, 0.1); color: #198754; }
            .badge.status-inactive { background: rgba(108, 117, 125, 0.1); color: #6c757d; }

            /* Action Buttons */
            .action-btn {
                width: 35px;
                height: 35px;
                border-radius: 8px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
                margin: 0 2px;
            }

            .info-link { background: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
            .info-link:hover { background: #0dcaf0; color: white; }

            .edit-link { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
            .edit-link:hover { background: #ffc107; color: white; }

            .delete-link { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
            .delete-link:hover { background: #dc3545; color: white; }

            .lock-link { background: rgba(108, 117, 125, 0.15); color: #6c757d; }
            .lock-link:hover { background: #6c757d; color: white; }

            .unlock-link { background: rgba(25, 135, 84, 0.12); color: #198754; }
            .unlock-link:hover { background: #198754; color: white; }

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

            .pagination-wrapper .pagination {
                margin: 0;
                gap: 6px;
            }

            .pagination-wrapper .page-item:first-child .page-link,
            .pagination-wrapper .page-item:last-child .page-link {
                border-radius: 10px;
            }

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

            /* Modal Improvements */
            .modal {
                display: none;
                position: fixed;
                inset: 0;
                z-index: 1050;
                background: rgba(15, 23, 42, 0.65);
                backdrop-filter: blur(5px);
                overflow-y: auto;
                padding: 40px 24px;
            }

            .modal.active {
                display: block;
            }

            .modal-content {
                border-radius: 18px;
                border: none;
                box-shadow: 0 25px 55px rgba(15, 23, 42, 0.35);
                overflow: hidden;
                animation: modalSlide 0.35s ease;
                background: #fff;
                width: min(960px, 100%);
                margin: auto;
            }

            @keyframes modalSlide {
                from { transform: translateY(-25px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            
            .modal-header {
                background: linear-gradient(135deg, #435ebe 0%, #6f70f5 100%);
                border-bottom: none;
                border-radius: 18px 18px 0 0;
                padding: 24px 32px;
                color: #fff;
                display: flex;
                justify-content: space-between;
                align-items: center;
                box-shadow: inset 0 -1px 0 rgba(255,255,255,0.2);
            }

            .modal-header h3 {
                font-size: 22px;
                font-weight: 700;
                margin: 0;
            }

            .modal-close {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                border: 1px solid rgba(255,255,255,0.4);
                background: transparent;
                color: #fff;
                font-size: 20px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .modal-close:hover {
                background: rgba(255,255,255,0.2);
                transform: rotate(90deg);
            }

            .modal-body {
                padding: 28px 32px;
                background: linear-gradient(180deg, #f8f9fc 0%, #fdfdff 100%);
                max-height: 65vh;
                overflow-y: auto;
            }

            .modal-body::-webkit-scrollbar {
                width: 8px;
            }

            .modal-body::-webkit-scrollbar-track {
                background: #e9edf5;
                border-radius: 4px;
            }

            .modal-body::-webkit-scrollbar-thumb {
                background: #435ebe;
                border-radius: 4px;
            }

            .form-row {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 18px;
                margin-bottom: 18px;
            }

            .form-row.align-start {
                align-items: flex-start;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-group.image-group {
                display: flex;
                flex-direction: column;
            }

            .form-group.image-group .avatar-upload {
                gap: 14px;
                justify-content: flex-start;
            }

            .form-group label {
                font-weight: 600;
                color: #374151;
                margin-bottom: 8px;
            }

            .form-control {
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 12px 16px;
                font-size: 14px;
                transition: all 0.2s ease;
                background: #fff;
            }

            .form-control:focus {
                border-color: #435ebe;
                box-shadow: 0 0 0 3px rgba(67, 94, 190, 0.15);
                outline: none;
            }

            .form-control.is-invalid {
                border-color: #dc3545;
                background: #fff6f6;
            }

            .invalid-feedback {
                color: #dc3545;
                font-size: 12px;
                margin-top: 6px;
            }

            .required { color: #dc3545; }

            .modal-footer {
                padding: 20px 32px 28px;
                background: #fff;
                border-top: 1px solid #edf1f7;
                display: flex;
                justify-content: flex-end;
                gap: 12px;
            }

            .btn-submit {
                background: linear-gradient(135deg, #435ebe 0%, #6f70f5 100%);
                color: #fff;
                border: none;
                padding: 12px 26px;
                border-radius: 12px;
                font-weight: 600;
                letter-spacing: 0.3px;
                box-shadow: 0 12px 20px rgba(67, 94, 190, 0.25);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .btn-submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 28px rgba(67, 94, 190, 0.3);
            }

            .btn-cancel {
                background: #eef0f8;
                color: #4a4f63;
                border: none;
                padding: 12px 24px;
                border-radius: 12px;
                font-weight: 600;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                box-shadow: inset 0 0 0 1px rgba(74, 79, 99, 0.05);
            }

            .btn-cancel:hover {
                background: #dfe4f3;
                transform: translateY(-1px);
            }

            .detail-row {
                padding: 16px 0;
                border-bottom: 1px solid #e4e7f3;
                display: flex;
                justify-content: space-between;
                gap: 20px;
            }

            .detail-row strong {
                color: #1f2937;
                min-width: 180px;
            }

            @media (max-width: 768px) {
                .modal-content {
                    width: 100%;
                    border-radius: 0;
                }

                .modal-header,
                .modal-body,
                .modal-footer {
                    padding-left: 20px;
                    padding-right: 20px;
                }

                .form-row {
                    grid-template-columns: 1fr;
                }

                .detail-row {
                    flex-direction: column;
                    gap: 8px;
                }

                .detail-row strong {
                    min-width: auto;
                }
            }
        </style>
    </head>
    <body>
        <!-- SIDEBAR -->
        @include('admin.partials.sidebar')
        <!-- SIDEBAR -->

        <section id="content">
            @include('admin.partials.navbar')

            <!-- MAIN -->
            <main style="margin-top: 64px;">
                <h1 class="title">Quản lý Người dùng</h1>
                <ul class="breadcrumbs">
                    <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="divider">/</li>
                    <li><a href="#" class="active">Users</a></li>
                </ul>

                {{-- Thông báo --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Form tìm kiếm và lọc --}}
                <div class="filter-section">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="filter-form">
                        <div class="filter-row">
                            <input type="text" name="search" placeholder="Tìm kiếm theo tên, email, SĐT..." value="{{ request('search') }}" class="filter-input">
                            
                            <select name="role" class="filter-select">
                                <option value="">-- Tất cả vai trò --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->ID }}" {{ request('role') == $role->ID ? 'selected' : '' }}>
                                        {{ $role->TenVaiTro }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <select name="status" class="filter-select">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Bị khóa</option>
                            </select>
                            
                            <button type="submit" class="btn-filter">
                                <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn-reset">
                                <i class="fa-solid fa-rotate-right"></i> Đặt lại
                            </a>
                        </div>
                    </form>
                </div>

                <div class="">
                    <div class="card">
                        <div class="card-header">
                            <h6>Danh sách người dùng ({{ $users->total() }} người)</h6>
                        </div>
                        <div class="table-wrapper">
                            <table class="authors-table">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên & Email</th>
                                        <th style="width: 400px">SĐT & Địa chỉ</th>
                                        <th>Vai trò</th>
                                        <th>Trạng thái</th>
                                        <th style="width: 240px">Ngày đăng ký</th>
                                        <th style="width: 240px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $index => $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $index }}</td>
                                        <td>
                                            <div class="author-info">
                                                <img
                                                    src="{{ $user->HinhAnh ? asset($user->HinhAnh) : 'https://ui-avatars.com/api/?name=' . urlencode($user->TenNguoiDung) . '&background=random' }}"
                                                    alt="{{ $user->TenNguoiDung }}"
                                                    class="avatar"
                                                />
                                                <div>
                                                    <h6>{{ $user->TenNguoiDung }}</h6>
                                                    <small class="email">{{ $user->Email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="sdt">{{ $user->SDT ?? 'Chưa cập nhật' }}</p>
                                            <small class="address">{{ $user->DiaChi ?? 'Chưa cập nhật' }}</small>
                                        </td>
                                        <td>
                                            @if($user->vaiTro->IDVaiTro == 1)
                                                <span class="badge admin">{{ $user->vaiTro->TenVaiTro }}</span>
                                            @elseif($user->vaiTro->IDVaiTro == 3)
                                                <span class="badge manager">{{ $user->vaiTro->TenVaiTro }}</span>
                                            @elseif($user->vaiTro->IDVaiTro == 4)
                                                <span class="badge manager">{{ $user->vaiTro->TenVaiTro }}</span>
                                            @else
                                                <span class="badge user">{{ $user->vaiTro->TenVaiTro }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->TrangThai)
                                                <span class="badge status-active">Hoạt động</span>
                                            @else
                                                <span class="badge status-inactive">Bị khóa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="date">{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <button type="button" onclick="viewUser({{ $user->ID }})" class="action-btn info-link" title="Xem chi tiết">
                                                <i class="fa-solid fa-circle-info"></i>
                                            </button>
                                            @if($user->TrangThai)
                                                <button type="button" onclick="toggleUserStatus({{ $user->ID }}, true)" class="action-btn lock-link" title="Khóa tài khoản">
                                                    <i class="fa-solid fa-lock"></i>
                                                </button>
                                            @else
                                                <button type="button" onclick="toggleUserStatus({{ $user->ID }}, false)" class="action-btn unlock-link" title="Mở khóa tài khoản">
                                                    <i class="fa-solid fa-lock-open"></i>
                                                </button>
                                            @endif
                                            <button type="button" onclick="deleteUser({{ $user->ID }})" class="action-btn delete-link" title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                                            <i class="fa-solid fa-user-xmark" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                                            Không tìm thấy người dùng nào
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                {{-- Phân trang --}}
                @if($users->hasPages())
                    <div class="pagination-wrapper">
                        {{ $users->links('vendor.pagination.admin-users') }}
                    </div>
                @endif
            </main>
            <!-- MAIN -->
        </section>

        <!--------------------- Modal ------------------------>
        {{-- Modal Thêm người dùng --}}
        <div id="createModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Thêm người dùng mới</h3>
                    <span class="modal-close" onclick="closeCreateModal()">&times;</span>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" id="createForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="form_type" value="create">
                    <div class="modal-body">
                        @if($errors->any())
                            <div class="alert alert-danger" style="margin-bottom: 20px;">
                                <strong><i class="fa-solid fa-circle-exclamation"></i> Có lỗi xảy ra:</strong>
                                <ul style="margin: 10px 0 0 20px; padding: 0;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Tên người dùng <span class="required">*</span></label>
                                <input type="text" name="TenNguoiDung" class="form-control @error('TenNguoiDung') is-invalid @enderror" value="{{ old('TenNguoiDung') }}" required>
                                @error('TenNguoiDung')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Email <span class="required">*</span></label>
                                <input type="email" name="Email" class="form-control @error('Email') is-invalid @enderror" value="{{ old('Email') }}" required>
                                @error('Email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Mật khẩu <span class="required">*</span></label>
                                <input type="password" name="MatKhau" class="form-control @error('MatKhau') is-invalid @enderror" required>
                                @error('MatKhau')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" name="SDT" class="form-control @error('SDT') is-invalid @enderror" value="{{ old('SDT') }}">
                                @error('SDT')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row align-start">
                            <div class="form-group">
                                <label>Địa chỉ</label>
                                <input type="text" name="DiaChi" class="form-control @error('DiaChi') is-invalid @enderror" value="{{ old('DiaChi') }}">
                                @error('DiaChi')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group image-group">
                                <label>Ảnh đại diện</label>
                                <div class="avatar-upload">
                                    @php
                                        $defaultAvatar = asset('template/Assets/Images/default-avatar.png');
                                    @endphp
                                    <img src="{{ $defaultAvatar }}" class="avatar-preview" id="create_avatar_preview" data-default="{{ $defaultAvatar }}" alt="Xem trước ảnh">
                                    <label class="upload-label d-flex">
                                        <i class="fa-solid fa-upload"></i> Chọn ảnh
                                        <input type="file" name="HinhAnh" id="create_avatar_input" accept="image/*">
                                    </label>
                                </div>
                                @error('HinhAnh')
                                    <span class="invalid-feedback" style="display: block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Ngày sinh</label>
                                <input type="date" name="NgaySinh" class="form-control @error('NgaySinh') is-invalid @enderror" value="{{ old('NgaySinh') }}">
                                @error('NgaySinh')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Vai trò <span class="required">*</span></label>
                                <select name="IDVaiTro" class="form-control @error('IDVaiTro') is-invalid @enderror" required autocomplete="off">
                                    <option value="">-- Chọn vai trò --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->ID }}" {{ old('IDVaiTro') == $role->ID ? 'selected' : '' }}>
                                            {{ $role->TenVaiTro }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('IDVaiTro')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái <span class="required">*</span></label>
                            <select name="TrangThai" class="form-control @error('TrangThai') is-invalid @enderror" required autocomplete="off">
                                <option value="1" {{ old('TrangThai', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('TrangThai') == '0' ? 'selected' : '' }}>Bị khóa</option>
                            </select>
                            @error('TrangThai')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-floppy-disk"></i> Lưu
                        </button>
                        <button type="button" class="btn-cancel" onclick="closeCreateModal()">
                            <i class="fa-solid fa-xmark"></i> Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Sửa người dùng --}}
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Sửa thông tin người dùng</h3>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_type" value="edit">
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Tên người dùng <span class="required">*</span></label>
                                <input type="text" name="TenNguoiDung" id="edit_TenNguoiDung" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Email <span class="required">*</span></label>
                                <input type="email" name="Email" id="edit_Email" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Mật khẩu mới</label>
                                <input type="password" name="MatKhau" class="form-control" placeholder="Để trống nếu không đổi">
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" name="SDT" id="edit_SDT" class="form-control">
                            </div>
                        </div>
                        <div class="form-row align-start">
                            <div class="form-group">
                                <label>Địa chỉ</label>
                                <input type="text" name="DiaChi" id="edit_DiaChi" class="form-control">
                            </div>
                            <div class="form-group image-group">
                                <label>Ảnh đại diện</label>
                                @php
                                    $defaultAvatar = asset('template/Assets/Images/default-avatar.png');
                                @endphp
                                <div class="avatar-upload">
                                    <img src="{{ $defaultAvatar }}" class="avatar-preview" id="edit_avatar_preview" data-default="{{ $defaultAvatar }}" alt="Xem trước ảnh">
                                    <label class="upload-label">
                                        <i class="fa-solid fa-upload"></i> Chọn ảnh mới
                                        <input type="file" name="HinhAnh" id="edit_avatar_input" accept="image/*">
                                    </label>
                                </div>
                                @error('HinhAnh')
                                    <span class="invalid-feedback" style="display: block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Ngày sinh</label>
                                <input type="date" name="NgaySinh" id="edit_NgaySinh" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Vai trò <span class="required">*</span></label>
                                <select name="IDVaiTro" id="edit_IDVaiTro" class="form-control" required autocomplete="off">
                                    <option value="">-- Chọn vai trò --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->ID }}">{{ $role->TenVaiTro }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái <span class="required">*</span></label>
                            <select name="TrangThai" id="edit_TrangThai" class="form-control" required autocomplete="off">
                                <option value="1">Hoạt động</option>
                                <option value="0">Bị khóa</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-floppy-disk"></i> Cập nhật
                        </button>
                        <button type="button" class="btn-cancel" onclick="closeEditModal()">
                            <i class="fa-solid fa-xmark"></i> Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Xem chi tiết --}}
        <div id="viewModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Thông tin chi tiết người dùng</h3>
                    <span class="modal-close" onclick="closeViewModal()">&times;</span>
                </div>
                <div class="modal-body">
                    @php
                        $defaultAvatar = asset('template/Assets/Images/default-avatar.png');
                    @endphp
                    <img src="{{ $defaultAvatar }}" id="view_Avatar" class="detail-avatar avatar-preview" alt="Ảnh người dùng" data-default="{{ $defaultAvatar }}">
                    <div class="detail-row">
                        <strong>Tên người dùng:</strong>
                        <span id="view_TenNguoiDung"></span>
                    </div>
                    <div class="detail-row">
                        <strong>Email:</strong>
                        <span id="view_Email"></span>
                    </div>
                    <div class="detail-row">
                        <strong>Số điện thoại:</strong>
                        <span id="view_SDT"></span>
                    </div>
                    <div class="detail-row">
                        <strong>Địa chỉ:</strong>
                        <span id="view_DiaChi"></span>
                    </div>
                    <div class="detail-row">
                        <strong>Ngày sinh:</strong>
                        <span id="view_NgaySinh"></span>
                    </div>
                    <div class="detail-row">
                        <strong>Vai trò:</strong>
                        <span id="view_VaiTro"></span>
                    </div>
                    <div class="detail-row">
                        <strong>Trạng thái:</strong>
                        <span id="view_TrangThai"></span>
                    </div>
                    <div class="detail-row">
                        <strong>Ngày tạo:</strong>
                        <span id="view_CreatedAt"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeViewModal()">
                        <i class="fa-solid fa-xmark"></i> Đóng
                    </button>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="/template/admin/script.js"></script>
        <script>
    // === Tối ưu hóa modal bằng class thay vì display ===
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('active');
        document.body.style.overflow = 'hidden'; // ngăn scroll nền
    }

    function closeModal(modalId) {
        const modalElement = document.getElementById(modalId);
        if (!modalElement) return;
        modalElement.classList.remove('active');
        document.body.style.overflow = 'auto';
        const form = modalElement.querySelector('form');
        if (form) form.reset();
        modalElement.querySelectorAll('.avatar-preview').forEach(img => {
            if (img.dataset.default) {
                img.src = img.dataset.default;
            }
        });
    }

    function updatePreviewImage(inputElement, previewId) {
        const preview = document.getElementById(previewId);
        if (!preview) return;
        if (inputElement.files && inputElement.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(inputElement.files[0]);
        } else if (preview.dataset.default) {
            preview.src = preview.dataset.default;
        }
    }

    function setPreviewImage(previewId, imageUrl) {
        const preview = document.getElementById(previewId);
        if (!preview) return;
        preview.src = imageUrl || preview.dataset.default;
    }

    // Đóng modal khi click nền
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });

    // === Thêm người dùng ===
    function openCreateModal() {
        openModal('createModal');
    }

    function closeCreateModal() {
        closeModal('createModal');
    }

    // === Sửa người dùng - ĐÃ SỬA HOÀN TOÀN ===
    function editUser(id) {
        fetch(`/admin/users/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Không tải được dữ liệu');
            return response.json();
        })
        .then(data => {
            console.log('Dữ liệu user:', data);

            // Điền dữ liệu vào các field
            document.getElementById('edit_TenNguoiDung').value = data.TenNguoiDung || '';
            document.getElementById('edit_Email').value = data.Email || '';
            document.getElementById('edit_SDT').value = data.SDT || '';
            document.getElementById('edit_DiaChi').value = data.DiaChi || '';
            document.getElementById('edit_NgaySinh').value = data.NgaySinh || '';

            // Quan trọng: set select đúng cách
            const roleSelect = document.getElementById('edit_IDVaiTro');
            roleSelect.value = data.IDVaiTro; // Laravel trả về số nguyên → value là string nhưng vẫn match

            const statusSelect = document.getElementById('edit_TrangThai');
            statusSelect.value = data.TrangThai == 1 ? '1' : '0';

            setPreviewImage('edit_avatar_preview', data.avatar_url);

            // Set action cho form
            document.getElementById('editForm').action = `/admin/users/${id}`;

            // Mở modal sau khi đã điền dữ liệu
            openModal('editModal');
        })
        .catch(err => {
            console.error(err);
            alert('Không thể tải thông tin người dùng. Vui lòng thử lại!');
        });
    }

    function closeEditModal() {
        closeModal('editModal');
    }

    // === Xem chi tiết ===
    function viewUser(id) {
        fetch(`/admin/users/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('view_TenNguoiDung').textContent = data.TenNguoiDung || '-';
            document.getElementById('view_Email').textContent = data.Email || '-';
            document.getElementById('view_SDT').textContent = data.SDT || 'Chưa cập nhật';
            document.getElementById('view_DiaChi').textContent = data.DiaChi || 'Chưa cập nhật';
            document.getElementById('view_NgaySinh').textContent = data.NgaySinh || 'Chưa cập nhật';
            document.getElementById('view_VaiTro').textContent = data.vai_tro?.TenVaiTro || data.vaiTro?.TenVaiTro || '-';
            document.getElementById('view_TrangThai').textContent = data.TrangThai == 1 ? 'Hoạt động' : 'Bị khóa';
            document.getElementById('view_CreatedAt').textContent = data.created_at ? new Date(data.created_at).toLocaleDateString('vi-VN') : '-';
            setPreviewImage('view_Avatar', data.avatar_url);

            openModal('viewModal');
        })
        .catch(() => alert('Lỗi tải thông tin!'));
    }

    function closeViewModal() {
        closeModal('viewModal');
    }

    // === Preview avatar ===
    const createAvatarInput = document.getElementById('create_avatar_input');
    if (createAvatarInput) {
        createAvatarInput.addEventListener('change', function() {
            updatePreviewImage(this, 'create_avatar_preview');
        });
    }

    const editAvatarInput = document.getElementById('edit_avatar_input');
    if (editAvatarInput) {
        editAvatarInput.addEventListener('change', function() {
            updatePreviewImage(this, 'edit_avatar_preview');
        });
    }

    // === Khóa / Mở khóa người dùng ===
    function toggleUserStatus(id, isActive) {
        const message = isActive ? 'Khóa tài khoản người dùng này?' : 'Mở khóa tài khoản người dùng này?';
        if (!confirm(message)) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${id}/toggle-status`;
        form.style.display = 'none';

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        form.appendChild(tokenInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }

    // === Xóa người dùng ===
    function deleteUser(id) {
        if (confirm('Xóa người dùng này? Hành động không thể hoàn tác!')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${id}`;
            form.style.display = 'none';

            form.innerHTML = `
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <input name="_method" value="DELETE" type="hidden">
            `;

            document.body.appendChild(form);
            form.submit();
        }
    }

    // === Tự động mở modal tạo nếu có lỗi validate ===
    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->any() && request()->isMethod('post') && request()->route()->getName() == 'admin.users.store')
            openCreateModal();
        @endif
    });
</script>
    </body>
</html>
