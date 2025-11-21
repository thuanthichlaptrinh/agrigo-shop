@extends('layouts.app')

@section('title', 'Giỏ hàng - Organic Shop')

@push('styles')
<style>
    /* Hide header dropdown on cart page */
    .category-dropdown {
        display: none !important;
    }
    
    .close-cart-item {
        position: absolute;
        top: 12px;
        left: -8px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #f44336;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
    }
    .close-cart-item:hover {
        filter: brightness(1.2);
    }
    .btn-order-container {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        max-width: 700px;
        width: 700px;
        padding: 15px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        background-color: white;
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
    }
    .btn-number {
        width: 40px;
        height: 40px;
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
        height: 40px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="mx-auto p-0" style="position: relative; min-height: 100vh; width: 700px; border: 1px solid rgb(228 233 242/1); background-color: #f2f5fa">
        <div class="bg-white">
            <div class="d-flex align-items-center" style="padding: 5px 10px; margin: 0 0 8px; cursor: pointer">
                <a href="{{ route('home') }}">
                    <i class="ri-arrow-left-s-line" style="font-size: 23px; padding: 0; margin: 0"></i>
                </a>
                <span class="mx-auto" style="font-size: 17px; font-weight: 700; color: #333">Giỏ hàng</span>
            </div>
        </div>

        <div class="bg-white" style="margin: 4px 8px; margin-top: 30px; border-radius: 4px; position: relative">
            <div class="bg-white" style="width: 130px; font-size: 16px; padding: 6px 8px; position: absolute; top: -20px; left: 12px; border-radius: 4px; color: #535867; font-weight: 700">
                HÀNG CÓ SẴN
            </div>

            @forelse($cartItems ?? [] as $item)
            <div class="w-100 d-flex align-items-center" style="padding: 12px 10px 10px; @if(!$loop->first) border-top: 1px solid #e6e6e6; @endif position: relative">
                <div class="close-cart-item">
                    <i class="ri-close-large-fill"></i>
                </div>
                <div class="d-flex align-items-center" style="width: 70%">
                    <div style="width: 20%">
                        <img src="{{ asset('template/Assets/Images/chuoi.jpg') }}" alt="" style="width: 100%" />
                    </div>
                    <div style="width: 80%; padding-left: 8px">
                        <p style="margin-bottom: 0; font-size: 16px; color: #333">{{ $item['name'] ?? 'Sản phẩm' }}</p>
                        <p style="font-size: 14px; color: #8f9bb3">{{ $item['unit'] ?? '455ml' }}</p>
                    </div>
                </div>
                <div style="width: 30%">
                    <div class="" style="float: right; margin-right: 14px">
                        <p style="font-size: 20px; font-weight: 700; text-align: right">
                            {{ number_format($item['price'] ?? 252000) }} <span style="font-weight: 500; font-size: 18px">đ</span>
                        </p>
                        <div class="d-flex align-items-center justify-content-center quantity-selector" style="background-color: #e5e9f2; border-radius: 8px; width: 136px; height: 40px">
                            <div class="text-center btn-number">-</div>
                            <input type="text" class="order-quantity" value="{{ $item['quantity'] ?? 1 }}" />
                            <div class="text-center btn-number">+</div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="w-100 text-center py-5">
                <p class="text-muted">Giỏ hàng của bạn đang trống</p>
                <a href="{{ route('products') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
            </div>
            @endforelse
        </div>

        <!-- Button đặt hàng fixed bottom -->
        <div class="btn-order-container bg-white">
            <a href="{{ route('checkout') }}" class="text-white d-flex align-items-center justify-content-center text-center bg-primary-t btn-order" style="text-decoration: none;">
                <div class="btn-quantity" style="background: white; color: #333; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: 700; margin-right: 10px;">
                    {{ count($cartItems ?? []) }}
                </div>
                <i class="ri-shopping-cart-2-fill" style="font-size: 22px; margin-right: 10px;"></i>
                <span style="font-size: 20px; font-weight: 700;">Đặt hàng {{ number_format($cartTotal ?? 353000) }}đ</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add quantity controls logic here
    document.querySelectorAll('.btn-number').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.order-quantity');
            let value = parseInt(input.value);
            
            if (this.textContent === '+') {
                value++;
            } else if (this.textContent === '-' && value > 1) {
                value--;
            }
            
            input.value = value;
        });
    });
</script>
@endpush
