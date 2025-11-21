<header class="header">
    <div class="container-fluid" style="background: var(--bg-primary)">
        <div class="container">
            <div class="row">
                <div class="navbar navbar-expand-sm" style="margin-left: 4px; margin-top: 4px">
                    <div class="col-3">
                        <a href="{{ url('/') }}" class="navbar-brand text-white">
                            <!-- <b style="color: #ffea09; font-size: 25px">Organic Shop</b> -->
                            <img src="template/Assets/Images/logo5.png" alt="" style="height: 40px; width: 320px; object-fit: cover;">
                        </a>
                    </div>

                    <form class="col-6 d-flex align-items-center" action="{{ route('user.search') }}" method="GET">
                        <button type="submit" style="margin-right: -40px; border: none; background-color: transparent; z-index: 2">
                            <i class="ri-search-line fs-22-t" style="color: green"></i>
                        </button>
                        <input type="search" name="q" class="form-control w-100 header-search" style="padding-left: 42px" placeholder="Bạn tìm gì ở nông sản xanh - nhóm 2" />
                        <a href="{{ route('user.cart.index') }}" class="nav-link text-white align-items-centers cart">
                            <i class="ri-shopping-cart-line d-flex align-content-center justify-content-center"></i>
                            <span>{{ session('cart_count', 0) }}</span>
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
                        <div class="d-flex bg-menu text-white align-items-center text-center px-2" style="height: 33px; border-top-left-radius: 8px; border-top-right-radius: 8px">
                            <i class="ri-user-line" style="display: flex; font-size: 18px; align-items: center; justify-content: center; margin-bottom: -2px"></i>
                            @auth
                                <a href="{{ route('user.profile') }}" class="d-block text-white" style="text-decoration: none; padding-left: 5px">
                                    Tài khoản của {{ Auth::user()->TenNguoiDung }}
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="d-block text-white" style="text-decoration: none; padding-left: 5px">
                                    Đăng nhập
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
