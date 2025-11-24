@extends('user.layouts.app')

@section('title', $product['name'] ?? 'Chi tiết sản phẩm - Organic Shop')

@php
    $placeholderImage = 'template/Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp';
    $gallery = collect($product['gallery'] ?? [])
        ->flatten()
        ->filter()
        ->map(fn ($img) => function_exists('product_image_url') ? product_image_url($img) : asset($img))
        ->values();

    if ($gallery->isEmpty()) {
        $gallery = collect([
            function_exists('product_image_url') ? product_image_url($product['image'] ?? null) : asset($product['image'] ?? $placeholderImage),
            asset($placeholderImage)
        ]);
    }

    $gallery = $gallery->unique()->values();

    $highlights = collect($product['highlights'] ?? [
        ['icon' => 'ri-leaf-line', 'label' => 'Chuẩn hữu cơ', 'value' => 'Chứng nhận VietGAP'],
        ['icon' => 'ri-shield-check-line', 'label' => 'Bảo quản lạnh', 'value' => 'Giữ tươi 48h'],
        ['icon' => 'ri-truck-line', 'label' => 'Giao nhanh', 'value' => 'Trong 2 giờ']
    ]);

    $facts = [
        ['label' => 'Thương hiệu', 'value' => $product['brand'] ?? 'Organic Shop'],
        ['label' => 'Xuất xứ', 'value' => $product['origin'] ?? 'Việt Nam'],
        ['label' => 'Đóng gói', 'value' => $product['weight'] ?? '1kg / túi'],
        ['label' => 'Hạn sử dụng', 'value' => $product['expiry'] ?? '07 ngày'],
    ];

    $relatedProducts = collect($relatedProducts ?? $product['related'] ?? [])->filter(function ($item) {
        return filled(data_get($item, 'name'));
    });
@endphp

