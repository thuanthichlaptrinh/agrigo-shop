@extends('user.layouts.app')

@section('title', 'Trang chủ - Organic Shop')

@push('styles')
<style>
    /* Hide header dropdown on home page since sidebar is already visible */
    .col-3:hover .category-dropdown {
        display: none !important;
    }

    /* Disable hover effect on category menu trigger */
    .category-menu-trigger {
        cursor: default !important;
    }

    /* Home banner carousel tweaks */
    .home-carousel .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(0, 0, 0, 0.1);
        margin: 0 6px;
        transition: transform 0.25s ease, background-color 0.25s ease;
    }

    .home-carousel .carousel-indicators .active {
        background-color: #00a86b;
        transform: scale(1.2);
    }

    .home-carousel {
        position: relative;
    }

    .home-carousel .carousel-control-prev,
    .home-carousel .carousel-control-next {
        width: 46px;
        height: 46px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .home-carousel .carousel-control-prev {
        left: 18px;
    }

    .home-carousel .carousel-control-next {
        right: 18px;
    }

    .home-carousel .carousel-control-prev::after,
    .home-carousel .carousel-control-next::after {
        display: none;
    }

    .home-carousel:hover .carousel-control-prev,
    .home-carousel:hover .carousel-control-next {
        opacity: 1;
    }

    .home-carousel .carousel-control-icon {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #1f2937;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.18);
        transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .home-carousel .carousel-control-prev:hover .carousel-control-icon,
    .home-carousel .carousel-control-next:hover .carousel-control-icon {
        background: #00a86b;
        color: #ffffff;
        transform: scale(1.05);
    }

    .home-carousel .carousel-item img {
        box-shadow: 0 20px 35px rgba(15, 23, 42, 0.25);
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
        height: 180px;
        object-fit: cover;
        width: 100%;
    }

    .home-product-card:hover img {
        transform: scale(1.02);
    }

    .home-product-card .card-body {
        padding: 14px 4px 0;
        display: flex;
        flex-direction: column;
        min-height: 130px;
    }

    .home-product-card .container-ThemVGio {
        margin-top: auto;
    }

    .home-product-card .card-title {
        margin-bottom: 6px;
        color: #1f2937;
    }

    .home-product-card .product-price span:first-child {
        font-size: 18px;
        color: #00a86b;
    }

    .home-product-card .btn-ThemVaoGio {
        border-radius: 20px !important;
        /* box-shadow: 0 8px 18px rgba(0, 168, 107, 0.25); */
        box-shadow: 0 14px 28px rgba(13, 91, 52, 0.25);
    }

    .home-product-card .box-flash {
        right: 16px;
        top: 16px;
    }

    .article-grid-row .article-grid-col--half {
        flex: 0 0 50%;
        max-width: 50%;
    }

    @media (max-width: 992px) {
        .article-grid-row .article-grid-col--half {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    /* Promo brand deals hover overlay */
    .promo-banner-card {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        background: #f9fafb;
    }

    .promo-banner-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 38px rgba(15, 23, 42, 0.28);
    }

    .promo-banner-link {
        display: block;
        position: relative;
        isolation: isolate;
        border-radius: 10px;
        overflow: hidden;
    }

    .promo-banner-link img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease, filter 0.4s ease;
    }

    .promo-banner-link:hover img,
    .promo-banner-link:focus-visible img {
        transform: scale(1.06);
        filter: saturate(1.08) brightness(1.04);
    }

    .promo-banner-link::before {
        content: attr(data-discount);
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
        padding: 6px 10px;
        border-radius: 999px;
        background: linear-gradient(135deg, #ff7a18, #ffb347);
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        box-shadow: 0 10px 20px rgba(255, 122, 24, 0.35);
        opacity: 0;
        transform: translateY(-8px);
        transition: opacity 0.25s ease, transform 0.25s ease;
        border: 1px solid rgba(255, 255, 255, 0.35);
        backdrop-filter: blur(2px);
        border-radius: inherit;
    }

    .promo-banner-link::after {
        content: attr(data-name) "\A" attr(data-price) "  |  " attr(data-discount) "\A" "Giá gốc " attr(data-original);
        white-space: pre-line;
        position: absolute;
        inset: 0;
        display: flex;
        align-items: flex-end;
        padding: 20px 18px;
        background: linear-gradient(185deg, rgba(3, 7, 18, 0.02) 0%, rgba(3, 7, 18, 0.86) 70%);
        color: #fdfdfd;
        font-weight: 700;
        font-size: 14.5px;
        line-height: 1.5;
        letter-spacing: 0.25px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.45);
        opacity: 0;
        transition: opacity 0.3s ease, transform 0.3s ease;
        transform: translateY(14px);
        backdrop-filter: blur(2px);
        border-top: 1px solid rgba(255, 255, 255, 0.16);
        box-shadow: inset 0 24px 48px rgba(0,0,0,0.32);
        border-radius: inherit;
    }

    .promo-banner-link:hover::after,
    .promo-banner-link:focus-visible::after,
    .promo-banner-link:hover::before,
    .promo-banner-link:focus-visible::before {
        opacity: 1;
        transform: translateY(0);
    }

    .promo-banner-link::selection,
    .promo-banner-link *::selection {
        background: rgba(0, 168, 107, 0.25);
        color: #fff;
    }

    @media (max-width: 768px) {
        .promo-banner-link::after {
            font-size: 13px;
            padding: 14px;
        }
        .promo-banner-link::before {
            top: 10px;
            left: 10px;
            font-size: 12px;
            padding: 5px 9px;
        }
    }
 
</style>
@endpush

@section('content')
<div class="row">
    <!-- Sidebar Menu -->
    @include('user.partials.sidebar')

    <!-- Main Content -->
    <div class="col-10 content-body">
        <!-- Categories -->
        <div class="row" style="margin-left: -23px">
            <div class="pro-cate d-flex align-items-center bg-white">
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/icon-history.v202301091407.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p style="font-size: 14px; color: rgb(0 126 66 / 1); font-weight: bold; margin-top: 5px; line-height: 1.2">Mua lại đơn cũ</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/raucuqua.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Rau, củ, trái cây</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/sua-tuoi-202210311320147075_202408291415515944.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Sữa tươi</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/gao-cac-loai-202209301453236694.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Gạo các loại</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/rau-la-cac-loai-202210311314254141.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Rau lá</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/trai-cay-cac-loai-202210311314516525.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Trái cây</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/rau-cu-cac-loai-202209301506432108.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Củ quả</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/274034_202410110846451339.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Kẹo các loại</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/image-1_202410181218245372.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Thịt heo</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/image-2466_202410151406552676.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Rau, củ làm sẵn</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/ca-hai-san-202209301439213205.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Cá, hải sản khô</p>
                    </div>
                </div>
                <div class="" style="width: calc(100% / 12)">
                    <img src="{{ asset('template/Assets/Images/trung-ga-vit-cut-202212051414238645.png') }}" class="w-75" alt="" />
                    <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                        <p>Trứng gà, vịt, cút</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Carousel -->
        <div class="row mt-2" style="margin-left: -34px; margin-right: -24px">
            <div id="demo" class="carousel slide carousel-fade home-carousel" data-bs-ride="carousel" data-bs-touch="true" data-bs-interval="2500">
                <div class="carousel-indicators">
                    @forelse(($homeBanners ?? collect()) as $banner)
                        <button type="button"
                                data-bs-target="#demo"
                                data-bs-slide-to="{{ $loop->index }}"
                                class="{{ $loop->first ? 'active' : '' }}"
                                aria-label="Banner {{ $loop->iteration }}" style="width: 8px; height: 8px; margin: 0 4px;"></button>
                    @empty
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
                    @endforelse
                </div>

                <div class="carousel-inner">
                    @forelse(($homeBanners ?? collect()) as $banner)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        @if($banner->LienKet)
                            <a href="{{ $banner->LienKet }}" target="_blank" rel="noopener">
                                <img src="{{ asset('uploads/banners/' . $banner->HinhAnh) }}"
                                     alt="{{ $banner->TieuDe ?? 'Banner ' . $loop->iteration }}"
                                     class="d-block w-100"
                                     style="height: 255px; object-fit: cover;" />
                            </a>
                        @else
                            <img src="{{ asset('uploads/banners/' . $banner->HinhAnh) }}"
                                 alt="{{ $banner->TieuDe ?? 'Banner ' . $loop->iteration }}"
                                 class="d-block w-100"
                                 style="height: 255px; object-fit: cover;" />
                        @endif
                    </div>
                    @empty
                    <div class="carousel-item">
                        <img src="{{ asset('template/Assets/Images/hoa-don-maggi-50k.jpg') }}" alt="Banner 1" class="d-block w-100" />
                    </div>
                    <div class="carousel-item active">
                        <img src="{{ asset('template/Assets/Images/qcbanner6-61-hinh.png') }}" style="width: 1000.01px; height: 255.01px; object-fit: cover" alt="Banner 2" class="d-block w-100" />
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('template/Assets/Images/s-4395-hinh.png') }}" style="width: 1000.01px; height: 255.01px; object-fit: cover" alt="Banner 3" class="d-block w-100" />
                    </div>
                    @endforelse
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev" aria-label="Banner trước">
                    <span class="carousel-control-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                    </span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next" aria-label="Banner kế tiếp">
                    <span class="carousel-control-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 6 15 12 9 18" />
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        <!-- Flash Sale Section -->
        <div class="row mt-3" style="background-color: #effff2; margin-left: -23px; padding-left: 10px">
            <div class="d-flex justify-content-between mt-2 pl-22-t">
                <h5 style="color: var(--text-primary); margin-top: 8px" class="fw-700">KHUYẾN MÃI SỐC</h5>
                <a href="{{ route('user.products.index', ['promotion' => 'flash']) }}" class="fs-14-t d-flex align-items-center" style="text-decoration: none">
                    Xem thêm khuyến mãi
                    <i class="ri-arrow-right-s-line fs-20-t mt-3-t fw-700"></i>
                </a>
            </div>

            @php
                $flashItems = collect($flashSaleProducts ?? [])->take(4);
            @endphp

            <div class="row pl-22-t" style="padding-right: 0; margin-bottom: 13px; margin-top: 6px">
                @for($i = 0; $i < 4; $i++)
                @php
                    $product = $flashItems[$i] ?? null;
                    $image = $product ? product_image_url($product['image'] ?? null) : asset('template/Assets/Images/thumb-2024-26_202410171658026192.jpg');
                    $finalPrice = $product['final_price'] ?? 25500;
                    $originalPrice = $product['original_price'] ?? 28000;
                    $detailUrl = $product ? route('user.products.detail', $product['id']) : '#';
                    $badgeText = $product['promotion_name'] ?? 'Còn 25 suất';
                @endphp
                <div class="col-km col-lg-3 col-md-4 col-sm-12 mb-2 position-relative">
                    @if($product)
                    <div class="d-flex align-items-center justify-content-center box-flash">
                        <i class="ri-flashlight-line"></i>
                        <span style="font-weight: 700">-{{ (int) round($product['discount_percent'] ?? 0) }}%</span>
                    </div>
                    @endif

                    <div class="p-1" style="border-radius: 4px; background: linear-gradient(180deg, #8eca51, #2c8e5f 84.5%, #5e9329)">
                        <a href="{{ $detailUrl }}">
                            <img src="{{ $image }}" class="w-100" alt="{{ $product['name'] ?? 'Flash Sale' }}" />
                        </a>
                        <div>
                            <p class="bg-white w-100 text-center text-secondary mt-1"
                            style="border-radius: 20px; padding: 0px; font-size: 11px; font-weight: 500">{{ $badgeText }}</p>
                            <div class="d-flex justify-content-between px-2 py-2">
                                <div>
                                    <p class="txt-yellow m-0" style="font-weight: 700">{{ number_format($finalPrice, 0, ',', '.') }}đ</p>
                                    <p class="text-white m-0 fw-500 fs-13-t" style="text-decoration: line-through">{{ number_format($originalPrice, 0, ',', '.') }}đ</p>
                                </div>
                                <a href="{{ $detailUrl }}"
                                    class="btn btn-buy bg-white d-block float-end txt-primary mt-2"
                                    style="width: 100px; height: 40px"
                                    @if($product)
                                        data-add-to-cart="true"
                                        data-product-id="{{ $product['id'] }}"
                                    @endif>
                                    <b>MUA</b>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Products Section -->
        @php
            $categorySections = collect($categoryProductSections ?? []);
            $defaultBanner = (object) [
                'HinhAnh' => 'template/Assets/Images/cate-pc-54_202410191139488622.jpg',
                'TieuDe' => 'Banner sản phẩm'
            ];

            $productBannerChunks = collect($productBanners ?? [])->chunk(3)->values();

            if ($productBannerChunks->isEmpty()) {
                $productBannerChunks = collect([collect([$defaultBanner, $defaultBanner, $defaultBanner])]);
            }
        @endphp 

        @foreach($categorySections as $section)
            @php
                $products = collect($section['products'] ?? []);
            @endphp

            @if($products->isEmpty())
                @continue
            @endif

            @php
                $categoryBanners = $productBannerChunks->get($loop->index, $productBannerChunks->first() ?? collect());

                // Always render 3 banners per category; pad with a fallback if missing and reset keys
                $categoryBanners = collect($categoryBanners)->pad(3, $defaultBanner)->values();
                $carouselId = 'category-banner-' . ($section['id'] ?? $loop->iteration);
            @endphp

            <!-- Category Banner Carousel -->
            @if($categoryBanners->isNotEmpty())
                <div class="row mt-3" style="margin-left: -34px; margin-right: -24px; margin-bottom: 25px">
                    <div id="{{ $carouselId }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($categoryBanners as $slideIndex => $banner)
                                <button type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide-to="{{ $slideIndex }}" class="{{ $loop->first ? 'active' : '' }}"></button>
                            @endforeach
                        </div>

                        <div class="carousel-inner">
                            @foreach($categoryBanners as $slideIndex => $banner)
                                @php
                                    $bannerPath = $banner->HinhAnh ?? '';
                                    $bannerSrc = \Illuminate\Support\Str::startsWith($bannerPath, ['http://', 'https://'])
                                        ? $bannerPath
                                        : asset($bannerPath ? (\Illuminate\Support\Str::startsWith($bannerPath, 'uploads/') ? $bannerPath : 'uploads/banners/' . ltrim($bannerPath, '/'))
                                                            : 'template/Assets/Images/cate-pc-54_202410191139488622.jpg');
                                @endphp
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <img src="{{ $bannerSrc }}" alt="{{ $banner->TieuDe ?? 'Category Banner' }}" class="d-block w-100" />
                                </div>
                            @endforeach
                        </div>

                        @if($categoryBanners->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            <div class="row mt-3 bg-white mb-13-t pb-12-t row-km" 
                style="margin-left: -23px; border-top: 1px solid #2c9f45 !important;">
                <div class="title-banner-wrapper">
                    <div class="triangle-left"></div>
                    <a class="title-banner">
                        <span>{{ \Illuminate\Support\Str::upper($section['name'] ?? 'Danh mục') }}</span>
                    </a>
                    <div class="triangle-right"></div>
                </div>

                @foreach($products as $product)
                    @php
                        $image = product_image_url($product['image'] ?? null);
                        $price = $product['final_price'] ?? 0;
                        $unit = $product['unit'] ?? 'Gói';
                        $detailUrl = !empty($product['id']) ? route('user.products.detail', $product['id']) : '#';
                        $hasDiscount = (bool) ($product['has_discount'] ?? false);
                        $discountPercent = (int) round($product['discount_percent'] ?? 0);
                    @endphp
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="card home-product-card">
                            @if($hasDiscount)
                                <div class="d-flex align-items-center justify-content-center box-flash">
                                    <i class="ri-flashlight-line"></i>
                                    <span style="font-weight: 700">-{{ $discountPercent }}%</span>
                                </div>
                            @endif
                            <a href="{{ $detailUrl }}">
                                <img src="{{ $image }}" class="w-100" alt="{{ $product['name'] ?? 'Sản phẩm' }}" />
                            </a>
                            <div class="card-body">
                                <p class="card-title fw-400 txt-gray product-name-single-line">{{ $product['name'] ?? 'Sản phẩm' }}</p>
                                <p class="card-title product-price">
                                    <span class="fw-700">{{ number_format($price, 0, ',', '.') }}đ</span>
                                    <span class="txt-gray fs-13-t">/{{ $unit }}</span>
                                </p>
                                <div class="container-ThemVGio">
                                    <a href="{{ $detailUrl }}" class="btn btn-ThemVaoGio text-white mx-auto fw-500 d-block"
                                        @if($product)
                                            data-add-to-cart="true"
                                            data-product-id="{{ $product['id'] }}"
                                        @endif>
                                        <i class="ri-shopping-cart-line me-1"></i>
                                        Thêm vào giỏ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('user.products.index', ['category' => $section['id']]) }}" class="text-center mt-2">Xem thêm</a>
            </div>
        @endforeach

        <!-- Gian hàng và ưu đãi từ hãng -->
        @php
            $brandDeals = collect($brandDealProducts ?? [])->take(4);
            $fallbackBanner = asset('template/Assets/Images/thumb-2024-26_202410171658026192.jpg');
        @endphp
        <div class="row mt-2 mb-3" style="margin-left: -23px; margin-right: -24px;">
            <div class="col-12 px-0">
                <!-- Header Section với ngôi sao - width 780px và center -->
                <div class="d-flex justify-content-center" style="margin-bottom: -1px; position: relative; z-index: 2;">
                    <div class="promo-header-wrapper" style="background: linear-gradient(90deg, #ff9966, #ffb84d); padding: 0; width: 780px; max-width: 100%; border-radius: 20px 20px 0 0; border: 1px solid rgb(255 93 1/1)">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-star-fill" style="font-size: 24px; color: #fff700; margin-right: 8px;"></i>
                            <h4 class="text-white fw-700 m-0" style="letter-spacing: 1px; text-transform: uppercase; font-size: 16px">GIAN HÀNG VÀ ƯU ĐÃI TỪ HÃNG</h4>
                            <i class="ri-star-fill" style="font-size: 24px; color: #fff700; margin-left: 8px;"></i>
                        </div>
                    </div>
                </div>

                <!-- Container cho 4 banner - full width -->
                <div style="background-color: #fff5e6; margin-right: 12px; padding: 15px; border-top: 1px solid rgb(255 93 1/1)">
                    <div class="row g-2">
                        <!-- Banner 1 - Tích Lũy Mua Sắm Nhận Quà Ưu Đãi -->
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="promo-banner-card" style="position: relative; overflow: hidden; border-radius: 8px; height: 100%;">
                                @php $deal = $brandDeals->get(0); @endphp
                                <a href="{{ $deal ? route('user.products.detail', $deal['id']) : '#' }}"
                                   class="promo-banner-link"
                                   data-name="{{ $deal['name'] ?? '' }}"
                                   data-price="{{ isset($deal['final_price']) ? number_format($deal['final_price'], 0, ',', '.') . 'đ' : '' }}"
                                   data-original="{{ isset($deal['original_price']) ? number_format($deal['original_price'], 0, ',', '.') . 'đ' : '' }}"
                                   data-discount="{{ $deal['discount_percent'] ?? 0 }}%">
                                    <img src="{{ $deal ? product_image_url($deal['image'] ?? null) : $fallbackBanner }}"
                                        class="w-100"
                                        style="height: 320px; object-fit: cover; display: block;"
                                        alt="{{ $deal['name'] ?? 'Tích lũy mua sắm nhận quà ưu đãi' }}"
                                        title="{{ $deal ? ($deal['name'] . ' • Giảm ' . (int) round($deal['discount_percent'] ?? 0) . '%') : '' }}" />
                                </a>
                            </div>
                        </div>

                        <!-- Banner 2 - Tích Lũy Mua Sắm Nhận Phiếu Mua Hàng -->
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="promo-banner-card" style="position: relative; overflow: hidden; border-radius: 8px; height: 100%;">
                                @php $deal = $brandDeals->get(1); @endphp
                                <a href="{{ $deal ? route('user.products.detail', $deal['id']) : '#' }}"
                                   class="promo-banner-link"
                                   data-name="{{ $deal['name'] ?? '' }}"
                                   data-price="{{ isset($deal['final_price']) ? number_format($deal['final_price'], 0, ',', '.') . 'đ' : '' }}"
                                   data-original="{{ isset($deal['original_price']) ? number_format($deal['original_price'], 0, ',', '.') . 'đ' : '' }}"
                                   data-discount="{{ $deal['discount_percent'] ?? 0 }}%">
                                    <img src="{{ $deal ? product_image_url($deal['image'] ?? null) : $fallbackBanner }}"
                                        class="w-100"
                                        style="height: 320px; object-fit: cover; display: block;"
                                        alt="{{ $deal['name'] ?? 'Tích lũy mua sắm nhận phiếu mua hàng' }}"
                                        title="{{ $deal ? ($deal['name'] . ' • Giảm ' . (int) round($deal['discount_percent'] ?? 0) . '%') : '' }}" />
                                </a>
                            </div>
                        </div>

                        <!-- Banner 3 - P&G Giặt Xả Gia Tốt -->
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="promo-banner-card" style="position: relative; overflow: hidden; border-radius: 8px; height: 100%;">
                                @php $deal = $brandDeals->get(2); @endphp
                                <a href="{{ $deal ? route('user.products.detail', $deal['id']) : '#' }}"
                                   class="promo-banner-link"
                                   data-name="{{ $deal['name'] ?? '' }}"
                                   data-price="{{ isset($deal['final_price']) ? number_format($deal['final_price'], 0, ',', '.') . 'đ' : '' }}"
                                   data-original="{{ isset($deal['original_price']) ? number_format($deal['original_price'], 0, ',', '.') . 'đ' : '' }}"
                                   data-discount="{{ $deal['discount_percent'] ?? 0 }}%">
                                    <img src="{{ $deal ? product_image_url($deal['image'] ?? null) : $fallbackBanner }}"
                                        class="w-100"
                                        style="height: 320px; object-fit: cover; display: block;"
                                        alt="{{ $deal['name'] ?? 'P&G Giặt Xả Gia Tốt' }}"
                                        title="{{ $deal ? ($deal['name'] . ' • Giảm ' . (int) round($deal['discount_percent'] ?? 0) . '%') : '' }}" />
                                </a>
                            </div>
                        </div>

                        <!-- Banner 4 - Cô Sprite Mát Lành Cực Đã -->
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="promo-banner-card" style="position: relative; overflow: hidden; border-radius: 8px; height: 100%;">
                                @php $deal = $brandDeals->get(3); @endphp
                                <a href="{{ $deal ? route('user.products.detail', $deal['id']) : '#' }}"
                                   class="promo-banner-link"
                                   data-name="{{ $deal['name'] ?? '' }}"
                                   data-price="{{ isset($deal['final_price']) ? number_format($deal['final_price'], 0, ',', '.') . 'đ' : '' }}"
                                   data-original="{{ isset($deal['original_price']) ? number_format($deal['original_price'], 0, ',', '.') . 'đ' : '' }}"
                                   data-discount="{{ $deal['discount_percent'] ?? 0 }}%">
                                    <img src="{{ $deal ? product_image_url($deal['image'] ?? null) : $fallbackBanner }}"
                                        class="w-100"
                                        style="height: 320px; object-fit: cover; display: block;"
                                        alt="{{ $deal['name'] ?? 'Cô Sprite Mát Lành Cực Đã' }}"
                                        title="{{ $deal ? ($deal['name'] . ' • Giảm ' . (int) round($deal['discount_percent'] ?? 0) . '%') : '' }}" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- View More Button -->
                    <div class="text-center mt-3 pb-2">
                        <a href="{{ route('user.products.index', ['promotion' => 'flash', 'only_half_off' => 1]) }}" class="text-decoration-none" style="color: #666; font-size: 14px; font-weight: 500;">
                            Xem thêm ưu đãi 50%
                            <i class="ri-arrow-down-s-line"></i>
                        </a>
                    </div>
                </div>
                <!-- End container -->
            </div>
        </div>

        @php
            $homeArticlesCollection = collect($homeArticles ?? []);
            $featuredArticle = $homeArticlesCollection->get(0);
            $secondaryArticles = $homeArticlesCollection->slice(1)->values();
            $defaultArticleImage = asset('template/Assets/Images/thumb-2024-26_202410171658026192.jpg');
            $resolveArticleImage = function ($article) use ($defaultArticleImage) {
                if (!$article || empty($article->HinhAnh)) {
                    return $defaultArticleImage;
                }

                $path = $article->HinhAnh;
                return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
                    ? $path
                    : asset($path);
            };

            $articleLink = function ($article) {
                return $article && $article->Slug ? route('articles.show', $article->Slug) : '#';
            };

            $articleTime = function ($article) {
                return $article && $article->NgayTao ? $article->NgayTao->diffForHumans() : 'Đang cập nhật';
            };
        @endphp

        <!-- Bài viết -->
        <div class="row mt-2 mb-3 bg-white" style="margin-left: -23px; margin-right: -12px; padding: 20px 15px; border-radius: 8px;">
            <div class="col-12">
                <div class="row g-3">
                    <!-- Article 1 - Cá song biển -->
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="article-card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%;">
                            <a href="{{ $articleLink($featuredArticle) }}" class="text-decoration-none">
                                <!-- Hình ảnh lớn với 2 hàng 3 cột -->
                                <div class="article-images" style="display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(2, 1fr); gap: 2px; height: 280px;">
                                    <div style="grid-column: 1 / 2; grid-row: 1 / 2;">
                                        <img src="{{ $resolveArticleImage($featuredArticle) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $featuredArticle->TieuDe ?? 'Bài viết nổi bật' }} 1" />
                                    </div>
                                    <div style="grid-column: 2 / 3; grid-row: 1 / 2;">
                                        <img src="{{ $resolveArticleImage($featuredArticle) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $featuredArticle->TieuDe ?? 'Bài viết nổi bật' }} 2" />
                                    </div>
                                    <div style="grid-column: 3 / 4; grid-row: 1 / 2;">
                                        <img src="{{ $resolveArticleImage($featuredArticle) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $featuredArticle->TieuDe ?? 'Bài viết nổi bật' }} 3" />
                                    </div>
                                    <div style="grid-column: 1 / 2; grid-row: 2 / 3;">
                                        <img src="{{ $resolveArticleImage($featuredArticle) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $featuredArticle->TieuDe ?? 'Bài viết nổi bật' }} 4" />
                                    </div>
                                    <div style="grid-column: 2 / 4; grid-row: 2 / 3;">
                                        <img src="{{ $resolveArticleImage($featuredArticle) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $featuredArticle->TieuDe ?? 'Bài viết nổi bật' }} 5" />
                                    </div>
                                </div>
                                <!-- Nội dung -->
                                <div style="padding: 15px; background: white;">
                                    <h5 class="fw-600 mb-2" style="color: #333; font-size: 16px;">{{ $featuredArticle->TieuDe ?? 'Đang cập nhật bài viết nổi bật' }}</h5>
                                    <p class="text-muted mb-0" style="font-size: 13px;">
                                        <i class="ri-time-line"></i> {{ $articleTime($featuredArticle) }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Column 2 - 4 bài viết nhỏ -->
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="row g-3 article-grid-row">
                            <!-- Article 2 - Cá vàng dưới công -->
                            <div class="col-lg-6 col-md-12 article-grid-col--half">
                                <div class="article-card-mini" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%;">
                                    @php $article = $secondaryArticles->get(0); @endphp
                                    <a href="{{ $articleLink($article) }}" class="text-decoration-none">
                                        <img src="{{ $resolveArticleImage($article) }}"
                                             style="width: 100%; height: 140px; object-fit: cover;"
                                             alt="{{ $article->TieuDe ?? 'Bài viết' }}" />
                                        <div style="padding: 12px; background: white;">
                                            <h6 class="fw-600 mb-2" style="color: #333; font-size: 15px; line-height: 1.4;">{{ $article->TieuDe ?? 'Đang cập nhật bài viết' }}</h6>
                                            <p class="text-muted mb-0" style="font-size: 13px;">
                                                <i class="ri-time-line"></i> {{ $articleTime($article) }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Article 3 - Kem lạnh Carslam -->
                            <div class="col-lg-6 col-md-12 article-grid-col--half">
                                <div class="article-card-mini" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%;">
                                    @php $article = $secondaryArticles->get(1); @endphp
                                    <a href="{{ $articleLink($article) }}" class="text-decoration-none">
                                        <img src="{{ $resolveArticleImage($article) }}"
                                             style="width: 100%; height: 140px; object-fit: cover;"
                                             alt="{{ $article->TieuDe ?? 'Bài viết' }}" />
                                        <div style="padding: 12px; background: white;">
                                            <h6 class="fw-600 mb-2" style="color: #333; font-size: 15px; line-height: 1.4;">{{ $article->TieuDe ?? 'Đang cập nhật bài viết' }}</h6>
                                            <p class="text-muted mb-0" style="font-size: 13px;">
                                                <i class="ri-time-line"></i> {{ $articleTime($article) }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Article 4 - Thạch rau câu -->
                            <div class="col-6">
                                <div class="article-card-mini" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%;">
                                    @php $article = $secondaryArticles->get(2); @endphp
                                    <a href="{{ $articleLink($article) }}" class="text-decoration-none">
                                        <img src="{{ $resolveArticleImage($article) }}"
                                             style="width: 100%; height: 140px; object-fit: cover;"
                                             alt="{{ $article->TieuDe ?? 'Bài viết' }}" />
                                        <div style="padding: 10px; background: white;">
                                            <h6 class="fw-600 mb-1" style="color: #333; font-size: 14px; line-height: 1.3;">{{ $article->TieuDe ?? 'Đang cập nhật bài viết' }}</h6>
                                            <p class="text-muted mb-0" style="font-size: 12px;">
                                                <i class="ri-time-line"></i> {{ $articleTime($article) }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Article 5 - Review phim -->
                            <div class="col-6">
                                <div class="article-card-mini" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%;">
                                    @php $article = $secondaryArticles->get(3); @endphp
                                    <a href="{{ $articleLink($article) }}" class="text-decoration-none">
                                        <img src="{{ $resolveArticleImage($article) }}"
                                             style="width: 100%; height: 140px; object-fit: cover;"
                                             alt="{{ $article->TieuDe ?? 'Bài viết' }}" />
                                        <div style="padding: 10px; background: white;">
                                            <h6 class="fw-600 mb-1" style="color: #333; font-size: 14px; line-height: 1.3;">{{ $article->TieuDe ?? 'Đang cập nhật bài viết' }}</h6>
                                            <p class="text-muted mb-0" style="font-size: 12px;">
                                                <i class="ri-time-line"></i> {{ $articleTime($article) }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View More Button -->
                <div class="text-center mt-4">
                    <a href="{{ $homeArticlesCollection->isNotEmpty() ? route('articles.index') : '#' }}" class="text-decoration-none fw-600" style="color: #333; font-size: 15px;">
                        {{ $homeArticlesCollection->isNotEmpty() ? 'Xem thêm bài viết' : 'Đang cập nhật thêm bài viết' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-3" >
            @include('user.partials.footer')
        </div>
    </div>
</div>
@endsection
