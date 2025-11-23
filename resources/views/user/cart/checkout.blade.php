@extends('user.layouts.app')

@section('title', 'Thanh toán - Organic Shop')

@push('styles')
<style>
    .checkout-wrapper {
        width: min(640px, 100%);
        min-height: 90vh;
        border-radius: 18px;
        box-shadow: 0 18px 35px rgba(15, 23, 42, 0.08);
        background: #fff;
    }

    .success-hero {
        border-bottom: 1px solid rgba(15, 23, 42, 0.05);
        padding: 24px 28px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .success-hero .hero-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: rgba(34, 197, 94, 0.14);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .success-hero h3 {
        font-weight: 700;
        margin-bottom: 4px;
        color: #0f172a;
    }

    .checkout-card {
        border-radius: 18px;
        background-color: #ffffff;
        padding: 28px;
        margin: 0 28px 28px;
        box-shadow: none;
        border: 1px solid rgba(148, 163, 184, 0.15);
    }

    .order-meta {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding-bottom: 18px;
        border-bottom: 1px dashed rgba(15, 23, 42, 0.08);
    }

    @media (min-width: 768px) {
        .order-meta {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }

    .meta-label {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.08em;
        color: #94a3b8;
        font-weight: 600;
    }

    .meta-value {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }

    .meta-value.code {
        font-family: 'Fira Code', 'SFMono-Regular', Consolas, monospace;
        letter-spacing: 0.02em;
    }

    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 14px;
        text-transform: capitalize;
    }

    .status-chip.status-success {
        background: #dcfce7;
        color: #166534;
    }

    .status-chip.status-danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-chip.status-pending {
        background: #fef9c3;
        color: #92400e;
    }

    .info-grid {
        margin-top: 22px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 14px;
    }

    .info-row {
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 14px;
    }

    .info-row.full {
        grid-column: span 2;
    }

    .info-label {
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .info-value {
        font-size: 15px;
        color: #0f172a;
        font-weight: 600;
    }

    .payment-summary {
        margin-top: 24px;
        background: #fefefe;
        border-radius: 14px;
        padding: 18px 22px;
        border: 1px solid rgba(15, 23, 42, 0.05);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: 15px;
        color: #475569;
    }

    .summary-row.total {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }

    .btn-t {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .btn-centered {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .cta-row {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 22px;
    }

    @media (min-width: 576px) {
        .cta-row {
            flex-direction: row;
        }
    }

    .cta-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-t:focus-visible {
        outline: 3px solid rgba(16, 185, 129, 0.3);
        outline-offset: 2px;
    }

    .btn-t:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 18px rgba(34, 197, 94, 0.18);
    }

    .huyDon {
        transition: color 0.2s ease;
        color: #94a3b8;
        font-size: 14px;
    }

    .huyDon:hover span,
    .huyDon:hover {
        color: #6b7280;
    }

    .support-link-icon {
        color: #4c1d95;
        width: 28px;
        height: 28px;
        background-color: #e0e7ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .support-link {
        gap: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        text-align: center;
    }

    .support-link-button {
        border: none;
        background: transparent;
        padding: 0;
        color: inherit;
        font: inherit;
        cursor: pointer;
        text-decoration: none;
        width: 100%;
    }

    .support-link-button:focus-visible {
        outline: 2px solid rgba(59, 130, 246, 0.4);
        border-radius: 999px;
    }
</style>
@endpush

@section('content')
@php
    $order = $summary ?? [];
    $shippingFee = $order['shipping_fee'] ?? 0;
    $voucherDiscount = $order['voucher_discount'] ?? 0;
    $orderStatus = $order['order_status'] ?? ($order['payment_status'] ?? 'Chờ xác nhận');
    $statusKey = function_exists('mb_strtolower') ? mb_strtolower($orderStatus, 'UTF-8') : strtolower($orderStatus);
    $statusClass = 'pending';
    $statusIcon = 'ri-time-line';

    if (str_contains($statusKey, 'thành công') || str_contains($statusKey, 'giao') || str_contains($statusKey, 'hoàn')) {
        $statusClass = 'success';
        $statusIcon = 'ri-checkbox-circle-line';
    } elseif (str_contains($statusKey, 'hủy') || str_contains($statusKey, 'huỷ')) {
        $statusClass = 'danger';
        $statusIcon = 'ri-close-circle-line';
    }

    $orderCode = $order['order_code'] ?? 'Đang cập nhật';
    $deliveryWindow = $order['delivery_window'] ?? '16h - 19h ngày mai';
    $paymentMethod = $order['payment_method'] ?? 'Thanh toán khi nhận hàng';
    $placedAt = $order['placed_at'] ?? ($order['created_at'] ?? 'Đang cập nhật');
    $subtotal = $order['subtotal'] ?? ($order['total'] ?? 0);
    $total = $order['total'] ?? 0;
@endphp

<div class="row justify-content-center">
    <div class="checkout-wrapper bg-white px-0">
            <div class="success-hero" style="display: flex; align-items: center; justify-content: center; padding: 24px 28px;">
                <div class="hero-icon">
                    <i class="ri-checkbox-circle-line text-success" style="font-size: 34px;"></i>
                </div>
                <div>
                    <h3 class="mb-1">Đặt hàng thành công!</h3>
                    <p class="meta-label mb-0">Mã đơn hàng: {{ $orderCode }}</p>
                </div>
            </div>

            <div class="checkout-card">
                @if(session('status'))
                    <div class="alert alert-info py-2 px-3 mb-3" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="order-meta">
                    {{-- <div>
                        <p class="meta-label">Thời gian đặt</p>
                        <p class="meta-value">{{ $placedAt }}</p>
                    </div> --}}
                    <div>
                        <p class="meta-label">Trạng thái đơn</p>
                        <span class="status-chip status-{{ $statusClass }}">
                            <i class="{{ $statusIcon }}"></i>
                            {{ $orderStatus }}
                        </span>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-row">
                        <span class="info-label">Người nhận</span>
                        <span class="info-value">{{ $order['receiver'] ?? 'Khách hàng Organic' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số điện thoại</span>
                        <span class="info-value">{{ $order['phone'] ?? 'Đang cập nhật' }}</span>
                    </div>
                    <div class="info-row full">
                        <span class="info-label">Địa chỉ giao</span>
                        <span class="info-value">{{ $order['address'] ?? 'Vui lòng bổ sung địa chỉ nhận hàng' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Giao dự kiến</span>
                        <span class="info-value">{{ $deliveryWindow }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phương thức thanh toán</span>
                        <span class="info-value">{{ $paymentMethod }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tình trạng thanh toán</span>
                        <span class="info-value">{{ $order['payment_status'] ?? 'Chờ xử lý' }}</span>
                    </div>
                </div>

                <div class="payment-summary">
                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <span>{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển</span>
                        <span>{{ number_format($shippingFee, 0, ',', '.') }}đ</span>
                    </div>
                    @if($voucherDiscount > 0)
                        <div class="summary-row" style="color: #16a34a;">
                            <span>Voucher @if(!empty($order['voucher_code'])) ({{ $order['voucher_code'] }}) @endif</span>
                            <span>-{{ number_format($voucherDiscount, 0, ',', '.') }}đ</span>
                        </div>
                    @endif
                    <div class="summary-row total">
                        <span>Tổng thanh toán</span>
                        <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                </div>

                <p class="text-muted mt-3 mb-3" style="font-size: 13px;">Trạng thái đơn sẽ được cập nhật tự động khi nhân viên xác nhận. Nếu cần hỗ trợ, vui lòng liên hệ hotline 1900-0000.</p>

                <div class="cta-row">
                    <a href="{{ route('user.home') }}" class="btn btn-success btn-t btn-centered flex-fill fw-semibold py-3">
                        Tiếp tục mua hàng
                    </a>
                    <a href="{{ route('user.checkout.edit') }}" class="btn btn-outline-success btn-t flex-fill py-3 cta-link">
                        <i class="ri-pencil-line" style="font-size: 18px;"></i>
                        <div class="text-start">
                            <div class="fw-semibold">Xem / Sửa đơn hàng</div>
                            <small class="text-muted">Cập nhật địa chỉ, ghi chú, sản phẩm...</small>
                        </div>
                    </a>
                </div>

                @if(!empty($order['order_id']))
                    <form method="POST" action="{{ route('user.checkout.cancel') }}" class="mt-3 w-100 d-flex justify-content-center">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order['order_id'] }}">
                        <button type="submit" class="huyDon support-link support-link-button" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                            <span class="support-link-icon">
                                <i class="ri-customer-service-2-line"></i>
                            </span>
                            <span>Hủy đơn hàng / Liên hệ CSKH</span>
                        </button>
                    </form>
                @endif
            </div>
    </div>
    </div>
    @endsection
