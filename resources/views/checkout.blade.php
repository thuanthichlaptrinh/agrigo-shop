@extends('layouts.app')

@section('title', 'Thanh toán - Organic Shop')

@push('styles')
<style>
    .huyDon:hover {
        filter: brightness(1.2);
    }
    .btn-t:hover {
        filter: brightness(1.3);
        border: 1px solid rgb(2, 186, 2) !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="mx-auto bg-white" style="width: 50%; min-height: 90vh; border-radius: 8px">
        <!-- Success Message -->
        <div class="row">
            <div class="d-flex align-items-center justify-content-center p-4">
                <img src="{{ asset('template/Assets/Images/checked.png') }}" alt="" />
                <h3 class="text-success mb-0" style="margin-left: 8px">Đặt hàng thành công</h3>
            </div>
        </div>

        <!-- Order Info -->
        <div class="row p-3" style="padding-top: 0 !important">
            <div style="border-radius: 4px; padding: 16px 24px; background-color: #eceff7">
                <div class="d-flex align-items-center mb-2">
                    <div style="width: 120px; margin-right: 14px; color: #999; font-weight: 500; font-size: 17px">Người nhận:</div>
                    <span style="font-size: 17px">{{ $order['receiver_name'] ?? 'Anh Ngô Minh Thuận' }}</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <div style="width: 120px; margin-right: 14px; color: #999; font-weight: 500; font-size: 17px">Số điện thoại:</div>
                    <span style="font-size: 17px">{{ $order['phone'] ?? '0783363383' }}</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <div style="width: 120px; margin-right: 14px; color: #999; font-weight: 500; font-size: 17px">Địa chỉ:</div>
                    <span style="font-size: 17px">{{ $order['address'] ?? '157/29/01 Bùi Minh Trực, Phường 5, Quận 8, Hồ Chí Minh' }}</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <div style="width: 120px; margin-right: 14px; color: #999; font-weight: 500; font-size: 17px">Giao lúc:</div>
                    <span style="font-size: 17px">{{ $order['delivery_time'] ?? '16H - 19H - Thứ Hai (18/11)' }}</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <div style="width: 120px; margin-right: 14px; color: #999; font-weight: 500; font-size: 17px">Thanh toán:</div>
                    <span style="font-size: 17px"><span class="fw-500">{{ $order['payment_method'] ?? 'Tiền mặt' }}</span> khi nhận hàng</span>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 120px; margin-right: 14px; color: #999; font-weight: 500; font-size: 17px">Tổng tiền:</div>
                    <span style="font-size: 17px; font-weight: 500">{{ number_format($order['total'] ?? 667000) }} VND</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row mt-2">
            <div class="d-flex align-items-center justify-content-center" style="gap: 8px">
                <a href="{{ route('home') }}" class="btn-t" style="width: 200px; text-align: center; text-decoration: none; padding: 15px; border: 1px solid green; border-radius: 4px; color: green">
                    Tiếp tục mua hàng
                </a>
                <a href="{{ route('profile') }}" class="btn-t d-flex align-items-center justify-content-center" style="width: 220px; text-align: center; text-decoration: none; padding: 6px; border: 1px solid green; border-radius: 4px; color: green">
                    <div class="px-2">
                        <img src="{{ asset('template/Assets/Images/pencil1.png') }}" alt="" />
                    </div>
                    <span>Xem đơn hàng</span>
                </a>
            </div>
        </div>

        <!-- Order Items -->
        <div class="row mt-4 p-3">
            <h5 class="fw-700">Chi tiết đơn hàng</h5>
            @forelse($orderItems ?? [] as $item)
            <div class="d-flex align-items-center border-bottom py-3">
                <div style="width: 80px">
                    <img src="{{ asset($item['image'] ?? 'template/Assets/Images/chuoi.jpg') }}" class="w-100" alt="" />
                </div>
                <div class="flex-grow-1 px-3">
                    <p class="mb-1 fw-500">{{ $item['name'] ?? 'Sản phẩm' }}</p>
                    <p class="text-muted mb-0">Số lượng: {{ $item['quantity'] ?? 1 }}</p>
                </div>
                <div>
                    <p class="mb-0 fw-700">{{ number_format($item['price'] ?? 0) }}đ</p>
                </div>
            </div>
            @empty
            <p class="text-center text-muted">Không có sản phẩm</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
