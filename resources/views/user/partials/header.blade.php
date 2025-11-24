<header class="header">
    <div class="container-fluid" style="background: var(--bg-primary)">
        <div class="container">
            <div class="row">
                <div class="navbar navbar-expand-sm" style="margin-left: 4px; margin-top: 4px">
                    <div class="col-3">
                        <a href="{{ url('/') }}" class="navbar-brand text-white">
                            <img src="{{ asset('template/Assets/Images/logo5.png') }}" alt="" style="height: 40px; width: 320px; object-fit: cover;">
                        </a>
                    </div>

                    <form class="col-6 d-flex align-items-center" action="{{ route('user.search') }}" method="GET">
                        <button type="submit" style="margin-right: -40px; border: none; background-color: transparent; z-index: 2">
                            <i class="ri-search-line fs-22-t" style="color: green"></i>
                        </button>
                        <input type="search" name="q" class="form-control w-100 header-search" style="padding-left: 42px" placeholder="Bạn tìm gì ở Agrigo Shop" />
                        <a href="{{ route('user.cart.index') }}" class="nav-link text-white align-items-centers cart">
                            <i class="ri-shopping-cart-line d-flex align-content-center justify-content-center"></i>
                            <span data-cart-count-target="true" style="top: 0; text-align: center;">{{ session('cart_count', 0) }}</span>
                        </a>
                    </form>

                    <div class="col-3">
                        <ul class="navbar-nav" style="float: right">
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white location" style="padding-right: 3px">
                                    <i class="ri-map-pin-line fs-17-t"></i>
                                    <span>Địa chỉ:</span>
                                    <span class="fw-700" style="margin-left: 4px; color: rgb(255 255 255 / 1)">Thành phố Hồ Chí Minh, P5, Q8</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-3" style="margin-left: -12px; position: relative;">
                    <div class="category-menu-trigger d-flex align-items-center py-0 text-white bg-menu" style="border-top-left-radius: 8px; border-top-right-radius: 8px; width: 300.5px; cursor: pointer;">
                        <i class="ri-menu-line mx-2" style="font-size: 22px"></i>
                        <span class="text-uperc fw-500 fs-16-t">Danh mục sản phẩm</span>
                    </div>
                    
                    <!-- Dropdown Menu -->
                    @include('user.partials.sidebar-dropdown')
                </div>
                <div class="col-7">
                    <div></div>
                </div>
                <div class="col-2" style="padding-left: 0; position: relative; right: -24px">
                    <div class="float-end">
                        <div class="user-menu-wrapper" style="position: relative;">
                            <div class="d-flex bg-menu text-white align-items-center text-center px-2 user-menu-trigger" style="height: 33px; border-top-left-radius: 8px; border-top-right-radius: 8px; cursor: pointer;">
                                <i class="ri-user-line" style="display: flex; font-size: 18px; align-items: center; justify-content: center; margin-bottom: -2px"></i>
                                @if(auth_user())
                                    <span class="d-block text-white" style="padding-left: 5px; user-select: none;">
                                        {{ auth_user()->TenNguoiDung }}
                                    </span>
                                    <i class="ri-arrow-down-s-line" style="font-size: 16px; margin-left: 3px; transition: transform 0.3s;"></i>
                                @else
                                    <a href="{{ route('login') }}" class="d-block text-white" style="text-decoration: none; padding-left: 5px;">
                                        Đăng nhập
                                    </a>
                                @endif
                            </div>
                            
                            @if(auth_user())
                                <!-- Dropdown Menu -->
                                <div class="user-dropdown" style="
                                    position: absolute;
                                    top: 100%;
                                    right: 0;
                                    background: white;
                                    border-radius: 8px;
                                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                                    min-width: 200px;
                                    opacity: 0;
                                    visibility: hidden;
                                    transform: translateY(-10px);
                                    transition: all 0.3s ease;
                                    z-index: 10000;
                                    margin-top: 5px;
                                ">
                                    <div style="padding: 10px 0;">
                                        <a href="{{ route('user.profile') }}" style="
                                            display: flex;
                                            align-items: center;
                                            padding: 10px 20px;
                                            color: #333;
                                            text-decoration: none;
                                            transition: background 0.2s;
                                        " class="dropdown-item-custom">
                                            <i class="ri-user-line" style="font-size: 18px; margin-right: 10px; color: #4CAF50;"></i>
                                            <span>Tài khoản của tôi</span>
                                        </a>
                                        <div style="height: 1px; background: #eee; margin: 5px 0;"></div>
                                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" style="
                                                width: 100%;
                                                display: flex;
                                                align-items: center;
                                                padding: 10px 20px;
                                                color: #f44336;
                                                text-decoration: none;
                                                background: none;
                                                border: none;
                                                cursor: pointer;
                                                transition: background 0.2s;
                                                text-align: left;
                                            " class="dropdown-item-custom">
                                                <i class="ri-logout-box-line" style="font-size: 18px; margin-right: 10px;"></i>
                                                <span>Đăng xuất</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    .user-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 9999;
        margin-top: 5px;
    }
    
    .dropdown-item-custom:hover {
        background-color: #f5f5f5;
    }
    
    .user-menu-wrapper.active .user-dropdown {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
    }
    
    .user-menu-wrapper.active .ri-arrow-down-s-line {
        transform: rotate(180deg);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuWrapper = document.querySelector('.user-menu-wrapper');
        const userMenuTrigger = document.querySelector('.user-menu-trigger');
        const userDropdown = document.querySelector('.user-dropdown');
        
        if (userMenuTrigger && userDropdown) {
            // Toggle dropdown khi click
            userMenuTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenuWrapper.classList.toggle('active');
            });
            
            // Đóng dropdown khi click ra ngoài
            document.addEventListener('click', function(e) {
                if (!userMenuWrapper.contains(e.target)) {
                    userMenuWrapper.classList.remove('active');
                }
            });
            
            // Ngăn đóng dropdown khi click vào bên trong
            userDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>
