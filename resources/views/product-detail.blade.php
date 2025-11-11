@extends('layouts.app')

@section('title', $product['name'] ?? 'Chi tiết sản phẩm - Organic Shop')

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<style>
    .btn-Them:hover,
    .btn-ThanhToan:hover {
        filter: brightness(1.5);
    }
    .product-images img {
        cursor: pointer;
        border: 2px solid transparent;
    }
    .product-images img.active,
    .product-images img:hover {
        border-color: var(--bg-primary);
    }
</style>
@endpush

@section('content')
<div class="row" style="margin-left: -24px; margin-right: -24px">
    <div class="col-12">
        <!-- Breadcrumb -->
        <div class="p-2 bg-white my-2" style="border-radius: 4px">
            <a href="{{ route('home') }}" style="text-decoration: none; color: #000">
                <i class="ri-home-line" style="font-size: 18px; margin-right: 6px"></i>
            </a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="{{ route('products') }}" style="text-decoration: none; color: #000">Sản phẩm</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="fw-500">{{ $product['name'] ?? 'Chi tiết sản phẩm' }}</span>
        </div>
    </div>
</div>

<div class="row mt-3" style="margin-left: -24px; margin-right: -24px">
    <!-- Product Images -->
    <div class="col-md-5">
        <div class="bg-white p-3" style="border-radius: 8px">
            <div class="main-image mb-3">
                <img id="mainImage" src="{{ asset($product['image'] ?? 'template/Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp') }}" class="w-100" alt="" style="border-radius: 8px" />
            </div>
            <div class="product-images d-flex gap-2">
                @for($i = 0; $i < 4; $i++)
                <img src="{{ asset('template/Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp') }}" 
                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px" 
                     alt="" 
                     @if($i === 0) class="active" @endif
                     onclick="changeMainImage(this)" />
                @endfor
            </div>
        </div>
    </div>

    <!-- Product Info -->
    <div class="col-md-7">
        <div class="bg-white p-4" style="border-radius: 8px">
            <h3 class="fw-700">{{ $product['name'] ?? 'Táo Gala Pháp size 100' }}</h3>
            <p class="text-muted">{{ $product['description'] ?? 'Sản phẩm chất lượng cao' }}</p>
            
            <div class="my-4">
                <div class="d-flex align-items-center gap-3">
                    <span class="fs-1 fw-700 text-danger">{{ number_format($product['price'] ?? 89000) }}đ</span>
                    @if(isset($product['old_price']))
                    <span class="fs-5 text-muted" style="text-decoration: line-through">{{ number_format($product['old_price']) }}đ</span>
                    <span class="badge bg-danger">-{{ round((($product['old_price'] - $product['price']) / $product['old_price']) * 100) }}%</span>
                    @endif
                </div>
                <p class="text-muted mt-2">Đơn vị: {{ $product['unit'] ?? '1kg' }}</p>
            </div>

            <div class="mb-4">
                <h5>Số lượng:</h5>
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center" style="border: 1px solid #ddd; border-radius: 8px; padding: 8px">
                        <button class="btn btn-sm" onclick="decreaseQuantity()">-</button>
                        <input type="number" id="quantity" value="1" min="1" class="form-control text-center mx-2" style="width: 60px; border: none" />
                        <button class="btn btn-sm" onclick="increaseQuantity()">+</button>
                    </div>
                    <span class="text-muted">{{ $product['stock'] ?? 100 }} sản phẩm có sẵn</span>
                </div>
            </div>

            <div class="d-flex gap-3">
                <button class="btn btn-primary btn-lg flex-grow-1" onclick="addToCart()">
                    <i class="ri-shopping-cart-line me-2"></i>
                    Thêm vào giỏ hàng
                </button>
                <a href="{{ route('checkout') }}" class="btn btn-success btn-lg flex-grow-1">
                    Mua ngay
                </a>
            </div>
        </div>

        <!-- Product Details -->
        <div class="bg-white p-4 mt-3" style="border-radius: 8px">
            <h5 class="fw-700">Thông tin sản phẩm</h5>
            <table class="table">
                <tbody>
                    <tr>
                        <td class="fw-500">Thương hiệu:</td>
                        <td>{{ $product['brand'] ?? 'Organic Shop' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-500">Xuất xứ:</td>
                        <td>{{ $product['origin'] ?? 'Việt Nam' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-500">Trọng lượng:</td>
                        <td>{{ $product['weight'] ?? '1kg' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-500">Hạn sử dụng:</td>
                        <td>{{ $product['expiry'] ?? '7 ngày' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Product Description -->
<div class="row mt-3" style="margin-left: -24px; margin-right: -24px">
    <div class="col-12">
        <div class="bg-white p-4" style="border-radius: 8px">
            <h5 class="fw-700">Mô tả sản phẩm</h5>
            <div class="mt-3">
                {!! $product['full_description'] ?? '<p>Sản phẩm chất lượng cao, được chọn lọc kỹ càng từ các nhà cung cấp uy tín.</p>' !!}
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
<div class="row mt-3" style="margin-left: -24px; margin-right: -24px">
    <div class="col-12">
        <div class="bg-white p-4" style="border-radius: 8px">
            <h5 class="fw-700 mb-3">Sản phẩm liên quan</h5>
            <div class="row">
                @for($i = 0; $i < 4; $i++)
                <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                    <div class="card" style="border: 1px solid #d8e1f9">
                        <a href="{{ route('product-detail', 1) }}">
                            <img src="{{ asset('template/Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp') }}" class="w-100" alt="" />
                        </a>
                        <div class="card-body">
                            <p class="card-title fw-400 txt-gray">Sản phẩm tương tự</p>
                            <p class="card-title">
                                <span class="fw-700">89.000đ</span>
                                <span class="txt-gray fs-13-t">/1kg</span>
                            </p>
                            <div class="container-ThemVGio">
                                <a href="#" class="btn btn-ThemVaoGio text-white mx-auto fw-500 d-block">Thêm vào giỏ</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>

<!-- Đánh giá Section -->
        <div class="row mt-3">
            <div class="col-12 bg-white p-4" style="border-radius: 8px">
                <h4 class="fw-700 mb-4" style="color: var(--text-primary)">Đánh giá Đồng hồ thông minh Huawei Watch Fit 4</h4>
                
                <div class="row">
                    <!-- Rating Summary -->
                    <div class="col-md-5 border-end">
                        <div class="text-center mb-3">
                            <h1 class="display-1 fw-700 mb-0" style="color: var(--text-primary)">4.8<span class="fs-3 text-muted">/5</span></h1>
                            <div class="mb-2">
                                <i class="ri-star-fill" style="color: #ffc107; font-size: 1.5rem;"></i>
                                <i class="ri-star-fill" style="color: #ffc107; font-size: 1.5rem;"></i>
                                <i class="ri-star-fill" style="color: #ffc107; font-size: 1.5rem;"></i>
                                <i class="ri-star-fill" style="color: #ffc107; font-size: 1.5rem;"></i>
                                <i class="ri-star-fill" style="color: #ffc107; font-size: 1.5rem;"></i>
                            </div>
                            <p class="text-muted mb-3">10 lượt đánh giá</p>
                            <button class="btn btn-write-review text-white fw-600 px-4 py-2">Viết đánh giá</button>
                        </div>

                        <!-- Rating Bars -->
                        <div class="rating-bars mt-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2" style="color: var(--text-primary); font-weight: 600; width: 20px;">5</span>
                                <i class="ri-star-fill me-2" style="color: #ffc107;"></i>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-rating" role="progressbar" style="width: 80%; background-color: #00713b !important;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="text-muted" style="width: 80px;">8 đánh giá</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2" style="color: var(--text-primary); font-weight: 600; width: 20px;">4</span>
                                <i class="ri-star-fill me-2" style="color: #ffc107;"></i>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-rating" role="progressbar" style="width: 20%; background-color: #00713b !important;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="text-muted" style="width: 80px;">2 đánh giá</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2" style="color: var(--text-primary); font-weight: 600; width: 20px;">3</span>
                                <i class="ri-star-fill me-2" style="color: #ffc107;"></i>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-rating" role="progressbar" style="width: 0%; background-color: #00713b !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="text-muted" style="width: 80px;">0 đánh giá</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2" style="color: var(--text-primary); font-weight: 600; width: 20px;">2</span>
                                <i class="ri-star-fill me-2" style="color: #ffc107;"></i>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-rating" role="progressbar" style="width: 0%; background-color: #00713b !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="text-muted" style="width: 80px;">0 đánh giá</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2" style="color: var(--text-primary); font-weight: 600; width: 20px;">1</span>
                                <i class="ri-star-fill me-2" style="color: #ffc107;"></i>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-rating" role="progressbar" style="width: 0%; background-color: #00713b !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="text-muted" style="width: 80px;">0 đánh giá</span>
                            </div>
                        </div>
                    </div>

                    <!-- User Experience Ratings -->
                    <div class="col-md-7 ps-4">
                        <h5 class="fw-600 mb-3" style="color: var(--text-primary)">Đánh giá theo trải nghiệm</h5>
                        
                        <div class="experience-rating mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-500">Thời lượng pin</span>
                                <div>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <span class="ms-2 fw-600" style="color: var(--text-primary)">5/5</span>
                                    <span class="text-muted ms-1">(3 đánh giá)</span>
                                </div>
                            </div>
                        </div>

                        <div class="experience-rating mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-500">Độ chỉ số sức khỏe</span>
                                <div>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <span class="ms-2 fw-600" style="color: var(--text-primary)">5/5</span>
                                    <span class="text-muted ms-1">(3 đánh giá)</span>
                                </div>
                            </div>
                        </div>

                        <div class="experience-rating mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-500">Tiện ích thông minh</span>
                                <div>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <span class="ms-2 fw-600" style="color: var(--text-primary)">5/5</span>
                                    <span class="text-muted ms-1">(3 đánh giá)</span>
                                </div>
                            </div>
                        </div>

                        <div class="experience-rating mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-500">Cảm giác đeo</span>
                                <div>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <i class="ri-star-fill" style="color: #ffc107;"></i>
                                    <span class="ms-2 fw-600" style="color: var(--text-primary)">5/5</span>
                                    <span class="text-muted ms-1">(3 đánh giá)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="fw-600 mb-3" style="color: var(--text-primary)">Lọc đánh giá theo</h5>
                        <div class="filter-tabs d-flex flex-wrap gap-2">
                            <button class="btn btn-filter-active">Tất cả</button>
                            <button class="btn btn-filter">Có hình ảnh</button>
                            <button class="btn btn-filter">Đã mua hàng</button>
                            <button class="btn btn-filter">5 sao</button>
                            <button class="btn btn-filter">4 sao</button>
                            <button class="btn btn-filter">3 sao</button>
                            <button class="btn btn-filter">2 sao</button>
                            <button class="btn btn-filter">1 sao</button>
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="row mt-4">
                    <div class="col-12">
                        <!-- Review 1 -->
                        <div class="review-item border-bottom pb-3 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="review-avatar me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-600" 
                                         style="width: 40px; height: 40px; background-color: #9c27b0;">B</div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-600 mb-1">Bế Quang Ninh</h6>
                                    <div class="mb-2">
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <span class="ms-2 badge" style="background-color: #00713b; color: white; font-size: 11px;">Tuyệt vời</span>
                                    </div>
                                    <p class="mb-2">sản phẩm có độ được huyết áp không</p>
                                    <p class="text-muted small mb-0">
                                        <i class="ri-time-line"></i> Đánh giá đã đăng vào 2 tháng trước
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Review 2 -->
                        <div class="review-item border-bottom pb-3 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="review-avatar me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-600" 
                                         style="width: 40px; height: 40px; background-color: #00897b;">H</div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-600 mb-1">Huong Can</h6>
                                    <div class="mb-2">
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <span class="ms-2 badge" style="background-color: #00713b; color: white; font-size: 11px;">Tuyệt vời</span>
                                    </div>
                                    <p class="mb-2">Rất hài lòng</p>
                                    <p class="text-muted small mb-0">
                                        <i class="ri-time-line"></i> Đánh giá đã đăng vào 3 tháng trước
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Review 3 -->
                        <div class="review-item border-bottom pb-3 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="review-avatar me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-600" 
                                         style="width: 40px; height: 40px; background-color: #5e35b1;">L</div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-600 mb-1">Le Dung</h6>
                                    <div class="mb-2">
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <span class="ms-2 badge" style="background-color: #00713b; color: white; font-size: 11px;">Tuyệt vời</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge me-1" style="background-color: #e8f5e9; color: #00713b; border: 1px solid #00713b;">Thời lượng pin Cực khoẻ</span>
                                        <span class="badge me-1" style="background-color: #e8f5e9; color: #00713b; border: 1px solid #00713b;">Độ chỉ số sức khoẻ Chính xác tuyệt đối</span>
                                        <span class="badge me-1" style="background-color: #e8f5e9; color: #00713b; border: 1px solid #00713b;">Tiện ích thông minh Đa dạng</span>
                                        <span class="badge me-1" style="background-color: #e8f5e9; color: #00713b; border: 1px solid #00713b;">Cảm giác đeo Rất thoải mái</span>
                                    </div>
                                    <p class="mb-2">Có thu cũ đổi mới kg shop</p>
                                    <p class="text-muted small mb-0">
                                        <i class="ri-time-line"></i> Đánh giá đã đăng vào 3 tháng trước
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Review 4 -->
                        <div class="review-item pb-3 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="review-avatar me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-600" 
                                         style="width: 40px; height: 40px; background-color: #d32f2f;">A</div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-600 mb-1">
                                        Anh Quang
                                        <span class="badge ms-2" style="background-color: #00713b; color: white; font-size: 10px;">
                                            <i class="ri-checkbox-circle-fill"></i> Đã mua tại CellphoneS
                                        </span>
                                    </h6>
                                    <div class="mb-2">
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <i class="ri-star-fill" style="color: #ffc107;"></i>
                                        <span class="ms-2 badge" style="background-color: #00713b; color: white; font-size: 11px;">Tuyệt vời</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge me-1" style="background-color: #e8f5e9; color: #00713b; border: 1px solid #00713b;">Thời lượng pin Cực khoẻ</span>
                                        <span class="badge me-1" style="background-color: #e8f5e9; color: #00713b; border: 1px solid #00713b;">Độ chỉ số sức khoẻ Chính xác tuyệt đối</span>
                                        <span class="badge me-1" style="background-color: #e8f5e9; color: #00713b; border: 1px solid #00713b;">Tiện ích thông minh Đa dạng</span>
                                        <span class="badge me-1" style="background-color: #e8f5e9; color: #00713b; border: 1px solid #00713b;">Cảm giác đeo Rất thoải mái</span>
                                    </div>
                                    <p class="mb-2">Sản phẩm tốt. Thời Trang. Nhiều tiện ích thông minh.</p>
                                    <p class="mb-2">Tuy nhiên, dồng ở của tôi không báo khi có cuộc gọi zalo (cuộc gọi qua sim và tin nhắn zalo thì vẫn báo). Huawei Watch Fit 4 có dùng khí bơi được không? Bản Pro thì nói có hỗ trợ lặn sâu 40m. Bản thường không nói rõ việc sử dụng dưới nước.</p>
                                    <p class="text-muted small mb-0">
                                        <i class="ri-time-line"></i> Đánh giá đã đăng vào 3 tháng trước
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- View More Button -->
                        <div class="text-center mt-4">
                            <button class="btn btn-view-more px-4 py-2">
                                Xem tất cả đánh giá
                                <i class="ri-arrow-right-s-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div> 
        </div>

    <!-- Footer --> 
    <div class="mt-3" style="margin-left: 10px"> 
        @include('partials.footer')
    </div>
@endsection 

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
function changeMainImage(img) {
    document.getElementById('mainImage').src = img.src;
    document.querySelectorAll('.product-images img').forEach(i => i.classList.remove('active'));
    img.classList.add('active');
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    input.value = parseInt(input.value) + 1;
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function addToCart() {
    const quantity = document.getElementById('quantity').value;
    alert('Đã thêm ' + quantity + ' sản phẩm vào giỏ hàng!');
    // Add AJAX call here to add to cart
}
</script>
@endpush
