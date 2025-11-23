@extends('user.layouts.app')

@section('title', 'Giỏ hàng - Organic Shop')

@push('styles')
<style>
    /* Hide header dropdown on cart page */
    .category-dropdown {
        display: none !important;
    }

    .cart-shell {
        position: relative;
        min-height: 100vh;
        width: 660px;
        padding: 20px 0 140px;
        /* border-radius: 28px; */
        border: 1px solid rgb(228 233 242/1);
        background: linear-gradient(135deg, #fdfcff7b 0%, #eef5ff65 100%);
        /* box-shadow: 0 35px 60px rgba(15, 23, 42, 0.18); */
        overflow: hidden;
    }

    .cart-header {
        /* padding: 18px 28px 10px; */
        background: transparent;
        background-color: white;
    }

    .cart-caption {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 600;
        font-size: 12px;
        position: absolute;
        top: 55px;
        left: 44px;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        z-index: 10;
    }

    .cart-meta-card {
        margin: 0 28px 20px;
        background: #ffffff;
        padding: 22px 20px;
        border-radius: 22px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        text-align: center;
    }

    .meta-section {
        padding: 12px 0;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(129, 140, 248, 0.08));
    }

    .meta-label {
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 12px;
        color: #94a3b8;
    }

    .meta-value {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .meta-value span {
        font-size: 16px;
        font-weight: 600;
        color: #6b7280;
        margin-left: 4px;
    }

    .cart-card-wrapper {
        padding: 30px 26px 0;
        background: #fff;
        margin-top: 10px;
        padding-bottom: 30px
    }

    .cart-item-card {
        background: #fff;
        border-radius: 18px;
        padding: 18px;
        position: relative;
        display: flex;
        gap: 18px;
        box-shadow: 0 15px 40px rgba(15, 23, 42, 0.1);
    }

    .cart-item-card + .cart-item-card {
        margin-top: 18px;
    }

    .close-cart-item {
        position: absolute;
        top: 12px;
        left: 12px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        /* background-color: #fee2e2; */
        color: #dc2626;
        /* border: 1px solid #fecaca; */
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.2s ease;
    }

    .close-cart-item:hover {
        background-color: #fecaca;
    }

    .item-thumb {
        width: 96px;
        height: 96px;
        border-radius: 18px;
        overflow: hidden;
        background: #f8fafc;
        flex-shrink: 0;
    }

    .item-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-content {
        flex: 1;
    }

    .item-details {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        margin-bottom: 6px;
        font-size: 17px;
        font-weight: 700;
        color: #111827;
    }

    .item-unit {
        color: #94a3b8;
        font-size: 14px;
    }

    .btn-order-container {
        position: fixed;
        bottom: 0;
        left: 843px; 
        transform: translateX(-50%);
        max-width: 660px;
        width: 660px;
        padding: 15px;
        /* box-shadow: 0 -2px 10px rgba(0,0,0,0.1); */
        /* background-color: white; */
        z-index: 1000;
        border-radius: 8px 8px 0 0;
    }
    
    @media (max-width: 768px) {
        .btn-order-container {
            width: calc(100% - 40px);
        }
    }
    .btn-order {
        border: none;
        padding: 15px 20px;
        border-radius: 8px;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-order:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .quantity-selector {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #eef2ff;
        border-radius: 12px;
        width: 150px;
        height: 46px;
        box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.2);
    }
    .btn-number {
        width: 42px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 20px;
        font-weight: 700;
        user-select: none;
    }
    .order-quantity {
        width: 56px;
        height: 46px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 700;
    }

    .item-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
        min-width: 150px;
    }

    .price-stack {
        text-align: right;
    }

    .price-stack .current {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }

    .price-stack .unit-price {
        font-size: 13px;
        color: #94a3b8;
    }

    .empty-cart {
        text-align: center;
        padding: 90px 30px 140px;
        position: relative;
    }

    .empty-cart img {
        width: 180px;
        margin-bottom: 12px;
    }

    .empty-cart h5 {
        font-weight: 700;
        color: #1f2937;
    }

    .empty-cart p {
        color: #6b7280;
        margin-bottom: 26px;
    }

    .empty-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(59, 130, 246, 0.12);
        color: #2563eb;
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 16px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="mx-auto p-0 cart-shell">
        <div class="cart-header d-flex align-items-center">
            <a href="{{ route('user.home') }}" class="text-dark" style="text-decoration: none">
                <i class="ri-arrow-left-s-line" style="font-size: 24px"></i>
            </a>
            <span class="mx-auto" style="font-size: 18px; font-weight: 700; color: #111827">Giỏ hàng</span>
        </div>


        <div class="cart-card-wrapper">

            @php
                $cartCollection = collect($cartItems ?? [])->values();
            @endphp

            @if($cartCollection->isNotEmpty())
                <div class="cart-caption">
                    <i class="ri-store-line"></i>
                    HÀNG CÓ SẴN
                </div>
            @endif

            @forelse($cartCollection as $item)
            <div class="cart-item-card" data-product-id="{{ $item['id'] ?? '' }}">
                <div class="close-cart-item" data-remove-url="{{ isset($item['id']) ? route('user.cart.remove', ['id' => $item['id']], false) : '' }}">
                    <i class="ri-close-line"></i>
                </div>
                <div class="item-thumb">
                    <img src="{{ product_image_url($item['image'] ?? null) }}" alt="{{ $item['name'] ?? 'Sản phẩm' }}" />
                </div>
                <div class="item-content">
                    <div class="item-details">
                        <div class="item-info">
                            <p class="item-name">{{ $item['name'] ?? 'Sản phẩm' }}</p>
                            <p class="item-unit">{{ $item['unit'] ?? 'Đơn vị tính' }}</p>
                        </div>
                        <div class="item-actions">
                            <div class="price-stack">
                                <div class="current">
                                    {{ number_format($item['line_total'] ?? (($item['price'] ?? 0) * ($item['quantity'] ?? 1)), 0, ',', '.') }}đ
                                </div>
                                <div class="unit-price">{{ number_format($item['price'] ?? 0, 0, ',', '.') }}đ / {{ $item['unit'] ?? 'sp' }}</div>
                            </div>
                            <div class="quantity-selector">
                                <div class="text-center btn-number" data-delta="-1" data-product-id="{{ $item['id'] ?? '' }}">-</div>
                                <input type="text" class="order-quantity" value="{{ $item['quantity'] ?? 1 }}" data-product-id="{{ $item['id'] ?? '' }}" readonly />
                                <div class="text-center btn-number" data-delta="1" data-product-id="{{ $item['id'] ?? '' }}">+</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-cart">
                {{-- <div class="empty-badge"><i class="ri-shopping-basket-2-line"></i> Empty cart</div> --}}
                <h5>Giỏ hàng của bạn đang trống</h5>
                <p>Khám phá thêm nhiều sản phẩm tươi ngon và ưu đãi hấp dẫn hôm nay.</p>
                <a href="{{ route('user.products.index') }}" class="btn btn-primary px-4">Tiếp tục mua sắm</a>
            </div>
            @endforelse
        </div>

        <!-- Button đặt hàng fixed bottom -->
        @php
            $canCheckout = ($cartCount ?? 0) > 0;
        @endphp
        @if($canCheckout)
            <div class="btn-order-container ">
                <a href="{{ route('user.checkout.payment') }}" class="text-white d-flex align-items-center justify-content-center text-center bg-primary-t btn-order" style="text-decoration: none;">
                    <span class="btn-quantity" data-cart-count style="background: yellow; color: black !important; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: 700; margin-right: 10px;">
                        {{ $cartCount ?? 0 }}
                    </span>
                    <i class="ri-shopping-cart-2-fill" style="font-size: 22px; margin-right: 10px;"></i>
                    <span data-cart-total style="font-size: 20px; font-weight: 700;">Đặt hàng {{ number_format($cartTotal ?? 0, 0, ',', '.') }}đ</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const updateUrl = "{{ route('user.cart.update', [], false) }}";
        const headers = {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
        };

        const queueToastAndReload = (message, type = 'info') => {
            if (message) {
                if (window.AppAlert && typeof window.AppAlert.queue === 'function') {
                    window.AppAlert.queue({ message, type });
                } else {
                    try {
                        const key = 'app.toast.queue';
                        const cached = sessionStorage.getItem(key);
                        const parsed = cached ? JSON.parse(cached) : [];
                        const list = Array.isArray(parsed) ? parsed : [parsed];
                        list.push({ message, type });
                        sessionStorage.setItem(key, JSON.stringify(list));
                    } catch (error) {
                        sessionStorage.setItem('app.toast.queue', JSON.stringify([{ message, type }]));
                    }
                }
            }
            window.location.reload();
        };

        const sendUpdate = (productId, quantity) => {
            if (!productId) return;
            quantity = Math.max(1, quantity || 1);
            const body = new URLSearchParams({ product_id: productId, quantity: quantity });
            fetch(updateUrl, { method: 'POST', headers, body, credentials: 'same-origin' })
                .then(async (response) => {
                    if (!response.ok) throw new Error('Request failed');
                    try {
                        return await response.json();
                    } catch (error) {
                        return {};
                    }
                })
                .then((data) => queueToastAndReload(data?.message || 'Đã cập nhật số lượng.', data?.type || 'success'))
                .catch(() => queueToastAndReload('Không thể cập nhật giỏ hàng.', 'error'));
        };

        const sendRemove = (url) => {
            if (!url) return;
            fetch(url, { method: 'DELETE', headers, credentials: 'same-origin' })
                .then(async (response) => {
                    if (!response.ok) throw new Error('Request failed');
                    try {
                        return await response.json();
                    } catch (error) {
                        return {};
                    }
                })
                .then((data) => queueToastAndReload(data?.message || 'Đã xóa sản phẩm khỏi giỏ hàng.', data?.type || 'success'))
                .catch(() => queueToastAndReload('Không thể xóa sản phẩm.', 'error'));
        };

        document.querySelectorAll('.btn-number').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const delta = parseInt(this.dataset.delta || '0', 10);
                const input = this.parentElement.querySelector('.order-quantity');
                const current = parseInt(input.value || '1', 10);
                sendUpdate(productId, current + delta);
            });
        });

        document.querySelectorAll('.close-cart-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.dataset.removeUrl;
                sendRemove(url);
            });
        });
    });
</script>
@endpush
