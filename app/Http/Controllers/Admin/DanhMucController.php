<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DanhMucController extends Controller
{
    /**
     * Danh sách danh mục với tìm kiếm, lọc, phân trang.
     */
    public function index(Request $request)
    {
        $query = DanhMuc::withCount('loaiSanPham');

        if ($search = $request->input('search')) {
            $query->where('TenDanhMuc', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('TrangThai', $request->status);
        }

        $sortBy = $request->input('sort_by', 'ID');
        $sortDirection = $request->input('sort_direction', 'desc');

        $allowedSorts = ['ID', 'TenDanhMuc', 'ThuTu', 'loai_san_pham_count'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'ID';
        }
        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }

        if ($sortBy === 'loai_san_pham_count') {
            $query->orderBy('loai_san_pham_count', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $perPage = (int)$request->input('per_page', 10);
        $perPageOptions = [5, 10, 15, 20];
        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $categories = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => DanhMuc::count(),
            'active' => DanhMuc::where('TrangThai', 1)->count(),
            'inactive' => DanhMuc::where('TrangThai', 0)->count(),
        ];

            return view('admin.catalog.index', compact('categories', 'perPageOptions', 'stats'));
    }

    /**
     * Lưu danh mục mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenDanhMuc' => ['required', 'string', 'max:255', Rule::unique('DanhMuc', 'TenDanhMuc')],
            'ThuTu' => ['nullable', 'integer', 'min:0'],
            'TrangThai' => ['required', 'in:0,1'],
            'HinhAnh' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'TenDanhMuc.required' => 'Vui lòng nhập tên danh mục',
            'TenDanhMuc.unique' => 'Tên danh mục đã tồn tại',
            'ThuTu.integer' => 'Thứ tự phải là số',
            'TrangThai.required' => 'Vui lòng chọn trạng thái',
        ]);

        $validated['HinhAnh'] = $this->uploadImage($request);

        DanhMuc::create($validated);

        return redirect()->route('admin.catalog.index')->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Thêm nhiều danh mục một lần.
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'TenDanhMucList' => ['required', 'string', 'max:5000'],
            'TrangThai' => ['required', 'in:0,1'],
        ], [
            'TenDanhMucList.required' => 'Vui lòng nhập danh sách tên (mỗi dòng một tên)',
        ]);

        $names = collect(preg_split("/\r\n|\r|\n/", $validated['TenDanhMucList']))
            ->map(function ($name) {
                return trim($name);
            })
            ->filter();

        if ($names->isEmpty()) {
            return back()->withInput()->with('error', 'Danh sách tên không hợp lệ.');
        }

        $created = 0;
        $skipped = [];
        $currentOrder = (int)DanhMuc::max('ThuTu');

        foreach ($names as $name) {
            if (DanhMuc::where('TenDanhMuc', $name)->exists()) {
                $skipped[] = $name;
                continue;
            }

            $currentOrder++;
            DanhMuc::create([
                'TenDanhMuc' => $name,
                'ThuTu' => $currentOrder,
                'TrangThai' => $validated['TrangThai'],
            ]);
            $created++;
        }

        if ($created === 0) {
            return redirect()->route('admin.catalog.index')
                ->with('error', 'Không có danh mục nào được thêm. Tất cả đều trùng.')->with('skipped', $skipped);
        }

        $message = "Đã thêm {$created} danh mục.";
        if (!empty($skipped)) {
            $message .= ' Một số tên đã tồn tại và bị bỏ qua.';
        }

        return redirect()->route('admin.catalog.index')->with('success', $message)->with('skipped', $skipped);
    }

    /**
     * Cập nhật danh mục.
     */
    public function update(Request $request, $id)
    {
        $category = DanhMuc::findOrFail($id);

        $validated = $request->validate([
            'TenDanhMuc' => ['required', 'string', 'max:255', Rule::unique('DanhMuc', 'TenDanhMuc')->ignore($category->ID, 'ID')],
            'ThuTu' => ['nullable', 'integer', 'min:0'],
            'TrangThai' => ['required', 'in:0,1'],
            'HinhAnh' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        if ($request->hasFile('HinhAnh')) {
            $validated['HinhAnh'] = $this->uploadImage($request, $category->HinhAnh);
        } else {
            $validated['HinhAnh'] = $category->HinhAnh;
        }

        $category->update($validated);

        return redirect()->route('admin.catalog.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa danh mục nếu không còn loại sản phẩm con.
     */
    public function destroy($id)
    {
        $category = DanhMuc::withCount('loaiSanPham')->findOrFail($id);

        if ($category->loai_san_pham_count > 0) {
            return back()->with('error', 'Không thể xóa vì còn loại sản phẩm thuộc danh mục này.');
        }

        if ($category->HinhAnh && File::exists(public_path($category->HinhAnh))) {
            File::delete(public_path($category->HinhAnh));
        }

        $category->delete();

        return redirect()->route('admin.catalog.index')->with('success', 'Xóa danh mục thành công!');
    }

    /**
     * Chi tiết danh mục (AJAX).
     */
    public function show($id)
    {
        $category = DanhMuc::withCount('loaiSanPham')->findOrFail($id);

        if (!request()->wantsJson()) {
            return redirect()->route('admin.catalog.index');
        }

        return response()->json([
            'ID' => $category->ID,
            'TenDanhMuc' => $category->TenDanhMuc,
            'ThuTu' => $category->ThuTu,
            'TrangThai' => (int)$category->TrangThai,
            'loai_san_pham_count' => $category->loai_san_pham_count,
            'HinhAnh' => $category->HinhAnh ? asset($category->HinhAnh) : null,
        ]);
    }

    /**
     * Upload hình danh mục.
     */
    protected function uploadImage(Request $request, ?string $oldPath = null)
    {
        if (!$request->hasFile('HinhAnh')) {
            return $oldPath;
        }

        $file = $request->file('HinhAnh');
        $destination = public_path('uploads/categories');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        if ($oldPath && File::exists(public_path($oldPath))) {
            File::delete(public_path($oldPath));
        }

        $filename = 'category_' . now()->format('YmdHis') . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/categories/' . $filename;
    }
}