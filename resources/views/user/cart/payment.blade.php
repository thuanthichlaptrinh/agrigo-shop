@extends('user.layouts.app')

@section('title', 'Thông tin thanh toán - Organic Shop')

@push('styles')
<style>
    .payment-page-wrapper {
        margin-top: 20px;
    }

    .payment-template {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        padding: 0;
    }

    .left-panel {
        border-right: 1px solid #e0e0e0;
        padding: 28px 40px;
        min-height: 90vh;
    }

    .right-panel {
        padding: 28px 40px;
        background: #f7f9fc;
    }

    .breadcrumb-flow {
        font-weight: 600;
        color: #555;
        margin-bottom: 18px;
    }

    .breadcrumb-flow a {
        text-decoration: none;
        color: inherit;
    }

    .input-floating {
        height: 52px;
        border: 1px solid #e0e0e0;
        position: relative;
        padding: 8px 12px;
        border-radius: 6px;
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
        color: #777;
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
        height: 100px;
        resize: none;
    }

    .input-floating.is-invalid {
        border-color: #f44336;
    }

    .payment-method {
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-top: 16px;
        overflow: hidden;
    }

    .payment-method .option {
        padding: 14px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #ccc;
        gap: 14px;
    }

    .payment-method .option:last-child {
        border-bottom: none;
    }

    .payment-method img {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }

    .payment-method input {
        width: 18px;
        height: 18px;
    }

    .payment-method small {
        display: block;
        color: #6b7280;
        margin-top: 2px;
    }

    .brand-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
    }

    .brand-vnpay {
        background: #fee2e2;
        color: #e11d48;
    }

    .brand-momo {
        background: #fce7f3;
        color: #a21caf;
    }

    .back-cart {
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #333;
    }

    .submit-btn {
        font-size: 17px;
        padding: 12px 26px;
    }

    .summary-item {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 12px;
        margin-bottom: 12px;
    }

    .summary-item img {
        width: 64px;
        height: 64px;
        border-radius: 4px;
        border: 1px solid #e0e0e0;
        object-fit: cover;
    }

    .discount-pill {
        display: inline-flex;
        padding: 2px 8px;
        border-radius: 999px;
        background: #dcfce7;
        color: #15803d;
        font-size: 12px;
        font-weight: 600;
    }

    .price-strike {
        text-decoration: line-through;
        color: #9ca3af;
    }

    .voucher-box {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 16px;
    }

    .voucher-box input {
        flex: 1;
        padding: 10px 14px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
    }

    .voucher-box button {
        border: none;
        border-radius: 6px;
        padding: 10px 18px;
    }

    .voucher-feedback {
        margin-top: 8px;
        font-size: 13px;
    }

    .order-summary {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 12px;
    }

    @media (max-width: 992px) {
        .left-panel {
            border-right: none;
            border-bottom: 1px solid #e0e0e0;
        }
    }
</style>
@endpush

@section('content')
@php
    $items = collect($cartItems ?? []);
    $selectedMethod = old('payment_method', 'cod');
    $deliverySelection = old('delivery_window', $deliverySlots[0] ?? '');
    $shippingFee = $shippingFeeAmount ?? 20000;
    $voucherData = is_array($appliedVoucher ?? null) ? $appliedVoucher : null;
    $voucherDiscount = $voucherData['discount_amount'] ?? 0;
    $voucherCode = old('voucher_code', $voucherData['code'] ?? '');
    $payableTotal = max(0, ($cartTotal ?? 0) + $shippingFee - $voucherDiscount);
@endphp

