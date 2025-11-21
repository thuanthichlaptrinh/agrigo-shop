<nav>
    <i class="bx bx-menu toggle-sidebar"></i>
    <form action="{{ route('admin.search') }}" method="GET">
        <div class="form-group">
            <input type="text" name="q" placeholder="Tìm kiếm..." value="{{ request('q') }}" />
            <i class="bx bx-search icon"></i>
        </div>
    </form>
    <a href="{{ route('admin.notifications') }}" class="nav-link">
        <i class="bx bxs-bell icon"></i>
        <span class="badge">{{ auth_user()->thongBao()->where('DaXem', 0)->count() ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.messages') }}" class="nav-link">
        <i class="bx bxs-message-square-dots icon"></i>
        <span class="badge">0</span>
    </a>
    <span class="divider"></span>
    <div class="profile">
        <img src="{{ auth_user()->HinhAnh ?? asset('template/Assets/Images/default-avatar.png') }}" alt="{{ auth_user()->TenNguoiDung }}" />
        <ul class="profile-link">
            <li>
                <a href="{{ route('admin.profile') }}">
                    <i class="bx bxs-user-circle icon"></i> Hồ sơ
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings') }}">
                    <i class="bx bxs-cog"></i> Cài đặt
                </a>
            </li>
            <li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bx bxs-log-out-circle"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </div>
</nav>
