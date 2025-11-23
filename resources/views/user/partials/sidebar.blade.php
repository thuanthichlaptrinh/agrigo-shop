@php
    $categories = collect($sidebarCategories ?? []);
@endphp

{{-- Sidebar Menu - Danh mục sản phẩm --}}
<div class="col-2 slide-menu p-0" style="max-height: calc(100vh); height: calc(100vh) !important;">
    <div class="sidebar-scroll-container">
        <ul class="nav flex-column bg-white sidebar-menu">
        <li class="nav-item">
            <a href="{{ route('user.products.index', ['promotion' => 'flash']) }}" class="nav-link border-b mx-3 p-0 text-dark fw-500 text-uperc fs-14-t d-flex align-items-center justify-content-between" style="text-transform: uppercase; font-weight: 700; padding: 11px 0 !important;">
                <span><i class="ri-fire-fill" style="color: #ff5722; margin-right: 6px;"></i>Khuyến mãi sốc</span>
                <i class="ri-arrow-right-up-line" style="color: #1c8b2f; font-size: 18px;"></i>
            </a>
        </li>

        @forelse($categories as $category)
        <li class="nav-item" data-category-id="{{ $category['id'] }}">
            <a href="{{ route('user.products.index', ['cat' => $category['id']]) }}" class="nav-link border-b mx-3 p-0 text-dark fw-500 text-uperc fs-14-t d-flex align-items-center justify-content-between" style="text-transform: uppercase; font-weight: 700; padding: 11px 0 !important;">
                <span>{{ $category['name'] }}</span>
                <span class="d-flex align-items-center" style="gap: 8px;">
                    @if(($category['subcategories']->count() ?? 0) > 0)
                        <i class="ri-arrow-down-s-line sidebar-toggle" role="button" tabindex="0" aria-label="Mở danh mục con" style="color: silver; font-size: 18px;"></i>
                    @endif
                </span>
            </a>

            @if(($category['subcategories']->count() ?? 0) > 0)
            <ul class="sub-category-list list-unstyled" style="display: none; margin: 0 24px 6px 24px; padding-left: 0;">
                @foreach($category['subcategories'] as $subCategory)
                <li>
                    <a href="{{ route('user.products.index', ['subcat' => $subCategory['id']]) }}" class="d-flex justify-content-between align-items-center sub-category-link" style="padding: 6px 0; font-size: 13px; color: #4b5563; text-decoration: none;">
                        <span>{{ $subCategory['name'] }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @empty
        <li class="nav-item">
            <a href="#" class="nav-link border-b mx-3 p-0 text-dark fw-500 text-uperc fs-14-t" style="text-transform: uppercase; font-weight: 700; padding: 11px 0 !important;">
                Đang cập nhật danh mục
            </a>
        </li>
        @endforelse
        <li class="nav-item">
            <a href="{{ route('user.products.index', ['all' => 1]) }}" class="nav-link border-b mx-3 p-0 text-dark fw-500 text-uperc fs-14-t" style="text-transform: uppercase; font-weight: 700; padding: 11px 0 !important;">
                <span><i class="ri-gift-line" style="color: #ff9800; margin-right: 6px;"></i>Ưu đãi</span>
                <i class="ri-arrow-right-up-line" style="color: #1c8b2f; font-size: 18px; float: right"></i>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.products.index', ['all' => 1]) }}" class="nav-link mx-3 p-0 text-dark fw-500 text-uperc fs-14-t d-flex align-items-center justify-content-between" style="text-transform: uppercase; font-weight: 700; padding: 11px 0 !important;">
                <span><i class="ri-store-2-line" style="color: #ff9800; margin-right: 6px;"></i>Xem 3.298 sản phẩm</span>
                <i class="ri-arrow-right-up-line" style="color: #1c8b2f; font-size: 18px;"></i>
            </a>
        </li>
        </ul>
    </div>
</div>

<style>
    /* Hover effect for sidebar nav links */
    .slide-menu .nav-link {
        transition: all 0.3s ease;
    }
    
    .slide-menu .nav-link:hover {
        color: #007e42 !important;
        background-color: #f8f9fa;
        padding-left: 8px !important;
    }
    
    .slide-menu .nav-link:hover i {
        transition: all 0.3s ease !important;
        color: #007e42 !important;
    }

    .sidebar-scroll-container {
        max-height: calc(100vh - 110px);
        overflow-y: hidden;
    }

    .sidebar-scroll-container::-webkit-scrollbar {
        width: 0;
    }

    .sidebar-scroll-container.overflowing:hover {
        overflow-y: auto;
    }

    .sidebar-scroll-container.overflowing:hover::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-scroll-container.overflowing:hover::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 6px;
    }

    .sidebar-scroll-container.overflowing:hover::-webkit-scrollbar-track {
        background-color: transparent;
    }
</style>