@push('styles')
<style>
    .product-hero {
        border-radius: 8px;
        width: 1320px !important;
        background:  #fff;
        box-shadow: 0 24px 55px rgba(41, 148, 85, 0.08);
        padding-top: 0 !important;
    }
    .breadcrumb-trail {
        align-items: center;
        gap: 10px;
        background: #fff;
        padding: 10px 18px;
        box-shadow: 0 12px 30px rgba(28, 130, 68, 0.08);
        margin-bottom: 22px;
        font-size: 13px;
        color: #5b6472;
        margin-left: -12px;
        margin-right: -12px;
        margin-bottom: 30px;
    }
    .breadcrumb-trail .breadcrumb-icon {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c3f3cb 0%, #6ccf8b 100%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #115c32;
        font-size: 1.1rem;
    }
    .breadcrumb-icon i {
        font-size: 14px
    }
    .breadcrumb-trail .breadcrumb-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: inherit;
    }
    .breadcrumb-trail .breadcrumb-item a {
        color: #0c8b46;
        font-weight: 600;
        text-decoration: none;
    }
    .breadcrumb-trail .breadcrumb-item span {
        color: #7b8698;
        font-weight: 600;
    }
    .breadcrumb-trail .breadcrumb-sep {
        color: #c5ced9;
        font-size: 13px;
    }
    .product-media {
        border-radius: 20px;
        box-shadow: inset 0 0  1px rgba(0, 0, 0, 0.04);
        background-color: rgba(255, 255, 255, 0.9);
    }
    .media-surface {
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        background: #f6f9fb;
    }
    .media-track {
        display: flex;
        transition: transform 0.6s cubic-bezier(.4,0,.2,1);
        will-change: transform;
    }
    .media-slide {
        min-width: 100%;
        padding: 32px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: white;
    }
    .media-slide img {
        width: 100%;
        max-height: 420px;
        object-fit: contain;
    }
    .media-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: none;
        background: rgba(28, 130, 68, 0.12);
        color: #1c8244;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        opacity: 0;
        pointer-events: none;
    }
    .media-nav:hover { background: #1c8244; color: #fff; }
    .media-nav--prev { left: 12px; }
    .media-nav--next { right: 12px; }
    .media-surface:hover .media-nav,
    .media-nav:focus-visible {
        opacity: 1;
        pointer-events: auto;
    }
    .media-thumbs {
        display: flex;
        gap: 12px;
        margin-top: 18px;
        overflow-x: auto;
        padding-bottom: 4px; 
        padding-left: 24px;
        padding-bottom: 24px;
    }
    .media-thumb {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid transparent;
        flex-shrink: 0;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .media-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .media-thumb.is-active { border-color: #1c8244; box-shadow: 0 8px 16px rgba(28,130,68,.18); }

    .product-summary {
        background: #fff;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.04);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .btn-wishlist {
        position: absolute;
        top: 24px;
        right: 24px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #eee;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        z-index: 10;
    }
    .btn-wishlist:hover {
        background: #fff0f3;
        color: #ff4757;
        border-color: #ff4757;
        transform: scale(1.1);
    }
    .btn-wishlist.active {
        background: #ff4757;
        color: #fff;
        border-color: #ff4757;
    }
    .product-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1.3;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }
    .product-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        font-size: 0.95rem;
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #666;
    }
    .meta-item i {
        color: #1c8244;
        font-size: 1.1rem;
    }
    .price-section {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 28px;
        display: flex;
        align-items: baseline;
        gap: 16px;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .current-price {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1c8244;
        line-height: 1;
    }
    .old-price {
        font-size: 1.1rem;
        color: #999;
        text-decoration: line-through;
        font-weight: 500;
    }
    .discount-badge {
        background: #ffebee;
        color: #d32f2f;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.9rem;
    }
    .highlights-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 32px;
    }
    .highlight-item {
        background: #fff;
        border: 1px solid #eef1f3;
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
    }
    .highlight-item:hover {
        border-color: #c3f3cb;
        background: #f9fdfa;
        transform: translateY(-2px);
    }
    .highlight-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #e8f5e9;
        color: #1c8244;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .highlight-text {
        display: flex;
        flex-direction: column;
    }
    .highlight-label {
        font-size: 0.75rem;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .highlight-value {
        font-weight: 700;
        color: #333;
        font-size: 0.9rem;
    }
    .quantity-section {
        margin-bottom: 32px;
        display: flex;
        align-items: center;
        gap: 24px;
    }
    .quantity-wrapper {
        display: flex;
        align-items: center;
        background: #fff;
        border: 2px solid #eef1f3;
        border-radius: 12px;
        padding: 4px;
        height: 48px;
    }
    .qty-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: transparent;
        border-radius: 8px;
        color: #333;
        font-size: 1.2rem;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        line-height: 1;
    }
    .qty-btn:hover {
        background: #f5f5f5;
    }
    .qty-input {
        width: 50px;
        border: none;
        text-align: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: #333;
        background: transparent;
        height: 36px;
        padding: 0;
        margin: 0;
        line-height: 36px;
    }
    #quantity {
        padding-left: 10px;
    }
    .qty-input:focus {
        outline: none;
    }
    .stock-status {
        font-size: 0.9rem;
        color: #1c8244;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .stock-status::before {
        content: '';
        display: block;
        width: 8px;
        height: 8px;
        background: #1c8244;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(28, 130, 68, 0.15);
    }
    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: auto;
    }
    .btn-action {
        padding: 16px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
    }
    .btn-add-cart {
        background: #e8f5e9;
        color: #1c8244;
    }
    .btn-add-cart:hover {
        background: #d0e8d2;
        transform: translateY(-2px);
    }
    .btn-buy-now {
        background: linear-gradient(135deg, #1c8244 0%, #156634 100%);
        color: #fff;
        box-shadow: 0 8px 20px rgba(28, 130, 68, 0.25);
    }
    .btn-buy-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(28, 130, 68, 0.35);
        color: #fff;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    .detail-card {
        background: #fff;
        border-radius: 16px;
        padding: 18px;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .detail-card span {
        display: block;
    }
    .detail-card .label { color: #6b7785; font-size: 0.9rem; }
    .detail-card .value { font-weight: 600; margin-top: 4px; }

    .related-section {
        margin-left: -24px;
        margin-right: -24px;
    }
    .related-product-card {
        border-radius: 20px;
        border: 1px solid rgba(28,130,68,0.12);
        overflow: hidden;
        background: #fff;
        transition: transform .2s ease, box-shadow .2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .related-product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 35px rgba(15, 86, 52, 0.12);
    }
    .related-product-card:hover .wishlist-btn {
        opacity: 1;
        visibility: visible;
    }
    .related-product-card img {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
    }
    .related-product-card .card-body {
        padding: 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .related-product-card .price {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1c8244;
    }
    .related-product-card .unit {
        color: #7b8698;
        font-size: 0.9rem;
    }
    .related-product-card .btn {
        border-radius: 12px;
        font-weight: 600;
        margin-top: auto;
    }
    .related-carousel {
        position: relative;
        padding: 0 48px;
    }
    .related-carousel-track {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        scroll-snap-type: x proximity;
        scroll-behavior: smooth;
        padding-bottom: 8px;
    }
    .related-carousel-track::-webkit-scrollbar { display: none; }
    .related-carousel-item {
        flex: 0 0 calc(20% - 13px);
        min-width: 210px;
        scroll-snap-align: start;
    }
    .related-carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: rgba(28, 130, 68, 0.12);
        color: #1c8244;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        opacity: 0;
        pointer-events: none;
    }
    .related-carousel-nav:hover { background: #1c8244; color: #fff; }
    .related-carousel-nav--prev { left: 0; }
    .related-carousel-nav--next { right: 0; }
    .related-carousel:hover .related-carousel-nav,
    .related-carousel-nav:focus-visible {
        opacity: 1;
        pointer-events: auto;
    }

    @media (max-width: 991px) {
        .product-hero { margin: 0 -12px; }
        .product-summary, .product-media { padding: 20px; }
        .related-carousel { padding: 0 36px; }
        .related-carousel-item { flex: 0 0 calc(50% - 12px); }
    }

    @media (max-width: 575px) {
        .related-carousel { padding: 0 12px; }
        .related-carousel-item { flex: 0 0 80%; }
        .related-carousel-nav { width: 32px; height: 32px; }
    }
    .btn-gradient-add {
        border: none;
        background: linear-gradient(135deg, #8eca51 0%, #2c8e5f 60%, #1f7a4c 100%);
        color: #fff !important;
        box-shadow: 0 14px 28px rgba(13, 91, 52, 0.25);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-gradient-add:hover {
        transform: translateY(-2px);
        color: #fff;
        box-shadow: 0 20px 35px rgba(13, 91, 52, 0.35);
    }
    .review-score {
        font-size: clamp(2.25rem, 4vw, 3rem);
        font-weight: 700;
        color: #1c8244;
    }
    .review-stars i {
        font-size: 1.4rem;
        color: #ffc107;
    }
    .review-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #e2f5e9;
        color: #0c8b46;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .star-rating-input {
        display: inline-flex;
        gap: 8px;
    }
    .star-rating-input button {
        border: none;
        background: transparent;
        font-size: 1.8rem;
        color: #d5dce2;
        cursor: pointer;
        padding: 0;
        transition: color 0.15s ease;
    }
    .star-rating-input button.is-active {
        color: #ffc107;
    }
    .review-form textarea {
        resize: vertical;
        min-height: 110px;
    }
    .alert-inline {
        border-radius: 10px;
        font-size: 0.9rem;
    }
    .review-photos {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .review-photo {
        width: 88px;
        height: 88px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.08);
        display: block;
    }
    .review-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .review-filter .btn {
        border-radius: 999px;
        border: 1px solid rgba(12, 139, 70, 0.2);
        color: #0c8b46;
        background: transparent;
        font-weight: 600;
        padding: 6px 14px;
    }
    .review-filter .btn.active,
    .review-filter .btn:hover {
        background: #0c8b46;
        color: #fff;
        border-color: #0c8b46;
    }

    .wishlist-btn {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        background: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
        opacity: 0;
        visibility: hidden;
    }

    .wishlist-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .wishlist-btn i {
        font-size: 20px;
        color: #94a3b8;
        transition: color 0.2s ease;
    }

    .wishlist-btn.active i {
        color: #ef4444;
    }

    .wishlist-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
@if(!empty($product['breadcrumbs']))
    <nav class="breadcrumb-trail">
        <span class="breadcrumb-icon"><i class="ri-home-5-line"></i></span>
        @foreach($product['breadcrumbs'] as $index => $crumb)
            <span class="breadcrumb-item">
                @if(!empty($crumb['url']))
                    <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                @else
                    <span>{{ $crumb['label'] }}</span>
                @endif
            </span>
            @if(!$loop->last)
                <i class="ri-arrow-right-s-line breadcrumb-sep"></i>
            @endif
        @endforeach
    </nav>
@endif
<div class="row g-4 product-hero pt-0">
    <div class="col-lg-6 mt-0">
        <div class="product-media h-100">
            <div class="media-surface">
                <div class="media-track" id="productMediaTrack">
                    @foreach($gallery as $image)
                        <div class="media-slide" data-slide-index="{{ $loop->index }}">
                            <img src="{{ $image }}" alt="{{ $product['name'] ?? 'Hình ảnh sản phẩm' }}" loading="lazy">
                        </div>
                    @endforeach
                </div>
                <button type="button" class="media-nav media-nav--prev" aria-label="Ảnh trước" data-media-nav="-1">
                    <i class="ri-arrow-left-s-line"></i>
                </button>
                <button type="button" class="media-nav media-nav--next" aria-label="Ảnh tiếp" data-media-nav="1">
                    <i class="ri-arrow-right-s-line"></i>
                </button>
            </div>
            <div class="media-thumbs" id="productMediaThumbs">
                @foreach($gallery as $image)
                    <div class="media-thumb" data-thumb-index="{{ $loop->index }}">
                        <img src="{{ $image }}" alt="thumb-{{ $loop->index }}" loading="lazy">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="product-summary">
            <button class="btn-wishlist" title="Thêm vào yêu thích">
                <i class="ri-heart-line"></i>
            </button>
            <div class="product-meta">
                <div class="meta-item">
                    <i class="ri-verified-badge-fill"></i>
                    <span>{{ $product['cert'] ?? 'Chứng nhận hữu cơ' }}</span>
                </div>
                <div class="meta-item">
                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                    <span>{{ number_format($reviewStats['average'] ?? 0, 1) }} ({{ $reviewStats['total'] ?? 0 }} đánh giá)</span>
                </div>
                <div class="meta-item">
                    <i class="ri-fire-fill" style="color: #ff5722;"></i>
                    <span>{{ $product['sold_label'] ?? 'Đã bán 0' }}</span>
                </div>
            </div>

            <h1 class="product-title">{{ $product['name'] ?? 'Táo Gala Pháp size 100' }}</h1>
            <p class="text-muted mb-4" style="line-height: 1.6;">{{ $product['description'] ?? 'Táo Gala Pháp có vỏ đỏ sọc vàng bắt mắt, thịt giòn, ngọt thanh và rất thơm. Sản phẩm được nhập khẩu trực tiếp, đảm bảo tươi ngon.' }}</p>

            <div class="price-section">
                <span class="current-price">{{ number_format($product['price'] ?? 89000) }}đ</span>
                @if(isset($product['old_price']) && $product['old_price'] > ($product['price'] ?? 0))
                    <span class="old-price">{{ number_format($product['old_price']) }}đ</span>
                    <span class="discount-badge">-{{ round((($product['old_price'] - ($product['price'] ?? 0)) / $product['old_price']) * 100) }}%</span>
                @endif
                <span class="text-muted ms-auto">Đơn vị: <strong>{{ $product['unit'] ?? '1kg' }}</strong></span>
            </div>

            <div class="highlights-grid">
                @foreach($highlights->take(3) as $highlight)
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="{{ $highlight['icon'] ?? 'ri-leaf-line' }}"></i>
                        </div>
                        <div class="highlight-text">
                            <span class="highlight-label">{{ $highlight['label'] ?? '' }}</span>
                            <span class="highlight-value">{{ $highlight['value'] ?? '' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="quantity-section">
                <div class="quantity-wrapper">
                    <button type="button" class="qty-btn" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" value="1" min="1" class="qty-input">
                    <button type="button" class="qty-btn" onclick="increaseQuantity()">+</button>
                </div>
                <div class="stock-status">
                    {{ $product['stock'] ?? 100 }} sản phẩm
                </div>
            </div>

            <div class="action-buttons">
                <button type="button"
                    class="btn-action btn-add-cart"
                    data-add-to-cart="true"
                    data-product-id="{{ $product['id'] ?? '' }}"
                    data-quantity-field="#quantity">
                    <i class="ri-shopping-bag-3-line"></i>
                    Thêm vào giỏ
                </button>
                <button type="button"
                    class="btn-action btn-buy-now"
                    data-add-to-cart="true"
                    data-product-id="{{ $product['id'] ?? '' }}"
                    data-quantity-field="#quantity"
                    data-redirect-url="{{ route('user.cart.index') }}">
                    <i class="ri-flashlight-fill"></i>
                    Mua ngay
                </button>
            </div>
        </div>
    </div>
</div>

<div class="bg-white p-4 mt-4" style="border-radius: 8px; width: 1320px; margin-left: -12px;">
    <h4 class="fw-700 mb-3">Thông tin sản phẩm</h4>
    <div class="detail-grid mb-4">
        @foreach($facts as $fact)
            <div class="detail-card">
                <span class="label">{{ $fact['label'] }}</span>
                <span class="value">{{ $fact['value'] }}</span>
            </div>
        @endforeach
    </div>
    <div class="mt-3">
        {!! $product['full_description'] ?? '<p>Sản phẩm chất lượng cao, được chọn lọc kỹ càng từ các nhà cung cấp uy tín, đảm bảo độ tươi ngon và giàu dinh dưỡng cho mỗi bữa ăn của gia đình bạn.</p>' !!}
    </div>
</div>

@if($relatedProducts->isNotEmpty())
<div class="related-section mt-4">
    <div class="bg-white p-4" style="border-radius: 8px; width: 1320px; margin-left: 12px; ">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h4 class="fw-700 mb-1">Sản phẩm liên quan</h4>
                <p class="text-muted mb-0">Gợi ý dành riêng cho bạn dựa trên sản phẩm đang xem</p>
            </div>
            <a href="{{ $product['category_url'] ?? route('user.products.index') }}" class="text-success fw-600">Xem tất cả <i class="ri-arrow-right-up-line"></i></a>
        </div>
        <div class="related-carousel" id="relatedCarousel">
            <button type="button" class="related-carousel-nav related-carousel-nav--prev" aria-label="Sản phẩm trước" data-related-nav="-1">
                <i class="ri-arrow-left-s-line"></i>
            </button>
            <div class="related-carousel-track" id="relatedCarouselTrack">
                @foreach($relatedProducts as $item)
                    <div class="related-carousel-item">
                        <div class="related-product-card">
                            {{-- Nút yêu thích --}}
                            <button class="wishlist-btn {{ !empty($item['is_wishlisted']) ? 'active' : '' }}" 
                                    data-product-id="{{ $item['id'] ?? '' }}"
                                    data-wishlisted="{{ !empty($item['is_wishlisted']) ? 'true' : 'false' }}"
                                    title="{{ !empty($item['is_wishlisted']) ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                                <i class="{{ !empty($item['is_wishlisted']) ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                            </button>
                            
                            <a href="{{ route('user.products.detail', $item['id'] ?? 0) }}">
                                <img src="{{ function_exists('product_image_url') ? product_image_url($item['image'] ?? null) : asset($item['image'] ?? $placeholderImage) }}" alt="{{ $item['name'] ?? '' }}" loading="lazy">
                            </a>
                            <div class="card-body">
                                <p class="text-muted mb-1">{{ $item['category'] ?? 'Rau củ quả' }}</p>
                                <h6 class="fw-700 mb-2">{{ $item['name'] ?? '' }}</h6>
                                <div class="d-flex align-items-baseline gap-2 mb-3">
                                    <span class="price">{{ number_format($item['price'] ?? 0) }}đ</span>
                                    <span class="unit">/{{ $item['unit'] ?? '1kg' }}</span>
                                </div>
                                <button type="button" class="btn btn-gradient-add" data-add-to-cart="true" data-product-id="{{ $item['id'] ?? '' }}" data-quantity-field="#quantity">
                                    <i class="ri-shopping-cart-line me-1"></i>Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="related-carousel-nav related-carousel-nav--next" aria-label="Sản phẩm tiếp" data-related-nav="1">
                <i class="ri-arrow-right-s-line"></i>
            </button>
        </div>
    </div>
</div>
@endif

@php
    $averageRating = $reviewStats['average'] ?? 0;
    $totalReviews = $reviewStats['total'] ?? 0;
    $breakdown = $reviewStats['breakdown'] ?? [];
    $selectedRating = $selectedRating ?? null;
@endphp

<div class="row mt-3" id="reviews">
    <div class="col-12 bg-white p-4" style="border-radius: 8px">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <h4 class="fw-700 mb-1" style="color: var(--text-primary)">Đánh giá {{ $product['name'] ?? 'sản phẩm' }}</h4>
                <p class="text-muted mb-0">{{ $totalReviews ? $totalReviews . ' lượt đánh giá đã đăng' : 'Chưa có đánh giá nào' }}</p>
            </div>
            @if(session('success'))
                <div class="alert alert-success mb-0 alert-inline">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger mb-0 alert-inline">{{ session('error') }}</div>
            @endif
        </div>

        <div class="row gy-4">
            <div class="col-md-5 border-end">
                <div class="text-center mb-4">
                    <div class="review-score">{{ number_format((float) $averageRating, 1) }}<span class="fs-4 text-muted">/5</span></div>
                    <div class="review-stars mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="ri-star-{{ $i <= round($averageRating) ? 'fill' : 'line' }}"></i>
                        @endfor
                    </div>
                    <p class="text-muted mb-0">{{ $totalReviews }} lượt đánh giá hợp lệ</p>
                </div>
                <div class="rating-bars">
                    @for ($star = 5; $star >= 1; $star--)
                        @php
                            $bar = $breakdown[$star] ?? ['count' => 0, 'percent' => 0];
                        @endphp
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-2 fw-600" style="width: 22px;">{{ $star }}</span>
                            <i class="ri-star-fill me-2" style="color: #ffc107;"></i>
                            <div class="progress flex-fill me-2" style="height: 8px;">
                                <div class="progress-bar bg-rating" role="progressbar" style="width: {{ $bar['percent'] ?? 0 }}%; background-color: #00713b !important;" aria-valuenow="{{ $bar['percent'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="text-muted" style="width: 110px;">{{ $bar['count'] ?? 0 }} đánh giá</span>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="col-md-7 ps-md-4">
                <h5 class="fw-600 mb-3" style="color: var(--text-primary)">Chia sẻ trải nghiệm của bạn</h5>
                @auth
                    <form action="{{ route('user.products.reviews.store', $product['id']) }}" method="POST" class="review-form" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="fw-600 mb-2">Chọn số sao</label>
                            <div class="star-rating-input" data-rating-input>
                                <input type="hidden" name="rating" value="{{ old('rating', 5) }}">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" data-value="{{ $i }}" aria-label="{{ $i }} sao">
                                        <i class="ri-star-fill"></i>
                                    </button>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="reviewComment" class="fw-600 mb-2">Nội dung đánh giá</label>
                            <textarea id="reviewComment" name="comment" class="form-control @error('comment') is-invalid @enderror" placeholder="Chia sẻ cảm nhận về chất lượng, hương vị, cách đóng gói...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="fw-600 mb-2" for="reviewPhotos">Hình ảnh minh họa <span class="text-muted fw-normal" style="font-size: 0.9rem;">(tối đa 4 ảnh, 3MB/ảnh)</span></label>
                            <input type="file" name="photos[]" id="reviewPhotos" class="form-control" accept="image/jpeg,image/png,image/webp" multiple>
                            <div class="form-text">Ưu tiên ảnh thực tế để người khác dễ tham khảo.</div>
                            @if($errors->has('photos') || $errors->has('photos.*'))
                                <div class="text-danger small mt-1">{{ $errors->first('photos') ?? $errors->first('photos.*') }}</div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-gradient-add px-4">
                            <i class="ri-send-plane-line me-1"></i>Gửi đánh giá
                        </button>
                    </form>
                @else
                    <div class="p-4 bg-light rounded-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                        <div>
                            <p class="fw-600 mb-1">Vui lòng đăng nhập để đánh giá sản phẩm</p>
                            <p class="text-muted mb-0">Chúng tôi sẽ hiển thị ngay sau khi bạn gửi.</p>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-gradient-add px-4">Đăng nhập</a>
                    </div>
                @endauth
            </div>
        </div>

        <div class="mt-4">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div class="review-filter d-flex flex-wrap gap-2">
                    <a href="{{ route('user.products.detail', ['id' => $product['id']]) }}#reviews" class="btn {{ $selectedRating === null ? 'active' : '' }}">
                        Tất cả ({{ $totalReviews }})
                    </a>
                    @for ($star = 5; $star >= 1; $star--)
                        <a href="{{ route('user.products.detail', ['id' => $product['id'], 'rating' => $star]) }}#reviews" class="btn {{ $selectedRating === $star ? 'active' : '' }}">
                            {{ $star }} sao ({{ $breakdown[$star]['count'] ?? 0 }})
                        </a>
                    @endfor
                </div>
                @if($selectedRating)
                    <div class="text-muted small">
                        Đang lọc theo <strong>{{ $selectedRating }} sao</strong> ·
                        <a href="{{ route('user.products.detail', ['id' => $product['id']]) }}#reviews" class="text-success text-decoration-none">Xóa lọc</a>
                    </div>
                @endif
            </div>
        </div>

        @php
            $reviewsCollection = $reviews instanceof \Illuminate\Support\Collection ? $reviews : collect($reviews ?? []);
            $reviewCount = $reviewsCollection->count();
        @endphp

        <div class="mt-4" data-review-list>
            @forelse($reviews as $review)
                @php $isHiddenReview = $loop->index >= 2; @endphp
                <div class="review-item border-bottom pb-3 mb-3 {{ $isHiddenReview ? 'd-none js-review-hidden' : '' }}" data-review-item data-hidden="{{ $isHiddenReview ? 'true' : 'false' }}">
                    <div class="d-flex align-items-start gap-3">
                        <div class="review-avatar">{{ $review['user_initial'] ?? 'U' }}</div>
                        <div class="flex-fill">
                            <h6 class="fw-600 mb-1">{{ $review['user_name'] ?? 'Người dùng' }}</h6>
                            <div class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="ri-star-{{ $i <= ($review['rating'] ?? 0) ? 'fill' : 'line' }}" style="color: #ffc107;"></i>
                                @endfor
                            </div>
                            @if(!empty($review['comment']))
                                <p class="mb-2">{{ $review['comment'] }}</p>
                            @endif
                            @if(!empty($review['images']))
                                <div class="review-photos mb-2">
                                    @foreach($review['images'] as $photo)
                                        <a href="{{ $photo }}" target="_blank" class="review-photo" rel="noopener">
                                            <img src="{{ $photo }}" alt="Ảnh đánh giá">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            <p class="text-muted small mb-0">
                                <i class="ri-time-line"></i> {{ $review['created_at'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <i class="ri-chat-1-line d-block mb-2" style="font-size: 2rem;"></i>
                    <p class="mb-0">Hãy là người đầu tiên chia sẻ cảm nhận về sản phẩm này!</p>
                </div>
            @endforelse
            @if($reviewCount > 2)
                <div class="text-center mt-3">
                    <button class="btn btn-outline-success px-4" data-review-more>
                        Hiển thị thêm đánh giá
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

    <!-- Footer --> 
    <div class="mt-3" style="margin-left: 10px"> 
        @include('user.partials.footer')
    </div>
@endsection 

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('productMediaTrack');
    const slides = track ? track.querySelectorAll('.media-slide') : [];
    const thumbs = Array.from(document.querySelectorAll('.media-thumb'));
    let currentIndex = 0;

    const goToSlide = (index) => {
        if (!track || slides.length === 0) return;
        const total = slides.length;
        currentIndex = (index + total) % total;
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
        thumbs.forEach((thumb, i) => thumb.classList.toggle('is-active', i === currentIndex));
    };

    document.querySelectorAll('[data-media-nav]').forEach((button) => {
        button.addEventListener('click', () => {
            const delta = parseInt(button.dataset.mediaNav, 10) || 0;
            goToSlide(currentIndex + delta);
        });
    });

    thumbs.forEach((thumb) => {
        thumb.addEventListener('click', () => {
            const targetIndex = parseInt(thumb.dataset.thumbIndex, 10) || 0;
            goToSlide(targetIndex);
        });
    });

    goToSlide(0);

    document.querySelectorAll('.related-carousel').forEach((carousel) => {
        const relatedTrack = carousel.querySelector('.related-carousel-track');
        if (!relatedTrack) return;

        carousel.querySelectorAll('[data-related-nav]').forEach((button) => {
            button.addEventListener('click', () => {
                const direction = parseInt(button.dataset.relatedNav, 10) || 0;
                const item = relatedTrack.querySelector('.related-carousel-item');
                const itemWidth = item ? item.getBoundingClientRect().width + 16 : carousel.getBoundingClientRect().width * 0.9;
                relatedTrack.scrollBy({ left: itemWidth * direction, behavior: 'smooth' });
            });
        });
    });

    document.querySelectorAll('[data-rating-input]').forEach((wrapper) => {
        const hiddenInput = wrapper.querySelector('input[name="rating"]');
        const buttons = wrapper.querySelectorAll('button[data-value]');

        const syncStars = (value) => {
            buttons.forEach((button) => {
                const starValue = parseInt(button.dataset.value, 10) || 0;
                const isActive = starValue <= value;
                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        };

        const initial = hiddenInput ? parseInt(hiddenInput.value || '0', 10) : 0;
        syncStars(initial || 0);

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                const value = parseInt(button.dataset.value, 10) || 0;
                if (hiddenInput) {
                    hiddenInput.value = value;
                }
                syncStars(value);
            });
        });
    });

    const showMoreButton = document.querySelector('[data-review-more]');
    if (showMoreButton) {
        showMoreButton.addEventListener('click', () => {
            document.querySelectorAll('.js-review-hidden').forEach((item) => item.classList.remove('d-none', 'js-review-hidden'));
            showMoreButton.classList.add('d-none');
        });
    }
});

function increaseQuantity() {
    const input = document.getElementById('quantity');
    input.value = parseInt(input.value || '1', 10) + 1;
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value || '1', 10);
    if (current > 1) {
        input.value = current - 1;
    }
}

// Xử lý nút yêu thích trong trang detail
document.addEventListener('DOMContentLoaded', function() {
    const wishlistBtn = document.querySelector('.wishlist-detail-btn');
    
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const isWishlisted = this.dataset.wishlisted === 'true';
            const icon = this.querySelector('i');
            const text = this.querySelector('span');
            
            if (!productId) {
                return;
            }

            // Kiểm tra đăng nhập
            @guest
                window.location.href = '{{ route("login") }}';
                return;
            @endguest

            // Disable button
            this.disabled = true;
            
            const url = isWishlisted 
                ? '{{ url("user/wishlist/remove") }}/' + productId
                : '{{ url("user/wishlist/add") }}/' + productId;
            
            const method = isWishlisted ? 'DELETE' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Toggle trạng thái
                if (isWishlisted) {
                    // Bỏ yêu thích
                    icon.className = 'ri-heart-line me-2';
                    text.textContent = 'Yêu thích';
                    this.classList.remove('active');
                    this.dataset.wishlisted = 'false';
                } else {
                    // Thêm yêu thích
                    icon.className = 'ri-heart-fill me-2';
                    text.textContent = 'Đã yêu thích';
                    this.classList.add('active');
                    this.dataset.wishlisted = 'true';
                }
                
                this.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại');
                this.disabled = false;
            });
        });
    }

    // Xử lý nút yêu thích trong related products
    const relatedWishlistButtons = document.querySelectorAll('.related-product-card .wishlist-btn');
    relatedWishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.dataset.productId;
            const isWishlisted = this.dataset.wishlisted === 'true';
            const icon = this.querySelector('i');
            
            if (!productId) {
                return;
            }

            // Kiểm tra đăng nhập
            @guest
                window.location.href = '{{ route("login") }}';
                return;
            @endguest

            // Disable button
            this.disabled = true;
            
            const url = isWishlisted 
                ? '{{ url("user/wishlist/remove") }}/' + productId
                : '{{ url("user/wishlist/add") }}/' + productId;
            
            const method = isWishlisted ? 'DELETE' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Toggle trạng thái
                if (isWishlisted) {
                    icon.className = 'ri-heart-line';
                    this.classList.remove('active');
                    this.dataset.wishlisted = 'false';
                    this.title = 'Thêm vào yêu thích';
                } else {
                    icon.className = 'ri-heart-fill';
                    this.classList.add('active');
                    this.dataset.wishlisted = 'true';
                    this.title = 'Bỏ yêu thích';
                    
                    // Animation
                    this.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);
                }
                
                this.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại');
                this.disabled = false;
            });
        });
    });

    // Main product wishlist button handler
    const mainWishlistBtn = document.querySelector('.product-summary .btn-wishlist');
    if (mainWishlistBtn) {
        mainWishlistBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            this.classList.toggle('active');
            
            if (this.classList.contains('active')) {
                icon.classList.remove('ri-heart-line');
                icon.classList.add('ri-heart-fill');
                
                // Animation effect
                this.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            } else {
                icon.classList.remove('ri-heart-fill');
                icon.classList.add('ri-heart-line');
            }
        });
    }
});
</script>
@endpush
