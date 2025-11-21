@extends('admin.layouts.app')

@section('title', 'Hồ sơ quản trị')

@push('styles')
<style>
    .profile-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
        animation: fadeIn 0.35s ease;
    }

    .profile-card {
        background: linear-gradient(135deg, #435ebe, #6a7efc);
        border-radius: 24px;
        padding: 32px;
        color: #fff;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 24px;
        box-shadow: 0 20px 40px rgba(67, 94, 190, 0.35);
    }

    .profile-card img {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .profile-meta {
        flex: 1;
        min-width: 240px;
    }

    .profile-meta h2 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 700;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.2);
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .profile-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .profile-actions a {
        padding: 10px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-primary {
        background: #fff;
        color: #435ebe;
        box-shadow: 0 12px 24px rgba(255, 255, 255, 0.25);
    }

    .btn-outline {
        border: 1px solid rgba(255,255,255,0.4);
        color: #fff;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
    }

    .info-card {
        background: #fff;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 20px 35px rgba(0, 10, 60, 0.08);
        border: 1px solid #f1f1fb;
    }

    .info-card h4 {
        margin-bottom: 16px;
        color: #1f2a55;
        font-weight: 700;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .info-list li {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        font-size: 14px;
        color: #4b4d63;
    }

    .info-list span {
        font-weight: 600;
        color: #1f2a55;
    }

    .stat-card {
        background: #f8f9ff;
        border-radius: 18px;
        padding: 20px;
        text-align: center;
        border: 1px dashed #dfe3ff;
    }

    .stat-card h3 {
        margin: 0;
        font-size: 32px;
        color: #435ebe;
    }

    .stat-card p {
        margin: 8px 0 0;
        color: #6d719c;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .profile-card {
            flex-direction: column;
            text-align: center;
        }

        .profile-meta h2 {
            font-size: 24px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-list li {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
@php
    $avatarPath = $user->HinhAnh ? asset($user->HinhAnh) : asset('template/Assets/Images/default-avatar.png');
    $lastUpdated = $user->NgayCapNhat ?? $user->updated_at ?? null;
    $experienceYears = $user->created_at ? max($user->created_at->diffInYears(now()), 1) : 1;
@endphp
<div class="profile-page">
    <div class="profile-card">
        <img src="{{ $avatarPath }}" alt="{{ $user->TenNguoiDung }}" />
        <div class="profile-meta">
            <h2>{{ $user->TenNguoiDung }}</h2>
            <div class="role-badge">
                <i class="fa-solid fa-shield-halved"></i>
                {{ $user->vaiTro->TenVaiTro ?? 'Quản trị viên' }}
            </div>
            <p style="margin-top: 12px; opacity: 0.85; max-width: 480px;">
                Quản lý hệ thống và chịu trách nhiệm kiểm soát dữ liệu người dùng, sản phẩm, đơn hàng trong hệ thống Nông sản xanh.
            </p>
            <div class="profile-actions">
                <a href="{{ route('admin.users.edit', $user->ID) }}" class="btn-primary">
                    <i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa hồ sơ
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn-outline">
                    <i class="fa-solid fa-gauge"></i> Quay lại Dashboard
                </a>
            </div>
        </div>
        <div class="stat-card">
            <h3>{{ $experienceYears }}</h3>
            <p>Năm làm việc</p>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <h4>Thông tin liên hệ</h4>
            <ul class="info-list">
                <li>
                    <span>Email:</span>
                    <p>{{ $user->Email }}</p>
                </li>
                <li>
                    <span>Số điện thoại:</span>
                    <p>{{ $user->SDT ?? 'Chưa cập nhật' }}</p>
                </li>
                <li>
                    <span>Địa chỉ:</span>
                    <p>{{ $user->DiaChi ?? 'Chưa cập nhật' }}</p>
                </li>
                <li>
                    <span>Ngày sinh:</span>
                    <p>{{ optional($user->NgaySinh)->format('d/m/Y') ?? 'Chưa cập nhật' }}</p>
                </li>
            </ul>
        </div>
        <div class="info-card">
            <h4>Bảo mật & quyền hạn</h4>
            <ul class="info-list">
                <li>
                    <span>Vai trò:</span>
                    <p>{{ $user->vaiTro->TenVaiTro ?? '—' }}</p>
                </li>
                <li>
                    <span>Trạng thái:</span>
                    <p>{{ $user->TrangThai ? 'Hoạt động' : 'Bị khóa' }}</p>
                </li>
                <li>
                    <span>Tài khoản tạo lúc:</span>
                    <p>{{ optional($user->created_at)->format('d/m/Y H:i') }}</p>
                </li>
                <li>
                    <span>Lần cập nhật cuối:</span>
                    <p>{{ optional($lastUpdated)->format('d/m/Y H:i') ?? '—' }}</p>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
