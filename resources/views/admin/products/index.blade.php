<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm - ADMIN</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Free CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- Boxicons CDN (Backup) -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="/template/admin/style.css">
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
    <style>
        :root { 
            --primary:#2563eb; 
            --primary-dark:#1d4ed8;
            --muted:#6b7280; 
            --danger:#dc2626; 
            --success:#059669; 
            --warning:#f59e0b;
            --card:#ffffff; 
            --border:#e5e7eb; 
            --bg:#f8fafc;
            --shadow-sm:0 2px 8px rgba(15,23,42,.05);
            --shadow-md:0 8px 20px rgba(15,23,42,.08);
            --shadow-lg:0 20px 40px rgba(15,23,42,.12);
        }
        
        * { transition: all 0.3s ease; }
        
        body { 
            background: linear-gradient(135deg, #f8fafc 0%, #e7eef5 100%); 
            font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; 
            min-height:100vh;
        }
        
        .page-header { 
            display:flex; 
            align-items:center; 
            justify-content:space-between; 
            gap:20px; 
            flex-wrap:wrap; 
            margin-bottom:28px; 
            padding:0 4px;
        }
        
        .page-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        .page-header p {
            color: var(--muted);
            margin-top: 8px;
            font-size: 14px;
        }
        
        .action-group { 
            display:flex; 
            flex-wrap:wrap; 
            gap:12px; 
        }
        
        .btn {
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 14px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
        }
        
        .stat-grid { 
            display:grid; 
            grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); 
            gap:20px; 
            margin-bottom:28px; 
        }
        
        .stat-card { 
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 24px; 
            border-radius: 20px; 
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(226, 232, 240, 0.8);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary) 0%, #60a5fa 100%);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-card span { 
            color: var(--muted); 
            font-size: 13px; 
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card h3 { 
            margin: 10px 0 0; 
            color: #1e293b;
            font-size: 32px;
            font-weight: 700;
        }
        
        .filter-section { 
            background: var(--card); 
            border-radius: 20px; 
            padding: 24px; 
            box-shadow: var(--shadow-md);
            margin-bottom: 28px;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .filter-row { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); 
            gap: 14px; 
        }
        
        .filter-actions { 
            display: flex; 
            gap: 12px; 
            grid-column: 1 / -1;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }
        
        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #475569;
            margin-bottom: 8px;
        }
        
        .table-card { 
            border: none; 
            border-radius: 20px; 
            box-shadow: var(--shadow-md);
            background: var(--card); 
            margin-bottom: 28px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .card-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 2px solid #f1f5f9;
            padding: 20px 24px;
        }
        
        .card-header h6 {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        
        thead th { 
            text-transform: uppercase; 
            font-size: 11px; 
            letter-spacing: 0.8px; 
            color: #64748b; 
            background: #f8fafc;
            padding: 16px 20px;
            font-weight: 700;
            border-bottom: 2px solid #e2e8f0;
        }
        
        tbody tr {
            transition: all 0.2s ease;
        }
        
        tbody tr:hover { 
            background: #f8fafc;
            transform: scale(1.002);
        }
        
        tbody td { 
            padding: 18px 20px; 
            border-bottom: 1px solid #f1f5f9; 
            vertical-align: middle;
            font-size: 14px;
        }
        
        .product-info { 
            display: flex; 
            gap: 14px; 
            align-items: center; 
        }
        
        .product-thumb { 
            width: 64px; 
            height: 64px; 
            border-radius: 16px; 
            object-fit: cover; 
            border: 2px solid var(--border); 
            background: #fff;
            box-shadow: var(--shadow-sm);
        }
        
        .product-info strong {
            color: #1e293b;
            font-size: 15px;
            font-weight: 600;
        }
        
        .badge-soft { 
            border-radius: 8px; 
            padding: 4px 10px; 
            font-size: 11px; 
            margin-left: 8px;
            font-weight: 600;
        }
        
        .badge-status { 
            padding: 6px 16px; 
            border-radius: 20px; 
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-active { 
            background: linear-gradient(135deg, rgba(16,185,129,.2) 0%, rgba(16,185,129,.15) 100%);
            color: #0f766e;
            border: 1px solid rgba(16,185,129,.3);
        }
        
        .badge-inactive { 
            background: linear-gradient(135deg, rgba(248,113,113,.2) 0%, rgba(248,113,113,.15) 100%);
            color: #b91c1c;
            border: 1px solid rgba(248,113,113,.3);
        }
        
        .badge-featured { 
            background: linear-gradient(135deg, rgba(250,204,21,.25) 0%, rgba(250,204,21,.2) 100%);
            color: #a16207;
            border: 1px solid rgba(250,204,21,.4);
        }
        
        .action-btn { 
            width: 38px; 
            height: 38px; 
            border: none; 
            border-radius: 10px; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            margin-right: 6px; 
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px) scale(1.05);
        }
        
        .action-btn.view { 
            background: rgba(59,130,246,.15); 
            color: #1d4ed8;
        }
        
        .action-btn.view:hover { 
            background: rgba(59,130,246,.25);
        }
        
        .action-btn.edit { 
            background: rgba(250,204,21,.2); 
            color: #a16207;
        }
        
        .action-btn.edit:hover { 
            background: rgba(250,204,21,.3);
        }
        
        .action-btn.delete { 
            background: rgba(248,113,113,.2); 
            color: #b91c1c;
        }
        
        .action-btn.delete:hover { 
            background: rgba(248,113,113,.3);
        }
        
        .bulk-row { 
            border: 2px dashed var(--border); 
            border-radius: 16px; 
            padding: 20px; 
            margin-bottom: 18px; 
            background: #fdfdfe;
            transition: all 0.3s ease;
        }
        
        .bulk-row:hover {
            border-color: var(--primary);
            background: #f8fafc;
        }
        
        .bulk-row-header { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }
        
        .bulk-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 14px; 
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-label {
            font-size: 14px;
            color: #475569;
        }
        
        .ck-editor__editable {
            min-height: 120px;
            border-radius: 10px;
        }
        
        .ck.ck-toolbar {
            border-radius: 10px 10px 0 0;
            background: #f8fafc;
        }
        
        .modal { 
            display: none; 
            position: fixed; 
            inset: 0; 
            background: rgba(15,23,42,.6);
            backdrop-filter: blur(4px);
            z-index: 1200; 
            padding: 40px 20px; 
            overflow-y: auto;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal.active { 
            display: block; 
        }
        
        .modal-content { 
            background: #fff; 
            border-radius: 24px; 
            max-width: 900px; 
            margin: auto; 
            box-shadow: 0 40px 80px rgba(15,23,42,.4);
            animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header { 
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: #fff; 
            padding: 22px 28px;
            border-radius: 24px 24px 0 0;
            display: flex; 
            justify-content: space-between; 
            align-items: center;
        }
        
        .modal-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }
        
        .modal-body { 
            padding: 28px; 
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .modal-footer { 
            padding: 0 28px 28px; 
            display: flex; 
            justify-content: flex-end; 
            gap: 12px; 
        }
        
        .modal-close { 
            cursor: pointer; 
            font-size: 28px; 
            line-height: 1;
            opacity: 0.9;
            transition: all 0.2s ease;
        }
        
        .modal-close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }
        
        .form-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
            gap: 18px; 
        }
        
        .gallery-preview { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 14px; 
        }
        
        .gallery-item { 
            position: relative;
            border-radius: 14px;
            overflow: hidden;
        }
        
        .gallery-item img { 
            width: 100px; 
            height: 100px; 
            border-radius: 14px; 
            object-fit: cover; 
            border: 2px solid var(--border);
            transition: all 0.3s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.05);
            box-shadow: var(--shadow-md);
        }
        
        .table-responsive { 
            overflow-x: auto; 
        }
        
        .alert {
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            border: none;
            box-shadow: var(--shadow-sm);
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(16,185,129,.15) 0%, rgba(16,185,129,.1) 100%);
            color: #0f766e;
            border-left: 4px solid #10b981;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(248,113,113,.15) 0%, rgba(248,113,113,.1) 100%);
            color: #b91c1c;
            border-left: 4px solid #f87171;
        }
        
        @media (max-width:768px) {
            .page-header { 
                flex-direction: column; 
                align-items: flex-start; 
            }
            
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            tbody td { 
                min-width: 160px; 
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
@include('admin.partials.sidebar')
<section id="content">
    @include('admin.partials.navbar')
    <main>
        <div class="page-header">
            <div>
                <h2>Quản lý sản phẩm</h2>
                <p style="color:var(--muted); margin-top:6px;">Theo dõi, lọc và cập nhật sản phẩm trong kho.</p>
            </div>
            <div class="action-group">
                <button class="btn btn-outline-primary" onclick="openBulkModal()">
                    <i class="fa-solid fa-layer-group me-1"></i>
                    Thêm nhiều sản phẩm
                </button>
                <button class="btn btn-primary" onclick="openCreateModal()">
                    <i class="fa-solid fa-plus me-1"></i>
                    Thêm sản phẩm
                </button>
            </div>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <span>Tổng sản phẩm</span>
                <h3>{{ number_format($stats['total'] ?? 0) }}</h3>
            </div>
            <div class="stat-card">
                <span>Đang bán</span>
                <h3>{{ number_format($stats['active'] ?? 0) }}</h3>
            </div>
            <div class="stat-card">
                <span>Sản phẩm nổi bật</span>
                <h3>{{ number_format($stats['featured'] ?? 0) }}</h3>
            </div>
            <div class="stat-card">
                <span>Còn hàng</span>
                <h3>{{ number_format($stats['inStock'] ?? 0) }}</h3>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Đã xảy ra lỗi:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="filter-section">
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="filter-row">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm tên, mô tả, ...">
                    <select name="category" class="form-control">
                        <option value="">-- Loại sản phẩm --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->ID }}" {{ (string)request('category')===(string)$category->ID ? 'selected' : '' }}>{{ $category->TenLoai }}</option>
                        @endforeach
                    </select>
                    <select name="supplier" class="form-control">
                        <option value="">-- Nhà cung cấp --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->ID }}" {{ (string)request('supplier')===(string)$supplier->ID ? 'selected' : '' }}>{{ $supplier->TenNhaCungCap }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-control">
                        <option value="">-- Trạng thái --</option>
                        <option value="1" {{ request('status')==='1' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="0" {{ request('status')==='0' ? 'selected' : '' }}>Ngừng bán</option>
                    </select>
                    <select name="featured" class="form-control">
                        <option value="">-- Nổi bật --</option>
                        <option value="1" {{ request('featured')==='1' ? 'selected' : '' }}>Có</option>
                        <option value="0" {{ request('featured')==='0' ? 'selected' : '' }}>Không</option>
                    </select>
                    <select name="stock" class="form-control">
                        <option value="">-- Trạng thái kho --</option>
                        <option value="out" {{ request('stock')==='out' ? 'selected' : '' }}>Hết hàng</option>
                        <option value="low" {{ request('stock')==='low' ? 'selected' : '' }}>Sắp hết (≤20)</option>
                        <option value="in" {{ request('stock')==='in' ? 'selected' : '' }}>Còn nhiều</option>
                    </select>
                    <input type="number" name="price_min" value="{{ request('price_min') }}" class="form-control" placeholder="Giá từ">
                    <input type="number" name="price_max" value="{{ request('price_max') }}" class="form-control" placeholder="Giá đến">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                    <select name="sort_by" class="form-control">
                        @php $sort = request('sort_by','NgayTao'); @endphp
                        <option value="NgayTao" {{ $sort==='NgayTao' ? 'selected' : '' }}>Ngày tạo</option>
                        <option value="Gia" {{ $sort==='Gia' ? 'selected' : '' }}>Giá</option>
                        <option value="TenSanPham" {{ $sort==='TenSanPham' ? 'selected' : '' }}>Tên</option>
                        <option value="SoLuongTon" {{ $sort==='SoLuongTon' ? 'selected' : '' }}>Tồn kho</option>
                        <option value="LuotBan" {{ $sort==='LuotBan' ? 'selected' : '' }}>Lượt bán</option>
                    </select>
                    <select name="sort_direction" class="form-control">
                        <option value="desc" {{ request('sort_direction','desc')==='desc' ? 'selected' : '' }}>Giảm dần</option>
                        <option value="asc" {{ request('sort_direction')==='asc' ? 'selected' : '' }}>Tăng dần</option>
                    </select>
                    <select name="per_page" class="form-control">
                        @foreach($perPageOptions as $option)
                            <option value="{{ $option }}" {{ (int)request('per_page',10)===$option ? 'selected' : '' }}>{{ $option }} / trang</option>
                        @endforeach
                    </select>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card table-card">
            <div class="card-header">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <h6 class="mb-0">Danh sách sản phẩm</h6>
                    <span class="badge bg-primary" style="font-size:13px; padding:8px 16px; border-radius:10px;">
                        Hiển thị {{ $products->count() }} / {{ $products->total() }}
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Nhà cung cấp</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php $cover = $product->HinhAnh ? asset($product->HinhAnh) : 'https://via.placeholder.com/120x120?text=No+Image'; @endphp
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <img src="{{ $cover }}" alt="{{ $product->TenSanPham }}" class="product-thumb">
                                        <div>
                                            <strong>{{ $product->TenSanPham }}</strong>
                                            <div style="color:var(--muted); font-size:13px;">#SP{{ $product->ID }} • {{ optional($product->NgayTao)->format('d/m/Y') ?? '---' }}</div>
                                            @if(($product->hinh_anh_count ?? 0) > 0)
                                                <span class="badge-soft badge bg-light text-dark">{{ $product->hinh_anh_count }} ảnh</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->loaiSanPham->TenLoai ?? '-' }}</td>
                                <td>{{ $product->nhaCungCap->TenNhaCungCap ?? '-' }}</td>
                                <td>
                                    <strong>{{ number_format($product->Gia ?? 0, 0, ',', '.') }} đ</strong>
                                    <div style="color:var(--muted); font-size:12px;">Đã bán: {{ number_format($product->LuotBan ?? 0) }}</div>
                                </td>
                                <td>
                                    {{ number_format($product->SoLuongTon ?? 0) }} {{ $product->DonViTinh ?? '' }}
                                    <div style="color:var(--muted); font-size:12px;">HSD: {{ optional($product->HanSuDung)->format('d/m/Y') ?: '---' }}</div>
                                </td>
                                <td>
                                    <span class="badge-status {{ $product->TrangThai ? 'badge-active' : 'badge-inactive' }}">{{ $product->TrangThai ? 'Đang bán' : 'Ngừng bán' }}</span>
                                    @if($product->NoiBat)
                                        <div class="badge-status badge-featured mt-1">Nổi bật</div>
                                    @endif
                                </td>
                                <td>
                                    <button class="action-btn view" onclick="viewProduct({{ $product->ID }})" title="Chi tiết"><i class="fa-solid fa-eye"></i></button>
                                    <button class="action-btn edit" onclick="openEditModal({{ $product->ID }})" title="Chỉnh sửa"><i class="fa-solid fa-pen"></i></button>
                                    <form action="{{ route('admin.products.destroy', $product->ID) }}" method="POST" style="display:inline" onsubmit="return confirm('Xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-btn delete" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:40px; color:var(--muted);">Không tìm thấy sản phẩm phù hợp.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($products->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links('pagination::rounded') }}
            </div>
        @endif

    </main>
