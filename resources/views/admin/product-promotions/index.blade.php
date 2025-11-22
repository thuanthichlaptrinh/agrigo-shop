@extends('admin.layouts.app')

@section('title', 'Quản lý sản phẩm khuyến mãi')

@php
    $productMap = $productOptions->mapWithKeys(function ($product) {
        return [$product->ID => [
            'id' => $product->ID,
            'name' => $product->TenSanPham,
            'price' => (float) ($product->Gia ?? 0),
            'image' => $product->HinhAnh,
        ]];
    });

    $promotionMap = $promotionOptions->mapWithKeys(function ($promotion) {
        return [$promotion->ID => [
            'id' => $promotion->ID,
            'name' => $promotion->TenKhuyenMai,
            'type' => $promotion->LoaiKhuyenMai,
            'value' => (float) ($promotion->GiaTriGiam ?? 0),
            'max' => $promotion->GiamToiDa ? (float) $promotion->GiamToiDa : null,
            'status' => (bool) $promotion->TrangThai,
            'start' => optional($promotion->NgayBatDau)->format('Y-m-d H:i:s'),
            'end' => optional($promotion->NgayKetThuc)->format('Y-m-d H:i:s'),
        ]];
    });
@endphp

@section('content')
<style>
    :root {
        --primary:#2563eb;
        --primary-dark:#1d4ed8;
        --muted:#64748b;
        --success:#16a34a;
        --danger:#dc2626;
        --warning:#f59e0b;
        --card:#ffffff;
        --bg:#f8fafc;
        --border:#e2e8f0;
        --shadow:0 12px 30px rgba(15, 23, 42, 0.08);
    }
    .page-header {
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        flex-wrap:wrap;
        margin-bottom:24px;
    }
    .page-header h2 {
        font-size:28px;
        font-weight:700;
        color:#0f172a;
        margin-bottom:6px;
    }
    .action-group {
        display:flex;
        gap:12px;
        flex-wrap:wrap;
    }
    .stat-grid {
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
        gap:18px;
        margin-bottom:24px;
    }
    .stat-card {
        background:var(--card);
        padding:20px;
        border-radius:18px;
        border:1px solid rgba(226,232,240,.8);
        box-shadow:var(--shadow);
        position:relative;
        overflow:hidden;
    }
    .stat-card span {font-size:12px;font-weight:600;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;}
    .stat-card h3 {font-size:30px;margin:10px 0 0;color:#0f172a;}
    .stat-card small {color:var(--muted);}
    .filter-card {
        background:var(--card);
        border-radius:20px;
        padding:22px;
        border:1px solid var(--border);
        margin-bottom:24px;
        box-shadow:var(--shadow);
    }
    .filter-grid {
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:14px;
    }
    .filter-grid label {
        font-weight:600;
        font-size:13px;
        color:#475569;
        margin-bottom:6px;
        display:block;
    }
    .filter-grid .form-control,
    .filter-grid .form-select {
        border-radius:12px;
        border:2px solid var(--border);
        padding:9px 14px;
        font-size:14px;
    }
    .filter-actions {
        display:flex;
        gap:12px;
        grid-column:1 / -1;
    }
    .table-card {
        background:var(--card);
        border-radius:22px;
        border:1px solid rgba(226,232,240,.9);
        box-shadow:var(--shadow);
    }
    .table-card .card-header {
        border-bottom:1px solid var(--border);
        padding:20px 24px;
    }
    table {width:100%;border-collapse:collapse;}
    thead th {
        font-size:12px;
        text-transform:uppercase;
        letter-spacing:0.7px;
        color:var(--muted);
        padding:14px 18px;
        background:var(--bg);
    }
    tbody td {
        padding:16px 18px;
        border-bottom:1px solid #f1f5f9;
        vertical-align:middle;
    }
    tbody tr:hover {background:#f8fafc;}
    .product-cell {display:flex;gap:14px;align-items:center;}
    .product-thumb {
        width:60px;height:60px;border-radius:16px;object-fit:cover;
        border:2px solid #e2e8f0;
    }
    .badge-status {
        padding:6px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:600;
        display:inline-flex;
        align-items:center;
        gap:6px;
    }
    .badge-active {background:rgba(34,197,94,.15);color:#15803d;}
    .badge-upcoming {background:rgba(59,130,246,.15);color:#1d4ed8;}
    .badge-expired {background:rgba(248,113,113,.15);color:#b91c1c;}
    .badge-muted {background:rgba(148,163,184,.2);color:#475569;}
    .price-highlight {font-weight:700;color:#0f172a;}
    .price-sub {font-size:13px;color:var(--muted);}
    .action-btn {
        width:38px;height:38px;border:none;border-radius:12px;
        display:inline-flex;align-items:center;justify-content:center;
        margin-right:6px;
    }
    .action-btn.view {background:rgba(59,130,246,.15);color:#1d4ed8;}
    .action-btn.edit {background:rgba(251,191,36,.2);color:#a16207;}
    .action-btn.delete {background:rgba(248,113,113,.2);color:#b91c1c;}
    .modal .form-control,
    .modal .form-select {
        border-radius:12px;
        border:1px solid var(--border);
    }
    .bulk-select-wrapper {
        border:1px solid var(--border);
        border-radius:14px;
        padding:14px;
        background:#f8fafc;
    }
    .bulk-select-wrapper .form-control {
        margin-bottom:10px;
        border-radius:10px;
        border:1px solid #d1d5db;
    }
    .bulk-multi-select {
        width:100%;
        height:250px;
        border-radius:12px;
        border:1px solid #cbd5f5;
        padding:8px;
        background:#fff;
        font-size:14px;
    }
    #bulkNoResults {
        font-size:13px;
        color:#9ca3af;
        display:none;
    }
    .price-preview {
        border:1px dashed var(--border);
        border-radius:14px;
        padding:14px;
        background:#f8fafc;
    }
</style>

<div class="container-fluid py-3">
    <div class="page-header">
        <div>
            <h2><i class="fa-solid fa-fire me-2 text-warning"></i>Quản lý sản phẩm khuyến mãi</h2>
            <p class="text-muted mb-0">Liên kết sản phẩm với các chương trình khuyến mãi, quản lý trạng thái và giá trị giảm.</p>
        </div>
        <div class="action-group">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkPromotionModal">
                <i class="fa-solid fa-layer-group me-1"></i> Thêm nhiều sản phẩm
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAssignmentModal">
                <i class="fa-solid fa-plus me-1"></i> Thêm sản phẩm
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Xuất hiện lỗi:</strong>
            <ul class="mt-2 mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="stat-grid">
        <div class="stat-card">
            <span>Tổng liên kết</span>
            <h3>{{ number_format($stats['total'] ?? 0) }}</h3>
            <small>Số sản phẩm đang nằm trong chương trình khuyến mãi</small>
        </div>
        <div class="stat-card">
            <span>Đang chạy</span>
            <h3>{{ number_format($stats['active'] ?? 0) }}</h3>
            <small>Khuyến mãi hoạt động thời điểm hiện tại</small>
        </div>
        <div class="stat-card">
            <span>Sắp diễn ra</span>
            <h3>{{ number_format($stats['upcoming'] ?? 0) }}</h3>
            <small>Khuyến mãi đã lên lịch và bật trạng thái</small>
        </div>
        <div class="stat-card">
            <span>Sản phẩm tham gia</span>
            <h3>{{ number_format($stats['uniqueProducts'] ?? 0) }}</h3>
            <small>Tổng số sản phẩm khác nhau đang được giảm giá</small>
        </div>
    </div>

    <div class="filter-card">
        <form method="GET" action="{{ route('admin.product-promotions.index') }}" class="filter-grid align-items-end">
            <div>
                <label>Từ khóa</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tên sản phẩm hoặc chương trình">
            </div>
            <div>
                <label>Chương trình</label>
                <select name="promotion_id" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach($promotionOptions as $promotion)
                        <option value="{{ $promotion->ID }}" {{ (string)request('promotion_id') === (string)$promotion->ID ? 'selected' : '' }}>
                            {{ $promotion->TenKhuyenMai }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Danh mục</label>
                <select name="category_id" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->ID }}" {{ (string)request('category_id') === (string)$category->ID ? 'selected' : '' }}>
                            {{ $category->TenLoai }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>Đang chạy</option>
                    <option value="upcoming" {{ request('status')==='upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                    <option value="expired" {{ request('status')==='expired' ? 'selected' : '' }}>Đã kết thúc</option>
                    <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>Tạm khóa</option>
                </select>
            </div>
            <div>
                <label>Giá từ</label>
                <input type="number" min="0" name="price_min" value="{{ request('price_min') }}" class="form-control" placeholder="0">
            </div>
            <div>
                <label>Giá đến</label>
                <input type="number" min="0" name="price_max" value="{{ request('price_max') }}" class="form-control" placeholder="10.000.000">
            </div>
            <div>
                <label>Sắp xếp</label>
                <select name="sort_by" class="form-select">
                    @php $sortBy = request('sort_by','recent'); @endphp
                    <option value="recent" {{ $sortBy==='recent' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="product_name" {{ $sortBy==='product_name' ? 'selected' : '' }}>Tên sản phẩm</option>
                    <option value="promotion_end" {{ $sortBy==='promotion_end' ? 'selected' : '' }}>Ngày kết thúc</option>
                    <option value="price" {{ $sortBy==='price' ? 'selected' : '' }}>Giá sản phẩm</option>
                </select>
            </div>
            <div>
                <label>Thứ tự</label>
                <select name="sort_direction" class="form-select">
                    <option value="desc" {{ request('sort_direction','desc')==='desc' ? 'selected' : '' }}>Giảm dần</option>
                    <option value="asc" {{ request('sort_direction')==='asc' ? 'selected' : '' }}>Tăng dần</option>
                </select>
            </div>
            <div>
                <label>Số dòng</label>
                <select name="per_page" class="form-select">
                    @foreach($perPageOptions as $option)
                        <option value="{{ $option }}" {{ (int)request('per_page',10)===$option ? 'selected' : '' }}>{{ $option }} / trang</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
                <a href="{{ route('admin.product-promotions.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách sản phẩm khuyến mãi</h5>
            <span class="badge bg-primary rounded-pill">{{ $promotedProducts->total() }} bản ghi</span>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Chương trình</th>
                        <th>Giá & giảm</th>
                        <th>Ghi chú</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotedProducts as $item)
                        @php
                            $product = $item->sanPham;
                            $promotion = $item->khuyenMai;
                            $cover = $product && $product->HinhAnh ? asset($product->HinhAnh) : 'https://via.placeholder.com/120x120?text=No+Image';
                            $detailPayload = [
                                'product_name' => $product->TenSanPham ?? 'N/A',
                                'product_price' => $item->GiaGoc ?? 0,
                                'product_image' => $product->HinhAnh ? asset($product->HinhAnh) : null,
                                'category' => optional($product->loaiSanPham)->TenLoai,
                                'promotion_name' => $promotion->TenKhuyenMai ?? '---',
                                'promotion_note' => $item->GhiChu,
                                'promotion_range' => optional($promotion->NgayBatDau)->format('d/m/Y H:i') . ' - ' . optional($promotion->NgayKetThuc)->format('d/m/Y H:i'),
                                'discount_text' => $item->discount_text ?? '',
                                'final_price' => $item->GiaKhuyenMai ?? 0,
                                'state' => $item->promotion_state ?? ['label' => '', 'badge' => ''],
                                'product_id' => $item->IDSanPham,
                                'promotion_id' => $item->IDKhuyenMai,
                            ];
                        @endphp
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <img src="{{ $cover }}" class="product-thumb" alt="{{ $product->TenSanPham ?? 'Sản phẩm' }}">
                                    <div>
                                        <strong>{{ $product->TenSanPham ?? 'Sản phẩm không tồn tại' }}</strong>
                                        <div class="price-sub">#SP{{ $product->ID ?? '---' }} · {{ optional($product->NgayTao)->format('d/m/Y') ?? '---' }}</div>
                                        <div class="text-muted" style="font-size:12px;">{{ optional($product->loaiSanPham)->TenLoai ?? 'Không phân loại' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $promotion->TenKhuyenMai ?? '---' }}</div>
                                <div class="price-sub">{{ optional($promotion->NgayBatDau)->format('d/m/Y') }} - {{ optional($promotion->NgayKetThuc)->format('d/m/Y') }}</div>
                                @php $state = $item->promotion_state ?? ['label' => '', 'badge' => 'badge-muted']; @endphp
                                <span class="badge-status {{ $state['badge'] ?? 'badge-muted' }}">{{ $state['label'] ?? 'Chưa rõ' }}</span>
                            </td>
                            <td>
                                <div class="price-highlight">{{ number_format($item->GiaKhuyenMai ?? 0, 0, ',', '.') }} đ</div>
                                <div class="price-sub">Giá gốc: {{ number_format($item->GiaGoc ?? 0, 0, ',', '.') }} đ</div>
                                <span class="badge bg-light text-dark mt-2">{{ $item->discount_text }}</span>
                            </td>
                            <td>
                                {{ $item->GhiChu ? \Illuminate\Support\Str::limit($item->GhiChu, 60) : '—' }}
                            </td>
                            <td>
                                <button class="action-btn view" data-details='@json($detailPayload)' onclick="openDetailModal(this)" title="Chi tiết"><i class="fa-solid fa-eye"></i></button>
                                <button class="action-btn edit" data-product-id="{{ $item->IDSanPham }}" data-promotion-id="{{ $item->IDKhuyenMai }}" data-note="{{ $item->GhiChu }}" data-product-name="{{ $product->TenSanPham ?? 'N/A' }}" data-product-price="{{ $item->GiaGoc ?? 0 }}" data-action="{{ route('admin.product-promotions.update', [$item->IDSanPham, $item->IDKhuyenMai]) }}" onclick="openEditModal(this)" title="Chỉnh sửa"><i class="fa-solid fa-pen"></i></button>
                                <form action="{{ route('admin.product-promotions.destroy', [$item->IDSanPham, $item->IDKhuyenMai]) }}" method="POST" class="d-inline" onsubmit="return confirm('Gỡ sản phẩm này khỏi khuyến mãi?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn delete" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">Không tìm thấy sản phẩm phù hợp với tiêu chí lọc.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $promotedProducts->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createAssignmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.product-promotions.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm sản phẩm vào khuyến mãi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Chọn sản phẩm</label>
                            <select name="IDSanPham" id="createProductSelect" class="form-select" required>
                                <option value="">-- Chọn sản phẩm --</option>
                                @foreach($productOptions as $product)
                                    <option value="{{ $product->ID }}"
                                            data-price="{{ $product->Gia ?? 0 }}"
                                            data-image="{{ $product->HinhAnh }}">
                                        {{ $product->TenSanPham }} ({{ number_format($product->Gia ?? 0, 0, ',', '.') }} đ)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Chọn khuyến mãi</label>
                            <select name="IDKhuyenMai" id="createPromotionSelect" class="form-select" required>
                                <option value="">-- Chọn khuyến mãi --</option>
                                @foreach($promotionOptions as $promotion)
                                    <option value="{{ $promotion->ID }}">
                                        {{ $promotion->TenKhuyenMai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="GhiChu" rows="3" class="form-control" placeholder="Thông tin thêm cho liên kết này"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="price-preview" id="createPricePreview">
                                <div class="fw-semibold mb-1">Xem nhanh giá sau khuyến mãi</div>
                                <div class="text-muted">Chọn sản phẩm và chương trình để xem giá trị giảm.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm vào khuyến mãi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Modal -->
<div class="modal fade" id="bulkPromotionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.product-promotions.bulk-store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm nhiều sản phẩm vào một khuyến mãi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chọn sản phẩm (nhấn giữ Ctrl để chọn nhiều)</label>
                        <div class="bulk-select-wrapper">
                            <input type="text" class="form-control" id="bulkProductSearch" placeholder="Gõ để tìm kiếm sản phẩm...">
                            <select name="bulk_products[]" id="bulkProductSelect" class="bulk-multi-select" multiple required>
                                @foreach($productOptions as $product)
                                    <option value="{{ $product->ID }}">{{ $product->TenSanPham }} ({{ number_format($product->Gia ?? 0, 0, ',', '.') }} đ)</option>
                                @endforeach
                            </select>
                            <div id="bulkNoResults">Không tìm thấy sản phẩm phù hợp.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chương trình khuyến mãi</label>
                        <select name="bulk_promotion" class="form-select" required>
                            <option value="">-- Chọn chương trình --</option>
                            @foreach($promotionOptions as $promotion)
                                <option value="{{ $promotion->ID }}">{{ $promotion->TenKhuyenMai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú chung</label>
                        <textarea name="bulk_note" class="form-control" rows="3" placeholder="Áp dụng cho tất cả sản phẩm được chọn"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm danh sách</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông tin khuyến mãi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4 text-center">
                        <img id="detailProductImage" src="https://via.placeholder.com/200" class="img-fluid rounded-4 border" alt="Sản phẩm">
                    </div>
                    <div class="col-md-8">
                        <h5 id="detailProductName" class="fw-bold mb-1"></h5>
                        <div class="text-muted mb-2" id="detailCategory"></div>
                        <div class="price-highlight" id="detailFinalPrice"></div>
                        <div class="price-sub" id="detailOriginalPrice"></div>
                        <div class="mt-3">
                            <div class="fw-semibold">Chương trình</div>
                            <div id="detailPromotionName"></div>
                            <div class="text-muted" id="detailPromotionRange"></div>
                            <div class="mt-2"><span class="badge bg-light text-dark" id="detailDiscountText"></span></div>
                        </div>
                        <div class="mt-3">
                            <div class="fw-semibold">Ghi chú</div>
                            <p id="detailNote" class="mb-0 text-muted"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="editPromotionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật liên kết khuyến mãi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Sản phẩm</label>
                        <div id="editProductInfo" class="fw-semibold"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chương trình khuyến mãi</label>
                        <select name="IDKhuyenMai" id="editPromotionSelect" class="form-select" required>
                            @foreach($promotionOptions as $promotion)
                                <option value="{{ $promotion->ID }}">{{ $promotion->TenKhuyenMai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="GhiChu" id="editNote" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="price-preview" id="editPricePreview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const productMap = @json($productMap);
    const promotionMap = @json($promotionMap);

    const createProductSelect = document.getElementById('createProductSelect');
    const createPromotionSelect = document.getElementById('createPromotionSelect');
    const createPricePreview = document.getElementById('createPricePreview');
    const editModalElement = document.getElementById('editModal');
    const editPromotionForm = document.getElementById('editPromotionForm');
    const editPromotionSelect = document.getElementById('editPromotionSelect');
    const editPricePreview = document.getElementById('editPricePreview');
    const bulkProductSelect = document.getElementById('bulkProductSelect');
    const bulkProductSearch = document.getElementById('bulkProductSearch');
    const bulkNoResults = document.getElementById('bulkNoResults');

    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value || 0);
    }

    function calculatePrice(original, promotion) {
        if (!promotion) {
            return { final: original || 0, discount: 0 };
        }
        let discount = promotion.type === 'Phần trăm'
            ? (original * (promotion.value / 100))
            : promotion.value;
        if (promotion.type === 'Phần trăm' && promotion.max) {
            discount = Math.min(discount, promotion.max);
        }
        const finalPrice = Math.max(0, (original || 0) - discount);
        return { final: finalPrice, discount };
    }

    function renderPreview(container, productId, promotionId) {
        const product = productMap[productId];
        const promotion = promotionMap[promotionId];
        if (!product) {
            container.innerHTML = '<div class="text-muted">Chọn sản phẩm để xem thông tin.</div>';
            return;
        }
        const result = calculatePrice(product.price, promotion);
        const promoLabel = promotion ? `${promotion.name} (${promotion.type === 'Phần trăm' ? promotion.value + '% giảm' : formatCurrency(promotion.value)})` : 'Chưa áp dụng';
        container.innerHTML = `
            <div class="d-flex justify-content-between">
                <div>
                    <div class="fw-semibold">${product.name}</div>
                    <div class="text-muted">Giá gốc: ${formatCurrency(product.price)}</div>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-success">${formatCurrency(result.final)}</div>
                    <div class="text-muted">Tiết kiệm ${formatCurrency(result.discount)}</div>
                </div>
            </div>
            <div class="mt-2"><small class="text-muted">Chương trình: ${promoLabel}</small></div>
        `;
    }

    function openDetailModal(button) {
        const data = JSON.parse(button.getAttribute('data-details'));
        document.getElementById('detailProductName').textContent = data.product_name;
        document.getElementById('detailCategory').textContent = data.category || 'Không phân loại';
        document.getElementById('detailFinalPrice').textContent = formatCurrency(data.final_price);
        document.getElementById('detailOriginalPrice').textContent = `Giá gốc: ${formatCurrency(data.product_price)}`;
        document.getElementById('detailPromotionName').textContent = data.promotion_name;
        document.getElementById('detailPromotionRange').textContent = data.promotion_range || '';
        document.getElementById('detailDiscountText').textContent = data.discount_text || '';
        document.getElementById('detailNote').textContent = data.promotion_note || 'Không có ghi chú';
        document.getElementById('detailProductImage').src = data.product_image || 'https://via.placeholder.com/300x300?text=No+Image';
        new bootstrap.Modal(document.getElementById('detailModal')).show();
    }

    function openEditModal(button) {
        const productId = button.dataset.productId;
        const promotionId = button.dataset.promotionId;
        const note = button.dataset.note || '';
        const action = button.dataset.action;
        const productName = button.dataset.productName;
        const price = parseFloat(button.dataset.productPrice || 0);

        if (!productMap[productId]) {
            productMap[productId] = { id: productId, name: productName, price, image: null };
        }

        editPromotionForm.action = action;
        editPromotionSelect.value = promotionId;
        document.getElementById('editNote').value = note;
        document.getElementById('editProductInfo').innerHTML = `${productName} • ${formatCurrency(price)}`;
        editPromotionForm.dataset.productId = productId;
        editPromotionForm.dataset.productPrice = price;
        renderPreview(editPricePreview, productId, promotionId);
        new bootstrap.Modal(editModalElement).show();
    }

    function updateCreatePreview() {
        renderPreview(createPricePreview, createProductSelect.value, createPromotionSelect.value);
    }

    function updateEditPreview() {
        const productId = editPromotionForm.dataset.productId;
        if (!productId) return;
        renderPreview(editPricePreview, productId, editPromotionSelect.value);
    }

    if (createProductSelect) {
        createProductSelect.addEventListener('change', updateCreatePreview);
    }
    if (createPromotionSelect) {
        createPromotionSelect.addEventListener('change', updateCreatePreview);
    }
    if (editPromotionSelect) {
        editPromotionSelect.addEventListener('change', updateEditPreview);
    }

    function normalizeText(value) {
        return (value || '')
            .toString()
            .normalize('NFD')
            .replace(/\p{Diacritic}/gu, '')
            .toLowerCase();
    }

    function filterBulkOptions() {
        if (!bulkProductSelect || !bulkProductSearch) {
            return;
        }
        const term = normalizeText(bulkProductSearch.value);
        let visibleCount = 0;
        Array.from(bulkProductSelect.options).forEach(option => {
            const optionText = normalizeText(option.textContent || '');
            const isVisible = term === '' || optionText.includes(term);
            option.hidden = !isVisible;
            if (isVisible) {
                visibleCount++;
            }
        });
        if (bulkNoResults) {
            bulkNoResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    if (bulkProductSearch) {
        bulkProductSearch.addEventListener('input', filterBulkOptions);
        filterBulkOptions();
    }
</script>
@endpush
