<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use App\Models\LoaiSanPham;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoaiSanPhamController extends Controller
{
    /**
     * Hiển thị danh sách loại sản phẩm cùng bộ lọc và phân trang.
     */
    public function index(Request $request)
    {
        $query = LoaiSanPham::with('danhMuc')->withCount('sanPham');

        if ($search = $request->input('search')) {
            $query->where('TenLoai', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('TrangThai', $request->status);
        }

        if ($request->filled('parent')) {
            $query->where('IDDanhMuc', $request->parent);
        }

        $sortBy = $request->input('sort_by', 'ID');
        $sortDirection = $request->input('sort_direction', 'desc');

        $allowedSorts = ['ID', 'TenLoai', 'san_pham_count'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'ID';
        }

        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }

        if ($sortBy === 'san_pham_count') {
            $query->orderBy('san_pham_count', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $perPage = (int) $request->input('per_page', 10);
        $perPageOptions = [5, 10, 15, 20];
        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $productTypes = $query->paginate($perPage)->withQueryString();
        $danhMucs = DanhMuc::orderBy('TenDanhMuc')->get();

        $stats = [
            'total' => LoaiSanPham::count(),
            'active' => LoaiSanPham::where('TrangThai', 1)->count(),
            'inactive' => LoaiSanPham::where('TrangThai', 0)->count(),
        ];

        return view('admin.categories.index', compact('productTypes', 'danhMucs', 'stats', 'perPageOptions'));
    }

    /**
     * Thêm mới một loại sản phẩm.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenLoai' => [
                'required',
                'string',
                'max:255',
                Rule::unique('LoaiSanPham', 'TenLoai')->where(function ($query) use ($request) {
                    return $query->where('IDDanhMuc', $request->input('IDDanhMuc'));
                }),
            ],
            'IDDanhMuc' => ['required', 'exists:DanhMuc,ID'],
            'TrangThai' => ['required', 'in:0,1'],
        ], [
            'TenLoai.required' => 'Vui lòng nhập tên loại sản phẩm',
            'TenLoai.unique' => 'Tên loại đã tồn tại trong danh mục này',
            'IDDanhMuc.required' => 'Vui lòng chọn danh mục gốc',
            'TrangThai.required' => 'Vui lòng chọn trạng thái',
        ]);

        LoaiSanPham::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm loại sản phẩm thành công!');
    }

    /**
     * Thêm nhiều loại sản phẩm cùng lúc.
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'IDDanhMuc' => ['required', 'exists:DanhMuc,ID'],
            'TrangThai' => ['required', 'in:0,1'],
            'TenLoaiList' => ['required', 'string', 'max:5000'],
        ], [
            'TenLoaiList.required' => 'Vui lòng nhập danh sách tên loại (mỗi dòng một tên)',
        ]);

        $names = collect(preg_split("/\r\n|\r|\n/", $validated['TenLoaiList']))
            ->map(function ($name) {
                return trim($name);
            })
            ->filter();

        if ($names->isEmpty()) {
            return back()->withInput()->with('error', 'Danh sách tên không hợp lệ.');
        }

        $created = 0;
        $skipped = [];

        foreach ($names as $name) {
            $exists = LoaiSanPham::where('IDDanhMuc', $validated['IDDanhMuc'])
                ->where('TenLoai', $name)
                ->exists();

            if ($exists) {
                $skipped[] = $name;
                continue;
            }

            LoaiSanPham::create([
                'TenLoai' => $name,
                'IDDanhMuc' => $validated['IDDanhMuc'],
                'TrangThai' => $validated['TrangThai'],
            ]);
            $created++;
        }

        if ($created === 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Không có loại sản phẩm nào được thêm mới. Tất cả đều trùng lặp.')
                ->with('skipped', $skipped);
        }

        $message = "Đã thêm {$created} loại sản phẩm.";
        if (!empty($skipped)) {
            $message .= ' Một số tên đã tồn tại và bị bỏ qua.';
        }

        return redirect()->route('admin.categories.index')
            ->with('success', $message)
            ->with('skipped', $skipped);
    }

    /**
     * Cập nhật thông tin loại sản phẩm.
     */
    public function update(Request $request, $id)
    {
        $productType = LoaiSanPham::findOrFail($id);

        $validated = $request->validate([
            'TenLoai' => [
                'required',
                'string',
                'max:255',
                Rule::unique('LoaiSanPham', 'TenLoai')
                    ->ignore($productType->ID, 'ID')
                    ->where(function ($query) use ($request) {
                        return $query->where('IDDanhMuc', $request->input('IDDanhMuc'));
                    }),
            ],
            'IDDanhMuc' => ['required', 'exists:DanhMuc,ID'],
            'TrangThai' => ['required', 'in:0,1'],
        ]);

        $productType->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật loại sản phẩm thành công!');
    }

    /**
     * Xóa loại sản phẩm nếu không còn sản phẩm con.
     */
    public function destroy($id)
    {
        $productType = LoaiSanPham::withCount('sanPham')->findOrFail($id);

        if ($productType->san_pham_count > 0) {
            return back()->with('error', 'Không thể xóa vì còn sản phẩm thuộc loại này.');
        }

        $productType->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Xóa loại sản phẩm thành công!');
    }

    /**
     * Trả về chi tiết loại sản phẩm dưới dạng JSON.
     */
    public function show($id)
    {
        $productType = LoaiSanPham::with('danhMuc')->withCount('sanPham')->findOrFail($id);

        if (!request()->wantsJson()) {
            return redirect()->route('admin.categories.index');
        }

        return response()->json([
            'ID' => $productType->ID,
            'TenLoai' => $productType->TenLoai,
            'IDDanhMuc' => $productType->IDDanhMuc,
            'TrangThai' => (int) $productType->TrangThai,
            'san_pham_count' => $productType->san_pham_count,
            'danh_muc' => $productType->danhMuc ? $productType->danhMuc->TenDanhMuc : null,
        ]);
    }
}