</section>

<div class="modal" id="createModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Thêm sản phẩm</h3>
            <span class="modal-close" onclick="closeCreateModal()">&times;</span>
        </div>
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm *</label>
                        <input type="text" name="TenSanPham" class="form-control" value="{{ old('TenSanPham') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá bán *</label>
                        <input type="number" name="Gia" class="form-control" value="{{ old('Gia') }}" min="0" step="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng tồn *</label>
                        <input type="number" name="SoLuongTon" class="form-control" value="{{ old('SoLuongTon') }}" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Đơn vị tính *</label>
                        <input list="unitOptions" name="DonViTinh" class="form-control" value="{{ old('DonViTinh') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại sản phẩm *</label>
                        <select name="IDLoaiSP" class="form-control" required>
                            <option value="">-- Chọn loại --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->ID }}">{{ $category->TenLoai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nhà cung cấp</label>
                        <select name="IDNhaCungCap" class="form-control">
                            <option value="">-- Không chọn --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->ID }}">{{ $supplier->TenNhaCungCap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xuất xứ</label>
                        <input type="text" name="XuatXu" class="form-control" value="{{ old('XuatXu') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hạn sử dụng</label>
                        <input type="date" name="HanSuDung" class="form-control" value="{{ old('HanSuDung') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="MoTa" class="form-control" rows="4">{{ old('MoTa') }}</textarea>
                </div>
                <div class="form-grid">
                    <div class="mb-3">
                        <label class="form-label">Ảnh đại diện *</label>
                        <input type="file" name="HinhAnh" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bộ sưu tập (nhiều ảnh)</label>
                        <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                    </div>
                </div>
                <div class="d-flex gap-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="NoiBat" value="1" id="createFeatured">
                        <label class="form-check-label" for="createFeatured">Đặt làm sản phẩm nổi bật</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="TrangThai" value="1" id="createStatus" checked>
                        <label class="form-check-label" for="createStatus">Đang hoạt động</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" onclick="closeCreateModal()">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Cập nhật sản phẩm</h3>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>
        <form method="POST" id="editForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm *</label>
                        <input type="text" name="TenSanPham" id="edit_TenSanPham" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá bán *</label>
                        <input type="number" name="Gia" id="edit_Gia" class="form-control" min="0" step="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng tồn *</label>
                        <input type="number" name="SoLuongTon" id="edit_SoLuongTon" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Đơn vị tính *</label>
                        <input list="unitOptions" name="DonViTinh" id="edit_DonViTinh" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại sản phẩm *</label>
                        <select name="IDLoaiSP" id="edit_IDLoaiSP" class="form-control" required>
                            <option value="">-- Chọn loại --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->ID }}">{{ $category->TenLoai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nhà cung cấp</label>
                        <select name="IDNhaCungCap" id="edit_IDNhaCungCap" class="form-control">
                            <option value="">-- Không chọn --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->ID }}">{{ $supplier->TenNhaCungCap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xuất xứ</label>
                        <input type="text" name="XuatXu" id="edit_XuatXu" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hạn sử dụng</label>
                        <input type="date" name="HanSuDung" id="edit_HanSuDung" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="MoTa" id="edit_MoTa" class="form-control" rows="4"></textarea>
                </div>
                <div class="form-grid">
                    <div class="mb-3">
                        <label class="form-label">Ảnh đại diện (nếu thay)</label>
                        <input type="file" name="HinhAnh" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thêm ảnh bộ sưu tập</label>
                        <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ảnh hiện có</label>
                    <div class="gallery-preview" id="editGallery"></div>
                </div>
                <div class="d-flex gap-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="NoiBat" id="edit_NoiBat" value="1">
                        <label class="form-check-label" for="edit_NoiBat">Nổi bật</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="TrangThai" id="edit_TrangThai" value="1">
                        <label class="form-check-label" for="edit_TrangThai">Đang hoạt động</label>
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

