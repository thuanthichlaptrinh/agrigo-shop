@extends('user.layouts.app')

@section('title', 'Chỉnh sửa thông tin đơn hàng - Organic Shop')

@push('styles')
<style>
    .edit-wrapper {
        margin-top: 24px;
    }

    .edit-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .edit-form-panel {
        padding: 28px 40px;
        border-right: 1px solid #e0e0e0;
    }

    .edit-summary-panel {
        padding: 28px 40px;
        background: #f8fafc;
    }

    .input-floating {
        height: 52px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        position: relative;
        padding: 8px 12px;
        margin-top: 18px;
        background: #fff;
    }

    .input-floating label {
        position: absolute;
        top: -10px;
        left: 12px;
        background: #fff;
        padding: 0 6px;
        font-size: 13px;
        color: #64748b;
    }

    .input-floating input,
    .input-floating textarea,
    .input-floating select {
        width: 100%;
        height: 100%;
        border: none;
        outline: none;
        font-size: 16px;
        background: transparent;
    }

    .input-floating textarea {
        height: 110px;
        resize: none;
    }

    .payment-method-edit {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-top: 18px;
        overflow: hidden;
    }

    .payment-method-option {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 0;
    }

    .payment-method-option:last-child {
        border-bottom: none;
    }

    .payment-method-option input {
        width: 18px;
        height: 18px;
    }

    .payment-method-option img,
    .payment-method-option .brand-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #fff;
        object-fit: contain;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
    }

    .payment-method-option small {
        display: block;
        color: #94a3b8;
        font-size: 13px;
        margin-top: 2px;
    }

    .brand-vnpay {
        background: #fee2e2;
        color: #dc2626;
    }

    .brand-momo {
        background: #fce7f3;
        color: #a21caf;
    }

    .voucher-box {
        border: 1px dashed #cbd5f5;
        border-radius: 12px;
        padding: 16px;
        background: #f8fafc;
        margin-top: 18px;
    }

    .voucher-box label {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        margin-bottom: 8px;
    }

    .voucher-box input {
        flex: 1;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 10px 14px;
        font-weight: 600;
        background: #fff;
    }

    .voucher-box button {
        border-radius: 10px;
        min-width: 140px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        padding-bottom: 14px;
        margin-bottom: 14px;
        border-bottom: 1px solid #e2e8f0;
    }

    .summary-item img {
        width: 56px;
        height: 56px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }

    .summary-pricing div {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 15px;
    }

    .summary-pricing div.total {
        font-size: 18px;
        font-weight: 700;
    }

    .back-cart {
        color: #64748b;
    }

    .back-cart:hover {
        color: #0f172a;
    }

    @media (max-width: 992px) {
        .edit-form-panel {
            border-right: none;
            border-bottom: 1px solid #e0e0e0;
        }
    }
</style>
@endpush

@section('content')
@php
    $summaryData = $summary ?? [];
    $items = collect($summaryData['items'] ?? []);
    $deliverySlots = $deliverySlots ?? [];
    $selectedDelivery = old('delivery_window', $summaryData['delivery_window'] ?? ($deliverySlots[0] ?? ''));
    $paymentCode = old('payment_method', $summaryData['payment_method_code'] ?? 'cod');
    $voucherCode = old('voucher_code', $summaryData['voucher_code'] ?? '');
    $voucherDiscount = $summaryData['voucher_discount'] ?? 0;
@endphp

