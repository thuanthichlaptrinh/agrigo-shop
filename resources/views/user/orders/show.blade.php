@extends('user.layouts.app')

@section('title', 'Chi tiết đơn hàng ' . ($orderSummary['code'] ?? ''))

@section('content')
<div class="row" style="margin-top: 1rem">
    <div class="col-12 col-lg-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h4 class="fw-700 mb-1">Đơn hàng {{ $orderSummary['code'] }}</h4>
                <p class="text-muted mb-0">Trạng thái: <strong>{{ $orderSummary['status'] }}</strong></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('user.profile', ['section' => 'orders']) }}" class="btn btn-outline-secondary">Quay lại danh sách</a>
                @if($orderSummary['can_cancel'])
                    <form action="{{ route('user.orders.cancel', ['order' => $order->ID]) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="cancel_reason" value="Người dùng hủy từ trang chi tiết">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn hủy đơn này?');">Hủy đơn hàng</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-700 mb-3">Sản phẩm</h5>
                        @foreach($orderSummary['items'] as $item)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ product_image_url($item['image'] ?? null) }}" alt="{{ $item['name'] }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px" class="me-3">
                                <div class="flex-fill">
                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                    <small class="text-muted">Số lượng: {{ $item['quantity'] }}</small>
                                </div>
                                <div class="text-end">
                                    <p class="mb-0 text-muted">Đơn giá</p>
                                    <strong>{{ number_format($item['price'], 0, ',', '.') }} đ</strong>
                                    <p class="mb-0 text-muted">Thành tiền</p>
                                    <strong>{{ number_format($item['subtotal'], 0, ',', '.') }} đ</strong>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="fw-700">Thông tin giao hàng</h5>
                        <p class="mb-1"><strong>Người nhận:</strong> {{ $orderSummary['recipient'] ?? '---' }}</p>
                        <p class="mb-1"><strong>Số điện thoại:</strong> {{ $orderSummary['phone'] ?? '---' }}</p>
                        <p class="mb-1"><strong>Địa chỉ:</strong> {{ $orderSummary['address'] ?? '---' }}</p>
                        <p class="mb-0 text-muted">Ghi chú: {{ $orderSummary['note'] ?? 'Không có' }}</p>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-700">Thanh toán</h5>
                        <p class="mb-1"><strong>Phương thức:</strong> {{ $orderSummary['payment'] }}</p>
                        <div class="d-flex justify-content-between">
                            <span>Tạm tính</span>
                            <strong>{{ number_format($orderSummary['total'] + $orderSummary['voucher_discount'] - $orderSummary['shipping_fee'], 0, ',', '.') }} đ</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Phí vận chuyển</span>
                            <strong>{{ number_format($orderSummary['shipping_fee'], 0, ',', '.') }} đ</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Giảm giá / Voucher</span>
                            <strong class="text-success">-{{ number_format($orderSummary['voucher_discount'], 0, ',', '.') }} đ</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-700">Tổng cộng</span>
                            <span class="fw-700 text-success">{{ number_format($orderSummary['total'], 0, ',', '.') }} đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
