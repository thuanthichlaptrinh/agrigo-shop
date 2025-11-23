@extends('user.layouts.app')

@section('title', 'Danh sách sản phẩm - Organic Shop')

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<style>
    .category-dropdown {
        display: none !important;
    }

    .category-menu-trigger {
        cursor: default !important;
    }

    .btn-Them:hover,
    .btn-ThanhToan:hover {
        filter: brightness(1.5);
    }

    .product-name-single-line {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .home-product-card {
        border: 1px solid rgba(44, 159, 69, 0.15);
        border-radius: 14px;
        padding: 14px;
        background: linear-gradient(180deg, #ffffff 0%, #f5fff8 100%);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        height: 100%;
    }

    .home-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px rgba(15, 23, 42, 0.15);
    }

    .home-product-card img {
        border-radius: 10px;
        background: #f3f8f2;
        transition: transform 0.25s ease;
    }

    .home-product-card:hover img {
        transform: scale(1.02);
    }

    .home-product-card .card-body {
        padding: 14px 4px 0;
    }

    .home-product-card .product-price span:first-child {
        font-size: 18px;
        color: #00a86b;
    }

    .home-product-card .btn-ThemVaoGio {
        border-radius: 999px;
        box-shadow: 0 8px 18px rgba(0, 168, 107, 0.25);
    }

    .home-product-card .box-flash {
        right: 16px;
        top: 16px;
    }

    .original-price-text {
        text-decoration: line-through;
        color: #94a3b8;
        font-size: 13px;
        margin-left: 6px;
    }

    .promo-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        color: #b35c00;
        background: rgba(255, 184, 77, 0.25);
        margin-left: 4px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Sidebar Menu -->
    @include('user.partials.sidebar')

    <!-- Main Content -->
    <div class="col-9 content-body">
        <!-- Breadcrumb -->
        <div class="row" style="margin-left: -23px">
            <div class="p-2 bg-white my-2" style="border-radius: 4px">
                <a href="{{ route('user.home') }}" style="text-decoration: none; color: #000">
                    <i class="ri-arrow-left-s-line" style="font-size: 18px; border-right: 1px solid #dedede; padding-right: 6px; margin-right: 6px"></i>
                </a>
                <span class="fw-500">{{ $categoryName ?? 'Trái cây' }}</span>
            </div>
        </div>

        <!-- Filter Form -->
        <form class="bg-white" style="margin-left: -23px; margin-right: -12px; border-radius: 4px; padding: 10px; padding-bottom: 12px" method="GET" action="{{ route('user.products.index') }}">
            <div>
                <h6>Chọn theo tiêu chí</h6>
                <div class="d-flex align-items-center" style="gap: 8px">
                    <div>
                        <select name="sort_price" class="form-select" style="height: 34px; font-size: 15px">
                            <option value="">Giá bán</option>
                            <option value="asc" {{ request('sort_price') === 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="desc" {{ request('sort_price') === 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        </select>
                    </div>
                    <div>
                        <select name="price_range" class="form-select" style="height: 34px; font-size: 15px">
                            <option value="">Mức giá</option>
                            <option value="0-100000" {{ request('price_range') === '0-100000' ? 'selected' : '' }}>Dưới 100.000đ</option>
                            <option value="100000-300000" {{ request('price_range') === '100000-300000' ? 'selected' : '' }}>Từ 100.000đ - 300.000đ</option>
                            <option value="300000-" {{ request('price_range') === '300000-' ? 'selected' : '' }}>Trên 300.000đ</option>
                        </select>
                    </div>
                    <div>
                        <select name="promotion" class="form-select" style="height: 34px; font-size: 15px">
                            <option value="">Khuyến mãi</option>
                            <option value="yes" {{ request('promotion') === 'yes' ? 'selected' : '' }}>Còn khuyến mãi</option>
                        </select>
                    </div>
                    <div>
                        <select name="supplier" class="form-select" style="height: 34px; font-size: 15px">
                            <option value="">Nhà cung cấp</option>
                            <option value="us" {{ request('supplier') === 'us' ? 'selected' : '' }}>Mỹ</option>
                            <option value="vn" {{ request('supplier') === 'vn' ? 'selected' : '' }}>Việt Nam</option>
                        </select>
                    </div>
                    @if(!empty($selectedCategoryId))
                        <input type="hidden" name="category" value="{{ $selectedCategoryId }}" />
                    @endif
                    @if(!empty($selectedSubCategoryId))
                        <input type="hidden" name="subcat" value="{{ $selectedSubCategoryId }}" />
                    @endif
                    <button type="submit" class="btn btn-primary" style="height: 34px; font-size: 15px">Lọc</button>
                </div>
            </div>
        </form>

        <!-- Banner -->
        <div class="mt-2" style="background-color: #fff9f9; margin-left: -23px; margin-right: -11px; margin-bottom: 12px !important; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px">
            <img src="{{ asset('template/Assets/Images/Screenshot 2024-11-17 233057.png') }}" class="mx-auto d-block" style="width: 400px" alt="" />
        </div>

        <!-- Products Grid -->
        <div class="row mt-4 bg-white mb-13-t pb-12-t row-km" style="margin-left: -23px">
            <div class="title-banner-wrapper">
                <div class="triangle-left"></div>
                <a class="title-banner">
                    <span>{{ \Illuminate\Support\Str::upper($categoryName ?? 'SẢN PHẨM') }}</span>
                </a>
                <div class="triangle-right"></div>
            </div>

            @forelse($products ?? [] as $product)
            <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                <div class="card home-product-card">
                    @if(!empty($product['has_discount']))
                        <div class="d-flex align-items-center justify-content-center box-flash">
                            <i class="ri-flashlight-line"></i>
                            <span style="font-weight: 700">-{{ (int) round($product['discount_percent'] ?? 0) }}%</span>
                        </div>
                    @endif
                    <a href="{{ route('user.products.detail', $product['id'] ?? 1) }}">
                        <img src="{{ product_image_url($product['image'] ?? null) }}" class="w-100" alt="{{ $product['name'] ?? 'Sản phẩm' }}" />
                    </a>
                    <div class="card-body">
                        <p class="card-title fw-400 txt-gray product-name-single-line">{{ $product['name'] ?? 'Ức gà có xương' }}</p>
                        <p class="card-title product-price mb-1">
                            <span class="fw-700">{{ number_format($product['final_price'] ?? ($product['price'] ?? 28350), 0, ',', '.') }}đ</span>
                            <span class="txt-gray fs-13-t">/{{ $product['unit'] ?? '300g' }}</span>
                            @if(!empty($product['has_discount']))
                                <span class="original-price-text">{{ number_format($product['original_price'] ?? 0, 0, ',', '.') }}đ</span>
                            @endif
                        </p>
                        {{-- @if(!empty($product['has_discount']))
                            <span class="promo-pill">
                                <i class="ri-coupon-2-line"></i>
                                Đang khuyến mãi
                            </span>
                        @endif --}}
                        <div class="container-ThemVGio">
                            <a href="#" class="btn btn-ThemVaoGio text-white mx-auto fw-500 d-block">Thêm vào giỏ</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted mb-0">Chưa có sản phẩm nào phù hợp với bộ lọc hiện tại.</p>
            </div>
            @endforelse

            @if($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
            <div class="col-12 text-center mt-3">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
@endpush