<div class="row edit-wrapper">
    <form action="{{ route('user.checkout.update') }}" method="POST" class="col-12 edit-card">
        @csrf
        <div class="row g-0">
            <div class="col-lg-7 edit-form-panel">
                <a href="{{ route('user.checkout.index') }}" class="back-cart text-decoration-none d-inline-flex align-items-center mb-3 text-muted" style="gap: 6px;">
                    <i class="ri-arrow-left-line"></i>
                    <span>Quay lại đơn hàng</span>
                </a>

                <h4 class="mb-3">Chỉnh sửa thông tin giao hàng</h4>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-floating @error('receiver') is-invalid @enderror">
                            <label>Họ và tên</label>
                            <input type="text" name="receiver" value="{{ old('receiver', $summaryData['receiver'] ?? $order->TenNguoiNhan) }}" />
                        </div>
                        @error('receiver')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="input-floating @error('phone') is-invalid @enderror">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" value="{{ old('phone', $summaryData['phone'] ?? $order->SDT) }}" />
                        </div>
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="input-floating @error('address') is-invalid @enderror" style="height: auto; padding-bottom: 0;">
                    <label>Địa chỉ nhận hàng</label>
                    <textarea name="address" rows="3">{{ old('address', $summaryData['address'] ?? $order->DiaChi) }}</textarea>
                </div>
                @error('address')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                <div class="input-floating @error('delivery_window') is-invalid @enderror" style="height: 62px;">
                    <label>Khung giờ giao hàng</label>
                    <select name="delivery_window">
                        @foreach($deliverySlots as $slot)
                            <option value="{{ $slot }}" {{ $selectedDelivery === $slot ? 'selected' : '' }}>{{ $slot }}</option>
                        @endforeach
                    </select>
                </div>
                @error('delivery_window')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                <div class="mt-4">
                    <p class="fw-semibold">Phương thức thanh toán</p>
                    <div class="payment-method-edit">
                        <label class="payment-method-option">
                            <input type="radio" name="payment_method" value="cod" {{ $paymentCode === 'cod' ? 'checked' : '' }} />
                            <img src="{{ asset('template/Assets/Images/cash-on-delivery1.png') }}" alt="COD" />
                            <div>
                                <div>Thanh toán khi giao hàng (COD)</div>
                                <small>Kiểm tra hàng xong rồi mới thanh toán</small>
                            </div>
                        </label>
                        <label class="payment-method-option">
                            <input type="radio" name="payment_method" value="bank" {{ $paymentCode === 'bank' ? 'checked' : '' }} />
                            <img src="{{ asset('template/Assets/Images/atm-card1.png') }}" alt="Bank" />
                            <div>
                                <div>Thẻ ATM / Visa / Master</div>
                                <small>Hỗ trợ hầu hết ngân hàng nội địa và quốc tế</small>
                            </div>
                        </label>
                        <label class="payment-method-option">
                            <input type="radio" name="payment_method" value="vnpay" {{ $paymentCode === 'vnpay' ? 'checked' : '' }} />
                            <span class="brand-icon brand-vnpay">VN</span>
                            <div>
                                <div>VNPay QR</div>
                                <small>Quét QR trên app ngân hàng, xác nhận tức thì</small>
                            </div>
                        </label>
                        <label class="payment-method-option">
                            <input type="radio" name="payment_method" value="momo" {{ $paymentCode === 'momo' ? 'checked' : '' }} />
                            <span class="brand-icon brand-momo">Mo</span>
                            <div>
                                <div>Ví MoMo</div>
                                <small>Thanh toán nhanh chóng bằng ví điện tử</small>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-floating" style="height: auto; padding-bottom: 0; margin-bottom: 0;">
                    <label>Ghi chú</label>
                    <textarea name="note" rows="3">{{ old('note', $summaryData['note'] ?? $order->GhiChu) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 flex-wrap">
                    <a href="{{ route('user.checkout.index') }}" class="btn btn-light">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </div>

            <div class="col-lg-5 edit-summary-panel">
                <h5 class="mb-3">Đơn hàng #{{ $summaryData['order_code'] ?? $order->MaDonHang }}</h5>
                @foreach($items as $item)
                    <div class="summary-item">
                        <img src="{{ product_image_url($item['image'] ?? null) }}" alt="{{ $item['name'] ?? 'Sản phẩm' }}" />
                        <div class="flex-fill px-3">
                            <p class="mb-1 fw-semibold">{{ $item['name'] ?? 'Sản phẩm' }}</p>
                            <small class="text-muted">Số lượng: {{ $item['quantity'] ?? 1 }}</small>
                        </div>
                        <div class="fw-semibold">{{ number_format($item['line_total'] ?? (($item['price'] ?? 0) * ($item['quantity'] ?? 1)), 0, ',', '.') }}đ</div>
                    </div>
                @endforeach

                <div class="voucher-box">
                    <label>Voucher áp dụng</label>
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <input type="text" name="voucher_code" value="{{ $voucherCode }}" placeholder="Chưa áp dụng" readonly />
                        <button type="button" class="btn btn-outline-secondary" disabled>Đã áp dụng</button>
                    </div>
                    @if($voucherDiscount > 0 && $voucherCode)
                        <small class="text-success d-block mt-2">Giảm {{ number_format($voucherDiscount, 0, ',', '.') }}đ từ mã {{ $voucherCode }}.</small>
                    @else
                        <small class="text-muted d-block mt-2">Đơn hàng chưa áp dụng mã giảm giá.</small>
                    @endif
                </div>

                <div class="summary-pricing mt-3">
                    <div>
                        <span>Tạm tính</span>
                        <span>{{ number_format($summaryData['subtotal'] ?? 0, 0, ',', '.') }}đ</span>
                    </div>
                    <div>
                        <span>Phí vận chuyển</span>
                        <span>{{ number_format($summaryData['shipping_fee'] ?? 0, 0, ',', '.') }}đ</span>
                    </div>
                    @if(($summaryData['voucher_discount'] ?? 0) > 0)
                        <div class="text-success">
                            <span>Voucher</span>
                            <span>-{{ number_format($summaryData['voucher_discount'], 0, ',', '.') }}đ</span>
                        </div>
                    @endif
                    <div class="total">
                        <span>Tổng thanh toán</span>
                        <span>{{ number_format($summaryData['total'] ?? 0, 0, ',', '.') }}đ</span>
                    </div>
                </div>

                <p class="text-muted small mt-3">Chỉ có thể chỉnh sửa các đơn hàng đang chờ xác nhận / đã xác nhận.</p>
            </div>
        </div>
    </form>
</div>
@endsection
