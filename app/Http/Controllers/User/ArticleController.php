<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the published articles.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $articles = BaiViet::with('nguoiDung')
            ->where('TrangThai', 1)
            ->when($search, function ($query) use ($search) {
                $query->where('TieuDe', 'like', "%{$search}%");
            })
            ->orderByDesc('NgayTao')
            ->paginate(9)
            ->withQueryString();

        $highlights = BaiViet::where('TrangThai', 1)
            ->orderByDesc('LuotXem')
            ->orderByDesc('NgayTao')
            ->limit(4)
            ->get();

        return view('user.articles.index', [
            'articles' => $articles,
            'highlights' => $highlights,
            'search' => $search,
        ]);
    }

    /**
     * Display the specified article details.
     */
    public function show(string $slug)
    {
        $article = BaiViet::with(['nguoiDung', 'danhMuc'])
            ->where('Slug', $slug)
            ->where('TrangThai', 1)
            ->firstOrFail();

        // Increase view counter
        $article->increment('LuotXem');

        $related = BaiViet::where('TrangThai', 1)
            ->where('ID', '!=', $article->ID)
            ->when($article->IDDanhMuc, function ($query) use ($article) {
                $query->where('IDDanhMuc', $article->IDDanhMuc);
            })
            ->orderByDesc('NgayTao')
            ->limit(4)
            ->get();

        return view('user.articles.show', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}
