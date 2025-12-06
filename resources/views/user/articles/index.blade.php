@extends('user.layouts.app')

@section('title', 'Bài viết - Organic Shop')

@push('styles')
<style>
    .article-page {
        padding: 40px 0;
        background-color: #f8f9fa;
    }

    .page-header {
        margin-bottom: 40px;
        text-align: center;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
    }

    /* Search Box */
    .search-box {
        max-width: 500px;
        margin: 30px auto 0;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 15px 20px;
        padding-right: 50px;
        border: 1px solid #e0e0e0;
        border-radius: 50px;
        background: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .search-input:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.15);
    }

    .search-btn {
        position: absolute;
        right: 5px;
        top: 5px;
        height: 46px;
        width: 46px;
        border: none;
        background: #22c55e;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .search-btn:hover {
        background: #16a34a;
        transform: scale(1.05);
    }

    /* Featured/Highlight Section */
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-left: 15px;
        border-left: 4px solid #22c55e;
    }

    .highlight-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        text-decoration: none;
    }

    .highlight-card:hover {
        transform: translateY(-5px);
    }

    .highlight-img {
        height: 160px;
        width: 100%;
        object-fit: cover;
    }

    .highlight-body {
        padding: 15px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .highlight-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 10px;
        line-height: 1.4;
        color: #1a1a1a;
    }

    .highlight-card:hover .highlight-title {
        color: #22c55e;
    }

    .highlight-meta {
        margin-top: auto;
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Main Article Grid */
    .article-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid #f0f0f0;
    }

    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #22c55e;
    }

    .article-img-wrapper {
        position: relative;
        overflow: hidden;
        padding-top: 60%; /* 16:9 Aspect Ratio */
    }

    .article-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .article-card:hover .article-img {
        transform: scale(1.05);
    }

    .article-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .article-date {
        font-size: 0.85rem;
        color: #22c55e;
        font-weight: 500;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .article-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .article-title a {
        color: #1a1a1a;
        text-decoration: none;
        transition: color 0.2s;
    }

    .article-title a:hover {
        color: #22c55e;
    }

    .article-excerpt {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .article-footer {
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .read-more {
        color: #22c55e;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: gap 0.2s;
    }

    .read-more:hover {
        gap: 8px;
        color: #16a34a;
    }

    .article-author {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .author-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #adb5bd;
    }

    /* Pagination */
    .pagination {
        justify-content: center;
        gap: 5px;
    }

    .page-item .page-link {
        border: none;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #1a1a1a;
        font-weight: 500;
        margin: 0 2px;
    }

    .page-item.active .page-link {
        background-color: #22c55e;
        color: white;
    }

    .page-item .page-link:hover:not(.active) {
        background-color: #e9ecef;
        color: #22c55e;
    }
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
<div class="article-page" style="margin-left: -12px; margin-right: -12px">
    <div class="container">
        <!-- Header Section -->
        <div class="page-header">
            <h1 class="page-title">Góc Chia Sẻ</h1>
            <p class="page-subtitle">Khám phá những kiến thức hữu ích về nông sản và sức khỏe</p>
            
            <div class="search-box">
                <form action="{{ route('articles.index') }}" method="GET">
                    <input type="text" name="search" class="search-input" placeholder="Tìm kiếm bài viết..." value="{{ request('search') }}">
                    <button type="submit" class="search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <h2 class="section-title">Bài viết mới nhất</h2>
                
                @if($articles->count() > 0)
                    <div class="row g-4">
                        @foreach($articles as $article)
                        <div class="col-md-6">
                            <div class="article-card">
                                <div class="article-img-wrapper">
                                    <a href="{{ route('articles.show', $article->Slug) }}">
                                        <img src="{{ $imageUrl($article) }}" alt="{{ $article->TieuDe }}" class="article-img">
                                    </a>
                                </div>
                                <div class="article-content">
                                    <div class="article-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                            <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                                            <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($article->NgayTao)->format('d/m/Y') }}
                                    </div>
                                    <h3 class="article-title">
                                        <a href="{{ route('articles.show', $article->Slug) }}">{{ $article->TieuDe }}</a>
                                    </h3>
                                    <p class="article-excerpt">
                                        {{ Str::limit(strip_tags($article->NoiDung), 100) }}
                                    </p>
                                    <div class="article-footer">
                                        <div class="article-author">
                                            <div class="author-avatar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                </svg>
                                            </div>
                                            {{ $article->nguoiDung->TenNguoiDung ?? 'Admin' }}
                                        </div>
                                        <a href="{{ route('articles.show', $article->Slug) }}" class="read-more">
                                            Xem chi tiết 
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        {{ $articles->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <img src="{{ asset('template/images/empty-box.png') }}" alt="No articles" style="width: 100px; opacity: 0.5; margin-bottom: 20px;">
                        <p class="text-muted">Chưa có bài viết nào.</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="ps-lg-4">
                    <!-- Highlights -->
                    <div class="mb-5">
                        <h2 class="section-title">Nổi bật</h2>
                        <div class="d-flex flex-column gap-3">
                            @foreach($highlights as $highlight)
                            <a href="{{ route('articles.show', $highlight->Slug) }}" class="highlight-card">
                                <img src="{{ $imageUrl($highlight) }}" alt="{{ $highlight->TieuDe }}" class="highlight-img">
                                <div class="highlight-body">
                                    <h4 class="highlight-title">{{ $highlight->TieuDe }}</h4>
                                    <div class="highlight-meta">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                            </svg>
                                            {{ $highlight->LuotXem }}
                                        </span>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                                <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                                                <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($highlight->NgayTao)->format('d/m') }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
