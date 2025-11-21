<section id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand">
        <i class="bx bxs-smile icon"></i> ADMIN
    </a>
    <ul class="side-menu">
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-gauge icon"></i> Tổng quan
            </a>
        </li>
        <li class="divider" data-text="main">Main</li>
        <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}">
                <i class="fa-solid fa-users-line icon"></i> Người dùng
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.catalog.*') ? 'active' : '' }}">
            <a href="{{ route('admin.catalog.index') }}">
                <i class="fa-solid fa-layer-group icon"></i> Danh mục
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories.index') }}">
                <i class="fa-solid fa-users-line icon"></i> Loại sản phẩm
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <a href="{{ route('admin.products.index') }}">
                <i class="fa-solid fa-shirt icon"></i> Sản phẩm
            </a>
        </li> 
        <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}">
                <i class="fa-solid fa-briefcase icon"></i> Đơn hàng
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
            <a href="{{ route('admin.suppliers.index') }}">
                <i class="fa-solid fa-chart-area icon"></i> Nhà cung cấp
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
            <a href="{{ route('admin.vouchers.index') }}">
                <i class="fa-solid fa-ticket icon"></i> Voucher
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
            <a href="{{ route('admin.promotions.index') }}">
                <i class="fa-solid fa-bullhorn icon"></i> Khuyến mãi
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <a href="{{ route('admin.notifications.index') }}">
                <i class="fa-solid fa-bell icon"></i> Thông báo
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <a href="{{ route('admin.roles.index') }}">
                <i class="fa-solid fa-user-shield icon"></i> Vai trò
            </a>
        </li>
        <li class="divider" data-text="settings">Cài đặt</li>
        <li>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket icon"></i> Đăng xuất
            </a>
        </li>
    </ul>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</section>
