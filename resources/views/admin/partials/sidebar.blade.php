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
        <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories.index') }}">
                <i class="fa-solid fa-layer-group icon"></i> Danh mục
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
