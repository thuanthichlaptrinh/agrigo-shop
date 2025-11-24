@extends('user.layouts.app')

@section('title', 'Thông tin cá nhân - Organic Shop')

@push('styles')
<style>
    /* Custom Pagination */
    .pagination-custom .page-link {
        border: none;
        margin: 0 4px;
        border-radius: 8px;
        color: #666;
        font-weight: 600;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .pagination-custom .page-link:hover {
        background-color: #e8f5e9;
        color: #1c8244;
        transform: translateY(-1px);
    }
    .pagination-custom .page-item.active .page-link {
        background: linear-gradient(135deg, #1c8244 0%, #156634 100%);
        color: white;
        box-shadow: 0 4px 10px rgba(28, 130, 68, 0.2);
    }
    .pagination-custom .page-item.disabled .page-link {
        background-color: #f8f9fa;
        color: #ccc;
        box-shadow: none;
    }
    
    .info .action {
        color: green;
        font-weight: 500;
    }
    .info .nav-link {
        color: black;
        padding-left: 4px;
    }
    .profile-menu .nav-link {
        color: #333;
        transition: all 0.3s;
    }
    .profile-menu .nav-link:hover,
    .profile-menu .nav-link.active {
        color: #1f7a45;
        background-color: #f8f9fa;
    }
    .order-card {
        border: 1px solid #eef1f3;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: #fff;
        overflow: hidden;
    }
    .order-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        border-color: #e0e0e0;
        transform: translateY(-2px);
    }
    .order-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #eef1f3;
        padding: 12px 20px;
    }
    .order-body {
        padding: 20px;
    }
    .order-footer {
        padding: 15px 20px;
        border-top: 1px solid #eef1f3;
        background-color: #fff;
    }
    .dashed-divider {
        border-top: 1px dashed #eef1f3;
        margin: 1rem 0;
        opacity: 1;
    }
    .order-filters .nav-link {
        color: #666;
        border-radius: 30px;
        padding: 8px 18px;
        font-size: 0.9rem;
        margin-right: 8px;
        margin-bottom: 8px;
        border: 1px solid #f0f0f0;
        background: #fff;
        transition: all 0.2s;
    }
    .order-filters .nav-link:hover {
        background-color: #f8f9fa;
        color: #333;
        border-color: #e0e0e0;
    }
    .order-filters .nav-link.active {
        background-color: #e8f5e9;
        color: #1f7a45;
        font-weight: 600;
        border-color: #c8e6c9;
        box-shadow: 0 2px 5px rgba(31, 122, 69, 0.1);
    }
    .wishlist-card {
        border: 1px solid #edf2ee;
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    .wishlist-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }
    .wishlist-card h6 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }
    .notification-card {
        border-radius: 12px;
        border: 1px solid #eef0f3;
    }
    .notification-card.unread {
        background-color: #f1fff1;
        border-color: #b4deb4;
    }
    .avatar-uploader {
        border: 1px dashed #cfd8dc;
        border-radius: 12px;
        padding: 16px;
        background: #fdfdfd;
    }
    .stat-card {
        border-radius: 12px;
        border: 1px solid #ecf2ed;
        background: linear-gradient(135deg, rgba(76,175,80,0.08), rgba(255,255,255,0.9));
    }
    .order-filters .nav-link {
        border: 1px solid #dfe7df;
        border-radius: 999px;
        margin-right: 6px;
        margin-bottom: 6px;
        color: #2e7d32;
        padding: 6px 16px;
        font-weight: 500;
    }
    .order-filters .nav-link.active {
        background-color: #2e7d32;
        color: #fff;
        border-color: #2e7d32;
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.25);
    }
</style>
@endpush

