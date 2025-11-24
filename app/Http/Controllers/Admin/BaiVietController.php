<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BaiVietController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BaiViet::with(['nguoiDung', 'danhMuc']);

        if ($search = $request->input('search')) {
            $query->where('TieuDe', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('TrangThai', $request->status);
        }

        if ($request->has('category') && $request->category !== '') {
            $query->where('IDDanhMuc', $request->category);
        }

        $sortBy = $request->input('sort_by', 'ID');
        $sortDirection = $request->input('sort_direction', 'desc');

        $allowedSorts = ['ID', 'TieuDe', 'LuotXem', 'NgayTao'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'ID';
        }
        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortBy, $sortDirection);

        $perPage = (int)$request->input('per_page', 10);
        $perPageOptions = [5, 10, 15, 20];
        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $articles = $query->paginate($perPage)->withQueryString();
        $categories = DanhMuc::all();

        $stats = [
            'total' => BaiViet::count(),
            'active' => BaiViet::where('TrangThai', 1)->count(),
            'inactive' => BaiViet::where('TrangThai', 0)->count(),
        ];

        return view('admin.articles.index', compact('articles', 'categories', 'perPageOptions', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DanhMuc::where('TrangThai', 1)->get();
        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'TieuDe' => ['required', 'string', 'max:255', Rule::unique('BaiViet', 'TieuDe')],
            'IDDanhMuc' => ['required', 'exists:DanhMuc,ID'],
            'MoTaNgan' => ['required', 'string', 'max:500'],
            'NoiDung' => ['required', 'string'],
            'HinhAnh' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'TrangThai' => ['required', 'boolean'],
        ], [
            'TieuDe.required' => 'Vui lòng nhập tiêu đề bài viết',
            'TieuDe.unique' => 'Tiêu đề bài viết đã tồn tại',
            'IDDanhMuc.required' => 'Vui lòng chọn danh mục',
            'IDDanhMuc.exists' => 'Danh mục không tồn tại',
            'MoTaNgan.required' => 'Vui lòng nhập mô tả ngắn',
            'NoiDung.required' => 'Vui lòng nhập nội dung bài viết',
            'HinhAnh.image' => 'File tải lên phải là hình ảnh',
            'HinhAnh.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        ]);

        $validated['Slug'] = Str::slug($validated['TieuDe']);
        $validated['IDNguoiDung'] = Auth::id();
        $validated['NgayTao'] = now();
        $validated['NgayCapNhat'] = now();
        $validated['LuotXem'] = 0;

        if ($request->hasFile('HinhAnh')) {
            $file = $request->file('HinhAnh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/articles'), $filename);
            $validated['HinhAnh'] = 'uploads/articles/' . $filename;
        }

        BaiViet::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Thêm bài viết thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $article = BaiViet::with(['nguoiDung', 'danhMuc'])->findOrFail($id);
        return response()->json($article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $article = BaiViet::findOrFail($id);
        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $article = BaiViet::findOrFail($id);

        $validated = $request->validate([
            'TieuDe' => ['required', 'string', 'max:255', Rule::unique('BaiViet', 'TieuDe')->ignore($article->ID, 'ID')],
            'IDDanhMuc' => ['required', 'exists:DanhMuc,ID'],
            'MoTaNgan' => ['required', 'string', 'max:500'],
            'NoiDung' => ['required', 'string'],
            'HinhAnh' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'TrangThai' => ['required', 'boolean'],
        ], [
            'TieuDe.required' => 'Vui lòng nhập tiêu đề bài viết',
            'TieuDe.unique' => 'Tiêu đề bài viết đã tồn tại',
            'IDDanhMuc.required' => 'Vui lòng chọn danh mục',
            'IDDanhMuc.exists' => 'Danh mục không tồn tại',
            'MoTaNgan.required' => 'Vui lòng nhập mô tả ngắn',
            'NoiDung.required' => 'Vui lòng nhập nội dung bài viết',
            'HinhAnh.image' => 'File tải lên phải là hình ảnh',
            'HinhAnh.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        ]);

        $validated['Slug'] = Str::slug($validated['TieuDe']);
        $validated['NgayCapNhat'] = now();

        if ($request->hasFile('HinhAnh')) {
            // Delete old image if exists
            if ($article->HinhAnh && file_exists(public_path($article->HinhAnh))) {
                unlink(public_path($article->HinhAnh));
            }

            $file = $request->file('HinhAnh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/articles'), $filename);
            $validated['HinhAnh'] = 'uploads/articles/' . $filename;
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $article = BaiViet::findOrFail($id);
        
        if ($article->HinhAnh && file_exists(public_path($article->HinhAnh))) {
            unlink(public_path($article->HinhAnh));
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Xóa bài viết thành công!');
    }
}