<div class="modal" id="viewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Chi tiết sản phẩm</h3>
            <span class="modal-close" onclick="closeViewModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="d-flex gap-3 align-items-center mb-3">
                <img id="viewImage" src="https://via.placeholder.com/140x140?text=No+Image" alt="preview" style="width:120px;height:120px;object-fit:cover;border-radius:18px;border:1px solid var(--border);">
                <div>
                    <h4 id="viewName" class="mb-1"></h4>
                    <p class="text-muted mb-1" id="viewCategory"></p>
                    <span class="badge-status" id="viewStatus"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Giá:</strong> <span id="viewPrice"></span></p>
                    <p><strong>Tồn kho:</strong> <span id="viewStock"></span></p>
                    <p><strong>Đơn vị:</strong> <span id="viewUnit"></span></p>
                    <p><strong>Xuất xứ:</strong> <span id="viewOrigin"></span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Nhà cung cấp:</strong> <span id="viewSupplier"></span></p>
                    <p><strong>Hạn sử dụng:</strong> <span id="viewExpire"></span></p>
                    <p><strong>Ngày tạo:</strong> <span id="viewCreated"></span></p>
                    <p><strong>Lượt bán:</strong> <span id="viewSold"></span></p>
                </div>
            </div>
            <div class="mb-3">
                <strong>Mô tả:</strong>
                <p id="viewDescription" class="mt-1 text-muted"></p>
            </div>
            <div>
                <strong>Bộ sưu tập:</strong>
                <div class="gallery-preview mt-2" id="viewGallery"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" onclick="closeViewModal()">Đóng</button>
        </div>
    </div>
