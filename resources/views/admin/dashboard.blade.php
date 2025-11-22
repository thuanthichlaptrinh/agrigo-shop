@extends('admin.layouts.app')

@section('title', 'Tổng quan - Admin')

@section('content')
<style>
    .dashboard-header {
        margin-bottom: 30px;
    }
    
    .dashboard-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }
    
    .dashboard-header p {
        color: #6b7280;
        font-size: 14px;
    }
    
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 24px;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    
    .stat-card.orange {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stat-card.green {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stat-card.purple {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .stat-card.blue {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .stat-card-content {
        position: relative;
        z-index: 1;
    }
    
    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        backdrop-filter: blur(10px);
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 4px;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 14px;
        opacity: 0.9;
        font-weight: 500;
    }
    
    .stat-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 13px;
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }
    
    .stat-trend.up {
        color: rgba(255, 255, 255, 0.95);
    }
    
    .stat-trend.down {
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Chart Section */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .chart-card, .activity-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .card-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-header h3 i {
        color: #667eea;
    }
    
    .filter-buttons {
        display: flex;
        gap: 8px;
    }
    
    .filter-btn {
        padding: 6px 14px;
        border: 1px solid #e5e7eb;
        background: white;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        color: #6b7280;
        font-weight: 500;
    }
    
    .filter-btn:hover {
        background: #f9fafb;
        border-color: #667eea;
        color: #667eea;
    }
    
    .filter-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }
    
    /* Recent Orders */
    .order-list {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .order-item {
        padding: 16px;
        border-radius: 12px;
        background: #f9fafb;
        margin-bottom: 12px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    
    .order-item:hover {
        background: white;
        border-color: #e5e7eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .order-id {
        font-weight: 700;
        color: #1f2937;
        font-size: 14px;
    }
    
    .order-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .order-status.success {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }
    
    .order-status.warning {
        background: rgba(251, 191, 36, 0.1);
        color: #d97706;
    }
    
    .order-status.info {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }
    
    .order-status.danger {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    
    .order-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .order-customer {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .customer-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 14px;
        overflow: hidden;
    }
    
    .customer-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .customer-info {
        display: flex;
        flex-direction: column;
    }
    
    .customer-name {
        font-weight: 600;
        color: #374151;
        font-size: 13px;
    }
    
    .order-time {
        font-size: 12px;
        color: #9ca3af;
    }
    
    .order-amount {
        font-weight: 700;
        color: #667eea;
        font-size: 15px;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
    }
    
    .empty-state p {
        font-size: 14px;
    }
    
    /* Bottom Grid */
    .bottom-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .top-products-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .product-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 10px;
        transition: all 0.2s;
    }
    
    .product-item:hover {
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .product-rank {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }
    
    .product-rank.gold {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #92400e;
    }
    
    .product-rank.silver {
        background: linear-gradient(135deg, #c0c0c0, #e8e8e8);
        color: #374151;
    }
    
    .product-rank.bronze {
        background: linear-gradient(135deg, #cd7f32, #e8a87c);
        color: #78350f;
    }
    
    .product-rank.normal {
        background: #e5e7eb;
        color: #6b7280;
    }
    
    .product-details {
        flex: 1;
    }
    
    .product-name {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
        margin-bottom: 4px;
    }
    
    .product-sales {
        font-size: 12px;
        color: #9ca3af;
    }
    
    .product-revenue {
        font-weight: 700;
        color: #667eea;
        font-size: 14px;
    }
</style>

<div class="dashboard-header">
    <h1>Dashboard - Tổng quan hệ thống</h1>
    <p>Chào mừng trở lại! Đây là tổng quan về hoạt động kinh doanh của bạn.</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-header">
                <div>
                    <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
                    <div class="stat-label">Tổng đơn hàng</div>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-trend {{ ($orderGrowth ?? 0) >= 0 ? 'up' : 'down' }}">
                    <i class="fa-solid fa-arrow-{{ ($orderGrowth ?? 0) >= 0 ? 'up' : 'down' }}"></i> {{ abs($orderGrowth ?? 0) }}%
                </span>
                <span style="opacity: 0.8;">so với tháng trước</span>
            </div>
        </div>
    </div>
    
    <div class="stat-card orange">
        <div class="stat-card-content">
            <div class="stat-card-header">
                <div>
                    <div class="stat-value">{{ number_format($totalRevenue ?? 0) }}đ</div>
                    <div class="stat-label">Doanh thu</div>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-trend {{ ($revenueGrowth ?? 0) >= 0 ? 'up' : 'down' }}">
                    <i class="fa-solid fa-arrow-{{ ($revenueGrowth ?? 0) >= 0 ? 'up' : 'down' }}"></i> {{ abs($revenueGrowth ?? 0) }}%
                </span>
                <span style="opacity: 0.8;">so với tháng trước</span>
            </div>
        </div>
    </div>
    
    <div class="stat-card green">
        <div class="stat-card-content">
            <div class="stat-card-header">
                <div>
                    <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
                    <div class="stat-label">Sản phẩm</div>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-box"></i>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-trend up">
                    <i class="fa-solid fa-box"></i> {{ $totalProducts ?? 0 }}
                </span>
                <span style="opacity: 0.8;">sản phẩm trong kho</span>
            </div>
        </div>
    </div>
    
    <div class="stat-card blue">
        <div class="stat-card-content">
            <div class="stat-card-header">
                <div>
                    <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                    <div class="stat-label">Người dùng</div>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-trend {{ ($userGrowth ?? 0) >= 0 ? 'up' : 'down' }}">
                    <i class="fa-solid fa-arrow-{{ ($userGrowth ?? 0) >= 0 ? 'up' : 'down' }}"></i> {{ abs($userGrowth ?? 0) }}%
                </span>
                <span style="opacity: 0.8;">so với tháng trước</span>
            </div>
        </div>
    </div>
</div>

<!-- Chart and Recent Orders -->
<div class="dashboard-grid">
    <div class="chart-card">
        <div class="card-header">
            <h3>
                <i class="fa-solid fa-chart-line"></i>
                Biểu đồ doanh thu
            </h3>
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="updateChart('week')">Tuần</button>
                <button class="filter-btn" onclick="updateChart('month')">Tháng</button>
                <button class="filter-btn" onclick="updateChart('year')">Năm</button>
            </div>
        </div>
        <div id="revenueChart" style="min-height: 350px;"></div>
    </div>
    
    <div class="activity-card">
        <div class="card-header">
            <h3>
                <i class="fa-solid fa-clock-rotate-left"></i>
                Đơn hàng gần đây
            </h3>
            <a href="{{ route('admin.orders.index') }}" style="color: #667eea; font-size: 13px; font-weight: 600; text-decoration: none;">
                Xem tất cả <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="order-list">
            @forelse($recentOrders ?? [] as $order)
            <div class="order-item">
                <div class="order-header">
                    <span class="order-id">#{{ $order->MaDonHang }}</span>
                    <span class="order-status {{ 
                        $order->TrangThai == 'Đã giao' ? 'success' : 
                        ($order->TrangThai == 'Đang giao' ? 'info' : 
                        ($order->TrangThai == 'Đã hủy' ? 'danger' : 'warning'))
                    }}">
                        {{ $order->TrangThai }}
                    </span>
                </div>
                <div class="order-body">
                    <div class="order-customer">
                        <div class="customer-avatar">
                            @if($order->nguoiDung && $order->nguoiDung->HinhAnh)
                               <img src="{{ asset($order->nguoiDung->HinhAnh) }}" alt="{{ $order->TenNguoiNhan }}">
                            @else
                                {{ strtoupper(substr($order->TenNguoiNhan, 0, 1)) }}
                            @endif
                        </div>
                        <div class="customer-info">
                            <span class="customer-name">{{ $order->TenNguoiNhan }}</span>
                            <span class="order-time">{{ $order->NgayDat->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="order-amount">{{ number_format($order->TongThanhToan) }}đ</div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fa-solid fa-inbox"></i>
                <p>Chưa có đơn hàng nào</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Bottom Info Cards -->
<div class="bottom-grid">
    <div class="info-card">
        <div class="card-header">
            <h3>
                <i class="fa-solid fa-trophy"></i>
                Sản phẩm bán chạy
            </h3>
        </div>
        <div class="top-products-list">
            @forelse($topProducts ?? [] as $index => $product)
            <div class="product-item">
                <div class="product-rank {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'normal')) }}">{{ $index + 1 }}</div>
                <div class="product-details">
                    <div class="product-name">{{ $product['name'] }}</div>
                    <div class="product-sales">{{ $product['sold'] }} đã bán</div>
                </div>
                <div class="product-revenue">{{ number_format($product['revenue']) }}đ</div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px 20px; color: #9ca3af;">
                <i class="fa-solid fa-box-open" style="font-size: 48px; margin-bottom: 12px; opacity: 0.3;"></i>
                <p style="font-size: 14px;">Chưa có dữ liệu bán hàng</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <div class="info-card">
        <div class="card-header">
            <h3>
                <i class="fa-solid fa-chart-pie"></i>
                Thống kê trạng thái đơn hàng
            </h3>
        </div>
        <div id="orderStatusChart" style="min-height: 300px;"></div>
    </div>
    
    <div class="info-card">
        <div class="card-header">
            <h3>
                <i class="fa-solid fa-bell"></i>
                Thông báo hệ thống
            </h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @if(($lowStockProducts ?? 0) > 0)
            <div style="padding: 12px; background: rgba(239, 68, 68, 0.1); border-left: 3px solid #ef4444; border-radius: 8px;">
                <div style="font-weight: 600; color: #dc2626; font-size: 13px; margin-bottom: 4px;">
                    <i class="fa-solid fa-exclamation-triangle"></i> Sản phẩm sắp hết hàng
                </div>
                <div style="font-size: 12px; color: #6b7280;">
                    {{ $lowStockProducts }} sản phẩm có số lượng tồn kho dưới 10
                </div>
            </div>
            @endif
            
            @if(($pendingOrders ?? 0) > 0)
            <div style="padding: 12px; background: rgba(251, 191, 36, 0.1); border-left: 3px solid #f59e0b; border-radius: 8px;">
                <div style="font-weight: 600; color: #d97706; font-size: 13px; margin-bottom: 4px;">
                    <i class="fa-solid fa-clock"></i> Đơn hàng chờ xử lý
                </div>
                <div style="font-size: 12px; color: #6b7280;">
                    {{ $pendingOrders }} đơn hàng đang chờ xác nhận
                </div>
            </div>
            @endif
            
            @if(($todayNewUsers ?? 0) > 0)
            <div style="padding: 12px; background: rgba(59, 130, 246, 0.1); border-left: 3px solid #3b82f6; border-radius: 8px;">
                <div style="font-weight: 600; color: #2563eb; font-size: 13px; margin-bottom: 4px;">
                    <i class="fa-solid fa-user-plus"></i> Người dùng mới
                </div>
                <div style="font-size: 12px; color: #6b7280;">
                    {{ $todayNewUsers }} người dùng đăng ký mới hôm nay
                </div>
            </div>
            @endif
            
            @if(($lowStockProducts ?? 0) == 0 && ($pendingOrders ?? 0) == 0)
            <div style="padding: 12px; background: rgba(34, 197, 94, 0.1); border-left: 3px solid #22c55e; border-radius: 8px;">
                <div style="font-weight: 600; color: #16a34a; font-size: 13px; margin-bottom: 4px;">
                    <i class="fa-solid fa-check-circle"></i> Hệ thống hoạt động tốt
                </div>
                <div style="font-size: 12px; color: #6b7280;">
                    Không có cảnh báo, tất cả hoạt động bình thường
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Revenue Chart
    var revenueOptions = {
        series: [{
            name: 'Doanh thu',
            data: {!! json_encode($chartData ?? [30, 40, 35, 50, 49, 60, 70]) !!}
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#667eea'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: {!! json_encode($chartLabels ?? ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN']) !!},
            labels: {
                style: {
                    colors: '#9ca3af',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#9ca3af',
                    fontSize: '12px'
                },
                formatter: function (val) {
                    return val.toLocaleString('vi-VN') + "đ"
                }
            }
        },
        grid: {
            borderColor: '#f3f4f6',
            strokeDashArray: 4
        },
        tooltip: {
            theme: 'light',
            y: {
                formatter: function (val) {
                    return val.toLocaleString('vi-VN') + "đ"
                }
            }
        }
    };
    
    var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
    revenueChart.render();
    
    // Order Status Chart
    var statusOptions = {
        series: [
            {{ $orderStatusStats['delivered'] ?? 0 }},
            {{ $orderStatusStats['shipping'] ?? 0 }},
            {{ $orderStatusStats['pending'] ?? 0 }},
            {{ $orderStatusStats['cancelled'] ?? 0 }}
        ],
        chart: {
            type: 'donut',
            height: 300
        },
        labels: ['Đã giao', 'Đang giao', 'Chờ xác nhận', 'Đã hủy'],
        colors: ['#22c55e', '#3b82f6', '#f59e0b', '#ef4444'],
        legend: {
            position: 'bottom',
            fontSize: '13px',
            fontFamily: 'inherit',
            labels: {
                colors: '#6b7280'
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Tổng đơn',
                            fontSize: '14px',
                            color: '#6b7280',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        },
                        value: {
                            fontSize: '24px',
                            fontWeight: 700,
                            color: '#1f2937'
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        tooltip: {
            theme: 'light'
        }
    };
    
    var statusChart = new ApexCharts(document.querySelector("#orderStatusChart"), statusOptions);
    statusChart.render();
    
    // Update chart function
    function updateChart(period) {
        // Remove active class from all buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Add active class to clicked button
        event.target.classList.add('active');
        
        // Update chart data based on period (you can customize this)
        console.log('Updating chart for period:', period);
    }
</script>
@endpush
