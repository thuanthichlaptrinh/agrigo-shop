@extends('admin.layouts.app')

@section('title', 'Quản lý nhật ký')

@php
    use Illuminate\Support\Str;
    $today = now()->toDateString();
@endphp

@push('styles')
<style>
    .logs-shell {
        padding: 6px 6px 72px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
        align-items: flex-start;
        margin-bottom: 28px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .page-header p {
        color: #64748b;
        margin: 0;
    }

    .page-header .actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-soft {
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        padding: 10px 20px;
        background: #fff;
        color: #64748b;
        font-weight: 600;
    }

    .btn-primary-action {
        border-radius: 999px;
        border: none;
        padding: 12px 22px;
        background: #2563eb;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 15px 35px rgba(37, 99, 235, 0.25);
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    .stat-card span {
        display: block;
        font-size: 13px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 8px;
    }

    .stat-card h3 {
        margin: 0;
        font-size: 32px;
        color: #2563eb;
    }

    .filter-card {
        background: #fff;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 30px;
        box-shadow: 0 30px 80px rgba(15, 23, 42, 0.08);
    }

    .filter-card h5 {
        margin: 0 0 6px;
        font-weight: 700;
    }

    .filter-card p {
        margin: 0;
        color: #94a3b8;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
        margin-top: 20px;
    }

    .filter-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-field label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .filter-field input,
    .filter-field select {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        background: #f8fafc;
        font-size: 14px;
    }

    .filter-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 18px;
        flex-wrap: wrap;
    }

    .quick-filters {
        margin-top: 14px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 14px;
    }

    .quick-filters span {
        font-weight: 600;
        color: #475569;
    }

    .quick-filters a {
        border-radius: 999px;
        border: 1px dashed #cbd5f5;
        padding: 6px 14px;
        color: #2563eb;
        background: #eff6ff;
    }

    .logs-card {
        background: #fff;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 25px 70px rgba(15, 23, 42, 0.07);
    }

    .table-meta {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .table-meta h6 {
        margin: 0;
        font-weight: 700;
    }

    .log-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .log-table thead th {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        padding: 12px 10px;
    }

    .log-table tbody td {
        padding: 16px 10px;
        border-top: 1px solid #f1f5f9;
        vertical-align: top;
    }

    .log-detail {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .log-title {
        font-weight: 600;
        color: #0f172a;
    }

    .log-user {
        font-size: 13px;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .log-context {
        margin: 0;
        color: #94a3b8;
        font-size: 13px;
    }

    .type-pill,
    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .type-pill {
        background: #eef2ff;
        color: #3730a3;
    }

    .status-chip.success {
        background: rgba(22, 163, 74, 0.15);
        color: #15803d;
    }

    .status-chip.danger {
        background: rgba(220, 38, 38, 0.15);
        color: #b91c1c;
    }

    .payload-stack {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .payload-stack span {
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .payload-stack p {
        margin: 0;
        font-size: 13px;
        color: #475569;
    }

    .log-device {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .log-ip {
        font-family: 'JetBrains Mono', 'Fira Code', Consolas, monospace;
        font-size: 13px;
    }

    .log-agent {
        color: #94a3b8;
        font-size: 12px;
    }

    .log-actions {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    .action-btn.info { background: #3b82f6; }
    .action-btn.edit { background: #f59e0b; }
    .action-btn.delete { background: #ef4444; }

    .ui-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
        z-index: 1050;
    }

    .ui-modal.is-open {
        opacity: 1;
        pointer-events: auto;
    }

    .ui-modal__dialog {
        width: min(720px, 100%);
        background: #fff;
        border-radius: 22px;
        padding: 24px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }

    .ui-modal__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .modal-close {
        border: none;
        background: transparent;
        font-size: 24px;
        color: #94a3b8;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
    }

    .form-grid .form-control,
    .form-grid textarea {
        border-radius: 12px;
    }

    .modal-footer {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .log-payload {
        background: #0f172a;
        color: #e2e8f0;
        padding: 16px;
        border-radius: 14px;
        font-size: 13px;
        max-height: 220px;
        overflow: auto;
    }

    .log-timeline {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .log-card {
        display: flex;
        gap: 22px;
        position: relative;
    }

    .log-card__rail {
        width: 32px;
        display: flex;
        justify-content: center;
        position: relative;
    }

    .log-card__rail::after {
        content: '';
        position: absolute;
        top: 14px;
        bottom: -22px;
        width: 2px;
        background: #e2e8f0;
    }

    .log-card:last-child .log-card__rail::after {
        display: none;
    }

    .log-card__dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #94a3b8;
        border: 3px solid #fff;
        box-shadow: 0 0 0 6px rgba(148, 163, 184, 0.18);
        margin-top: 4px;
    }

    .log-card__dot.is-success {
        background: #22c55e;
        box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.25);
    }

    .log-card__dot.is-danger {
        background: #ef4444;
        box-shadow: 0 0 0 6px rgba(239, 68, 68, 0.2);
    }

    .log-card__content {
        flex: 1;
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        border-radius: 22px;
        border: 1px solid #e2e8f0;
        padding: 18px 22px 20px;
        box-shadow: 0 15px 40px rgba(15, 23, 42, 0.07);
    }

    .log-card__header {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }

    .log-card__header h5 {
        margin: 4px 0 0;
        font-size: 19px;
        font-weight: 700;
        color: #0f172a;
    }

    .log-card__eyebrow {
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.18em;
        color: #94a3b8;
        margin: 0;
    }

    .log-card__badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .log-card__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px 24px;
        font-size: 13px;
        color: #475569;
        margin-bottom: 14px;
    }

    .log-card__meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .log-card__payload {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 14px;
    }

    .log-card__payload .payload-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin-bottom: 6px;
    }

    .payload-snippet {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 12px 14px;
        font-size: 13px;
        color: #475569;
        min-height: 78px;
        white-space: pre-wrap;
        word-break: break-word;
        margin: 0;
    }

    .log-card__footer {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
    }

    .log-card__device {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 13px;
        color: #475569;
    }

    .log-card__device small {
        color: #94a3b8;
        max-width: 420px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .log-card__actions {
        display: flex;
        gap: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 48px 16px;
        border: 1px dashed #cbd5f5;
        border-radius: 20px;
        background: #f8fafc;
        color: #94a3b8;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .log-card {
            flex-direction: column;
        }

        .log-card__rail {
            display: none;
        }

        .log-card__content {
            padding: 18px;
        }

        .log-card__footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .log-card__device small {
            white-space: normal;
        }

        .ui-modal__dialog {
            padding: 18px;
        }
    }
</style>
@endpush

@section('content')
<div class="logs-shell">
    <div class="page-header">
        <div>
            <p class="text-uppercase text-muted mb-2" style="letter-spacing:0.08em;">Giám sát hệ thống</p>
            <h1>Nhật ký hoạt động</h1>
            <p>Theo dõi mọi hành động từ người dùng, quản trị và hệ thống.</p>
        </div>
        <div class="actions">
            <a href="{{ route('admin.logs.index') }}" class="btn-soft">
                <i class="fa-solid fa-rotate me-2"></i>Tải lại
            </a>
            <button type="button" class="btn-primary-action" data-open="create-log">
                <i class="fa-solid fa-plus me-2"></i>Thêm nhật ký
            </button>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <span>Tổng nhật ký</span>
            <h3>{{ number_format($stats['total'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <span>Thành công</span>
            <h3>{{ number_format($stats['success'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <span>Thất bại</span>
            <h3>{{ number_format($stats['failed'] ?? 0) }}</h3>
        </div>
    </div>

    <div class="filter-card">
        <div>
            <h5>Bộ lọc nâng cao</h5>
            <p>Thu hẹp phạm vi dữ liệu để tìm đúng sự kiện bạn cần.</p>
        </div>
        <form id="logFilter" method="GET" action="{{ route('admin.logs.index') }}">
            <div class="filter-grid">
                <div class="filter-field">
                    <label for="filter-search">Từ khóa</label>
                    <input id="filter-search" type="text" name="search" value="{{ request('search') }}" placeholder="Tìm hành động, dữ liệu...">
                </div>
                <div class="filter-field">
                    <label for="filter-user">Người dùng</label>
                    <select id="filter-user" name="user_id">
                        <option value="">Tất cả</option>
                        @foreach($users as $user)
                            <option value="{{ $user->ID }}" {{ (string)request('user_id')===(string)$user->ID ? 'selected' : '' }}>{{ $user->TenNguoiDung }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-field">
                    <label for="filter-type">Loại</label>
                    <select id="filter-type" name="type">
                        <option value="">Tất cả</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type')===$type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-field">
                    <label for="filter-result">Kết quả</label>
                    <select id="filter-result" name="result">
                        <option value="">Tất cả</option>
                        @foreach($results as $result)
                            <option value="{{ $result }}" {{ request('result')===$result ? 'selected' : '' }}>{{ $result }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-field">
                    <label for="filter-date-from">Từ ngày</label>
                    <input id="filter-date-from" type="date" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="filter-field">
                    <label for="filter-date-to">Đến ngày</label>
                    <input id="filter-date-to" type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="filter-field">
                    <label for="filter-sort-by">Sắp xếp</label>
                    <select id="filter-sort-by" name="sort_by">
                        <option value="ThoiGian" {{ request('sort_by','ThoiGian')==='ThoiGian' ? 'selected' : '' }}>Theo thời gian</option>
                        <option value="ID" {{ request('sort_by')==='ID' ? 'selected' : '' }}>Theo ID</option>
                    </select>
                </div>
                <div class="filter-field">
                    <label for="filter-sort-direction">Thứ tự</label>
                    <select id="filter-sort-direction" name="sort_direction">
                        <option value="desc" {{ request('sort_direction','desc')==='desc' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="asc" {{ request('sort_direction')==='asc' ? 'selected' : '' }}>Cũ nhất</option>
                    </select>
                </div>
                <div class="filter-field">
                    <label for="filter-per-page">Số dòng / trang</label>
                    <select id="filter-per-page" name="per_page">
                        @foreach($perPageOptions as $option)
                            <option value="{{ $option }}" {{ (int)request('per_page',10)===$option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <a href="{{ route('admin.logs.index') }}" class="btn btn-light">Đặt lại</a>
                <button type="submit" class="btn btn-primary">Áp dụng lọc</button>
            </div>
        </form>
        <div class="quick-filters">
            <span>Gợi ý nhanh:</span>
            <a href="{{ request()->fullUrlWithQuery(['date_from' => $today, 'date_to' => $today]) }}">Hoạt động hôm nay</a>
            <a href="{{ request()->fullUrlWithQuery(['result' => 'Thất bại']) }}">Chỉ thất bại</a>
            <a href="{{ request()->fullUrlWithQuery(['type' => 'Hệ thống']) }}">Sự kiện hệ thống</a>
        </div>
    </div>

    <div class="logs-card">
        <div class="table-meta">
            <div>
                <h6>Dòng thời gian hoạt động</h6>
                <p class="mb-0 text-muted">Mỗi sự kiện được hiển thị như một cột mốc giúp bạn nắm bắt nhanh ngữ cảnh.</p>
            </div>
            <small>Hiển thị {{ $logs->count() }} / {{ $logs->total() }}</small>
        </div>
        <div class="log-timeline">
            @forelse($logs as $log)
                @php
                    $success = $log->KetQua === 'Thành công';
                    $timestamp = optional($log->ThoiGian);
                @endphp
                <article class="log-card">
                    <div class="log-card__rail">
                        <span class="log-card__dot {{ $success ? 'is-success' : 'is-danger' }}"></span>
                    </div>
                    <div class="log-card__content">
                        <header class="log-card__header">
                            <div>
                                <p class="log-card__eyebrow">#{{ $log->ID }} · {{ $timestamp ? $timestamp->format('d/m/Y H:i') : 'Không rõ thời gian' }}</p>
                                <h5>{{ $log->HanhDong }}</h5>
                            </div>
                            <div class="log-card__badges">
                                <span class="type-pill">
                                    <i class="fa-solid fa-layer-group"></i>
                                    {{ $log->Loai }}
                                </span>
                                <span class="status-chip {{ $success ? 'success' : 'danger' }}">
                                    <i class="fa-solid {{ $success ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                                    {{ $log->KetQua }}
                                </span>
                            </div>
                        </header>
                        <div class="log-card__meta">
                            <span><i class="fa-solid fa-user"></i>{{ $log->nguoiDung->TenNguoiDung ?? 'Hệ thống' }}</span>
                            <span><i class="fa-solid fa-network-wired"></i>IP: {{ $log->DiaChiIP ?? '-' }}</span>
                            <span><i class="fa-solid fa-globe"></i>{{ Str::limit($log->TrinhDuyet ?? '---', 90) }}</span>
                        </div>
                        <div class="log-card__payload">
                            <div>
                                <p class="payload-label">Dữ liệu cũ</p>
                                <pre class="payload-snippet">{{ Str::limit($log->DuLieuCu ?? '---', 260) }}</pre>
                            </div>
                            <div>
                                <p class="payload-label">Dữ liệu mới</p>
                                <pre class="payload-snippet">{{ Str::limit($log->DuLieuMoi ?? '---', 260) }}</pre>
                            </div>
                        </div>
                        <footer class="log-card__footer">
                            <div class="log-card__device">
                                <strong>Thiết bị</strong>
                                <span>{{ $log->DiaChiIP ?? '-' }}</span>
                                <small title="{{ $log->TrinhDuyet }}">{{ Str::limit($log->TrinhDuyet ?? '---', 100) }}</small>
                            </div>
                            <div class="log-card__actions">
                                <button type="button" class="action-btn info" data-log-action="view" data-log-id="{{ $log->ID }}" title="Chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button type="button" class="action-btn edit" data-log-action="edit" data-log-id="{{ $log->ID }}" title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" class="action-btn delete" data-log-action="delete" data-log-id="{{ $log->ID }}" title="Xóa">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </footer>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    Không có nhật ký phù hợp với tiêu chí hiện tại.
                </div>
            @endforelse
        </div>
        @if($logs->hasPages())
            <div class="pagination-wrapper pt-3">
                {{ $logs->links('vendor.pagination.admin-users') }}
            </div>
        @endif
    </div>
</div>

{{-- Create modal --}}
<div class="ui-modal" id="logCreateModal" role="dialog" aria-hidden="true" aria-labelledby="logCreateTitle">
    <div class="ui-modal__dialog">
        <div class="ui-modal__header">
            <div>
                <h4 id="logCreateTitle" class="mb-1">Thêm nhật ký</h4>
                <p class="mb-0 text-muted">Ghi lại một hoạt động mới vào hệ thống.</p>
            </div>
            <button type="button" class="modal-close" data-modal-close>&times;</button>
        </div>
        <form id="logCreateForm" method="POST" action="{{ route('admin.logs.store') }}">
            @csrf
            <div class="form-grid mb-3">
                <div>
                    <label class="form-label">Người dùng</label>
                    <select name="IDNguoiDung" class="form-control">
                        <option value="">-- Hệ thống --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->ID }}" {{ old('IDNguoiDung') == $user->ID ? 'selected' : '' }}>{{ $user->TenNguoiDung }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Loại *</label>
                    <select name="Loai" class="form-control" required>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ old('Loai') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Kết quả *</label>
                    <select name="KetQua" class="form-control" required>
                        @foreach($results as $result)
                            <option value="{{ $result }}" {{ old('KetQua') === $result ? 'selected' : '' }}>{{ $result }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Thời gian</label>
                    <input type="datetime-local" name="ThoiGian" class="form-control" value="{{ old('ThoiGian') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Hành động *</label>
                <input type="text" name="HanhDong" class="form-control" value="{{ old('HanhDong') }}" required>
            </div>
            <div class="form-grid mb-3">
                <div>
                    <label class="form-label">Dữ liệu cũ</label>
                    <textarea name="DuLieuCu" class="form-control" rows="3">{{ old('DuLieuCu') }}</textarea>
                </div>
                <div>
                    <label class="form-label">Dữ liệu mới</label>
                    <textarea name="DuLieuMoi" class="form-control" rows="3">{{ old('DuLieuMoi') }}</textarea>
                </div>
            </div>
            <div class="form-grid">
                <div>
                    <label class="form-label">Địa chỉ IP</label>
                    <input type="text" name="DiaChiIP" class="form-control" value="{{ old('DiaChiIP') }}" placeholder="Sẽ tự lấy nếu bỏ trống">
                </div>
                <div>
                    <label class="form-label">Trình duyệt</label>
                    <input type="text" name="TrinhDuyet" class="form-control" value="{{ old('TrinhDuyet') }}" placeholder="Sẽ tự lấy nếu bỏ trống">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-modal-close>Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu nhật ký</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit modal --}}
<div class="ui-modal" id="logEditModal" role="dialog" aria-hidden="true" aria-labelledby="logEditTitle">
    <div class="ui-modal__dialog">
        <div class="ui-modal__header">
            <div>
                <h4 id="logEditTitle" class="mb-1">Chỉnh sửa nhật ký</h4>
                <p class="mb-0 text-muted">Cập nhật nội dung nhật ký đã chọn.</p>
            </div>
            <button type="button" class="modal-close" data-modal-close>&times;</button>
        </div>
        <form id="logEditForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid mb-3">
                <div>
                    <label class="form-label">Người dùng</label>
                    <select name="IDNguoiDung" id="edit_IDNguoiDung" class="form-control">
                        <option value="">-- Hệ thống --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->ID }}">{{ $user->TenNguoiDung }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Loại *</label>
                    <select name="Loai" id="edit_Loai" class="form-control" required>
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Kết quả *</label>
                    <select name="KetQua" id="edit_KetQua" class="form-control" required>
                        @foreach($results as $result)
                            <option value="{{ $result }}">{{ $result }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Thời gian</label>
                    <input type="datetime-local" name="ThoiGian" id="edit_ThoiGian" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Hành động *</label>
                <input type="text" name="HanhDong" id="edit_HanhDong" class="form-control" required>
            </div>
            <div class="form-grid mb-3">
                <div>
                    <label class="form-label">Dữ liệu cũ</label>
                    <textarea name="DuLieuCu" id="edit_DuLieuCu" class="form-control" rows="3"></textarea>
                </div>
                <div>
                    <label class="form-label">Dữ liệu mới</label>
                    <textarea name="DuLieuMoi" id="edit_DuLieuMoi" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="form-grid">
                <div>
                    <label class="form-label">Địa chỉ IP</label>
                    <input type="text" name="DiaChiIP" id="edit_DiaChiIP" class="form-control">
                </div>
                <div>
                    <label class="form-label">Trình duyệt</label>
                    <input type="text" name="TrinhDuyet" id="edit_TrinhDuyet" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-modal-close>Hủy</button>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

{{-- View modal --}}
<div class="ui-modal" id="logViewModal" role="dialog" aria-hidden="true" aria-labelledby="logViewTitle">
    <div class="ui-modal__dialog">
        <div class="ui-modal__header">
            <div>
                <h4 id="logViewTitle" class="mb-1">Chi tiết nhật ký</h4>
                <p class="mb-0 text-muted">Xem toàn bộ thông tin của sự kiện.</p>
            </div>
            <button type="button" class="modal-close" data-modal-close>&times;</button>
        </div>
        <div class="modal-body">
            <p><strong>Hành động:</strong> <span id="view_HanhDong">---</span></p>
            <p><strong>Người dùng:</strong> <span id="view_NguoiDung">---</span></p>
            <p><strong>Loại:</strong> <span id="view_Loai">---</span></p>
            <p><strong>Kết quả:</strong> <span id="view_KetQua">---</span></p>
            <p><strong>Thời gian:</strong> <span id="view_ThoiGian">---</span></p>
            <p><strong>Địa chỉ IP:</strong> <span id="view_DiaChiIP">---</span></p>
            <p><strong>Trình duyệt:</strong> <span id="view_TrinhDuyet">---</span></p>
            <p class="mb-1"><strong>Dữ liệu cũ:</strong></p>
            <pre class="log-payload" id="view_DuLieuCu">---</pre>
            <p class="mb-1"><strong>Dữ liệu mới:</strong></p>
            <pre class="log-payload" id="view_DuLieuMoi">---</pre>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-modal-close>Đóng</button>
        </div>
    </div>
</div>

<form id="logDeleteForm" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const baseUrl = @json(route('admin.logs.index'));
        const toast = window.AppAlert ?? { show: (message) => window.alert(message) };
        const modalStack = {
            active: null,
            open(id) {
                this.close();
                const modal = document.getElementById(id);
                if (!modal) {
                    return;
                }
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                this.active = modal;
            },
            close() {
                if (!this.active) {
                    return;
                }
                this.active.classList.remove('is-open');
                this.active.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                this.active = null;
            },
        };

        document.querySelectorAll('[data-modal-close]').forEach((btn) => {
            btn.addEventListener('click', () => modalStack.close());
        });

        document.querySelectorAll('.ui-modal').forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modalStack.close();
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                modalStack.close();
            }
        });

        document.querySelectorAll('[data-open="create-log"]').forEach((btn) => {
            btn.addEventListener('click', () => modalStack.open('logCreateModal'));
        });

        const editForm = document.getElementById('logEditForm');
        const deleteForm = document.getElementById('logDeleteForm');

        const formatDateForInput = (value) => {
            if (!value) {
                return '';
            }
            const date = new Date(value);
            if (Number.isNaN(date.getTime())) {
                return '';
            }
            const pad = (num) => String(num).padStart(2, '0');
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        };

        const fillViewModal = (log) => {
            document.getElementById('view_HanhDong').textContent = log.HanhDong || '---';
            document.getElementById('view_NguoiDung').textContent = (log.nguoi_dung && log.nguoi_dung.TenNguoiDung) || 'Hệ thống';
            document.getElementById('view_Loai').textContent = log.Loai || '---';
            document.getElementById('view_KetQua').textContent = log.KetQua || '---';
            document.getElementById('view_ThoiGian').textContent = log.ThoiGian || '---';
            document.getElementById('view_DiaChiIP').textContent = log.DiaChiIP || '---';
            document.getElementById('view_TrinhDuyet').textContent = log.TrinhDuyet || '---';
            document.getElementById('view_DuLieuCu').textContent = log.DuLieuCu || '---';
            document.getElementById('view_DuLieuMoi').textContent = log.DuLieuMoi || '---';
        };

        const fillEditModal = (log) => {
            editForm.action = `${baseUrl}/${log.ID}`;
            document.getElementById('edit_IDNguoiDung').value = log.IDNguoiDung ?? '';
            document.getElementById('edit_Loai').value = log.Loai || '';
            document.getElementById('edit_KetQua').value = log.KetQua || '';
            document.getElementById('edit_HanhDong').value = log.HanhDong || '';
            document.getElementById('edit_DuLieuCu').value = log.DuLieuCu || '';
            document.getElementById('edit_DuLieuMoi').value = log.DuLieuMoi || '';
            document.getElementById('edit_DiaChiIP').value = log.DiaChiIP || '';
            document.getElementById('edit_TrinhDuyet').value = log.TrinhDuyet || '';
            document.getElementById('edit_ThoiGian').value = formatDateForInput(log.ThoiGian);
        };

        const fetchLog = async (id) => {
            const response = await fetch(`${baseUrl}/${id}`, {
                headers: { 'Accept': 'application/json' },
            });
            if (!response.ok) {
                throw new Error('Không thể tải dữ liệu nhật ký.');
            }
            return await response.json();
        };

        document.addEventListener('click', async (event) => {
            const trigger = event.target.closest('[data-log-action]');
            if (!trigger) {
                return;
            }

            const id = trigger.dataset.logId;
            const action = trigger.dataset.logAction;
            if (!id) {
                return;
            }

            if (action === 'delete') {
                if (!confirm('Bạn chắc chắn muốn xóa nhật ký này?')) {
                    return;
                }
                deleteForm.action = `${baseUrl}/${id}`;
                deleteForm.submit();
                return;
            }

            try {
                const log = await fetchLog(id);
                if (action === 'view') {
                    fillViewModal(log);
                    modalStack.open('logViewModal');
                } else if (action === 'edit') {
                    fillEditModal(log);
                    modalStack.open('logEditModal');
                }
            } catch (error) {
                toast.show(error.message || 'Đã xảy ra lỗi', { type: 'error' });
            }
        });

        const inlineErrors = @json($errors->all());
        if (inlineErrors.length) {
            inlineErrors.forEach((message) => toast.show(message, { type: 'error', title: 'Lỗi xác thực' }));
        }
    });
</script>
@endpush
