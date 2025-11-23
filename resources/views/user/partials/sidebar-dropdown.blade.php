@php
    $categories = collect($sidebarCategories ?? []);
@endphp

{{-- Sidebar Dropdown Menu for Header --}}
<div class="category-dropdown sidebar-scroll-container " style="position: absolute; top: 100%; left: 12px; width: 300.5px; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; display: none; max-height: calc(100vh - 114px); height: calc(100vh);">
    <ul class="nav flex-column bg-white sidebar-menu" style="padding: 0; margin: 0;">
        <li class="nav-item">
            <a href="{{ route('user.products.index', ['promotion' => 'flash']) }}" class="nav-link border-b mx-3 p-0 text-dark fw-500 text-uperc fs-14-t d-flex align-items-center justify-content-between" style="text-transform: uppercase; font-weight: 700; padding: 11px 0 !important; gap: 8px; transition: all 0.2s;">
                <span><i class="ri-fire-fill" style="color: #ff5722; margin-right: 6px;"></i>Khuyến mãi sốc</span>
                <i class="ri-arrow-right-up-line" style="color: #1c8b2f; font-size: 18px;"></i>
            </a>
        </li>

        @forelse($categories as $category)
        <li class="nav-item" data-category-id="{{ $category['id'] }}">
            <a href="{{ route('user.products.index', ['cat' => $category['id']]) }}" class="nav-link border-b mx-3 p-0 text-dark fw-500 text-uperc fs-14-t d-flex align-items-center justify-content-between" style="text-transform: uppercase; font-weight: 700; padding: 11px 0 !important; gap: 8px; transition: all 0.2s;">
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
            <a href="{{ route('user.products.index', ['all' => 1]) }}" class="nav-link mx-3 p-0 text-dark fw-500 text-uperc fs-14-t d-flex align-items-center justify-content-between" style="text-transform: uppercase; font-weight: 700; padding: 11px 0 !important; transition: all 0.2s;">
                <span><i class="ri-store-2-line" style="color: #ff9800; margin-right: 6px;"></i>Xem 3.298 sản phẩm</span>
                <i class="ri-arrow-right-up-line" style="color: #1c8b2f; font-size: 18px;"></i>
            </a>
        </li>
    </ul>
</div>

@once
    @push('styles')
    <style>
        .sidebar-scroll-container {
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
            background-color: rgba(0, 0, 0, 0.25);
            border-radius: 6px;
        }

        .sidebar-scroll-container.overflowing:hover::-webkit-scrollbar-track {
            background-color: transparent;
        }

        .sidebar-menu .sub-category-list {
            border-left: 2px solid #e2e8f0;
            padding-left: 12px;
        }

        .sidebar-menu .sub-category-link:hover {
            color: #007e42;
        }

        .sidebar-menu .sidebar-toggle.rotate {
            transform: rotate(180deg);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const updateSidebarOverflow = () => {
                document.querySelectorAll('.sidebar-scroll-container').forEach(container => {
                    const needsScroll = container.scrollHeight - 1 > container.clientHeight;
                    container.classList.toggle('overflowing', needsScroll);
                });
            };

            window.__updateSidebarOverflow = updateSidebarOverflow;

            document.querySelectorAll('.sidebar-menu .sidebar-toggle').forEach(function (toggle) {
                toggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    const parentItem = toggle.closest('li');
                    const subList = parentItem ? parentItem.querySelector('.sub-category-list') : null;

                    if (!subList) {
                        return;
                    }

                    const isOpen = subList.style.display === 'block';
                    subList.style.display = isOpen ? 'none' : 'block';

                    if (isOpen) {
                        toggle.classList.remove('ri-arrow-up-s-line');
                        toggle.classList.add('ri-arrow-down-s-line');
                    } else {
                        toggle.classList.remove('ri-arrow-down-s-line');
                        toggle.classList.add('ri-arrow-up-s-line');
                    }

                    requestAnimationFrame(updateSidebarOverflow);
                });
            });

            window.addEventListener('resize', updateSidebarOverflow);
            updateSidebarOverflow();
        });
    </script>
    @endpush
@endonce
