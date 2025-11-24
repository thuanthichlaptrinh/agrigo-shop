@extends('user.layouts.app')

@section('title', 'Chi tiết đơn hàng ' . ($orderSummary['code'] ?? ''))

@section('content')
@php
    $status = $orderSummary['status'] ?? '';
    $statusLower = \Illuminate\Support\Str::lower($status);
    $statusColor = match(true) {
        \Illuminate\Support\Str::contains($statusLower, ['hoàn', 'giao']) => 'success',
        \Illuminate\Support\Str::contains($statusLower, ['chờ', 'xác nhận']) => 'warning',
        \Illuminate\Support\Str::contains($statusLower, 'hủy') => 'danger',
        default => 'secondary',
    };
@endphp

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.profile') }}" class="text-decoration-none text-muted">Tài khoản</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.profile', ['section' => 'orders']) }}" class="text-decoration-none text-muted">Đơn hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $orderSummary['code'] }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Left Column: Order Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">Đơn hàng #{{ $orderSummary['code'] }}</h5>
                        <span class="text-muted small"><i class="ri-time-line me-1"></i>Đặt ngày: {{ $orderSummary['date'] }}</span>
                    </div>
                    <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} px-3 py-2 rounded-pill border border-{{ $statusColor }} border-opacity-25">
                        {{ $status }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0 text-muted small text-uppercase fw-600">Sản phẩm</th>
                                    <th class="px-4 py-3 border-0 text-muted small text-uppercase fw-600 text-end">Đơn giá</th>
                                    <th class="px-4 py-3 border-0 text-muted small text-uppercase fw-600 text-center">SL</th>
                                    <th class="px-4 py-3 border-0 text-muted small text-uppercase fw-600 text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderSummary['items'] as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ product_image_url($item['image'] ?? null) }}" 
                                                     alt="{{ $item['name'] }}" 
                                                     style="width: 64px; height: 64px; object-fit: cover; border-radius: 8px;" 
                                                     class="me-3 border">
                                                <div>
                                                    <h6 class="mb-1 fw-600 text-dark">{{ $item['name'] }}</h6>
                                                    <small class="text-muted">{{ $item['unit'] ?? 'Sản phẩm' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-end fw-500">{{ number_format($item['price'], 0, ',', '.') }} đ</td>
                                        <td class="px-4 py-3 text-center">{{ $item['quantity'] }}</td>
                                        <td class="px-4 py-3 text-end fw-bold text-success">{{ number_format($item['subtotal'], 0, ',', '.') }} đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top px-4 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('user.profile', ['section' => 'orders']) }}" class="text-decoration-none text-muted fw-500">
                            <i class="ri-arrow-left-line me-1"></i> Quay lại danh sách
                        </a>
                        @if($orderSummary['can_cancel'])
                            <form action="{{ route('user.orders.cancel', ['order' => $order->ID]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="cancel_reason" value="Người dùng hủy từ trang chi tiết">
                                <button type="submit" class="btn btn-outline-danger rounded-pill px-4 fw-500" onclick="return confirm('Bạn chắc chắn muốn hủy đơn này?');">
                                    <i class="ri-close-circle-line me-1"></i> Hủy đơn hàng
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Info & Summary -->
        <div class="col-lg-4">
            <!-- Customer Info -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-uppercase text-muted small">Thông tin giao hàng</h6>
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-light rounded-circle p-2 me-3 text-primary">
                            <i class="ri-user-line"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Người nhận</small>
                            <span class="fw-600">{{ $orderSummary['recipient'] ?? '---' }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-light rounded-circle p-2 me-3 text-primary">
                            <i class="ri-phone-line"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Số điện thoại</small>
                            <span class="fw-600">{{ $orderSummary['phone'] ?? '---' }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-light rounded-circle p-2 me-3 text-primary">
                            <i class="ri-map-pin-line"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Địa chỉ</small>
                            <span class="fw-600">{{ $orderSummary['address'] ?? '---' }}</span>
                        </div>
                    </div>
                    @if(!empty($orderSummary['note']))
                    <div class="d-flex align-items-start">
                        <div class="bg-light rounded-circle p-2 me-3 text-primary">
                            <i class="ri-sticky-note-line"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Ghi chú</small>
                            <span class="fst-italic">{{ $orderSummary['note'] }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-uppercase text-muted small">Thanh toán</h6>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phương thức</span>
                        <span class="fw-600 text-end">{{ $orderSummary['payment'] }}</span>
                    </div>
                    <hr class="my-3 dashed-divider">
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính</span>
                        <span class="fw-500">{{ number_format($orderSummary['total'] + $orderSummary['voucher_discount'] - $orderSummary['shipping_fee'], 0, ',', '.') }} đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phí vận chuyển</span>
                        <span class="fw-500">{{ number_format($orderSummary['shipping_fee'], 0, ',', '.') }} đ</span>
                    </div>
                    @if($orderSummary['voucher_discount'] > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Giảm giá</span>
                        <span class="fw-500 text-success">-{{ number_format($orderSummary['voucher_discount'], 0, ',', '.') }} đ</span>
                    </div>
                    @endif
                    
                    <hr class="my-3">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-6">Tổng cộng</span>
                        <span class="fw-bold fs-4 text-danger">{{ number_format($orderSummary['total'], 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashed-divider {
        border-top: 1px dashed #e0e0e0;
        opacity: 1;
    }
    .fw-500 { font-weight: 500; }
    .fw-600 { font-weight: 600; }
    .fw-700 { font-weight: 700; }
</style>
@endsection
