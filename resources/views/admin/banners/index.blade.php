@extends('admin.layouts.app')

@section('title', 'Quản lý Banner')

@section('content')
@push('styles')
<style>
    .banner-page {
        padding: 24px;
        background: linear-gradient(120deg, #f8fafc 0%, #eef2ff 100%);
        border-radius: 28px;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
    }

    .banner-hero {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        border-radius: 24px;
        padding: 24px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        gap: 24px;
        align-items: center;
        box-shadow: 0 25px 50px rgba(37, 99, 235, 0.25);
    }

    .banner-hero h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .banner-hero p {
        margin: 0;
        opacity: 0.9;
    }

    .btn-gradient {
        background: #fff;
        color: #1d4ed8;
        font-weight: 600;
        border-radius: 14px;
        padding: 12px 22px;
        border: none;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 30px rgba(15, 23, 42, 0.25);
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 18px;
        margin: 24px 0;
    }

    .stat-card {
        background: #fff;
        border-radius: 20px;
        padding: 18px;
        border: 1px solid #eef2ff;
        box-shadow: 0 15px 30px rgba(15,23,42,0.05);
    }

    .stat-card span {
        font-size: 13px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card h3 {
        margin: 10px 0 0;
        font-size: 30px;
        font-weight: 700;
        color: #0f172a;
    }

    .stat-card small {
        color: #64748b;
        font-size: 12px;
    }

    .filter-card, .table-card {
        border-radius: 24px;
        background: #fff;
        box-shadow: 0 20px 40px rgba(15,23,42,0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }

    .filter-card .card-body {
        padding: 20px;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 11px 14px;
    }

    .filter-card label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .table-card .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-card thead th {
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.8px;
        color: #94a3b8;
        padding: 14px 18px;
        background: #f8fafc;
    }

    .table-card tbody td {
        padding: 18px;
        border-bottom: 1px solid #eef2ff;
        vertical-align: middle;
        font-size: 15px;
    }

    .banner-preview {
        width: 180px;
        height: 90px;
        border-radius: 18px;
        object-fit: cover;
        box-shadow: 0 8px 18px rgba(15,23,42,0.15);
        border: 1px solid rgba(255,255,255,0.6);
    }

    .tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
    }

    .tag-home { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
    .tag-product { background: linear-gradient(135deg, #ec4899, #f97316); }
    .tag-promotion { background: linear-gradient(135deg, #0ea5e9, #14b8a6); }
    .tag-sidebar { background: linear-gradient(135deg, #10b981, #22c55e); }

    .status-chip {
        border-radius: 999px;
        padding: 6px 14px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .status-chip.active {
        background: rgba(34, 197, 94, 0.15);
        color: #16a34a;
        border-color: rgba(34,197,94,0.3);
    }

    .status-chip.inactive {
        background: rgba(248,113,113,0.15);
        color: #dc2626;
        border-color: rgba(248,113,113,0.3);
    }

    .status-chip:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(15,23,42,0.08);
    }

    .action-group button {
        border-radius: 12px;
        border: none;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 6px;
        color: #0f172a;
        background: #f8fafc;
    }

    .action-group button:hover {
        background: #e0e7ff;
        color: #4338ca;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 12px;
    }

    @media (max-width: 992px) {
        .banner-hero {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@php
    $stats = $stats ?? [];
    $statTotal = number_format($stats['total'] ?? $banners->total());
    $statActive = number_format($stats['active'] ?? 0);
    $statInactive = number_format($stats['inactive'] ?? 0);
    $statHome = number_format($stats['home'] ?? 0);
@endphp

<div class="banner-page">
    <div class="banner-hero">
        <div>
            <h1><i class="fa-solid fa-image me-2"></i>Quản lý Banner</h1>
            <p>Theo dõi và tối ưu trải nghiệm hình ảnh cho toàn bộ website.</p>
        </div>
        <button class="btn-gradient" onclick="openCreateModal()">
            <i class="fa-solid fa-plus me-2"></i> Thêm banner mới
        </button>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <span>Tổng banner</span>
            <h3>{{ $statTotal }}</h3>
            <small>Hiện còn {{ $statActive }} banner đang hoạt động</small>
        </div>
        <div class="stat-card">
            <span>Đang hiển thị</span>
            <h3>{{ $statActive }}</h3>
            <small>{{ $statInactive }} banner đang tạm ẩn</small>
        </div>
        <div class="stat-card">
            <span>Trang chủ</span>
            <h3>{{ $statHome }}</h3>
            <small>Banner ở khu vực nổi bật nhất</small>
        </div>
    </div>

    <div class="filter-card card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.banners.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Từ khóa</label>
                    <input type="text" name="search" class="form-control" placeholder="Tìm tiêu đề hoặc liên kết" value="{{ request('search') }}">
                </div>
                <div class="col-lg-3 col-md-4">
                    <label class="form-label">Vị trí</label>
                    <select name="vi_tri" class="form-select">
                        <option value="">Tất cả vị trí</option>
                        <option value="Trang chủ" {{ request('vi_tri') == 'Trang chủ' ? 'selected' : '' }}>Trang chủ</option>
                        <option value="Sản phẩm" {{ request('vi_tri') == 'Sản phẩm' ? 'selected' : '' }}>Sản phẩm</option>
                        <option value="Khuyến mãi" {{ request('vi_tri') == 'Khuyến mãi' ? 'selected' : '' }}>Khuyến mãi</option>
                        <option value="Sidebar" {{ request('vi_tri') == 'Sidebar' ? 'selected' : '' }}>Sidebar</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>
                <div class="col-md-5 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-fill">
                        <i class="fa-solid fa-filter me-1"></i> Lọc
                    </button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary flex-fill">Đặt lại</a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-card card">
        <div class="card-header">
            <div>
                <h5 class="mb-0">Danh sách banner</h5>
                <small class="text-muted">Hiển thị {{ $banners->firstItem() ?? 0 }} - {{ $banners->lastItem() ?? 0 }} / {{ $banners->total() }}</small>
            </div>
            <span class="badge bg-primary-subtle text-primary">{{ $banners->count() }} mục trên trang</span>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Thông tin</th>
                        <th>Vị trí</th>
                        <th class="text-center">Thứ tự</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                    <tr>
                        <td><strong>#{{ $banner->ID }}</strong></td>
                        <td>
                            <img src="{{ asset('uploads/banners/' . $banner->HinhAnh) }}" alt="{{ $banner->TieuDe }}" class="banner-preview">
                        </td>
                        <td>
                            <div class="fw-semibold text-primary mb-1">{{ $banner->TieuDe }}</div>
                            @if($banner->LienKet)
                                <small class="text-muted"><i class="fa-solid fa-link me-1"></i>{{ Str::limit($banner->LienKet, 50) }}</small>
                            @else
                                <small class="text-muted">Không có liên kết</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $tagClass = match($banner->ViTri) {
                                    'Trang chủ' => 'tag-home',
                                    'Sản phẩm' => 'tag-product',
                                    'Khuyến mãi' => 'tag-promotion',
                                    default => 'tag-sidebar'
                                };
                            @endphp
                            <span class="tag-chip {{ $tagClass }}">
                                <i class="fa-solid fa-location-arrow"></i> {{ $banner->ViTri }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold" style="font-size: 16px; color: #4c1d95;">{{ $banner->ThuTu }}</span>
                        </td>
                        <td>
                            <span class="status-chip {{ $banner->TrangThai ? 'active' : 'inactive' }}" onclick="toggleStatus({{ $banner->ID }})">
                                <i class="fa-solid fa-{{ $banner->TrangThai ? 'circle-check' : 'circle-xmark' }}"></i>
                                {{ $banner->TrangThai ? 'Đang hiển thị' : 'Đang tắt' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="action-group">
                                <button type="button" onclick="openEditModal({{ $banner->ID }})" title="Chỉnh sửa"><i class="fa-solid fa-pen"></i></button>
                                <form action="{{ route('admin.banners.destroy', $banner->ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fa-solid fa-images"></i>
                                <p>Chưa có banner nào phù hợp với điều kiện lọc.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($banners->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">Trang {{ $banners->currentPage() }} / {{ $banners->lastPage() }}</small>
            <div class="pagination-wrapper">
                {{ $banners->links('vendor.pagination.admin-users') }}
            </div>
        </div>
        @endif
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

@push('scripts')
<script>
const toastFallback = (message, type = 'info') => {
    const log = type === 'error' ? console.error : console.log;
    log(message);
    if (typeof window !== 'undefined' && window.alert) {
        alert(message);
    }
};

const queueToast = (message, type = 'info') => {
    if (window.AppAlert && typeof window.AppAlert.queue === 'function') {
        window.AppAlert.queue({ message, type });
        return;
    }
    toastFallback(message, type);
};

const showToast = (message, type = 'info') => {
    if (window.AppAlert && typeof window.AppAlert.show === 'function') {
        window.AppAlert.show(message, { type });
        return;
    }
    queueToast(message, type);
};

function openCreateModal() {
    const modal = new bootstrap.Modal(document.getElementById('createModal'));
    modal.show();
}

function openEditModal(id) {
    fetch(`/admin/banners/${id}`)
        .then(response => response.ok ? response.json() : Promise.reject(response))
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
        .catch(() => {
            showToast('Không thể tải thông tin banner. Vui lòng thử lại.', 'error');
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
            queueToast(data.message || 'Đã cập nhật trạng thái banner.', 'success');
            setTimeout(() => window.location.reload(), 800);
        } else {
            showToast(data.message || 'Không thể cập nhật trạng thái.', 'error');
        }
    })
    .catch(() => {
        showToast('Có lỗi xảy ra khi thay đổi trạng thái.', 'error');
    });
}

@if($errors->any())
document.addEventListener('DOMContentLoaded', () => {
    @foreach($errors->all() as $error)
        queueToast(@json($error), 'error');
    @endforeach
});
@endif
</script>
@endpush

@endsection
