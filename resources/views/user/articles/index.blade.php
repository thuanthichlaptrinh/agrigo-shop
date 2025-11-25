@extends('user.layouts.app')

@section('title', 'Bài viết - Organic Shop')

@push('styles')
<style>
    .article-page {
        padding: 10px 0 40px;
    }

    .article-hero {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 28px;
        padding: 32px;
        color: #fff;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        box-shadow: 0 30px 60px rgba(34, 197, 94, 0.25);
    }

    .article-hero h1 {
        font-size: 34px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .article-hero p {
        margin: 0;
        opacity: 0.92;
    }

    .article-search {
        min-width: 280px;
        flex: 1;
        display: flex;
        justify-content: flex-end;
    }

    .article-search form {
        width: 100%;
        max-width: 360px;
    }

    .article-search input {
        width: 100%;
        border: none;
        border-radius: 16px;
        padding: 14px 16px;
        font-size: 15px;
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.18);
    }

    .article-search button {
        border: none;
        background: #0f172a;
        color: #fff;
        border-radius: 14px;
        padding: 12px 18px;
        font-weight: 600;
        margin-top: 12px;
        width: 100%;
    }

    .article-highlight {
        margin: 28px 0;
        border-radius: 22px;
        background: #fff;
        padding: 22px 26px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    .article-highlight-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 16px;
        color: #0f172a;
    }

    .article-highlight-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .article-highlight-card {
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        min-height: 220px;
        display: flex;
        flex-direction: column;
    }

    .article-highlight-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px rgba(15, 23, 42, 0.12);
    }

    .article-highlight-card img {
        width: 100%;
        height: 130px;
        object-fit: cover;
    }

    .article-highlight-card .info {
        padding: 14px;
        flex: 1;
    }

    .article-highlight-card .info h6 {
        font-size: 15px;
        margin-bottom: 6px;
        color: #0f172a;
        line-height: 1.35;
    }

    .article-highlight-card .info span {
        font-size: 13px;
        color: #64748b;
    }

    .article-card {
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #edf1f7;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.07);
        height: 100%;
        background: #fff;
        display: flex;
        flex-direction: column;
    }

    .article-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .article-card-body {
        padding: 18px 20px 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .article-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 12px;
    }

    .article-card-desc {
        color: #475569;
        font-size: 14px;
        flex: 1;
    }

    .article-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 18px;
        font-size: 13px;
        color: #94a3b8;
    }

    .article-card-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .article-pagination {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 992px) {
        .article-hero {
            flex-direction: column;
            text-align: center;
        }

        .article-search {
            justify-content: center;
        }
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
<div class="article-page">
    <div class="article-hero">
        <div>
            <p style="letter-spacing: 0.2em; text-transform: uppercase; font-weight: 600; opacity: 0.9;">Góc cảm hứng</p>
            <h1>Organic Blog</h1>
            <p>Những câu chuyện bếp núc, bí quyết dinh dưỡng và phong cách sống lành mạnh mỗi ngày.</p>
        </div>
        <div class="article-search">
            <form method="GET" action="{{ route('articles.index') }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Tìm kiếm bài viết...">
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>
    </div>

    @if($highlights->isNotEmpty())
    <div class="article-highlight">
        <div class="article-highlight-title">Nổi bật trong tuần</div>
        <div class="article-highlight-list">
            @foreach($highlights as $highlight)
                <a class="article-highlight-card" href="{{ route('articles.show', $highlight->Slug) }}">
                    <img src="{{ $imageUrl($highlight) }}" alt="{{ $highlight->TieuDe }}">
                    <div class="info">
                        <h6>{{ $highlight->TieuDe }}</h6>
                        <span><i class="ri-time-line"></i> {{ optional($highlight->NgayTao)->diffForHumans() }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="row g-4 mt-2">
        @forelse($articles as $article)
        <div class="col-xl-4 col-md-6">
            <article class="article-card">
                <a href="{{ route('articles.show', $article->Slug) }}">
                    <img src="{{ $imageUrl($article) }}" alt="{{ $article->TieuDe }}">
                </a>
                <div class="article-card-body">
                    <a href="{{ route('articles.show', $article->Slug) }}" class="text-decoration-none">
                        <h2 class="article-card-title">{{ $article->TieuDe }}</h2>
                    </a>
                    <p class="article-card-desc">{{ $article->MoTaNgan ?? \Illuminate\Support\Str::limit(strip_tags($article->NoiDung), 110) }}</p>
                    <div class="article-card-meta">
                        <span><i class="ri-user-line"></i> {{ $article->nguoiDung->TenNguoiDung ?? 'Organic Team' }}</span>
                        <span><i class="ri-time-line"></i> {{ optional($article->NgayTao)->diffForHumans() }}</span>
                    </div>
                </div>
            </article>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 text-muted">
                <i class="ri-article-line" style="font-size: 42px;"></i>
                <p class="mt-3 mb-0">Hiện chưa có bài viết nào phù hợp với tìm kiếm của bạn.</p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="article-pagination">
        {{ $articles->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
