<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::query();

        // Tìm kiếm
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('TieuDe', 'LIKE', "%{$search}%")
                    ->orWhere('LienKet', 'LIKE', "%{$search}%");
            });
        }

        // Lọc theo vị trí
        if ($request->filled('vi_tri')) {
            $query->where('ViTri', $request->vi_tri);
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('TrangThai', $request->trang_thai);
        }

        $banners = $query->orderBy('ThuTu')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json($banners);
        }

        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TieuDe' => 'required|string|max:255',
            'HinhAnh' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'LienKet' => 'nullable|string|max:500',
            'ViTri' => 'required|in:Trang chủ,Sản phẩm,Khuyến mãi,Sidebar',
            'ThuTu' => 'required|integer|min:0',
            'TrangThai' => 'required|boolean'
        ]);

        // Upload hình ảnh
        if ($request->hasFile('HinhAnh')) {
            $image = $request->file('HinhAnh');
            $imageName = time() . '_' . Str::slug($validated['TieuDe']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/banners'), $imageName);
            $validated['HinhAnh'] = $imageName;
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Thêm banner thành công!');
    }

    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return response()->json($banner);
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'TieuDe' => 'required|string|max:255',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'LienKet' => 'nullable|string|max:500',
            'ViTri' => 'required|in:Trang chủ,Sản phẩm,Khuyến mãi,Sidebar',
            'ThuTu' => 'required|integer|min:0',
            'TrangThai' => 'required|boolean'
        ]);

        // Upload hình ảnh mới nếu có
        if ($request->hasFile('HinhAnh')) {
            // Xóa ảnh cũ
            if ($banner->HinhAnh && file_exists(public_path('uploads/banners/' . $banner->HinhAnh))) {
                unlink(public_path('uploads/banners/' . $banner->HinhAnh));
            }

            $image = $request->file('HinhAnh');
            $imageName = time() . '_' . Str::slug($validated['TieuDe']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/banners'), $imageName);
            $validated['HinhAnh'] = $imageName;
        } else {
            unset($validated['HinhAnh']);
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        // Xóa ảnh
        if ($banner->HinhAnh && file_exists(public_path('uploads/banners/' . $banner->HinhAnh))) {
            unlink(public_path('uploads/banners/' . $banner->HinhAnh));
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Xóa banner thành công!');
    }

    public function toggleStatus($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->TrangThai = !$banner->TrangThai;
        $banner->save();

        return response()->json([
            'success' => true,
            'status' => $banner->TrangThai,
            'message' => 'Cập nhật trạng thái thành công!'
        ]);
    }
}