<form action="{{ route('user.checkout.process') }}" method="POST" class="payment-template payment-page-wrapper">
    @csrf
    <div class="row g-0">
        <div class="col-lg-7 left-panel">
            <div class="breadcrumb-flow d-flex align-items-center">
                <a href="{{ route('user.cart.index') }}">Giỏ hàng</a>
                <i class="ri-arrow-right-s-line" style="font-size: 20px; margin: 0 4px"></i>
                <span>Cung cấp thông tin giao hàng</span>
            </div>

            <p class="mb-2" style="font-size: 22px">Thông tin giao hàng</p>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-floating @error('receiver') is-invalid @enderror">
                        <label>Họ và tên</label>
                        <input type="text" name="receiver" value="{{ old('receiver', $defaults['receiver'] ?? '') }}" placeholder="Nguyễn Văn A" />
                    </div>
                    @error('receiver')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="input-floating @error('phone') is-invalid @enderror">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $defaults['phone'] ?? '') }}" placeholder="0909 000 000" />
                    </div>
                    @error('phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="input-floating @error('address') is-invalid @enderror" style="height: auto; padding-bottom: 0">
                <label>Địa chỉ nhận hàng</label>
                <textarea name="address" rows="3" placeholder="Số nhà, đường, phường, quận, thành phố">{{ old('address', $defaults['address'] ?? '') }}</textarea>
            </div>
            @error('address')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

            <div class="input-floating @error('delivery_window') is-invalid @enderror" style="height: 62px">
                <label>Khung giờ giao hàng</label>
                <select name="delivery_window">
                    @foreach($deliverySlots ?? [] as $slot)
                        <option value="{{ $slot }}" {{ $deliverySelection === $slot ? 'selected' : '' }}>{{ $slot }}</option>
                    @endforeach
                </select>
            </div>
            @error('delivery_window')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

            <div class="mt-4">
                <p style="font-size: 22px">Chọn phương thức thanh toán</p>
                <div class="payment-method">
                    <label class="option">
                        <input type="radio" name="payment_method" value="cod" {{ $selectedMethod === 'cod' ? 'checked' : '' }} />
                        <img src="{{ asset('template/Assets/Images/cash-on-delivery1.png') }}" alt="COD" />
                        <div>
                            <div>Thanh toán khi giao hàng (COD)</div>
                            <small>Kiểm tra và nhận hàng rồi mới thanh toán</small>
                        </div>
                    </label>
                    <label class="option">
                        <input type="radio" name="payment_method" value="bank" {{ $selectedMethod === 'bank' ? 'checked' : '' }} />
                        <img src="{{ asset('template/Assets/Images/atm-card1.png') }}" alt="ATM" />
                        <div>
                            <div>Thẻ ATM/ Visa/ Master</div>
                            <small>Hỗ trợ hầu hết ngân hàng nội địa và quốc tế</small>
                        </div>
                    </label>
                    <label class="option">
                        <input type="radio" name="payment_method" value="vnpay" {{ $selectedMethod === 'vnpay' ? 'checked' : '' }} />
                        <span class="brand-icon brand-vnpay">VN</span>
                        <div>
                            <div>VNPay QR</div>
                            <small>Quét QR trên app ngân hàng, xác nhận tức thì</small>
                        </div>
                    </label>
                    <label class="option">
                        <input type="radio" name="payment_method" value="momo" {{ $selectedMethod === 'momo' ? 'checked' : '' }} />
                        <span class="brand-icon brand-momo">Mo</span>
                        <div>
                            <div>Ví MoMo</div>
                            <small>Nhận thông báo và hoàn tất chỉ trong vài giây</small>
                        </div>
                    </label>
                </div>
                @error('payment_method')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-floating" style="height: auto; padding-bottom: 0; margin-bottom: 0">
                <label>Ghi chú thêm (nếu có)</label>
                <textarea name="note" rows="3" placeholder="Hướng dẫn giao hàng, ghi chú thanh toán">{{ old('note') }}</textarea>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-4">
                <a href="{{ route('user.cart.index') }}" class="back-cart">
                    <i class="ri-arrow-left-s-line" style="font-size: 20px"></i>
                    <span>Giỏ hàng</span>
                </a>
                <button type="submit" name="intent" value="place_order" class="btn btn-primary submit-btn">
                    Hoàn tất đặt hàng
                </button>
            </div>
        </div>

        <div class="col-lg-5 right-panel">
            <div class="d-flex align-items-center justify-content-between" style="border-bottom: 1px solid #e0e0e0; padding-bottom: 12px">
                <h5 class="mb-0">Đơn hàng của bạn ({{ $cartCount ?? 0 }} sản phẩm)</h5>
            </div>

            @foreach($items as $item)
                <div class="summary-item d-flex align-items-center">
                    <img src="{{ product_image_url($item['image'] ?? null) }}" alt="{{ $item['name'] ?? 'Sản phẩm' }}" />
                    <div class="flex-fill px-3">
                        <p class="mb-1" style="font-weight: 600">{{ $item['name'] ?? 'Sản phẩm' }}</p>
                        <div class="d-flex flex-wrap gap-2 align-items-center small text-muted mb-1">
                            <span>Số lượng: {{ $item['quantity'] ?? 1 }}</span>
                            @if(!empty($item['has_discount']))
                                <span class="discount-pill">-{{ number_format($item['discount_percent'] ?? 0, 0) }}%</span>
                            @endif
                        </div>
                        @if(!empty($item['has_discount']))
                            <div class="price-strike small">
                                {{ number_format($item['original_price'] ?? 0, 0, ',', '.') }}đ
                            </div>
                        @endif
                    </div>
                    <div style="font-weight: 600; text-align: right">
                        {{ number_format($item['line_total'] ?? (($item['price'] ?? 0) * ($item['quantity'] ?? 1)), 0, ',', '.') }}đ
                    </div>
                </div>
            @endforeach

            <div class="voucher-box mt-3">
                <label class="small text-muted d-block mb-2">Mã giảm giá</label>
                <div class="d-flex gap-2 flex-wrap">
                    <input type="text" name="voucher_code" value="{{ $voucherCode }}" placeholder="Nhập mã khuyến mãi" autocomplete="off" />
                    <button type="submit" formaction="{{ route('user.checkout.voucher') }}" name="intent" value="apply_voucher" class="btn btn-outline-primary">Áp dụng</button>
                </div>
                @error('voucher_code')
                    <div class="voucher-feedback text-danger">{{ $message }}</div>
                @enderror
                @if(session('voucher_error'))
                    <div class="voucher-feedback text-danger">{{ session('voucher_error') }}</div>
                @endif
                @if(session('voucher_success'))
                    <div class="voucher-feedback text-success">{{ session('voucher_success') }}</div>
                @endif
                @if($voucherData)
                    <div class="voucher-feedback text-success">
                        Đã áp dụng {{ $voucherData['code'] ?? '' }} (-{{ number_format($voucherDiscount, 0, ',', '.') }}đ)
                    </div>
                @endif
            </div>

            <div class="mt-3 order-summary">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span>Tổng tiền hàng</span>
                    <span>{{ number_format($cartTotal ?? 0, 0, ',', '.') }}đ</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span>Phí giao hàng</span>
                    <span>{{ number_format($shippingFee, 0, ',', '.') }}đ</span>
                </div>
                @if($voucherDiscount > 0)
                    <div class="d-flex align-items-center justify-content-between text-success">
                        <span>Mã giảm giá</span>
                        <span>-{{ number_format($voucherDiscount, 0, ',', '.') }}đ</span>
                    </div>
                @endif
            </div>

            <div class="mt-3 d-flex align-items-center justify-content-between">
                <p class="mb-0" style="font-size: 18px; font-weight: 600">Tổng thanh toán</p>
                <p class="mb-0" style="font-size: 20px; font-weight: 700">
                    {{ number_format($payableTotal, 0, ',', '.') }}đ
                </p>
            </div>
        </div>
    </div>
</form>
@endsection
