@extends('user.layouts.app')

@section('title', 'Thông tin cá nhân - Organic Shop')

@push('styles')
<style>
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
                        <div class="order-card p-3 mb-3">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <div>
                                    <p class="text-muted small mb-1">Mã đơn</p>
                                    <h6 class="mb-0">{{ $order['code'] }}</h6>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Ngày đặt</p>
                                    <span>{{ $order['date'] ?? 'Đang cập nhật' }}</span>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Tổng tiền</p>
                                    <strong class="text-success">{{ number_format($order['total'], 0, ',', '.') }} đ</strong>
                                </div>
                                <div class="text-end">
                                    <p class="text-muted small mb-1">Trạng thái</p>
                                    <span class="badge bg-{{ $order['status_color'] }}">{{ $order['status'] }}</span>
                                </div>
                            </div>
                            <hr>
                            @foreach($order['items'] as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ product_image_url($item['image'] ?? null) }}" alt="{{ $item['name'] }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" class="me-3">
                                    <div class="flex-fill">
                                        <h6 class="mb-1">{{ $item['name'] }}</h6>
                                        <small class="text-muted">x{{ $item['quantity'] }}</small>
                                    </div>
                                    <div>
                                        <strong>{{ number_format($item['price'], 0, ',', '.') }} đ</strong>
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('user.orders.show', ['order' => $order['id']]) }}" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                                @if($order['can_cancel'])
                                    <form action="{{ route('user.orders.cancel', ['order' => $order['id']]) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?')">
                                        @csrf
                                        <input type="hidden" name="cancel_reason" value="Người dùng yêu cầu hủy trong danh sách">
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Hủy đơn</button>
                                    </form>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm" type="button" disabled>Không thể hủy</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <img src="{{ asset('template/Assets/Images/logo5.png') }}" alt="Không có đơn" style="max-width: 160px;" class="mb-3">
                            <p class="text-muted mb-1">Bạn chưa có đơn hàng nào.</p>
                            <a href="{{ route('user.products.index') }}" class="btn btn-success">Mua sắm ngay</a>
                        </div>
                    @endforelse
                @elseif ($activeSection === 'wishlist')
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="fw-700 mb-0">Sản phẩm yêu thích</h5>
                        <p class="text-muted mb-0">Chỉ hiện khi bạn chọn "Sản phẩm yêu thích".</p>
                    </div>

                    <div class="row g-3">
                        @forelse($wishlistItems as $item)
                            <div class="col-md-4 col-sm-6">
                                <div class="wishlist-card h-100 p-3">
                                    <img src="{{ product_image_url($item['image'] ?? null) }}" alt="{{ $item['name'] }}" class="w-100 rounded-3 mb-3" style="height: 160px; object-fit: cover;">
                                    <h6 class="fw-600">{{ $item['name'] }}</h6>
                                    <p class="text-success fw-700 mb-1">{{ number_format($item['price'], 0, ',', '.') }} đ / {{ $item['unit'] }}</p>
                                    <small class="text-muted">Đã lưu: {{ $item['added_at'] ?? 'Gần đây' }}</small>
                                    <div class="d-flex gap-2 mt-3">
                                        <a href="{{ route('user.products.detail', ['id' => $item['id']]) }}" class="btn btn-outline-success btn-sm flex-fill">Xem chi tiết</a>
                                        <button class="btn btn-success btn-sm" type="button" disabled>Thêm giỏ</button>
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
    });
</script>
@endpush
