<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use App\Models\LoaiSanPham;
use App\Models\SanPham;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $now = now();

        $promotionScope = function ($query) use ($now) {
            $query->where('KhuyenMai.TrangThai', 1)
                ->where('KhuyenMai.NgayBatDau', '<=', $now)
                ->where('KhuyenMai.NgayKetThuc', '>=', $now);
        };

        $query = SanPham::query()
            ->with(['khuyenMai' => $promotionScope, 'loaiSanPham'])
            ->where('SanPham.TrangThai', 1);

        $categoryName = 'Tất cả sản phẩm';
        $categoryId = $request->filled('category')
            ? (int) $request->input('category')
            : ($request->filled('cat') ? (int) $request->input('cat') : null);
        $subCategoryId = $request->filled('subcat') ? (int) $request->input('subcat') : null;
        $selectedCategoryId = null;
        $selectedSubCategoryId = null;

        if ($subCategoryId) {
            $subCategory = LoaiSanPham::where('TrangThai', 1)->find($subCategoryId);
            if ($subCategory) {
                $categoryName = $subCategory->TenLoai;
                $selectedSubCategoryId = $subCategoryId;
                $selectedCategoryId = $subCategory->IDDanhMuc;
                $query->where('SanPham.IDLoaiSP', $subCategoryId);
            }
        } elseif ($categoryId) {
            $category = DanhMuc::where('TrangThai', 1)->find($categoryId);
            if ($category) {
                $categoryName = $category->TenDanhMuc;
                $selectedCategoryId = $categoryId;
                $query->whereHas('loaiSanPham', fn ($q) => $q->where('IDDanhMuc', $categoryId));
            }
        }

        $sortPrice = $request->input('sort_price');
        if (in_array($sortPrice, ['asc', 'desc'], true)) {
            $query->orderBy('SanPham.Gia', $sortPrice);
        } else {
            $query->orderByDesc('SanPham.NgayTao');
        }

        if ($range = $request->input('price_range')) {
            [$min, $max] = array_pad(explode('-', $range), 2, null);

            if ($min !== null && $min !== '') {
                $query->where('SanPham.Gia', '>=', (float) $min);
            }

            if ($max !== null && $max !== '') {
                $query->where('SanPham.Gia', '<=', (float) $max);
            }
        }

        $promotionFilter = $request->input('promotion');
        if (in_array($promotionFilter, ['yes', 'flash'], true)) {
            $query->whereHas('khuyenMai', $promotionScope);
        }

        $supplierMap = [
            'us' => 'Mỹ',
            'vn' => 'Việt Nam',
        ];

        $supplierKey = $request->input('supplier');
        if ($supplierKey && isset($supplierMap[$supplierKey])) {
            $query->where('SanPham.XuatXu', $supplierMap[$supplierKey]);
        }

        $products = $query->paginate(12)->withQueryString();
        $products->getCollection()->transform(fn (SanPham $product) => $this->formatProduct($product));

        return view('user.products.index', [
            'products' => $products,
            'categoryName' => $categoryName,
            'selectedCategoryId' => $selectedCategoryId,
            'selectedSubCategoryId' => $selectedSubCategoryId,
        ]);
    }

    public function show(int $id)
    {
        return view('user.products.detail', ['product' => ['id' => $id]]);
    }

    protected function formatProduct(SanPham $product): array
    {
        $promotion = $product->khuyenMai->first();
        $pricing = $promotion ? calculate_promotion_pricing($product, $promotion) : null;

        return [
            'id' => $product->ID,
            'name' => $product->TenSanPham,
            'image' => $product->HinhAnh,
            'unit' => $product->DonViTinh ?? 'Gói',
            'final_price' => $pricing['final_price'] ?? (float) ($product->Gia ?? 0),
            'original_price' => (float) ($product->Gia ?? 0),
            'discount_percent' => $pricing['discount_percent'] ?? 0,
            'has_discount' => (bool) $pricing,
        ];
    }
}
