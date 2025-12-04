<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - ADMIN</title>
    <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Free CDN -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- Boxicons CDN (Backup) -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="/template/admin/style.css">
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
        
        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }
        
        .stat-grid { 
            display:grid; 
            grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); 
            gap:16px; 
            margin-bottom:28px; 
        }
        
        .stat-card { 
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 20px; 
            border-radius: 18px; 
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
            font-size: 12px; 
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card h3 { 
            margin: 8px 0 0; 
            color: #1e293b;
            font-size: 26px;
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
            margin-bottom: 30px;
            overflow: visible;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .table-responsive {
            overflow: visible;
        }
        
        .card-body {
            overflow: visible;
        }
        
        table {
            margin-bottom: 0;
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
            position: relative;
        }
        
        tbody tr:hover { 
            background: #f8fafc;
        }
        
        tbody tr:has(.action-dropdown.active) {
            z-index: 10;
        }
        
        tbody td { 
            padding: 18px 20px; 
            border-bottom: 1px solid #f1f5f9; 
            vertical-align: middle;
            font-size: 14px;
            position: relative;
        }
        
        tbody td:last-child {
            overflow: visible;
        }
        
        .badge-status { 
            padding: 6px 16px; 
            border-radius: 20px; 
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-pending { 
            background: linear-gradient(135deg, rgba(251,191,36,.2) 0%, rgba(251,191,36,.15) 100%);
            color: #b45309;
            border: 1px solid rgba(251,191,36,.3);
        }
        
        .badge-confirmed { 
            background: linear-gradient(135deg, rgba(59,130,246,.2) 0%, rgba(59,130,246,.15) 100%);
            color: #1e40af;
            border: 1px solid rgba(59,130,246,.3);
        }
        
        .badge-shipping { 
            background: linear-gradient(135deg, rgba(168,85,247,.2) 0%, rgba(168,85,247,.15) 100%);
            color: #6b21a8;
            border: 1px solid rgba(168,85,247,.3);
        }
        
        .badge-completed { 
            background: linear-gradient(135deg, rgba(16,185,129,.2) 0%, rgba(16,185,129,.15) 100%);
            color: #0f766e;
            border: 1px solid rgba(16,185,129,.3);
        }
        
        .badge-cancelled { 
            background: linear-gradient(135deg, rgba(248,113,113,.2) 0%, rgba(248,113,113,.15) 100%);
            color: #b91c1c;
            border: 1px solid rgba(248,113,113,.3);
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
        
        .action-btn.edit { 
            background: rgba(250,204,21,.2); 
            color: #a16207;
        }
        
        .action-btn.delete { 
            background: rgba(248,113,113,.2); 
            color: #b91c1c;
        }
        
        .action-btn.approve { 
            background: rgba(16,185,129,.2); 
            color: #0f766e;
        }
        
        .action-btn.cancel { 
            background: rgba(239,68,68,.2); 
            color: #b91c1c;
        }
        
        .action-menu {
            position: relative;
            display: inline-block;
        }
        
        .action-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(15,23,42,.2);
            min-width: 200px;
            z-index: 9999;
            display: none;
            border: 1px solid #e2e8f0;
        }
        
        .action-dropdown.active {
            display: block;
            animation: slideDown 0.2s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .action-dropdown-item {
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #475569;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .action-dropdown-item:last-child {
            border-bottom: none;
        }
        
        .action-dropdown-item:first-child {
            border-radius: 12px 12px 0 0;
        }
        
        .action-dropdown-item:last-child {
            border-radius: 0 0 12px 12px;
        }
        
        .action-dropdown-item:hover {
            background: #f8fafc;
        }
        
        .action-dropdown-item i {
            width: 18px;
            text-align: center;
        }
        
        .action-dropdown-item.approve-action {
            color: #059669;
        }
        
        .action-dropdown-item.approve-action:hover {
            background: rgba(16,185,129,.1);
        }
        
        .action-dropdown-item.cancel-action {
            color: #dc2626;
        }
        
        .action-dropdown-item.cancel-action:hover {
            background: rgba(239,68,68,.1);
        }
        
        .action-dropdown-item.edit-action {
            color: #f59e0b;
        }
        
        .action-dropdown-item.edit-action:hover {
            background: rgba(251,191,36,.1);
        }
        
        .action-dropdown-item.delete-action {
            color: #dc2626;
        }
        
        .action-dropdown-item.delete-action:hover {
            background: rgba(239,68,68,.1);
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
        }
        
        .modal.active { 
            display: block; 
        }
        
        .modal-content { 
            background: #fff; 
            border-radius: 24px; 
            max-width: 1000px; 
            margin: auto; 
            box-shadow: 0 40px 80px rgba(15,23,42,.4);
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
        
        .product-row {
            border: 2px dashed var(--border);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 14px;
            background: #fdfdfe;
        }
        
        .product-row:hover {
            border-color: var(--primary);
            background: #f8fafc;
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
        
        @media (max-width:768px) {
            .page-header { 
                flex-direction: column; 
                align-items: flex-start; 
            }
            
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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
                <h2>Quản lý đơn hàng</h2>
                <p style="color:var(--muted); margin-top:6px;">Theo dõi, xử lý và cập nhật trạng thái đơn hàng.</p>
            </div>
            <div class="action-group">
                <button class="btn btn-outline-primary" onclick="openBulkModal()">
                    <i class="fa-solid fa-layer-group me-1"></i>
                    Tạo nhiều đơn hàng
                </button>
                <button class="btn btn-primary" onclick="openCreateModal()">
                    <i class="fa-solid fa-plus me-1"></i>
                    Tạo đơn hàng
                </button>
            </div>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <span>Tổng đơn hàng</span>
                <h3>{{ number_format($stats['total'] ?? 0) }}</h3>
            </div>
            <div class="stat-card">
                <span>Chờ xác nhận</span>
                <h3>{{ number_format($stats['pending'] ?? 0) }}</h3>
            </div>
            <div class="stat-card">
                <span>Đã xác nhận</span>
                <h3>{{ number_format($stats['confirmed'] ?? 0) }}</h3>
            </div>
            <div class="stat-card">
                <span>Đang giao</span>
                <h3>{{ number_format($stats['shipping'] ?? 0) }}</h3>
            </div>
            <div class="stat-card">
                <span>Hoàn thành</span>
                <h3>{{ number_format($stats['completed'] ?? 0) }}</h3>
            </div>
            <div class="stat-card">
                <span>Đã hủy</span>
                <h3>{{ number_format($stats['cancelled'] ?? 0) }}</h3>
            </div>
            <div class="stat-card">
                <span>Doanh thu</span>
                <h3>{{ number_format($stats['revenue'] ?? 0) }} đ</h3>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="filter-section">
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <div class="filter-row">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm mã đơn, tên, SĐT...">
                    
                    <select name="status" class="form-control">
                        <option value="">-- Trạng thái --</option>
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    
                    <select name="payment_method" class="form-control">
                        <option value="">-- Phương thức TT --</option>
                        @foreach($paymentMethods as $key => $label)
                            <option value="{{ $key }}" {{ request('payment_method') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    
                    <input type="number" name="min_total" value="{{ request('min_total') }}" class="form-control" placeholder="Tổng tiền từ">
                    <input type="number" name="max_total" value="{{ request('max_total') }}" class="form-control" placeholder="Tổng tiền đến">
                    
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                    
                    <select name="sort_by" class="form-control">
                        @php $sort = request('sort_by','NgayDat'); @endphp
                        <option value="NgayDat" {{ $sort === 'NgayDat' ? 'selected' : '' }}>Ngày đặt</option>
                        <option value="TongThanhToan" {{ $sort === 'TongThanhToan' ? 'selected' : '' }}>Tổng tiền</option>
                        <option value="MaDonHang" {{ $sort === 'MaDonHang' ? 'selected' : '' }}>Mã đơn</option>
                    </select>
                    
                    <select name="sort_direction" class="form-control">
                        <option value="desc" {{ request('sort_direction','desc') === 'desc' ? 'selected' : '' }}>Giảm dần</option>
                        <option value="asc" {{ request('sort_direction') === 'asc' ? 'selected' : '' }}>Tăng dần</option>
                    </select>
                    
                    <select name="per_page" class="form-control">
                        @foreach($perPageOptions as $option)
                            <option value="{{ $option }}" {{ (int)request('per_page',10) === $option ? 'selected' : '' }}>{{ $option }} / trang</option>
                        @endforeach
                    </select>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card table-card">
            <div class="card-header">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <h6 class="mb-0">Danh sách đơn hàng</h6>
                    <span class="badge bg-primary" style="font-size:13px; padding:8px 16px; border-radius:10px;">
                        Hiển thị {{ $orders->count() }} / {{ $orders->total() }}
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Liên hệ</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức TT</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->MaDonHang }}</strong>
                                    <div style="color:var(--muted); font-size:12px;">
                                        {{ $order->chi_tiet_count ?? 0 }} sản phẩm
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $order->TenNguoiNhan }}</strong>
                                    <div style="color:var(--muted); font-size:13px;">
                                        {{ optional($order->nguoiDung)->Email ?? '---' }}
                                    </div>
                                </td>
                                <td>
                                    {{ $order->SDT }}<br>
                                    <small style="color:var(--muted);">{{ Str::limit($order->DiaChi, 30) }}</small>
                                </td>
                                <td>
                                    <strong style="color:#059669;">{{ number_format($order->TongThanhToan ?? 0, 0, ',', '.') }} đ</strong>
                                    @if($order->GiamVoucher > 0)
                                        <div style="color:var(--muted); font-size:12px;">
                                            Giảm: {{ number_format($order->GiamVoucher, 0, ',', '.') }} đ
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $order->PhuongThucTT }}</td>
                                <td>
                                    @php
                                        $statusClass = match($order->TrangThai) {
                                            'Chờ xác nhận' => 'badge-pending',
                                            'Đã xác nhận' => 'badge-confirmed',
                                            'Đang giao' => 'badge-shipping',
                                            'Đã giao' => 'badge-completed',
                                            'Đã hủy' => 'badge-cancelled',
                                            default => 'badge-pending'
                                        };
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">{{ $order->TrangThai }}</span>
                                </td>
                                <td>{{ optional($order->NgayDat)->format('d/m/Y H:i') ?? '---' }}</td>
                                <td>
                                    <div class="action-menu">
                                        <button class="action-btn view" onclick="viewOrder({{ $order->ID }})" title="Chi tiết">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        
                                        <button class="action-btn" style="background: rgba(99,102,241,.15); color: #4f46e5;" onclick="toggleActionDropdown(event, {{ $order->ID }})" title="Thao tác">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        
                                        <div class="action-dropdown" id="dropdown-{{ $order->ID }}">
                                            @if($order->TrangThai === 'Chờ xác nhận')
                                                <div class="action-dropdown-item approve-action" onclick="openApproveModal({{ $order->ID }}); closeAllDropdowns();">
                                                    <i class="fa-solid fa-check"></i>
                                                    <span>Duyệt đơn hàng</span>
                                                </div>
                                            @endif

                                            @if($order->TrangThai === 'Đang giao')
                                                <div class="action-dropdown-item approve-action" onclick="openDeliveredModal({{ $order->ID }}); closeAllDropdowns();">
                                                    <i class="fa-solid fa-check-double"></i>
                                                    <span>Đánh dấu đã giao</span>
                                                </div>
                                            @endif
                                            
                                            @if(!in_array($order->TrangThai, ['Đã giao', 'Đã hủy']))
                                                <div class="action-dropdown-item cancel-action" onclick="openCancelModal({{ $order->ID }}); closeAllDropdowns();">
                                                    <i class="fa-solid fa-ban"></i>
                                                    <span>Hủy đơn hàng</span>
                                                </div>
                                            @endif
                                            
                                            <div class="action-dropdown-item edit-action" onclick="openEditModal({{ $order->ID }}); closeAllDropdowns();">
                                                <i class="fa-solid fa-pen"></i>
                                                <span>Chỉnh sửa</span>
                                            </div>
                                            
                                            <form action="{{ route('admin.orders.destroy', $order->ID) }}" method="POST" style="margin:0;" onsubmit="return confirm('Xóa đơn hàng này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-dropdown-item delete-action" style="width:100%; text-align:left; background:none; border:none;">
                                                    <i class="fa-solid fa-trash"></i>
                                                    <span>Xóa đơn hàng</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center; padding:40px; color:var(--muted);">
                                    Không tìm thấy đơn hàng phù hợp.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($orders->hasPages())
            <div class="pagination-wrapper mt-3">
                {{ $orders->links('vendor.pagination.admin-users') }}
            </div>
        @endif

    </main>
</section>

<!-- Modal Tạo đơn hàng -->
<div class="modal" id="createModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tạo đơn hàng mới</h3>
            <span class="modal-close" onclick="closeCreateModal()">&times;</span>
        </div>
        <form method="POST" action="{{ route('admin.orders.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="mb-3">
                        <label class="form-label">Khách hàng *</label>
                        <select name="IDNguoiDung" class="form-control" required id="customerSelect" onchange="fillCustomerInfo()">
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->ID }}" data-name="{{ $customer->TenNguoiDung }}" data-email="{{ $customer->Email }}">
                                    {{ $customer->TenNguoiDung }} ({{ $customer->Email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên người nhận *</label>
                        <input type="text" name="TenNguoiNhan" class="form-control" required id="tenNguoiNhan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="tel" name="SDT" class="form-control" required placeholder="0912345678">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phương thức thanh toán *</label>
                        <select name="PhuongThucTT" class="form-control" required>
                            @foreach($paymentMethods as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Địa chỉ giao hàng *</label>
                    <textarea name="DiaChi" class="form-control" rows="2" required></textarea>
                </div>
                
                <div class="form-grid">
                    <div class="mb-3">
                        <label class="form-label">Phí vận chuyển *</label>
                        <input type="number" name="PhiVanChuyen" class="form-control" value="30000" min="0" step="1000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Voucher (nếu có)</label>
                        <select name="IDVoucher" class="form-control">
                            <option value="">-- Không sử dụng --</option>
                            @foreach($vouchers as $voucher)
                                <option value="{{ $voucher->ID }}">
                                    {{ $voucher->MaVoucher }} ({{ $voucher->Loai === 'Phần trăm' ? $voucher->GiaTri . '%' : number_format($voucher->GiaTri) . 'đ' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="GhiChu" class="form-control" rows="2"></textarea>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <label class="form-label"><strong>Sản phẩm *</strong></label>
                    <div id="productsContainer"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addProductRow()">
                        <i class="fa-solid fa-plus"></i> Thêm sản phẩm
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" onclick="closeCreateModal()">Hủy</button>
                <button type="submit" class="btn btn-primary">Tạo đơn hàng</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Sửa đơn hàng -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Cập nhật đơn hàng</h3>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="mb-3">
                        <label class="form-label">Tên người nhận *</label>
                        <input type="text" name="TenNguoiNhan" id="edit_TenNguoiNhan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="tel" name="SDT" id="edit_SDT" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái *</label>
                        <select name="TrangThai" id="edit_TrangThai" class="form-control" required>
                            @foreach($statusOptions as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Địa chỉ giao hàng *</label>
                    <textarea name="DiaChi" id="edit_DiaChi" class="form-control" rows="2" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="GhiChu" id="edit_GhiChu" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" onclick="closeEditModal()">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Xem chi tiết -->
<div class="modal" id="viewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Chi tiết đơn hàng</h3>
            <span class="modal-close" onclick="closeViewModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Mã đơn:</strong> <span id="view_MaDonHang"></span></p>
                    <p><strong>Khách hàng:</strong> <span id="view_KhachHang"></span></p>
                    <p><strong>Người nhận:</strong> <span id="view_TenNguoiNhan"></span></p>
                    <p><strong>SĐT:</strong> <span id="view_SDT"></span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Trạng thái:</strong> <span id="view_TrangThai"></span></p>
                    <p><strong>Phương thức TT:</strong> <span id="view_PhuongThucTT"></span></p>
                    <p><strong>Ngày đặt:</strong> <span id="view_NgayDat"></span></p>
                </div>
            </div>
            
            <div class="mb-3">
                <strong>Địa chỉ:</strong>
                <p id="view_DiaChi" class="mt-1"></p>
            </div>
            
            <div class="mb-3">
                <strong>Chi tiết sản phẩm:</strong>
                <div class="table-responsive mt-2">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody id="view_ChiTiet"></tbody>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Phí vận chuyển:</strong> <span id="view_PhiVanChuyen"></span></p>
                    <p><strong>Giảm giá:</strong> <span id="view_GiamVoucher"></span></p>
                </div>
                <div class="col-md-6 text-end">
                    <h5><strong>Tổng thanh toán:</strong> <span id="view_TongThanhToan" style="color:#059669;"></span></h5>
                </div>
            </div>
            
            <div class="mb-3" id="view_GhiChuWrapper" style="display:none;">
                <strong>Ghi chú:</strong>
                <p id="view_GhiChu" class="mt-1"></p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" onclick="closeViewModal()">Đóng</button>
        </div>
    </div>
</div>

<!-- Modal Tạo nhiều đơn hàng -->
<div class="modal" id="bulkModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tạo nhiều đơn hàng</h3>
            <span class="modal-close" onclick="closeBulkModal()">&times;</span>
        </div>
        <form action="{{ route('admin.orders.bulk-store') }}" method="POST" id="bulkForm">
            @csrf
            <div class="modal-body">
                <p class="text-muted">Nhập tối đa 20 đơn hàng mỗi lần.</p>
                <div id="bulkOrdersContainer"></div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="btn btn-outline-primary" id="addBulkOrder">
                        <i class="fa-solid fa-plus"></i> Thêm đơn hàng
                    </button>
                    <small class="text-muted">Đã thêm <span id="bulkCount">0</span>/20 đơn hàng</small>
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

<template id="bulkOrderTemplate">
    <div class="product-row" data-index="__INDEX__" style="margin-bottom:20px; padding:20px; border:2px dashed #e5e7eb; border-radius:14px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid #e5e7eb;">
            <strong>Đơn hàng #<span class="bulk-order-number">__ORDER__</span></strong>
            <button type="button" class="btn btn-sm btn-link text-danger" onclick="removeBulkOrder(this)">
                <i class="fa-solid fa-times"></i> Xóa
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Khách hàng *</label>
                <select name="orders[__INDEX__][IDNguoiDung]" class="form-control bulk-customer-select" data-index="__INDEX__" required onchange="fillBulkCustomerInfo(__INDEX__)">
                    <option value="">-- Chọn khách hàng --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->ID }}" data-name="{{ $customer->TenNguoiDung }}" data-email="{{ $customer->Email }}">
                            {{ $customer->TenNguoiDung }} ({{ $customer->Email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tên người nhận *</label>
                <input type="text" name="orders[__INDEX__][TenNguoiNhan]" class="form-control" id="bulk_ten___INDEX__" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">SĐT *</label>
                <input type="tel" name="orders[__INDEX__][SDT]" class="form-control" id="bulk_sdt___INDEX__" required placeholder="0912345678">
            </div>
            <div class="col-md-6">
                <label class="form-label">Phương thức TT *</label>
                <select name="orders[__INDEX__][PhuongThucTT]" class="form-control" required>
                    @foreach($paymentMethods as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label">Địa chỉ *</label>
                <textarea name="orders[__INDEX__][DiaChi]" class="form-control" rows="2" id="bulk_diachi___INDEX__" required></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Phí vận chuyển *</label>
                <input type="number" name="orders[__INDEX__][PhiVanChuyen]" class="form-control bulk-phi-vanchuyen" data-order-index="__INDEX__" value="30000" min="0" required onchange="calculateBulkTotal(__INDEX__)">
            </div>
            <div class="col-md-4">
                <label class="form-label">Voucher</label>
                <select name="orders[__INDEX__][IDVoucher]" class="form-control bulk-voucher" data-order-index="__INDEX__" onchange="calculateBulkTotal(__INDEX__)">
                    <option value="">-- Không sử dụng --</option>
                    @foreach($vouchers as $voucher)
                        <option value="{{ $voucher->ID }}" 
                                data-loai="{{ $voucher->Loai }}" 
                                data-giatri="{{ $voucher->GiaTri }}" 
                                data-giamtoida="{{ $voucher->GiamToiDa ?? 0 }}">
                            {{ $voucher->MaVoucher }} ({{ $voucher->Loai === 'Phần trăm' ? $voucher->GiaTri . '%' : number_format($voucher->GiaTri) . 'đ' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tổng thanh toán *</label>
                <input type="number" name="orders[__INDEX__][TongThanhToan]" class="form-control bulk-total" id="bulk_total___INDEX__" min="0" required readonly style="background: #f1f5f9; font-weight: bold; color: #059669;">
            </div>
            <div class="col-md-12">
                <label class="form-label">Sản phẩm *</label>
                <div id="bulk_products_container___INDEX__"></div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addBulkProductRow(__INDEX__)">
                    <i class="fa-solid fa-plus"></i> Thêm sản phẩm
                </button>
            </div>
            <div class="col-md-12">
                <label class="form-label">Ghi chú</label>
                <textarea name="orders[__INDEX__][GhiChu]" class="form-control" rows="2"></textarea>
            </div>
        </div>
    </div>
</template>

<!-- Modal Hủy đơn hàng -->
<div class="modal" id="cancelModal">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3>Hủy đơn hàng</h3>
            <span class="modal-close" onclick="closeCancelModal()">&times;</span>
        </div>
        <form method="POST" action="" id="cancelForm">
            @csrf
            <div class="modal-body">
                <div class="alert alert-warning" style="background: rgba(251,191,36,.15); border: 1px solid rgba(251,191,36,.3); color: #92400e; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Bạn có chắc chắn muốn hủy đơn hàng này?
                </div>
                <div class="mb-3">
                    <label class="form-label">Lý do hủy (tùy chọn)</label>
                    <textarea name="ly_do_huy" class="form-control" rows="4" placeholder="Nhập lý do hủy đơn hàng..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCancelModal()">Đóng</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-ban me-1"></i>
                    Xác nhận hủy
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Approve Order Modal -->
<div class="modal" id="approveModal">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3>Duyệt đơn hàng</h3>
            <span class="modal-close" onclick="closeApproveModal()">&times;</span>
        </div>
        <form method="POST" action="" id="approveForm">
            @csrf
            <div class="modal-body">
                <div class="alert alert-info" style="background: rgba(59,130,246,.15); border: 1px solid rgba(59,130,246,.3); color: #1e40af; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    Chọn trạng thái cho đơn hàng sau khi duyệt
                </div>
                <div class="mb-3">
                    <label class="form-label">Trạng thái đơn hàng <span class="text-danger">*</span></label>
                    <select name="trang_thai" class="form-select" required>
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="Đang giao">Đang giao</option>
                        <option value="Đã giao">Đã giao</option>
                    </select>
                    <small class="text-muted d-block mt-2">
                        <i class="fa-solid fa-info-circle"></i> 
                        Chọn "Đã giao" nếu đơn hàng đã được giao thành công
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeApproveModal()">Đóng</button>
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check me-1"></i>
                    Xác nhận duyệt
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Mark as Delivered Modal -->
<div class="modal" id="deliveredModal">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3>Xác nhận đã giao hàng</h3>
            <span class="modal-close" onclick="closeDeliveredModal()">&times;</span>
        </div>
        <form method="POST" action="" id="deliveredForm">
            @csrf
            <input type="hidden" name="trang_thai" value="Đã giao">
            <div class="modal-body">
                <div class="alert alert-success" style="background: rgba(34,197,94,.15); border: 1px solid rgba(34,197,94,.3); color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                    <i class="fa-solid fa-check-circle me-2"></i>
                    Xác nhận đơn hàng đã được giao thành công?
                </div>
                <p class="text-muted mb-0">
                    <i class="fa-solid fa-info-circle me-1"></i>
                    Sau khi xác nhận, trạng thái đơn hàng sẽ được cập nhật thành <strong>"Đã giao"</strong> và số lượng sản phẩm trong kho sẽ được trừ đi.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeliveredModal()">Đóng</button>
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check-double me-1"></i>
                    Xác nhận đã giao
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const bodyEl = document.body;
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    const viewModal = document.getElementById('viewModal');
    const bulkModal = document.getElementById('bulkModal');
    const cancelModal = document.getElementById('cancelModal');
    const approveModal = document.getElementById('approveModal');
    const deliveredModal = document.getElementById('deliveredModal');

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
        if(form) form.reset();
    }

    function openCreateModal(){ 
        openModal(createModal); 
        if(document.getElementById('productsContainer').children.length === 0){
            addProductRow();
        }
    }
    
    function closeCreateModal(){ closeModal(createModal); }
    function openBulkModal(){ openModal(bulkModal); }
    function closeBulkModal(){ closeModal(bulkModal); }

    function fillCustomerInfo(){
        const select = document.getElementById('customerSelect');
        const option = select.options[select.selectedIndex];
        const name = option.getAttribute('data-name') || '';
        document.getElementById('tenNguoiNhan').value = name;
    }

    let productIndex = 0;
    const productsData = @json($products);

    function addProductRow(){
        const container = document.getElementById('productsContainer');
        const row = document.createElement('div');
        row.className = 'product-row';
        row.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <strong>Sản phẩm #${productIndex + 1}</strong>
                <button type="button" class="btn btn-sm btn-link text-danger" onclick="removeProductRow(this)">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-7">
                    <select name="products[${productIndex}][IDSanPham]" class="form-control" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        ${productsData.map(p => `<option value="${p.ID}">${p.TenSanPham} - ${Number(p.Gia).toLocaleString()}đ (Tồn: ${p.SoLuongTon})</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="number" name="products[${productIndex}][SoLuong]" class="form-control" placeholder="Số lượng" min="1" required>
                </div>
            </div>
        `;
        container.appendChild(row);
        productIndex++;
    }

    function removeProductRow(button){
        button.closest('.product-row').remove();
    }

    function openEditModal(id){
        fetch(`/admin/orders/${id}`, { headers:{ 'Accept':'application/json' }})
            .then(res => res.json())
            .then(order => {
                document.getElementById('editForm').action = `/admin/orders/${order.ID}`;
                document.getElementById('edit_TenNguoiNhan').value = order.TenNguoiNhan || '';
                document.getElementById('edit_SDT').value = order.SDT || '';
                document.getElementById('edit_DiaChi').value = order.DiaChi || '';
                document.getElementById('edit_TrangThai').value = order.TrangThai || '';
                document.getElementById('edit_GhiChu').value = order.GhiChu || '';
                openModal(editModal);
            });
    }
    
    function closeEditModal(){ closeModal(editModal); }
    
    function openCancelModal(id){
        document.getElementById('cancelForm').action = `/admin/orders/${id}/cancel`;
        openModal(cancelModal);
    }
    
    function closeCancelModal(){ 
        closeModal(cancelModal); 
    }

    function openApproveModal(id){
        document.getElementById('approveForm').action = `/admin/orders/${id}/approve`;
        openModal(approveModal);
    }
    
    function closeApproveModal(){ 
        closeModal(approveModal); 
    }

    function openDeliveredModal(id){
        document.getElementById('deliveredForm').action = `/admin/orders/${id}/approve`;
        openModal(deliveredModal);
    }
    
    function closeDeliveredModal(){ 
        closeModal(deliveredModal); 
    }

    function viewOrder(id){
        fetch(`/admin/orders/${id}`, { headers:{ 'Accept':'application/json' }})
            .then(res => res.json())
            .then(order => {
                document.getElementById('view_MaDonHang').textContent = order.MaDonHang || '';
                document.getElementById('view_KhachHang').textContent = order.nguoi_dung ? order.nguoi_dung.Email : '---';
                document.getElementById('view_TenNguoiNhan').textContent = order.TenNguoiNhan || '';
                document.getElementById('view_SDT').textContent = order.SDT || '';
                document.getElementById('view_DiaChi').textContent = order.DiaChi || '';
                document.getElementById('view_PhuongThucTT').textContent = order.PhuongThucTT || '';
                document.getElementById('view_NgayDat').textContent = order.NgayDat ? new Date(order.NgayDat).toLocaleString('vi-VN') : '---';
                document.getElementById('view_PhiVanChuyen').textContent = order.PhiVanChuyen ? Number(order.PhiVanChuyen).toLocaleString() + ' đ' : '0 đ';
                document.getElementById('view_GiamVoucher').textContent = order.GiamVoucher ? Number(order.GiamVoucher).toLocaleString() + ' đ' : '0 đ';
                document.getElementById('view_TongThanhToan').textContent = order.TongThanhToan ? Number(order.TongThanhToan).toLocaleString() + ' đ' : '0 đ';
                
                const statusEl = document.getElementById('view_TrangThai');
                statusEl.textContent = order.TrangThai || '';
                statusEl.className = 'badge-status';
                const statusClass = {
                    'Chờ xác nhận': 'badge-pending',
                    'Đã xác nhận': 'badge-confirmed',
                    'Đang giao': 'badge-shipping',
                    'Đã giao': 'badge-completed',
                    'Đã hủy': 'badge-cancelled'
                }[order.TrangThai] || 'badge-pending';
                statusEl.classList.add(statusClass);
                
                const chiTietEl = document.getElementById('view_ChiTiet');
                chiTietEl.innerHTML = '';
                if(order.chi_tiet && order.chi_tiet.length){
                    order.chi_tiet.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${item.TenSanPham}</td>
                            <td>${Number(item.DonGia).toLocaleString()} đ</td>
                            <td>${item.SoLuong}</td>
                            <td><strong>${Number(item.DonGia * item.SoLuong).toLocaleString()} đ</strong></td>
                        `;
                        chiTietEl.appendChild(tr);
                    });
                }
                
                if(order.GhiChu){
                    document.getElementById('view_GhiChu').textContent = order.GhiChu;
                    document.getElementById('view_GhiChuWrapper').style.display = 'block';
                } else {
                    document.getElementById('view_GhiChuWrapper').style.display = 'none';
                }
                
                openModal(viewModal);
            });
    }
    
    function closeViewModal(){ closeModal(viewModal); }

    // Bulk orders
    const bulkOrdersContainer = document.getElementById('bulkOrdersContainer');
    const bulkTemplate = document.getElementById('bulkOrderTemplate').innerHTML;
    const bulkCountEl = document.getElementById('bulkCount');
    let bulkIndex = 0;

    function renderBulkCount(){ 
        bulkCountEl.textContent = bulkOrdersContainer.children.length; 
    }

    function addBulkOrderRow(){
        if(bulkOrdersContainer.children.length >= 20){
            alert('Mỗi lần chỉ thêm tối đa 20 đơn hàng.');
            return;
        }
        const html = bulkTemplate.replace(/__INDEX__/g, bulkIndex).replace(/__ORDER__/g, bulkOrdersContainer.children.length + 1);
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        const row = wrapper.firstElementChild;
        bulkOrdersContainer.appendChild(row);
        
        // Add initial product row for this order
        addBulkProductRow(bulkIndex);
        
        bulkIndex++;
        renderBulkCount();
    }
    
    function fillBulkCustomerInfo(index) {
        const select = document.querySelector(`select[data-index="${index}"]`);
        if (!select) return;
        
        const option = select.options[select.selectedIndex];
        const name = option.getAttribute('data-name') || '';
        
        const nameInput = document.getElementById(`bulk_ten_${index}`);
        if (nameInput) nameInput.value = name;
    }
    
    function addBulkProductRow(orderIndex) {
        const container = document.getElementById(`bulk_products_container_${orderIndex}`);
        if (!container) return;
        
        const productCount = container.children.length;
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2';
        row.style.padding = '10px';
        row.style.background = '#f8fafc';
        row.style.borderRadius = '8px';
        row.innerHTML = `
            <div class="col-md-7">
                <select name="orders[${orderIndex}][products][${productCount}][IDSanPham]" class="form-control form-control-sm bulk-product-select" data-order-index="${orderIndex}" required onchange="calculateBulkTotal(${orderIndex})">
                    <option value="">-- Chọn sản phẩm --</option>
                    ${productsData.map(p => `<option value="${p.ID}" data-price="${p.Gia}">${p.TenSanPham} - ${Number(p.Gia).toLocaleString()}đ (Tồn: ${p.SoLuongTon})</option>`).join('')}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="orders[${orderIndex}][products][${productCount}][SoLuong]" class="form-control form-control-sm bulk-product-quantity" data-order-index="${orderIndex}" placeholder="Số lượng" min="1" required onchange="calculateBulkTotal(${orderIndex})">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeBulkProductRow(this, ${orderIndex})">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
    }
    
    function removeBulkProductRow(button, orderIndex) {
        const row = button.closest('.row');
        if (row) {
            row.remove();
            calculateBulkTotal(orderIndex);
        }
    }
    
    function calculateBulkTotal(orderIndex) {
        // Tính tổng tiền hàng từ sản phẩm
        const productContainer = document.getElementById(`bulk_products_container_${orderIndex}`);
        if (!productContainer) return;
        
        let tongTienHang = 0;
        const productSelects = productContainer.querySelectorAll('.bulk-product-select');
        const productQuantities = productContainer.querySelectorAll('.bulk-product-quantity');
        
        productSelects.forEach((select, index) => {
            const selectedOption = select.options[select.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
            const quantity = parseFloat(productQuantities[index].value || 0);
            tongTienHang += price * quantity;
        });
        
        // Lấy phí vận chuyển
        const phiVanChuyen = parseFloat(document.querySelector(`input.bulk-phi-vanchuyen[data-order-index="${orderIndex}"]`)?.value || 0);
        
        // Tính giảm giá voucher
        let giamVoucher = 0;
        const voucherSelect = document.querySelector(`select.bulk-voucher[data-order-index="${orderIndex}"]`);
        if (voucherSelect && voucherSelect.value) {
            const selectedVoucher = voucherSelect.options[voucherSelect.selectedIndex];
            const loai = selectedVoucher.getAttribute('data-loai');
            const giaTri = parseFloat(selectedVoucher.getAttribute('data-giatri') || 0);
            const giamToiDa = parseFloat(selectedVoucher.getAttribute('data-giamtoida') || 0);
            
            if (loai === 'Phần trăm') {
                giamVoucher = (tongTienHang * giaTri) / 100;
                if (giamToiDa > 0 && giamVoucher > giamToiDa) {
                    giamVoucher = giamToiDa;
                }
            } else {
                giamVoucher = giaTri;
            }
        }
        
        // Tính tổng thanh toán
        const tongThanhToan = tongTienHang + phiVanChuyen - giamVoucher;
        
        // Cập nhật vào input
        const totalInput = document.getElementById(`bulk_total_${orderIndex}`);
        if (totalInput) {
            totalInput.value = Math.max(0, Math.round(tongThanhToan));
        }
    }

    function removeBulkOrder(button){
        const row = button.closest('.product-row');
        if(!row) return;
        row.remove();
        Array.from(bulkOrdersContainer.children).forEach((child, idx) => {
            child.querySelector('.bulk-order-number').textContent = idx + 1;
        });
        renderBulkCount();
    }

    document.getElementById('addBulkOrder').addEventListener('click', addBulkOrderRow);
    addBulkOrderRow();
    
    // Action dropdown functions
    function toggleActionDropdown(e, orderId) {
        e.stopPropagation();
        
        const dropdown = document.getElementById(`dropdown-${orderId}`);
        const allDropdowns = document.querySelectorAll('.action-dropdown');
        
        // Close all other dropdowns
        allDropdowns.forEach(d => {
            if (d.id !== `dropdown-${orderId}`) {
                d.classList.remove('active');
            }
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('active');
    }
    
    function closeAllDropdowns() {
        const allDropdowns = document.querySelectorAll('.action-dropdown');
        allDropdowns.forEach(d => d.classList.remove('active'));
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-menu')) {
            closeAllDropdowns();
        }
    });
</script>

</body>
</html>
