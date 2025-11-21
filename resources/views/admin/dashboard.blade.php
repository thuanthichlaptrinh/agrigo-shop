@extends('admin.layouts.app')

@section('title', 'Tổng quan - Admin')

@section('content')
<h1 class="title">Tổng quan</h1>
<ul class="breadcrumbs">
    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">Tổng quan</a></li>
</ul>

<div class="info-data">
    <div class="card">
        <div class="head">
            <div>
                <h2>{{ $totalOrders ?? 0 }}</h2>
                <p>Đơn hàng</p>
            </div>
            <i class="bx bx-cart icon"></i>
        </div>
        <span class="progress" data-value="40%"></span>
        <span class="label">40%</span>
    </div>
    <div class="card">
        <div class="head">
            <div>
                <h2>{{ number_format($totalRevenue ?? 0) }}đ</h2>
                <p>Doanh thu</p>
            </div>
            <i class="bx bx-dollar icon"></i>
        </div>
        <span class="progress" data-value="60%"></span>
        <span class="label">60%</span>
    </div>
    <div class="card">
        <div class="head">
            <div>
                <h2>{{ $totalProducts ?? 0 }}</h2>
                <p>Sản phẩm</p>
            </div>
            <i class="bx bx-package icon"></i>
        </div>
        <span class="progress" data-value="30%"></span>
        <span class="label">30%</span>
    </div>
    <div class="card">
        <div class="head">
            <div>
                <h2>{{ $totalUsers ?? 0 }}</h2>
                <p>Người dùng</p>
            </div>
            <i class="bx bx-user icon"></i>
        </div>
        <span class="progress" data-value="80%"></span>
        <span class="label">80%</span>
    </div>
</div>

<div class="data">
    <div class="content-data">
        <div class="head">
            <h3>Biểu đồ doanh thu</h3>
            <div class="menu">
                <i class="bx bx-dots-horizontal-rounded icon"></i>
                <ul class="menu-link">
                    <li><a href="#">Chỉnh sửa</a></li>
                    <li><a href="#">Lưu</a></li>
                    <li><a href="#">Xóa</a></li>
                </ul>
            </div>
        </div>
        <div class="chart">
            <div id="chart"></div>
        </div>
    </div>
    
    <div class="content-data">
        <div class="head">
            <h3>Đơn hàng gần đây</h3>
            <div class="menu">
                <i class="bx bx-dots-horizontal-rounded icon"></i>
                <ul class="menu-link">
                    <li><a href="{{ route('admin.orders.index') }}">Xem tất cả</a></li>
                </ul>
            </div>
        </div>
        <div class="chat-box">
            @forelse($recentOrders ?? [] as $order)
            <div class="msg">
                <div class="chat">
                    <div class="profile">
                        <span class="username">{{ $order->TenNguoiNhan }}</span>
                        <span class="time">{{ $order->NgayDat->diffForHumans() }}</span>
                    </div>
                    <p>Đơn hàng #{{ $order->MaDonHang }} - {{ number_format($order->TongThanhToan) }}đ</p>
                    <span class="badge badge-{{ $order->TrangThai == 'Đã giao' ? 'success' : 'warning' }}">
                        {{ $order->TrangThai }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-center">Chưa có đơn hàng nào</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Chart configuration
    var options = {
        series: [{
            name: 'Doanh thu',
            data: @json($chartData ?? [])
        }],
        chart: {
            height: 350,
            type: 'area'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: @json($chartLabels ?? [])
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString('vi-VN') + "đ"
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
@endpush
