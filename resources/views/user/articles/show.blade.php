@extends('user.layouts.app')

@section('title', ($article->TieuDe ?? 'Bài viết') . ' - Organic Shop')

@push('styles')
<style>
    .article-detail-page {
        background-color: #f8f9fa;
    }

    /* Breadcrumb */
    .breadcrumb-nav {
        margin-bottom: 20px;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .breadcrumb-nav a {
        color: #6c757d;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-nav a:hover {
        color: #22c55e;
    }

    .breadcrumb-separator {
        margin: 0 8px;
        color: #adb5bd;
    }

    /* Main Content */
    .article-main {
        background: #fff;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .article-header {
        margin-bottom: 30px;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 20px;
    }

    .article-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 15px;
        line-height: 1.3;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .article-featured-img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .article-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
    }

    .article-content p {
        margin-bottom: 20px;
    }

    .article-content h2, 
    .article-content h3 {
        color: #1a1a1a;
        font-weight: 600;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
    }

    .article-content ul, 
    .article-content ol {
        margin-bottom: 20px;
        padding-left: 20px;
    }

    .article-content li {
        margin-bottom: 10px;
    }

    /* Sidebar */
    .sidebar-widget {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .widget-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
        position: relative;
    }

    .widget-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background: #22c55e;
    }

    /* Author Widget */
    .author-profile {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .author-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #adb5bd;
    }

    .author-info h4 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 5px;
        color: #1a1a1a;
    }

    .author-info p {
        font-size: 0.85rem;
        color: #6c757d;
        margin: 0;
    }

    /* Related Articles */
    .related-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .related-item {
        display: flex;
        gap: 15px;
        text-decoration: none;
        group: hover;
    }

    .related-img {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .related-content {
        flex: 1;
    }

    .related-title {
        font-size: 0.95rem;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 5px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s;
    }

    .related-item:hover .related-title {
        color: #22c55e;
    }

    .related-date {
        font-size: 0.8rem;
        color: #adb5bd;
    }

    /* Share Buttons */
    .share-section {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .share-label {
        font-weight: 600;
        color: #1a1a1a;
    }

    .share-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        transition: transform 0.2s;
    }

    .share-btn:hover {
        transform: translateY(-3px);
        color: #fff;
    }

    .share-facebook { background: #1877f2; }
    .share-twitter { background: #1da1f2; }
    .share-pinterest { background: #bd081c; }

</style>
@endpush

@php
    $imageUrl = function ($article) {
        if (!$article || empty($article->HinhAnh)) {
            return asset('template/Assets/Images/thumb-2024-26_202410171658026192.jpg');
        }

        return \Illuminate\Support\Str::startsWith($article->HinhAnh, ['http://', 'https://'])
            ? $article->HinhAnh
            : asset($article->HinhAnh);
    };
@endphp

@section('content')
<div class="article-detail-page" style="margin-left: -12px; margin-right: -12px;">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb-nav pt-3">
            <a href="{{ route('user.home') }}">Trang chủ</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('articles.index') }}">Góc chia sẻ</a>
            <span class="breadcrumb-separator">/</span>
            <span class="text-dark">{{ Str::limit($article->TieuDe, 50) }}</span>
        </div>

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <article class="article-main">
                    <header class="article-header">
                        <h1 class="article-title">{{ $article->TieuDe }}</h1>
                        <div class="article-meta">
                            <div class="meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                    <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                                    <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($article->NgayTao)->format('d/m/Y') }}
                            </div>
                            <div class="meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                                {{ $article->LuotXem }} lượt xem
                            </div>
                        </div>
                    </header>

                    <img src="{{ $imageUrl($article) }}" alt="{{ $article->TieuDe }}" class="article-featured-img">

                    <div class="article-content">
                        {!! $article->NoiDung !!}
                    </div>

                    <div class="share-section">
                        <span class="share-label">Chia sẻ:</span>
                        <a href="#" class="share-btn share-facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                            </svg>
                        </a>
                        <a href="#" class="share-btn share-twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                                <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                            </svg>
                        </a>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="ps-lg-4">
                    <!-- Author Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Tác giả</h3>
                        <div class="author-profile">
                            <div class="author-avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                </svg>
                            </div>
                            <div class="author-info">
                                <h4>{{ $article->nguoiDung->TenNguoiDung ?? 'Admin' }}</h4>
                                <p>Biên tập viên</p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Articles -->
                    @if($related->count() > 0)
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Bài viết liên quan</h3>
                        <div class="related-list">
                            @foreach($related as $item)
                            <a href="{{ route('articles.show', $item->Slug) }}" class="related-item">
                                <img src="{{ $imageUrl($item) }}" alt="{{ $item->TieuDe }}" class="related-img">
                                <div class="related-content">
                                    <h4 class="related-title">{{ $item->TieuDe }}</h4>
                                    <span class="related-date">{{ \Carbon\Carbon::parse($item->NgayTao)->format('d/m/Y') }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
