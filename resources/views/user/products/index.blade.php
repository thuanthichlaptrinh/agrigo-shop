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

    .home-product-card:hover .wishlist-btn {
        opacity: 1;
        visibility: visible;
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

    /* FAQ block */
    .product-faq {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        padding: 18px 20px;
        margin-top: 18px;
    }
    .product-faq h5 {
        font-weight: 800;
        margin-bottom: 10px;
    }
    .product-faq .faq-item {
        border-bottom: 1px solid #e5e7eb;
        padding: 12px 0;
    }
    .product-faq .faq-item:last-child {
        border-bottom: none;
    }
    .product-faq .faq-question {
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        color: #0f172a;
    }
    .product-faq .faq-answer {
        display: none;
        color: #4b5563;
        margin-top: 8px;
    }
    .product-faq .faq-item.active .faq-answer {
        display: block;
    }
    .product-faq .faq-item.active .faq-question i {
        transform: rotate(180deg);
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
                            <option value="flash" {{ request('promotion') === 'flash' ? 'selected' : '' }}>Flash sale</option>
                        </select>
                    </div>
                    <div>
                        <select name="discount_min" class="form-select" style="height: 34px; font-size: 15px">
                            <option value="">Giảm từ</option>
                            <option value="10" {{ request('discount_min') === '10' ? 'selected' : '' }}>Từ 10%</option>
                            <option value="20" {{ request('discount_min') === '20' ? 'selected' : '' }}>Từ 20%</option>
                            <option value="30" {{ request('discount_min') === '30' ? 'selected' : '' }}>Từ 30%</option>
                            <option value="50" {{ request('discount_min') === '50' ? 'selected' : '' }}>Từ 50%</option>
                        </select>
                    </div>
                    <div>
                        <select name="supplier" class="form-select" style="height: 34px; font-size: 15px">
                            <option value="">Nhà cung cấp</option>
                            @foreach(($supplierOptions ?? []) as $origin)
                                <option value="{{ $origin }}" {{ request('supplier') === $origin ? 'selected' : '' }}>{{ $origin }}</option>
                            @endforeach
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
                    
                    {{-- Nút yêu thích --}}
                    <button class="wishlist-btn {{ !empty($product['is_wishlisted']) ? 'active' : '' }}"  
                            data-product-id="{{ $product['id'] ?? '' }}"
                            data-wishlisted="{{ !empty($product['is_wishlisted']) ? 'true' : 'false' }}"
                            title="{{ !empty($product['is_wishlisted']) ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                        <i class="{{ !empty($product['is_wishlisted']) ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                    </button>
                    
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
                            <a href="#" class="btn btn-ThemVaoGio text-white mx-auto fw-500 d-block"
                                data-add-to-cart="true"
                                data-product-id="{{ $product['id'] ?? '' }}">
                                Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <img src="{{ url('template/Assets/Images/logo5.png') }}" alt="Không có sản phẩm" style="max-width: 200px; margin-bottom: 1rem; opacity: 0.5;">
                        <p class="text-muted mb-0">Chưa có sản phẩm nào phù hợp với bộ lọc hiện tại.</p>
                    </div>
                    @endforelse            @if($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
            <div class="col-12 text-center mt-3">
                {{ $products->links() }}
            </div>
            @endif
        </div>
        @php
            $productFaqs = [
                ['q' => 'Thời gian giao hàng bao lâu?', 'a' => 'Nội thành 2-4 giờ, các tỉnh lân cận 1-2 ngày tùy khu vực.'],
                ['q' => 'Có kiểm hàng khi nhận không?', 'a' => 'Bạn được kiểm tra ngoại quan và số lượng trước khi thanh toán.'],
                ['q' => 'Chính sách đổi trả thế nào?', 'a' => 'Đổi/hoàn trong 48h nếu hàng hỏng, kém chất lượng. Giữ lại hóa đơn và hình ảnh.'],
                ['q' => 'Phí vận chuyển tính ra sao?', 'a' => 'Miễn phí đơn từ 300.000đ nội thành; đơn thấp hơn tính theo khu vực/khối lượng.'],
                ['q' => 'Có hỗ trợ xuất hóa đơn không?', 'a' => 'Có. Ghi chú “xuất hóa đơn” khi đặt hoặc liên hệ sau khi đặt thành công.'],
            ];
        @endphp

        <div class="product-faq" style="margin-left: -23px; margin-right: -11px; margin-bottom: 20px; border-radius: 0">
            <h5>Câu hỏi thường gặp</h5>
            <div id="product-faq-list">
                @foreach($productFaqs as $item)
                    <div class="faq-item">
                        <div class="faq-question" data-faq-toggle>
                            <span>{{ $item['q'] }}</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">{{ $item['a'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút yêu thích
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    
    wishlistButtons.forEach(button => {
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
                    // Bỏ yêu thích
                    icon.className = 'ri-heart-line';
                    this.classList.remove('active');
                    this.dataset.wishlisted = 'false';
                    this.title = 'Thêm vào yêu thích';
                } else {
                    // Thêm yêu thích
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
});

document.querySelectorAll('[data-faq-toggle]').forEach(item => {
    item.addEventListener('click', () => {
        const parent = item.closest('.faq-item');
        parent.classList.toggle('active');
    });
});
</script>
@endpush
