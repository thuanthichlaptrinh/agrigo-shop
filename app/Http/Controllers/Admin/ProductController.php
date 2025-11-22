<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HinhAnhSanPham;
use App\Models\LoaiSanPham;
use App\Models\NhaCungCap;
use App\Models\SanPham;
use App\Support\Traits\AccentInsensitiveSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use AccentInsensitiveSearch;

    protected $unitPresets = ['kg', 'g', 'hộp', 'chai', 'túi', 'vỉ', 'bó'];

    public function index(Request $request)
    {
        $query = SanPham::with(['loaiSanPham', 'nhaCungCap'])
            ->withCount('hinhAnh');

        if ($search = trim((string) $request->input('search'))) {
            $keywords = array_filter(preg_split('/\s+/', $search));
            $query->where(function ($outer) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $normalized = $this->normalizeKeyword($keyword);
                    if ($normalized === '') {
                        continue;
                    }

                    $outer->where(function ($inner) use ($normalized) {
                        $pattern = "%{$normalized}%";
                        $inner->whereRaw($this->accentInsensitiveColumn('SanPham.TenSanPham') . ' LIKE ?', [$pattern])
                            ->orWhereRaw($this->accentInsensitiveColumn('SanPham.MoTa') . ' LIKE ?', [$pattern]);
                    });
                }
            });
        }

        if ($request->filled('category')) {
            $query->where('IDLoaiSP', $request->input('category'));
        }

        if ($request->filled('supplier')) {
            $query->where('IDNhaCungCap', $request->input('supplier'));
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('TrangThai', $request->input('status'));
        }

        if ($request->filled('featured') && $request->featured !== '') {
            $query->where('NoiBat', $request->input('featured'));
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'out') {
                $query->where('SoLuongTon', '<=', 0);
            } elseif ($request->stock === 'low') {
                $query->whereBetween('SoLuongTon', [1, 20]);
            } elseif ($request->stock === 'in') {
                $query->where('SoLuongTon', '>', 20);
            }
        }

        if ($request->filled('price_min')) {
            $query->where('Gia', '>=', (float) $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('Gia', '<=', (float) $request->price_max);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('NgayTao', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('NgayTao', '<=', $request->date_to);
        }

        $sortBy = $request->input('sort_by', 'NgayTao');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSorts = ['NgayTao', 'Gia', 'TenSanPham', 'SoLuongTon', 'LuotBan'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'NgayTao';
        }
        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }
        $query->orderBy($sortBy, $sortDirection);

        $perPageOptions = [10, 20, 30, 50];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $products = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => SanPham::count(),
            'active' => SanPham::where('TrangThai', 1)->count(),
            'featured' => SanPham::where('NoiBat', 1)->count(),
            'inStock' => SanPham::where('SoLuongTon', '>', 0)->count(),
        ];

        $categories = LoaiSanPham::orderBy('TenLoai')->get(['ID', 'TenLoai']);
        $suppliers = NhaCungCap::orderBy('TenNhaCungCap')->get(['ID', 'TenNhaCungCap']);

        return view('admin.products.index', [
            'products' => $products,
            'perPageOptions' => $perPageOptions,
            'stats' => $stats,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'unitPresets' => $this->unitPresets,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);
        $validated['NoiBat'] = $request->boolean('NoiBat');
        $validated['TrangThai'] = $request->boolean('TrangThai');

        if ($request->hasFile('HinhAnh')) {
            $validated['HinhAnh'] = $this->saveImage($request->file('HinhAnh'));
        }

        $product = SanPham::create($validated);

        $this->syncGallery($product, $request, true);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function update(Request $request, $id)
    {
        $product = SanPham::with('hinhAnh')->findOrFail($id);
        $validated = $this->validateProduct($request, $product->ID, false);
        $validated['NoiBat'] = $request->boolean('NoiBat');
        $validated['TrangThai'] = $request->boolean('TrangThai');

        if ($request->hasFile('HinhAnh')) {
            $validated['HinhAnh'] = $this->saveImage($request->file('HinhAnh'), $product->HinhAnh);
        } else {
            $validated['HinhAnh'] = $product->HinhAnh;
        }

        $product->update($validated);

        $this->syncGallery($product, $request, false);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = SanPham::with('hinhAnh')->findOrFail($id);

        if ($product->HinhAnh) {
            $this->deleteImage($product->HinhAnh);
        }

        foreach ($product->hinhAnh as $image) {
            $this->deleteImage($image->DuongDan);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm.');
    }

    public function show($id)
    {
        $product = SanPham::with(['loaiSanPham', 'nhaCungCap', 'hinhAnh'])->findOrFail($id);

        if (!request()->wantsJson()) {
            return redirect()->route('admin.products.index');
        }

        return response()->json($product);
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'products' => ['required', 'array', 'min:1', 'max:20'],
            'products.*.TenSanPham' => ['required', 'string', 'max:255'],
            'products.*.Gia' => ['required', 'numeric', 'min:0'],
            'products.*.SoLuongTon' => ['required', 'integer', 'min:0'],
            'products.*.DonViTinh' => ['required', 'string', 'max:30'],
            'products.*.IDLoaiSP' => ['required', 'exists:LoaiSanPham,ID'],
            'products.*.IDNhaCungCap' => ['nullable', 'exists:NhaCungCap,ID'],
            'products.*.XuatXu' => ['nullable', 'string', 'max:100'],
            'products.*.HinhAnh' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'products.*.gallery.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'products.*.MoTa' => ['nullable', 'string'],
            'products.*.HanSuDung' => ['nullable', 'date'],
            'products.*.NoiBat' => ['nullable', 'boolean'],
            'products.*.TrangThai' => ['nullable', 'boolean'],
        ], [
            'products.required' => 'Vui lòng thêm ít nhất một sản phẩm.',
            'products.*.HinhAnh.required' => 'Vui lòng chọn ảnh đại diện cho sản phẩm.',
        ]);

        $created = 0;
        foreach ($request->input('products') as $index => $item) {
            $payload = [
                'TenSanPham' => $item['TenSanPham'],
                'Gia' => $item['Gia'],
                'SoLuongTon' => $item['SoLuongTon'],
                'DonViTinh' => $item['DonViTinh'],
                'IDLoaiSP' => $item['IDLoaiSP'],
                'IDNhaCungCap' => $item['IDNhaCungCap'] ?? null,
                'XuatXu' => $item['XuatXu'] ?? null,
                'MoTa' => $item['MoTa'] ?? null,
                'HanSuDung' => $item['HanSuDung'] ?? null,
                'NoiBat' => isset($item['NoiBat']) && $item['NoiBat'] == '1',
                'TrangThai' => isset($item['TrangThai']) && $item['TrangThai'] == '1',
            ];

            // Upload ảnh đại diện
            if ($request->hasFile("products.{$index}.HinhAnh")) {
                $payload['HinhAnh'] = $this->saveImage($request->file("products.{$index}.HinhAnh"));
            }

            $product = SanPham::create($payload);

            // Tạo ảnh đại diện trong bảng HinhAnhSanPham
            if (isset($payload['HinhAnh'])) {
                $product->hinhAnh()->create([
                    'DuongDan' => $payload['HinhAnh'],
                    'LaChinh' => true,
                ]);
            }

            // Upload gallery
            if ($request->hasFile("products.{$index}.gallery")) {
                foreach ($request->file("products.{$index}.gallery") as $file) {
                    if ($file) {
                        $path = $this->saveImage($file);
                        $product->hinhAnh()->create([
                            'DuongDan' => $path,
                            'LaChinh' => false,
                        ]);
                    }
                }
            }

            $created++;
        }

        return redirect()->route('admin.products.index')->with('success', "Đã thêm {$created} sản phẩm.");
    }

    protected function validateProduct(Request $request, int $ignoreId = 0, bool $requireImage = true): array
    {
        $rules = [
            'TenSanPham' => ['required', 'string', 'max:255'],
            'MoTa' => ['nullable', 'string'],
            'Gia' => ['required', 'numeric', 'min:0'],
            'SoLuongTon' => ['required', 'integer', 'min:0'],
            'DonViTinh' => ['required', 'string', 'max:30'],
            'XuatXu' => ['nullable', 'string', 'max:100'],
            'HanSuDung' => ['nullable', 'date'],
            'NoiBat' => ['sometimes', 'boolean'],
            'TrangThai' => ['sometimes', 'boolean'],
            'IDLoaiSP' => ['required', 'exists:LoaiSanPham,ID'],
            'IDNhaCungCap' => ['nullable', 'exists:NhaCungCap,ID'],
            'HinhAnh' => [$requireImage ? 'required' : 'sometimes', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:HinhAnhSanPham,ID'],
        ];

        return $request->validate($rules, [
            'TenSanPham.required' => 'Tên sản phẩm không được bỏ trống',
            'Gia.required' => 'Giá bán không hợp lệ',
            'HinhAnh.required' => 'Vui lòng chọn hình chính cho sản phẩm',
        ]);
    }

    protected function syncGallery(SanPham $product, Request $request, bool $isNew): void
    {
        if ($request->filled('delete_images')) {
            $ids = array_map('intval', (array) $request->input('delete_images'));
            $images = HinhAnhSanPham::whereIn('ID', $ids)->where('IDSanPham', $product->ID)->get();
            foreach ($images as $image) {
                if ($image->LaChinh) {
                    continue;
                }
                $this->deleteImage($image->DuongDan);
                $image->delete();
            }
        }

        if ($isNew && $product->HinhAnh) {
            $product->hinhAnh()->create([
                'DuongDan' => $product->HinhAnh,
                'LaChinh' => true,
            ]);
        } elseif (!$isNew && $product->HinhAnh) {
            $main = $product->hinhAnh()->where('LaChinh', 1)->first();
            if ($main) {
                $main->update(['DuongDan' => $product->HinhAnh]);
            } else {
                $product->hinhAnh()->create([
                    'DuongDan' => $product->HinhAnh,
                    'LaChinh' => true,
                ]);
            }
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if (!$file) {
                    continue;
                }
                $path = $this->saveImage($file);
                $product->hinhAnh()->create([
                    'DuongDan' => $path,
                    'LaChinh' => false,
                ]);
            }
        }
    }

    protected function saveImage($file, ?string $oldPath = null): string
    {
        $destination = public_path('uploads/products');
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        if ($oldPath && File::exists(public_path($oldPath))) {
            File::delete(public_path($oldPath));
        }

        $filename = 'product_' . now()->format('YmdHis') . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/products/' . $filename;
    }

    protected function deleteImage(?string $path): void
    {
        if (!$path) {
            return;
        }

        $fullPath = public_path($path);
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
