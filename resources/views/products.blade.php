@extends('layouts.app')

@section('title', 'Danh sách sản phẩm - Organic Shop')

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<style>
    /* Hide header dropdown on products page since sidebar is already visible */
    .category-dropdown {
        display: none !important;
    }
    
    /* Disable hover effect on category menu trigger */
    .category-menu-trigger {
        cursor: default !important;
    }
    
    .btn-Them:hover,
    .btn-ThanhToan:hover {
        filter: brightness(1.5);
    }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Sidebar Menu -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="col-9 content-body">
        <!-- Breadcrumb -->
        <div class="row" style="margin-left: -23px">
            <div class="p-2 bg-white my-2" style="border-radius: 4px">
                <a href="{{ route('home') }}" style="text-decoration: none; color: #000">
                    <i class="ri-arrow-left-s-line" style="font-size: 18px; border-right: 1px solid #dedede; padding-right: 6px; margin-right: 6px"></i>
                </a>
                <span class="fw-500">{{ $categoryName ?? 'Trái cây' }}</span>
            </div>
        </div>

        <!-- Filter Form -->
        <form class="bg-white" style="margin-left: -23px; margin-right: -12px; border-radius: 4px; padding: 10px; padding-bottom: 12px" method="GET">
            <div>
                <h6>Chọn theo tiêu chí</h6>
                <div class="d-flex align-items-center" style="gap: 8px">
                    <div>
                        <select name="sort_price" class="form-select" style="height: 34px; font-size: 15px">
                            <option value="">Giá bán</option>
                            <option value="asc">Giá tăng dần</option>
                            <option value="desc">Giá giảm dần</option>
                        </select>
                    </div>
                    <div>
                        <select name="price_range" class="form-select" style="height: 34px; font-size: 15px">
                            <option value="">Mức giá</option>
                            <option value="0-100000">Dưới 100.000đ</option>
                            <option value="100000-300000">Từ 100.000đ - 300.000đ</option>
                            <option value="300000-">Trên 300.000đ</option>
                        </select>
                    </div>
                    <div>
                        <select name="promotion" class="form-select" style="height: 34px; font-size: 15px">
                            <option value="">Khuyến mãi</option>
                            <option value="yes">Còn khuyến mãi</option>
                        </select>
                    </div>
                    <div>
                        <select name="supplier" class="form-select" style="height: 34px; font-size: 15px">
                            <option value="">Nhà cung cấp</option>
                            <option value="us">Mỹ</option>
                            <option value="vn">Việt Nam</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="height: 34px; font-size: 15px">Lọc</button>
                </div>
            </div>
        </form>

        <!-- Banner -->
        <div class="mt-2" style="background-color: #fff9f9; margin-left: -23px; margin-right: -11px; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px">
            <img src="{{ asset('template/Assets/Images/Screenshot 2024-11-17 233057.png') }}" class="mx-auto d-block" style="width: 400px" alt="" />
        </div>

        <!-- Products Grid -->
        <div class="row mt-3 bg-white mb-13-t pb-12-t row-km" style="margin-left: -23px">
            <div class="title-banner-wrapper">
                <div class="triangle-left"></div>
                <a class="title-banner">
                    <span>{{ strtoupper($categoryName ?? 'SẢN PHẨM') }}</span>
                </a>
                <div class="triangle-right"></div>
            </div>

            @forelse($products ?? [] as $product)
            <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                <div class="card" style="border: 1px solid #d8e1f9">
                    <a href="{{ route('product-detail', $product['id'] ?? 1) }}">
                        <img src="{{ asset($product['image'] ?? 'template/Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp') }}" class="w-100" alt="" />
                    </a>
                    <div class="card-body">
                        <p class="card-title fw-400 txt-gray">{{ $product['name'] ?? 'Ức gà có xương' }}</p>
                        <p class="card-title">
                            <span class="fw-700">{{ number_format($product['price'] ?? 28350) }}đ</span>
                            <span class="txt-gray fs-13-t">/{{ $product['unit'] ?? '300g' }}</span>
                        </p>
                        <div class="container-ThemVGio">
                            <a href="#" class="btn btn-ThemVaoGio text-white mx-auto fw-500 d-block">Thêm vào giỏ</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @for($i = 0; $i < 12; $i++)
            <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                <div class="card" style="border: 1px solid #d8e1f9">
                    <a href="{{ route('product-detail', 1) }}">
                        <img src="{{ asset('template/Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp') }}" class="w-100" alt="" />
                    </a>
                    <div class="card-body">
                        <p class="card-title fw-400 txt-gray">Ức gà có xương</p>
                        <p class="card-title">
                            <span class="fw-700">28.350đ</span>
                            <span class="txt-gray fs-13-t">/300g</span>
                        </p>
                        <div class="container-ThemVGio">
                            <a href="#" class="btn btn-ThemVaoGio text-white mx-auto fw-500 d-block">Thêm vào giỏ</a>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
            @endforelse

            <div class="col-12 text-center mt-3">
                <a href="#" class="btn btn-outline-primary">Xem thêm</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
@endpush
