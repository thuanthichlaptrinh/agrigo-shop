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
    .list .nav-link {
        padding-left: 63px !important;
        padding-right: 63px !important;
        padding-bottom: 14px !important;
        padding-top: 5px !important;
    }
    .list .action {
        border-bottom: 2px solid green;
    }
    .profile-menu .nav-link {
        color: #333;
        transition: all 0.3s;
    }
    .profile-menu .nav-link:hover,
    .profile-menu .nav-link.active {
        color: green;
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="row"> 
    <!-- Sidebar -->
    <div class="col-md-3">
        <div class="bg-white p-3 mt-3" style="border-radius: 8px; margin-left: -12px; width: 300.5px">
            <div class="d-flex align-items-center mb-4"> 
                <div class="me-3">
                    <img src="{{ auth_user()->HinhAnh ?? asset('template/Assets/Images/user-avatar.png') }}" 
                         style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover" 
                         alt="Avatar" />
                </div>
                <div>
                    <h6 class="mb-0 fw-700">{{ auth_user()->TenNguoiDung ?? 'Người dùng' }}</h6>
                    <p class="text-muted mb-0 small">{{ auth_user()->Email ?? 'email@example.com' }}</p>
                </div>
            </div>

            <ul class="nav flex-column profile-menu">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('user.profile') }}">
                        <i class="ri-user-line me-2"></i>
                        Thông tin tài khoản
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.orders.index') }}">
                        <i class="ri-shopping-bag-line me-2"></i>
                        Đơn hàng của tôi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.addresses.index') }}">
                        <i class="ri-map-pin-line me-2"></i>
                        Sổ địa chỉ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.wishlist.index') }}">
                        <i class="ri-heart-line me-2"></i>
                        Sản phẩm yêu thích
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.notifications.index') }}">
                        <i class="ri-notification-line me-2"></i>
                        Thông báo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.change-password') }}">
                        <i class="ri-lock-line me-2"></i>
                        Đổi mật khẩu
                    </a>
                </li>
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
        <div class="bg-white p-4" style="border-radius: 8px; margin-right: -36px;">
            <h4 class="fw-700 mb-4">Thông tin cá nhân</h4>

            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-500">Họ và tên</label>
                        <input type="text" name="TenNguoiDung" class="form-control" value="{{ old('TenNguoiDung', auth_user()->TenNguoiDung ?? '') }}" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500">Số điện thoại</label>
                        <input type="tel" name="SDT" class="form-control" value="{{ old('SDT', auth_user()->SDT ?? '') }}" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-500">Email</label>
                        <input type="email" name="Email" class="form-control" value="{{ old('Email', auth_user()->Email ?? '') }}" required readonly />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500">Ngày sinh</label>
                        <input type="date" name="NgaySinh" class="form-control" value="{{ old('NgaySinh', auth_user()->NgaySinh ? auth_user()->NgaySinh->format('Y-m-d') : '') }}" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-500">Giới tính</label>
                        <select name="GioiTinh" class="form-select">
                            <option value="Nam" {{ (old('GioiTinh', auth_user()->GioiTinh ?? '') == 'Nam') ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ (old('GioiTinh', auth_user()->GioiTinh ?? '') == 'Nữ') ? 'selected' : '' }}>Nữ</option>
                            <option value="Khác" {{ (old('GioiTinh', auth_user()->GioiTinh ?? '') == 'Khác') ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500">Avatar</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*" />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-500">Địa chỉ</label>
                    <textarea name="DiaChi" class="form-control" rows="3">{{ old('DiaChi', auth_user()->DiaChi ?? '') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-2"></i>
                        Lưu thay đổi
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="ri-refresh-line me-2"></i>
                        Hủy bỏ
                    </button>
                </div>
            </form>
        </div>

        <!-- Order History -->
        <div class="bg-white p-4 mt-3" style="border-radius: 8px; margin-right: -36px;">
            <h5 class="fw-700 mb-3">Đơn hàng gần đây</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders ?? [] as $order)
                        <tr>
                            <td>{{ $order['code'] }}</td>
                            <td>{{ $order['created_at'] }}</td>
                            <td>{{ number_format($order['total']) }}đ</td>
                            <td>
                                <span class="badge bg-{{ $order['status_color'] }}">{{ $order['status_text'] }}</span>
                            </td>
                            <td>
                                <a href="{{ route('user.orders.show', $order['id']) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Chưa có đơn hàng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
