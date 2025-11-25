@extends('user.layouts.app')

@section('title', ($article->TieuDe ?? 'Bài viết') . ' - Organic Blog')

@push('styles')
<style>
    .article-detail-wrapper {
        padding-bottom: 60px;
    }

    .article-hero-card {
        background: linear-gradient(135deg, #0ea5e9 0%, #22c55e 100%);
        border-radius: 34px;
        color: #fff;
        padding: 32px;
        margin-bottom: 30px;
        box-shadow: 0 40px 80px rgba(14, 165, 233, 0.30);
    }

    .article-hero-top {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 18px;
    }

    .article-hero-top h1 {
        font-size: 36px;
        font-weight: 700;
        margin: 10px 0 12px;
        max-width: 720px;
    }

    .article-hero-badge {
        background: rgba(255,255,255,0.15);
        border-radius: 999px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
    }

    .article-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 14px 24px;
        font-size: 14px;
        opacity: 0.95;
    }

    .article-hero-meta span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .article-hero-cover {
        margin-top: 24px;
        border-radius: 24px;
        overflow: hidden;
        border: 2px solid rgba(255,255,255,0.3);
        box-shadow: 0 25px 50px rgba(15, 23, 42, 0.25);
    }

    .article-hero-cover img {
        width: 100%;
        height: 420px;
        object-fit: cover;
        display: block;
    }

    .article-body .article-content-card {
        background: #fff;
        border-radius: 28px;
        padding: 32px;
        box-shadow: 0 35px 60px rgba(15, 23, 42, 0.08);
    }

    .article-content-card .content-tools {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding-bottom: 18px;
        margin-bottom: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .info-pill {
        background: #f1f5f9;
        color: #0f172a;
        font-weight: 600;
        border-radius: 999px;
        padding: 8px 16px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .share-buttons {
        display: flex;
        gap: 10px;
    }

    .share-buttons a {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #0f172a;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .article-detail-content {
        font-size: 16px;
        line-height: 1.8;
        color: #1f2937;
    }

    .article-detail-content img {
        max-width: 100%;
        border-radius: 16px;
        margin: 18px 0;
    }

    .article-detail-content h2,
    .article-detail-content h3,
    .article-detail-content h4 {
        margin-top: 28px;
        font-weight: 700;
        color: #0f172a;
    }

    .article-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .author-card,
    .quick-card {
        background: #fff;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.07);
    }

    .author-card-header {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .author-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .author-card p {
        margin: 12px 0 0;
        color: #475569;
        font-size: 14px;
    }

    .quick-card ul {
        padding-left: 18px;
        margin-bottom: 0;
        color: #475569;
        font-size: 14px;
    }

    .related-section {
        margin-top: 40px;
    }

    .related-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .related-header h3 {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .related-card {
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.07);
        height: 100%;
    }

    .related-card img {
        width: 100%;
        height: 170px;
        object-fit: cover;
    }

    .related-card-body {
        padding: 16px;
    }

    .related-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .related-card-meta {
        font-size: 13px;
        color: #94a3b8;
    }

    @media (max-width: 992px) {
        .article-hero-cover img {
            height: 260px;
        }

        .article-content-card {
            padding: 24px !important;
        }
    }
</style>
@endpush

@php
    $coverImage = !empty($article->HinhAnh)
        ? (\Illuminate\Support\Str::startsWith($article->HinhAnh, ['http://', 'https://']) ? $article->HinhAnh : asset($article->HinhAnh))
        : asset('template/Assets/Images/thumb-2024-26_202410171658026192.jpg');
@endphp

@section('content')
<div class="article-detail-wrapper">
    <section class="article-hero-card">
        <div class="article-hero-top">
            <div>
                <div class="article-hero-badge">{{ $article->danhMuc->TenDanhMuc ?? 'Bài viết' }}</div>
                <h1>{{ $article->TieuDe }}</h1>
                <div class="article-hero-meta">
                    <span><i class="ri-user-line"></i> {{ $article->nguoiDung->TenNguoiDung ?? 'Organic Team' }}</span>
                    <span><i class="ri-time-line"></i> {{ optional($article->NgayTao)->translatedFormat('H:i d/m/Y') }}</span>
                    <span><i class="ri-eye-line"></i> {{ number_format($article->LuotXem ?? 0) }} lượt xem</span>
                </div>
            </div>
        </div>
        <div class="article-hero-cover">
            <img src="{{ $coverImage }}" alt="{{ $article->TieuDe }}">
        </div>
    </section>

    <div class="article-body row g-4">
        <div class="col-lg-8">
            <article class="article-content-card">
                <div class="content-tools">
                    <div class="info-pills">
                        @if($article->danhMuc)
                            <span class="info-pill"><i class="ri-price-tag-3-line"></i> {{ $article->danhMuc->TenDanhMuc }}</span>
                        @endif
                        <span class="info-pill"><i class="ri-timer-line"></i> {{ optional($article->NgayTao)->diffForHumans() }}</span>
                        <span class="info-pill"><i class="ri-eye-line"></i> {{ number_format($article->LuotXem ?? 0) }} lượt xem</span>
                    </div>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener" aria-label="Chia sẻ Facebook"><i class="ri-facebook-fill"></i></a>
                        <a href="https://www.messenger.com/t/?link={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener" aria-label="Chia sẻ Messenger"><i class="ri-messenger-fill"></i></a>
                        <a href="https://zalo.me/share?url={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener" aria-label="Chia sẻ Zalo"><i class="ri-send-plane-fill"></i></a>
                    </div>
                </div>

                <div class="article-detail-content">
                    {!! $article->NoiDung !!}
                </div>
            </article>
        </div>
        <div class="col-lg-4">
            <div class="article-sidebar">
                <div class="author-card">
                    <div class="author-card-header">
                        <div class="author-avatar">
                            {{ mb_substr($article->nguoiDung->TenNguoiDung ?? 'OT', 0, 2) }}
                        </div>
                        <div>
                            <strong>{{ $article->nguoiDung->TenNguoiDung ?? 'Organic Team' }}</strong>
                            <p class="mb-0 text-muted" style="font-size: 13px;">Người chia sẻ kinh nghiệm xanh mỗi ngày</p>
                        </div>
                    </div>
                    <p>Đồng hành cùng Organic Shop mang đến nhiều cảm hứng nấu ăn, dinh dưỡng và thói quen sống khỏe dành cho gia đình bạn.</p>
                </div>

                <div class="quick-card">
                    <h6 class="fw-bold mb-3">Gợi ý nhanh</h6>
                    <ul>
                        <li>Chọn thực phẩm sạch, nguồn gốc rõ ràng.</li>
                        <li>Ưu tiên chế biến trong ngày để giữ dưỡng chất.</li>
                        <li>Kết hợp rau củ đa màu cho mỗi bữa ăn.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if($related->isNotEmpty())
    <div class="related-section">
        <div class="related-header">
            <h3>Bài viết liên quan</h3>
            <a href="{{ route('articles.index') }}" class="text-decoration-none" style="color: #0ea5e9; font-weight: 600;">Xem tất cả</a>
        </div>
        <div class="row g-3">
            @foreach($related as $item)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('articles.show', $item->Slug) }}" class="text-decoration-none">
                    <div class="related-card">
                        <img src="{{ !empty($item->HinhAnh) ? (\Illuminate\Support\Str::startsWith($item->HinhAnh, ['http://', 'https://']) ? $item->HinhAnh : asset($item->HinhAnh)) : asset('template/Assets/Images/thumb-2024-26_202410171658026192.jpg') }}" alt="{{ $item->TieuDe }}">
                        <div class="related-card-body">
                            <div class="related-card-title">{{ $item->TieuDe }}</div>
                            <div class="related-card-meta"><i class="ri-time-line"></i> {{ optional($item->NgayTao)->diffForHumans() }}</div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
