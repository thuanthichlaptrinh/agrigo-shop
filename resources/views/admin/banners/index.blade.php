@extends('admin.layouts.app')

@section('title', 'Quản lý Banner')

@section('content')
<style>
    .banner-preview {
        width: 100%;
        max-width: 200px;
        height: auto;
        border-radius: 8px;
        object-fit: cover;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .status-badge.active {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }
    
    .status-badge.inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .position-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .position-badge.home {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .position-badge.product {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .position-badge.promotion {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    
    .position-badge.sidebar {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }
</style>

<div class="container">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h1 style="font-size: 24px; font-weight: 700; margin: 0;">
                <i class="fa-solid fa-image me-2"></i>
                Quản lý Banner
            </h1>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fa-solid fa-plus me-1"></i>
                Thêm Banner
            </button>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.banners.index') }}" class="row g-3 mb-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm banner..." value="{{ request('search') }}">
                </div>
                
                <div class="col-md-2">
                    <select name="vi_tri" class="form-select">
                        <option value="">Tất cả vị trí</option>
                        <option value="Trang chủ" {{ request('vi_tri') == 'Trang chủ' ? 'selected' : '' }}>Trang chủ</option>
                        <option value="Sản phẩm" {{ request('vi_tri') == 'Sản phẩm' ? 'selected' : '' }}>Sản phẩm</option>
                        <option value="Khuyến mãi" {{ request('vi_tri') == 'Khuyến mãi' ? 'selected' : '' }}>Khuyến mãi</option>
                        <option value="Sidebar" {{ request('vi_tri') == 'Sidebar' ? 'selected' : '' }}>Sidebar</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select name="trang_thai" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>
                
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i>
                        Lọc
                    </button>
                </div>
                
                <div class="col-md-auto">
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-rotate-right"></i>
                        Đặt lại
                    </a>
                </div>
            </form>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa-solid fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fa-solid fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <strong>Đã xảy ra lỗi:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="table-wrapper">
                <table class="authors-table">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th width="200">Hình ảnh</th>
                            <th width="280">Tiêu đề</th>
                            <th width="150">Vị trí</th>
                            <th width="100" class="text-center">Thứ tự</th>
                            <th width="120">Trạng thái</th>
                            <th width="150" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                        <tr>
                            <td>{{ $banner->ID }}</td>
                            <td>
                                <img src="{{ asset('uploads/banners/' . $banner->HinhAnh) }}" alt="{{ $banner->TieuDe }}" class="banner-preview">
                            </td>
                            <td>
                                <div style="font-weight: 600; margin-bottom: 4px;">{{ $banner->TieuDe }}</div>
                                @if($banner->LienKet)
                                <small class="text-muted">
                                    <i class="fa-solid fa-link"></i>
                                    {{ Str::limit($banner->LienKet, 50) }}
                                </small>
                                @endif
                            </td>
                            <td>
                                <span class="position-badge {{ 
                                    $banner->ViTri == 'Trang chủ' ? 'home' : 
                                    ($banner->ViTri == 'Sản phẩm' ? 'product' : 
                                    ($banner->ViTri == 'Khuyến mãi' ? 'promotion' : 'sidebar'))
                                }}">
                                    {{ $banner->ViTri }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span style="font-weight: 700; font-size: 16px; color: #667eea;">{{ $banner->ThuTu }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $banner->TrangThai ? 'active' : 'inactive' }}" 
                                      onclick="toggleStatus({{ $banner->ID }})">
                                    <i class="fa-solid fa-{{ $banner->TrangThai ? 'check-circle' : 'times-circle' }}"></i>
                                    {{ $banner->TrangThai ? 'Hoạt động' : 'Tắt' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary" onclick="openEditModal({{ $banner->ID }})" title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.banners.destroy', $banner->ID) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fa-solid fa-image" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;"></i>
                                <p class="text-muted">Chưa có banner nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($banners->hasPages())
            <div class="mt-3">
                {{ $banners->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-plus-circle me-2"></i>
                    Thêm Banner Mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="TieuDe" value="{{ old('TieuDe') }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh <span class="text-danger">*</span></label>
                        <input type="file" name="HinhAnh" class="form-control" accept="image/*" onchange="previewImage(this, 'createPreview')" required>
                        <small class="text-muted">Định dạng: JPG, PNG, GIF, WebP. Kích thước tối đa: 2MB</small>
                        <div id="createPreview" class="mt-3" style="display: none;">
                            <img src="" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Liên kết</label>
                        <input type="url" name="LienKet" value="{{ old('LienKet') }}" class="form-control" placeholder="https://...">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vị trí <span class="text-danger">*</span></label>
                            <select name="ViTri" class="form-select" required>
                                <option value="">Chọn vị trí</option>
                                <option value="Trang chủ" {{ old('ViTri') == 'Trang chủ' ? 'selected' : '' }}>Trang chủ</option>
                                <option value="Sản phẩm" {{ old('ViTri') == 'Sản phẩm' ? 'selected' : '' }}>Sản phẩm</option>
                                <option value="Khuyến mãi" {{ old('ViTri') == 'Khuyến mãi' ? 'selected' : '' }}>Khuyến mãi</option>
                                <option value="Sidebar" {{ old('ViTri') == 'Sidebar' ? 'selected' : '' }}>Sidebar</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thứ tự <span class="text-danger">*</span></label>
                            <input type="number" name="ThuTu" class="form-control" value="{{ old('ThuTu', 1) }}" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="TrangThai" value="0">
                            <input class="form-check-input" type="checkbox" name="TrangThai" value="1" id="createStatus" {{ old('TrangThai', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="createStatus">
                                Kích hoạt ngay
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-1"></i>
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-1"></i>
                        Lưu Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-edit me-2"></i>
                    Chỉnh sửa Banner
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="TieuDe" id="editTieuDe" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        <div class="mb-2">
                            <img id="editCurrentImage" src="" alt="Current" style="max-width: 200px; border-radius: 8px;">
                        </div>
                        <label class="form-label">Thay đổi hình ảnh (tùy chọn)</label>
                        <input type="file" name="HinhAnh" class="form-control" accept="image/*" onchange="previewImage(this, 'editPreview')">
                        <small class="text-muted">Để trống nếu không muốn thay đổi. Định dạng: JPG, PNG, GIF, WebP. Kích thước tối đa: 2MB</small>
                        <div id="editPreview" class="mt-3" style="display: none;">
                            <img src="" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Liên kết</label>
                        <input type="url" name="LienKet" id="editLienKet" class="form-control" placeholder="https://...">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vị trí <span class="text-danger">*</span></label>
                            <select name="ViTri" id="editViTri" class="form-select" required>
                                <option value="Trang chủ">Trang chủ</option>
                                <option value="Sản phẩm">Sản phẩm</option>
                                <option value="Khuyến mãi">Khuyến mãi</option>
                                <option value="Sidebar">Sidebar</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thứ tự <span class="text-danger">*</span></label>
                            <input type="number" name="ThuTu" id="editThuTu" class="form-control" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="TrangThai" value="0">
                            <input class="form-check-input" type="checkbox" name="TrangThai" value="1" id="editStatus">
                            <label class="form-check-label" for="editStatus">
                                Kích hoạt
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-1"></i>
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-1"></i>
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    const modal = new bootstrap.Modal(document.getElementById('createModal'));
    modal.show();
}

function openEditModal(id) {
    fetch(`/admin/banners/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editForm').action = `/admin/banners/${id}`;
            document.getElementById('editTieuDe').value = data.TieuDe;
            document.getElementById('editLienKet').value = data.LienKet || '';
            document.getElementById('editViTri').value = data.ViTri;
            document.getElementById('editThuTu').value = data.ThuTu;
            document.getElementById('editStatus').checked = data.TrangThai == 1;
            document.getElementById('editCurrentImage').src = `/uploads/banners/${data.HinhAnh}`;
            document.getElementById('editPreview').style.display = 'none';
            
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin banner');
        });
}

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const img = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

function toggleStatus(id) {
    if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái banner này?')) {
        return;
    }
    
    fetch(`/admin/banners/${id}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thay đổi trạng thái');
    });
}
</script>

@endsection