@section('content')
@php
    $currentUser = auth_user();
    $rawAvatar = $currentUser->HinhAnh ?? null;
    $avatarUrl = $rawAvatar
        ? (\Illuminate\Support\Str::startsWith($rawAvatar, ['http://', 'https://', 'data:']) ? $rawAvatar : asset($rawAvatar))
        : asset('template/Assets/Images/user-avatar.png');

    $menuItems = [
        'info' => ['icon' => 'ri-user-line', 'label' => 'Thông tin tài khoản'],
        'orders' => ['icon' => 'ri-shopping-bag-line', 'label' => 'Đơn hàng của tôi'],
        'wishlist' => ['icon' => 'ri-heart-line', 'label' => 'Sản phẩm yêu thích'],
        'notifications' => ['icon' => 'ri-notification-line', 'label' => 'Thông báo'],
        'password' => ['icon' => 'ri-lock-line', 'label' => 'Đổi mật khẩu'],
    ];

    $stats = $stats ?? [
        'orders' => 0,
        'wishlist' => 0,
        'notifications' => 0,
        'unreadNotifications' => 0,
    ];
@endphp

<div class="row" id="profile-dashboard" data-active-section="{{ $activeSection }}">
    <!-- Sidebar -->
    <div class="col-md-3">
        <div class="bg-white p-3 mt-3" style="border-radius: 12px; margin-left: -12px; width: 300.5px">
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    <img src="{{ $avatarUrl }}"
                         style="width: 58px; height: 58px; border-radius: 50%; object-fit: cover"
                         alt="Avatar" />
                </div>
                <div>
                    <h6 class="mb-0 fw-700">{{ $currentUser->TenNguoiDung ?? 'Người dùng' }}</h6>
                    <p class="text-muted mb-0 small">{{ $currentUser->Email ?? 'email@example.com' }}</p>
                </div>
            </div>

            <ul class="nav flex-column profile-menu">
                @foreach($menuItems as $section => $item)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeSection === $section ? 'active' : '' }}" href="{{ route('user.profile', ['section' => $section]) }}">
                            <i class="{{ $item['icon'] }} me-2"></i>
                            {{ $item['label'] }}
                            @if($section === 'notifications' && ($stats['unreadNotifications'] ?? 0) > 0)
                                <span class="badge bg-danger ms-2">{{ $stats['unreadNotifications'] }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ri-logout-box-line me-2"></i>
                        Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-9 mt-3" style="margin-left: -24px;">
        <div class="bg-white p-4" style="border-radius: 12px; margin-right: -36px;">
            <div class="d-flex flex-column gap-3 mb-4">
                <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center">
                    <div>
                        <h4 class="fw-700 mb-1">Trung tâm tài khoản</h4>
                        <p class="text-muted mb-0">Quản lý thông tin cá nhân, đơn hàng, yêu thích, thông báo và bảo mật.</p>
                    </div>
                    <span class="badge bg-light text-dark">
                        Cập nhật: {{ optional($currentUser->NgayCapNhat)->diffForHumans() ?? 'Chưa cập nhật' }}
                    </span>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="stat-card p-3 h-100">
                            <p class="text-muted small mb-1">Đơn hàng</p>
                            <h4 class="mb-0">{{ number_format($stats['orders']) }}</h4>
                            <small class="text-secondary">Đã đặt trên Organic Shop</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 h-100">
                            <p class="text-muted small mb-1">Yêu thích</p>
                            <h4 class="mb-0">{{ number_format($stats['wishlist']) }}</h4>
                            <small class="text-secondary">Sản phẩm đã lưu</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 h-100">
                            <p class="text-muted small mb-1">Thông báo</p>
                            <h4 class="mb-0">{{ number_format($stats['notifications']) }}</h4>
                            <small class="text-secondary">Mới nhất hôm nay</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 h-100">
                            <p class="text-muted small mb-1">Chưa đọc</p>
                            <h4 class="mb-0">{{ number_format($stats['unreadNotifications']) }}</h4>
                            <small class="text-secondary">Thông báo chưa xem</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                @if ($activeSection === 'info')
                    <h5 class="fw-700 mb-3">Thông tin cá nhân</h5>

                    @if ($errors->profileUpdate->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->profileUpdate->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="row g-4 align-items-start">
                        @csrf
                        @method('PUT')

                        <div class="col-lg-4">
                            <div class="avatar-uploader text-center">
                                <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover" data-avatar-preview>
                                <p class="text-muted small mb-2">Ảnh đại diện của bạn</p>
                                <input type="file" name="avatar" class="form-control" accept="image/*" data-avatar-input>
                                <small class="text-muted d-block mt-2">Hỗ trợ JPG, PNG, tối đa 2MB.</small>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-500">Họ và tên</label>
                                    <input type="text" name="TenNguoiDung" class="form-control" value="{{ old('TenNguoiDung', $currentUser->TenNguoiDung ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-500">Số điện thoại</label>
                                    <input type="tel" name="SDT" class="form-control" value="{{ old('SDT', $currentUser->SDT ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-500">Email</label>
                                    <input type="email" class="form-control" value="{{ $currentUser->Email ?? '' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-500">Ngày sinh</label>
                                    <input type="date" name="NgaySinh" class="form-control" value="{{ old('NgaySinh', optional($currentUser->NgaySinh)->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-500">Giới tính</label>
                                    <select name="GioiTinh" class="form-select">
                                        <option value="Nam" {{ old('GioiTinh', $currentUser->GioiTinh ?? '') === 'Nam' ? 'selected' : '' }}>Nam</option>
                                        <option value="Nữ" {{ old('GioiTinh', $currentUser->GioiTinh ?? '') === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                        <option value="Khác" {{ old('GioiTinh', $currentUser->GioiTinh ?? '') === 'Khác' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-500">Địa chỉ nhận hàng</label>
                                    <textarea name="DiaChi" class="form-control" rows="3" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('DiaChi', $currentUser->DiaChi ?? '') }}</textarea>
                                </div>
                                <div class="col-12 d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="ri-save-line me-2"></i>
                                        Lưu thay đổi
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="ri-refresh-line me-2"></i>
                                        Hủy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @elseif ($activeSection === 'orders')
                    <div class="d-flex flex-column gap-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="fw-700 mb-0">Đơn hàng của tôi</h5>
                                <p class="text-muted mb-0">Quản lý mọi đơn hàng theo trạng thái và tìm kiếm sản phẩm.</p>
                            </div>
                            <form method="GET" action="{{ route('user.profile') }}" class="d-flex" style="max-width: 320px;">
                                <input type="hidden" name="section" value="orders">
                                <input type="hidden" name="order_status" value="{{ $orderStatusKey }}">
                                <div class="input-group">
                                    <input type="search" name="order_search" value="{{ $orderSearch }}" class="form-control" placeholder="ID đơn hoặc tên sản phẩm">
                                    <button class="btn btn-outline-success" type="submit"><i class="ri-search-line"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="order-filters nav nav-pills flex-wrap">
                            @foreach(($orderFilterLabels ?? []) as $key => $label)
                                <a href="{{ route('user.profile', ['section' => 'orders', 'order_status' => $key, 'order_search' => $orderSearch]) }}"
                                   class="nav-link {{ $orderStatusKey === $key ? 'active' : '' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @forelse($orders as $order)
                        <div class="order-card mb-4">
                            <div class="order-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <span class="text-muted small d-block text-uppercase fw-600" style="font-size: 11px;">Đơn hàng</span>
                                        <span class="fw-bold text-dark">#{{ $order['code'] }}</span>
                                    </div>
                                    <div class="vr d-none d-sm-block text-muted" style="height: 20px; opacity: 0.2;"></div>
                                    <div class="d-none d-sm-block">
                                        <span class="text-muted small d-block text-uppercase fw-600" style="font-size: 11px;">Ngày đặt</span>
                                        <span class="text-dark">{{ $order['date'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $order['status_color'] }} bg-opacity-10 text-{{ $order['status_color'] }} px-3 py-2 rounded-pill border border-{{ $order['status_color'] }} border-opacity-25">
                                        {{ $order['status'] }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="order-body">
                                @foreach($order['items'] as $item)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="position-relative me-3">
                                            <img src="{{ product_image_url($item['image'] ?? null) }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 style="width: 72px; height: 72px; object-fit: cover; border-radius: 10px;" 
                                                 class="border">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary border border-white" style="font-size: 0.7rem;">
                                                x{{ $item['quantity'] }}
                                            </span>
                                        </div>
                                        <div class="flex-fill">
                                            <h6 class="mb-1 fw-600 text-dark">{{ $item['name'] }}</h6>
                                            <p class="mb-0 text-muted small">{{ $item['unit'] ?? 'Sản phẩm' }}</p>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold text-success">{{ number_format($item['price'], 0, ',', '.') }} đ</span>
                                        </div>
                                    </div>
                                    @if(!$loop->last) <hr class="dashed-divider"> @endif
                                @endforeach
                            </div>

                            <div class="order-footer d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <span class="text-muted me-2">Tổng thành tiền:</span>
                                    <span class="fs-5 fw-bold text-danger">{{ number_format($order['total'], 0, ',', '.') }} đ</span>
                                </div>
                                <div class="d-flex gap-2">
                                    @if($order['can_cancel'])
                                        <form action="{{ route('user.orders.cancel', ['order' => $order['id']]) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?')">
                                            @csrf
                                            <input type="hidden" name="cancel_reason" value="Người dùng yêu cầu hủy">
                                            <button class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-500" type="submit">
                                                <i class="ri-close-circle-line me-1"></i> Hủy đơn
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('user.orders.show', ['order' => $order['id']]) }}" class="btn btn-success btn-sm px-4 rounded-pill fw-500 shadow-sm">
                                        Xem chi tiết <i class="ri-arrow-right-line ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <img src="{{ asset('template/Assets/Images/logo5.png') }}" alt="Không có đơn hàng" style="max-width: 160px; opacity: 0.5;" class="mb-3">
                            <p class="text-muted mb-3">Bạn chưa có đơn hàng nào trong mục này.</p>
                            <a href="{{ route('user.products.index') }}" class="btn btn-success rounded-pill px-4">
                                <i class="ri-shopping-cart-line me-1"></i> Mua sắm ngay
                            </a>
                        </div>
                    @endforelse

                    <div class="mt-4">
                        {{ $orders->links('pagination.custom') }}
                    </div>
                @elseif ($activeSection === 'wishlist')
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="fw-700 mb-0">Sản phẩm yêu thích</h5>
                        <p class="text-muted mb-0">Chỉ hiện khi bạn chọn "Sản phẩm yêu thích".</p>
                    </div>

                    <div class="row g-3">
                        @forelse($wishlistItems as $item)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                                <div class="wishlist-card h-100 p-3 position-relative">
                                    <form action="{{ route('user.wishlist.remove', $item['id']) }}" method="POST" class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light rounded-circle" style="width: 32px; height: 32px; padding: 0;" title="Xóa khỏi yêu thích">
                                            <i class="ri-close-line" style="font-size: 18px;"></i>
                                        </button>
                                    </form>
                                    <img src="{{ product_image_url($item['image'] ?? null) }}" alt="{{ $item['name'] }}" class="w-100 rounded-3 mb-3" style="height: 160px; object-fit: cover;">
                                    <h6 class="fw-600">{{ $item['name'] }}</h6>
                                    <p class="text-success fw-700 mb-1">{{ number_format($item['price'], 0, ',', '.') }} đ / {{ $item['unit'] }}</p>
                                    <small class="text-muted">Đã lưu: {{ $item['added_at'] ?? 'Gần đây' }}</small>
                                    <div class="d-flex gap-2 mt-3">
                                        <a href="{{ route('user.products.detail', ['id' => $item['id']]) }}" class="btn btn-outline-success btn-sm flex-fill">Xem chi tiết</a>
                                        <button class="btn btn-success btn-sm add-to-cart-btn" type="button" data-product-id="{{ $item['id'] }}" data-product-name="{{ $item['name'] }}">
                                            <i class="ri-shopping-cart-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <img src="{{ asset('template/Assets/Images/logo5.png') }}" alt="wishlist" style="max-width: 200px;" class="mb-3">
                                <p class="text-muted mb-1">Bạn chưa lưu sản phẩm nào.</p>
                                <a href="{{ route('user.products.index') }}" class="btn btn-outline-success">Khám phá sản phẩm</a>
                            </div>
                        @endforelse
                    </div>
                @elseif ($activeSection === 'notifications')
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="fw-700 mb-0">Thông báo của bạn</h5>
                        <form action="{{ route('user.notifications.readAll') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm" {{ ($stats['unreadNotifications'] ?? 0) === 0 ? 'disabled' : '' }}>Đánh dấu tất cả đã đọc</button>
                        </form>
                    </div>

                    @forelse($notifications as $notification)
                        <div class="notification-card p-3 mb-3 {{ $notification['is_read'] ? '' : 'unread' }}">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <span class="badge bg-success-subtle text-success mb-2">{{ $notification['type'] }}</span>
                                    <h6 class="mb-1">{{ $notification['title'] }}</h6>
                                    <p class="mb-1 text-muted">{{ $notification['content'] }}</p>
                                    <small class="text-secondary">{{ $notification['relative_time'] ?? $notification['created_at'] }}</small>
                                </div>
                                @if(!$notification['is_read'])
                                    <form action="{{ route('user.notifications.read', ['notification' => $notification['id']]) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-primary">Đã đọc</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <img src="{{ asset('template/Assets/Images/logo5.png') }}" alt="notification" style="max-width: 180px;" class="mb-3">
                            <p class="text-muted mb-0">Chưa có thông báo mới.</p>
                        </div>
                    @endforelse
                @elseif ($activeSection === 'password')
                    <h5 class="fw-700 mb-3">Đổi mật khẩu</h5>

                    @if ($errors->passwordUpdate->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->passwordUpdate->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.change-password.update') }}" method="POST" class="row g-3" autocomplete="off">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label fw-500">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Mật khẩu mới</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Xác nhận mật khẩu mới</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="ri-lock-password-line me-2"></i>
                                Cập nhật mật khẩu
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">Nhập lại</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dashboard = document.getElementById('profile-dashboard');
        if (!dashboard) {
            return;
        }

        const avatarInput = dashboard.querySelector('[data-avatar-input]');
        const avatarPreview = dashboard.querySelector('[data-avatar-preview]');
        if (avatarInput && avatarPreview) {
            avatarInput.addEventListener('change', function () {
                const file = this.files?.[0];
                if (!file) {
                    return;
                }
                const reader = new FileReader();
                reader.onload = (event) => {
                    avatarPreview.src = event.target?.result;
                };
                reader.readAsDataURL(file);
            });
        }

        // Xử lý nút thêm vào giỏ hàng từ wishlist
        const addToCartButtons = dashboard.querySelectorAll('.add-to-cart-btn');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const productName = this.dataset.productName;
                
                if (!productId) {
                    return;
                }

                // Disable button
                this.disabled = true;
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="ri-loader-4-line ri-spin"></i>';

                // Send AJAX request
                fetch('{{ route("user.cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Show success message
                    this.innerHTML = '<i class="ri-check-line"></i>';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-success');
                    
                    // Optional: Update cart count in header if exists
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount && data.count) {
                        cartCount.textContent = data.count;
                    }

                    // Show toast notification using AppAlert component
                    if (data.message && window.AppAlert) {
                        window.AppAlert.show(data.message, { type: 'success' });
                    }

                    // Reset button after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.classList.remove('btn-outline-success');
                        this.classList.add('btn-success');
                        this.disabled = false;
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (window.AppAlert) {
                        window.AppAlert.show('Không thể thêm vào giỏ hàng', { type: 'error' });
                    } else {
                        alert('Không thể thêm vào giỏ hàng');
                    }
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                });
            });
        });
    });
</script>
@endpush