</div>

<div class="modal" id="bulkModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Thêm nhiều sản phẩm</h3>
            <span class="modal-close" onclick="closeBulkModal()">&times;</span>
        </div>
        <form action="{{ route('admin.products.bulk-store') }}" method="POST" id="bulkForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <p class="text-muted">Nhập tối đa 20 sản phẩm mỗi lần. Upload ảnh đại diện và bộ sưu tập cho từng sản phẩm.</p>
                <div id="bulkRows"></div>
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <div>
                        <button type="button" class="btn btn-outline-primary" id="addBulkRow">
                            <i class="fa-solid fa-plus"></i> Thêm dòng
                        </button>
                        <small class="text-muted ms-2">Đã thêm <span id="bulkCount">0</span>/20 sản phẩm</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" onclick="closeBulkModal()">Đóng</button>
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-cloud-upload-alt me-1"></i>
                    Lưu danh sách
                </button>
            </div>
        </form>
    </div>
</div>

<datalist id="unitOptions">
    @foreach($unitPresets as $unit)
        <option value="{{ $unit }}"></option>
    @endforeach
</datalist>

<template id="bulkRowTemplate">
    <div class="bulk-row" data-index="__INDEX__">
        <div class="bulk-row-header">
            <strong>Sản phẩm #<span class="bulk-order">__ORDER__</span></strong>
            <button type="button" class="btn btn-sm btn-link text-danger" onclick="removeBulkRow(this)"><i class="fa-solid fa-times"></i> Xóa</button>
        </div>
        <div class="bulk-grid">
            <div>
                <label class="form-label">Tên sản phẩm *</label>
                <input type="text" class="form-control" name="products[__INDEX__][TenSanPham]" required>
            </div>
            <div>
                <label class="form-label">Giá *</label>
                <input type="number" class="form-control" name="products[__INDEX__][Gia]" min="0" step="100" required>
            </div>
            <div>
                <label class="form-label">Số lượng *</label>
                <input type="number" class="form-control" name="products[__INDEX__][SoLuongTon]" min="0" required>
            </div>
            <div>
                <label class="form-label">Đơn vị *</label>
                <input list="unitOptions" class="form-control" name="products[__INDEX__][DonViTinh]" required>
            </div>
            <div>
                <label class="form-label">Loại *</label>
                <select class="form-control" name="products[__INDEX__][IDLoaiSP]" required>
                    <option value="">-- Chọn --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->ID }}">{{ $category->TenLoai }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Nhà cung cấp</label>
                <select class="form-control" name="products[__INDEX__][IDNhaCungCap]">
                    <option value="">-- Không chọn --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->ID }}">{{ $supplier->TenNhaCungCap }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Xuất xứ</label>
                <input type="text" class="form-control" name="products[__INDEX__][XuatXu]">
            </div>
            <div>
                <label class="form-label">HSD</label>
                <input type="date" class="form-control" name="products[__INDEX__][HanSuDung]">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label class="form-label">Ảnh đại diện *</label>
                <input type="file" class="form-control" name="products[__INDEX__][HinhAnh]" accept="image/*" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Bộ sưu tập (nhiều ảnh)</label>
                <input type="file" class="form-control" name="products[__INDEX__][gallery][]" accept="image/*" multiple>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label class="form-label">Mô tả</label>
                <textarea class="form-control ckeditor-bulk" rows="3" name="products[__INDEX__][MoTa]" id="moTa__INDEX__"></textarea>
            </div>
        </div>
        <div class="d-flex gap-4 mt-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="products[__INDEX__][NoiBat]" value="1" id="noiBat__INDEX__">
                <label class="form-check-label" for="noiBat__INDEX__">Đặt làm sản phẩm nổi bật</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="products[__INDEX__][TrangThai]" value="1" id="trangThai__INDEX__" checked>
                <label class="form-check-label" for="trangThai__INDEX__">Đang hoạt động</label>
            </div>
        </div>
    </div>
