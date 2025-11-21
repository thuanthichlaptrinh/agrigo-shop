<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhaCungCap;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NhaCungCapController extends Controller
{
    /**
     * Danh sách nhà cung cấp với tìm kiếm, lọc, phân trang, sắp xếp.
     */
    public function index(Request $request)
    {
        $query = NhaCungCap::withCount('sanPham');

        if ($search = $request->input('search')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('TenNhaCungCap', 'like', "%{$search}%")
                    ->orWhere('SDT', 'like', "%{$search}%")
                    ->orWhere('Email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('product_status')) {
            $status = $request->input('product_status');
            if ($status === 'with') {
                $query->has('sanPham');
            } elseif ($status === 'without') {
                $query->doesntHave('sanPham');
            }
        }

        $sortBy = $request->input('sort_by', 'ID');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSorts = ['ID', 'TenNhaCungCap', 'san_pham_count'];
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

        $suppliers = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => NhaCungCap::count(),
            'with_products' => NhaCungCap::has('sanPham')->count(),
            'without_products' => NhaCungCap::doesntHave('sanPham')->count(),
        ];

        return view('admin.suppliers.index', compact('suppliers', 'perPageOptions', 'stats'));
    }

    /**
     * Thêm mới một nhà cung cấp.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenNhaCungCap' => ['required', 'string', 'max:255', Rule::unique('NhaCungCap', 'TenNhaCungCap')],
            'SDT' => ['nullable', 'string', 'max:20'],
            'Email' => ['nullable', 'email', 'max:255', Rule::unique('NhaCungCap', 'Email')],
            'DiaChi' => ['nullable', 'string', 'max:255'],
        ], [
            'TenNhaCungCap.required' => 'Vui lòng nhập tên nhà cung cấp',
            'TenNhaCungCap.unique' => 'Tên nhà cung cấp đã tồn tại',
            'Email.email' => 'Email không hợp lệ',
        ]);

        NhaCungCap::create($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Thêm nhà cung cấp thành công!');
    }

    /**
     * Thêm nhiều nhà cung cấp cùng lúc.
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'SuppliersData' => ['required', 'string', 'max:8000'],
        ], [
            'SuppliersData.required' => 'Vui lòng nhập danh sách nhà cung cấp',
        ]);

        $lines = collect(preg_split("/\r\n|\r|\n/", $validated['SuppliersData']))
            ->map(function ($line) {
                return trim($line);
            })
            ->filter();

        if ($lines->isEmpty()) {
            return back()->withInput()->with('error', 'Danh sách trống hoặc không hợp lệ.');
        }

        $created = 0;
        $skipped = [];

        foreach ($lines as $line) {
            $parts = array_map('trim', explode('|', $line));
            $name = $parts[0] ?? '';
            if ($name === '') {
                $skipped[] = $line;
                continue;
            }

            if (NhaCungCap::where('TenNhaCungCap', $name)->exists()) {
                $skipped[] = $name;
                continue;
            }

            NhaCungCap::create([
                'TenNhaCungCap' => $name,
                'SDT' => ($parts[1] ?? '') !== '' ? $parts[1] : null,
                'Email' => ($parts[2] ?? '') !== '' ? $parts[2] : null,
                'DiaChi' => ($parts[3] ?? '') !== '' ? $parts[3] : null,
            ]);
            $created++;
        }

        if ($created === 0) {
            return redirect()->route('admin.suppliers.index')
                ->with('error', 'Không có nhà cung cấp nào được thêm mới. Tất cả đều trùng hoặc thiếu tên.')
                ->with('skipped', $skipped);
        }

        $message = "Đã thêm {$created} nhà cung cấp.";
        if (!empty($skipped)) {
            $message .= ' Một số dòng bị bỏ qua.';
        }

        return redirect()->route('admin.suppliers.index')->with('success', $message)->with('skipped', $skipped);
    }

    /**
     * Cập nhật thông tin nhà cung cấp.
     */
    public function update(Request $request, $id)
    {
        $supplier = NhaCungCap::findOrFail($id);

        $validated = $request->validate([
            'TenNhaCungCap' => ['required', 'string', 'max:255', Rule::unique('NhaCungCap', 'TenNhaCungCap')->ignore($supplier->ID, 'ID')],
            'SDT' => ['nullable', 'string', 'max:20'],
            'Email' => ['nullable', 'email', 'max:255', Rule::unique('NhaCungCap', 'Email')->ignore($supplier->ID, 'ID')],
            'DiaChi' => ['nullable', 'string', 'max:255'],
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Cập nhật nhà cung cấp thành công!');
    }

    /**
     * Xóa nhà cung cấp nếu không có sản phẩm phụ thuộc.
     */
    public function destroy($id)
    {
        $supplier = NhaCungCap::withCount('sanPham')->findOrFail($id);

        if ($supplier->san_pham_count > 0) {
            return back()->with('error', 'Không thể xóa vì còn sản phẩm thuộc nhà cung cấp này.');
        }

        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Xóa nhà cung cấp thành công!');
    }

    /**
     * Trả về chi tiết nhà cung cấp dạng JSON cho AJAX.
     */
    public function show($id)
    {
        $supplier = NhaCungCap::withCount('sanPham')->findOrFail($id);

        if (!request()->wantsJson()) {
            return redirect()->route('admin.suppliers.index');
        }

        return response()->json([
            'ID' => $supplier->ID,
            'TenNhaCungCap' => $supplier->TenNhaCungCap,
            'SDT' => $supplier->SDT,
            'Email' => $supplier->Email,
            'DiaChi' => $supplier->DiaChi,
            'san_pham_count' => $supplier->san_pham_count,
        ]);
    }
}