</template>

<script>
    const bodyEl = document.body;
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    const viewModal = document.getElementById('viewModal');
    const bulkModal = document.getElementById('bulkModal');

    function resolveImagePath(path){
        if(!path) return 'https://via.placeholder.com/120x120?text=No+Image';
        return /^https?:\/\//i.test(path) ? path : '/' + path.replace(/^\/+/, '');
    }

    function openModal(element){
        if(!element) return;
        element.classList.add('active');
        bodyEl.style.overflow='hidden';
    }
    function closeModal(element){
        if(!element) return;
        element.classList.remove('active');
        bodyEl.style.overflow='auto';
        const form = element.querySelector('form');
        if(form && element.id !== 'bulkModal') form.reset();
        if(element.id === 'editModal'){
            document.getElementById('editGallery').innerHTML='';
        }
    }

    function openCreateModal(){ openModal(createModal); }
    function closeCreateModal(){ closeModal(createModal); }
    function openBulkModal(){ openModal(bulkModal); }
    function closeBulkModal(){ closeModal(bulkModal); }

    function openEditModal(id){
        fetch(`/admin/products/${id}`, { headers:{ 'Accept':'application/json' }})
            .then(res => res.json())
            .then(product => {
                document.getElementById('editForm').action = `/admin/products/${product.ID}`;
                document.getElementById('edit_TenSanPham').value = product.TenSanPham || '';
                document.getElementById('edit_Gia').value = product.Gia || 0;
                document.getElementById('edit_SoLuongTon').value = product.SoLuongTon || 0;
                document.getElementById('edit_DonViTinh').value = product.DonViTinh || '';
                document.getElementById('edit_IDLoaiSP').value = product.IDLoaiSP || '';
                document.getElementById('edit_IDNhaCungCap').value = product.IDNhaCungCap || '';
                document.getElementById('edit_XuatXu').value = product.XuatXu || '';
                document.getElementById('edit_HanSuDung').value = product.HanSuDung ? product.HanSuDung.substring(0,10) : '';
                document.getElementById('edit_MoTa').value = product.MoTa || '';
                document.getElementById('edit_NoiBat').checked = !!product.NoiBat;
                document.getElementById('edit_TrangThai').checked = !!product.TrangThai;
                const gallery = document.getElementById('editGallery');
                gallery.innerHTML = '';
                if(Array.isArray(product.hinh_anh)){
                    product.hinh_anh.forEach(image => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'gallery-item';
                        const img = document.createElement('img');
                        img.src = resolveImagePath(image.DuongDan || '');
                        wrapper.appendChild(img);
                        if(image.LaChinh){
                            const badge = document.createElement('span');
                            badge.textContent = 'Ảnh chính';
                            badge.className = 'badge bg-light text-dark';
                            badge.style.position='absolute';
                            badge.style.bottom='6px';
                            badge.style.left='6px';
                            wrapper.appendChild(badge);
                        } else {
                            const label = document.createElement('label');
                            label.className = 'form-check-label';
                            label.style.position='absolute';
                            label.style.top='6px';
                            label.style.right='6px';
                            const checkbox = document.createElement('input');
                            checkbox.type='checkbox';
                            checkbox.name='delete_images[]';
                            checkbox.value=image.ID;
                            checkbox.className='form-check-input me-1';
                            label.prepend(checkbox);
                            label.append('Xóa');
                            wrapper.appendChild(label);
                        }
                        gallery.appendChild(wrapper);
                    });
                }
                openModal(editModal);
            });
    }
    function closeEditModal(){ closeModal(editModal); }

    function viewProduct(id){
        fetch(`/admin/products/${id}`, { headers:{ 'Accept':'application/json' }})
            .then(res => res.json())
            .then(product => {
                document.getElementById('viewName').textContent = product.TenSanPham || '';
                document.getElementById('viewCategory').textContent = product.loai_san_pham ? product.loai_san_pham.TenLoai : '---';
                document.getElementById('viewPrice').textContent = product.Gia ? new Intl.NumberFormat('vi-VN').format(product.Gia) + ' đ' : '---';
                document.getElementById('viewStock').textContent = (product.SoLuongTon || 0) + ' ' + (product.DonViTinh || '');
                document.getElementById('viewUnit').textContent = product.DonViTinh || '---';
                document.getElementById('viewOrigin').textContent = product.XuatXu || '---';
                document.getElementById('viewSupplier').textContent = product.nha_cung_cap ? product.nha_cung_cap.TenNhaCungCap : '---';
                document.getElementById('viewExpire').textContent = product.HanSuDung ? new Date(product.HanSuDung).toLocaleDateString('vi-VN') : '---';
                document.getElementById('viewCreated').textContent = product.NgayTao ? new Date(product.NgayTao).toLocaleString('vi-VN') : '---';
                document.getElementById('viewSold').textContent = product.LuotBan || 0;
                document.getElementById('viewDescription').textContent = product.MoTa || '---';
                const statusEl = document.getElementById('viewStatus');
                statusEl.textContent = product.TrangThai ? 'Đang bán' : 'Ngừng bán';
                statusEl.className = `badge-status ${product.TrangThai ? 'badge-active' : 'badge-inactive'}`;
                document.getElementById('viewImage').src = resolveImagePath(product.HinhAnh || '');
                const gallery = document.getElementById('viewGallery');
                gallery.innerHTML = '';
                if(Array.isArray(product.hinh_anh) && product.hinh_anh.length){
                    product.hinh_anh.forEach(image => {
                        const img = document.createElement('img');
                        img.src = resolveImagePath(image.DuongDan || '');
                        img.style.width='90px';
                        img.style.height='90px';
                        img.style.objectFit='cover';
                        img.style.borderRadius='14px';
                        img.style.border='1px solid var(--border)';
                        gallery.appendChild(img);
                    });
                } else {
                    const span = document.createElement('span');
                    span.className='text-muted';
                    span.textContent='Chưa có ảnh phụ.';
                    gallery.appendChild(span);
                }
                openModal(viewModal);
            });
    }
    function closeViewModal(){ closeModal(viewModal); }

    const bulkRowsContainer = document.getElementById('bulkRows');
    const bulkTemplate = document.getElementById('bulkRowTemplate').innerHTML;
    const bulkCountEl = document.getElementById('bulkCount');
    let bulkIndex = 0;

    function renderBulkCount(){ bulkCountEl.textContent = bulkRowsContainer.children.length; }

    function addBulkRow(){
        if(bulkRowsContainer.children.length >= 20){
            alert('Mỗi lần chỉ thêm tối đa 20 sản phẩm.');
            return;
        }
        const html = bulkTemplate.replace(/__INDEX__/g, bulkIndex).replace(/__ORDER__/g, bulkRowsContainer.children.length + 1);
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        const row = wrapper.firstElementChild;
        bulkRowsContainer.appendChild(row);
        
        // Initialize CKEditor for the new textarea
        const textareaId = `moTa${bulkIndex}`;
        const textarea = document.getElementById(textareaId);
        if(textarea && typeof ClassicEditor !== 'undefined'){
            ClassicEditor
                .create(textarea, {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
                    language: 'vi'
                })
                .catch(error => {
                    console.error('CKEditor initialization error:', error);
                });
        }
        
        bulkIndex++;
        renderBulkCount();
    }

    function removeBulkRow(button){
        const row = button.closest('.bulk-row');
        if(!row) return;
        row.remove();
        Array.from(bulkRowsContainer.children).forEach((child, idx) => {
            child.querySelector('.bulk-order').textContent = idx + 1;
        });
        renderBulkCount();
    }

    document.getElementById('addBulkRow').addEventListener('click', addBulkRow);
    addBulkRow();
</script>
</body>
</html>
