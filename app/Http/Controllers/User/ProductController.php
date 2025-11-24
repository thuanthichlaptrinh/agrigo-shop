<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use App\Models\DanhMuc;
use App\Models\LoaiSanPham;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

    public function show(Request $request, int $id)
    {
        $now = now();

        $promotionScope = function ($query) use ($now) {
            $query->where('KhuyenMai.TrangThai', 1)
                ->where('KhuyenMai.NgayBatDau', '<=', $now)
                ->where('KhuyenMai.NgayKetThuc', '>=', $now);
        };

        $product = SanPham::with([
            'hinhAnh',
            'khuyenMai' => $promotionScope,
            'loaiSanPham',
            'nhaCungCap',
        ])
            ->where('SanPham.TrangThai', 1)
            ->findOrFail($id);

        $productData = $this->formatDetailProduct($product);
        $relatedProducts = $this->fetchRelatedProducts($product, $promotionScope);

        $ratingFilter = $request->integer('rating');
        if (!in_array($ratingFilter, [1, 2, 3, 4, 5], true)) {
            $ratingFilter = null;
        }

        $reviewData = $this->buildReviewData($product, $ratingFilter);

        return view('user.products.detail', [
            'product' => $productData,
            'relatedProducts' => $relatedProducts,
            'reviewStats' => $reviewData['stats'],
            'reviews' => $reviewData['reviews'],
            'selectedRating' => $ratingFilter,
        ]);
    }

    public function storeReview(Request $request, int $id)
    {
        $product = SanPham::where('SanPham.TrangThai', 1)->findOrFail($id);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'photos' => ['nullable', 'array', 'max:4'],
            'photos.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá',
            'rating.min' => 'Số sao tối thiểu là 1',
            'rating.max' => 'Số sao tối đa là 5',
            'comment.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự',
            'photos.max' => 'Bạn chỉ có thể tải lên tối đa 4 hình ảnh',
            'photos.*.image' => 'Tệp tải lên phải là hình ảnh',
            'photos.*.mimes' => 'Ảnh phải thuộc định dạng jpeg, jpg, png hoặc webp',
            'photos.*.max' => 'Mỗi ảnh không được vượt quá 3MB',
        ]);

        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $images = $this->storeReviewImages($request);

        DB::transaction(function () use ($product, $userId, $validated, $images) {
            $review = DanhGia::firstOrNew([
                'IDSanPham' => $product->ID,
                'IDNguoiDung' => $userId,
            ]);

            if ($images !== null) {
                if ($review->exists) {
                    $this->removeReviewImages($review);
                }
                $review->HinhAnh = $images ? json_encode($images) : null;
            }

            $review->fill([
                'SoSao' => $validated['rating'],
                'NoiDung' => $validated['comment'] ?? null,
                'TrangThai' => 'Đã duyệt',
            ]);

            $review->save();

            $this->syncAverageRating($product);
        });

        return redirect()
            ->route('user.products.detail', $product->ID)
            ->with('success', 'Đánh giá của bạn đã được ghi nhận!');
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

    protected function formatDetailProduct(SanPham $product): array
    {
        $promotion = $product->khuyenMai->first();
        $pricing = $promotion ? calculate_promotion_pricing($product, $promotion) : null;

        $gallery = $product->hinhAnh
            ->sortByDesc(fn ($image) => $image->LaChinh ? 1 : 0)
            ->pluck('DuongDan')
            ->filter()
            ->values();

        if ($product->HinhAnh) {
            $gallery->prepend($product->HinhAnh);
        }

        $gallery = $gallery->unique()->values();

        $category = $product->loaiSanPham;
        $categoryUrl = $category
            ? route('user.products.index', ['subcat' => $category->ID])
            : null;

        return [
            'id' => $product->ID,
            'name' => $product->TenSanPham,
            'description' => $product->MoTa,
            'full_description' => $product->MoTa ? '<p>' . nl2br(e($product->MoTa)) . '</p>' : null,
            'price' => $pricing['final_price'] ?? (float) ($product->Gia ?? 0),
            'old_price' => $pricing['original_price'] ?? null,
            'unit' => $product->DonViTinh ?? '1kg',
            'stock' => $product->SoLuongTon ?? 0,
            'brand' => optional($product->nhaCungCap)->TenNhaCungCap ?? 'Organic Shop',
            'origin' => $product->XuatXu ?? 'Việt Nam',
            'weight' => $product->KhoiLuong ?? '1kg / túi',
            'expiry' => optional($product->HanSuDung)->format('d/m/Y') ?? '07 ngày',
            'gallery' => $gallery->all(),
            'highlights' => $this->buildDetailHighlights($product),
            'cert' => $product->NoiBat ? 'Sản phẩm nổi bật' : 'An toàn đã kiểm định',
            'sold_label' => $product->LuotBan ? ('Đã bán ' . number_format((int) $product->LuotBan) . ' lượt') : 'Bán chạy tuần này',
            'category' => optional($category)->TenLoai,
            'category_url' => $categoryUrl,
            'breadcrumbs' => $this->buildBreadcrumbs($product, $categoryUrl),
        ];
    }

    protected function fetchRelatedProducts(SanPham $product, callable $promotionScope)
    {
        return SanPham::query()
            ->with([
                'khuyenMai' => $promotionScope,
                'hinhAnh',
                'loaiSanPham',
            ])
            ->where('SanPham.TrangThai', 1)
            ->where('SanPham.ID', '!=', $product->ID)
            ->when($product->IDLoaiSP, fn ($query, $category) => $query->where('SanPham.IDLoaiSP', $category))
            ->orderByDesc('SanPham.NoiBat')
            ->inRandomOrder()
            ->limit(10)
            ->get()
            ->map(function (SanPham $item) {
                $promotion = $item->khuyenMai->first();
                $pricing = $promotion ? calculate_promotion_pricing($item, $promotion) : null;
                $previewImage = $item->HinhAnh ?? optional($item->hinhAnh->first())->DuongDan;

                return [
                    'id' => $item->ID,
                    'name' => $item->TenSanPham,
                    'image' => $previewImage,
                    'price' => $pricing['final_price'] ?? (float) ($item->Gia ?? 0),
                    'old_price' => $pricing['original_price'] ?? null,
                    'unit' => $item->DonViTinh ?? '1kg',
                    'category' => optional($item->loaiSanPham)->TenLoai ?? 'Rau củ quả',
                ];
            });
    }

    protected function buildReviewData(SanPham $product, ?int $starFilter = null): array
    {
        $baseQuery = DanhGia::query()
            ->with('nguoiDung')
            ->where('IDSanPham', $product->ID)
            ->where('TrangThai', 'Đã duyệt');

        $total = (clone $baseQuery)->count();
        $average = $total ? round((clone $baseQuery)->avg('SoSao'), 1) : 0;

        $counts = (clone $baseQuery)
            ->selectRaw('SoSao, COUNT(*) as total')
            ->groupBy('SoSao')
            ->pluck('total', 'SoSao');

        $breakdown = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = (int) ($counts[$star] ?? 0);
            $breakdown[$star] = [
                'count' => $count,
                'percent' => $total ? round(($count / $total) * 100) : 0,
            ];
        }

        $filteredQuery = clone $baseQuery;
        if ($starFilter) {
            $filteredQuery->where('SoSao', $starFilter);
        }

        $reviews = $filteredQuery
            ->orderByDesc('NgayTao')
            ->get()
            ->map(function (DanhGia $review) {
                $name = $review->nguoiDung->TenNguoiDung ?? 'Người dùng ẩn danh';
                $initial = mb_substr($name, 0, 1, 'UTF-8');
                $timeLabel = $review->NgayTao
                    ? $review->NgayTao->copy()->locale('vi')->diffForHumans()
                    : null;

                return [
                    'id' => $review->ID,
                    'user_name' => $name,
                    'user_initial' => mb_strtoupper($initial ?: 'U', 'UTF-8'),
                    'rating' => $review->SoSao,
                    'comment' => $review->NoiDung,
                    'created_at' => $timeLabel,
                    'images' => $this->transformReviewImages($review->HinhAnh),
                ];
            });

        return [
            'stats' => [
                'average' => $average,
                'total' => $total,
                'breakdown' => $breakdown,
            ],
            'reviews' => $reviews,
        ];
    }

    protected function syncAverageRating(SanPham $product): void
    {
        $average = $product->danhGia()
            ->where('TrangThai', 'Đã duyệt')
            ->avg('SoSao');

        $product->forceFill([
            'DanhGiaTB' => $average ? round($average, 1) : 0,
        ])->saveQuietly();
    }

    protected function storeReviewImages(Request $request): ?array
    {
        if (!$request->hasFile('photos')) {
            return null;
        }

        $directory = public_path('uploads/reviews');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $paths = [];
        foreach ((array) $request->file('photos') as $file) {
            if ($file && $file->isValid()) {
                $filename = Str::uuid()->toString() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($directory, $filename);
                $paths[] = 'uploads/reviews/' . $filename;
            }
        }

        return $paths;
    }

    protected function removeReviewImages(DanhGia $review): void
    {
        $paths = json_decode($review->HinhAnh ?? '[]', true) ?: [];

        foreach ($paths as $path) {
            $relative = $this->normalizeReviewImagePath($path);
            if ($relative) {
                File::delete(public_path($relative));
            }
        }
    }

    protected function transformReviewImages(?string $raw): array
    {
        if (empty($raw)) {
            return [];
        }

        $paths = json_decode($raw, true);
        if (!is_array($paths)) {
            return [];
        }

        return collect($paths)
            ->filter()
            ->map(function ($path) {
                if (filter_var($path, FILTER_VALIDATE_URL)) {
                    return $path;
                }

                $relative = $this->normalizeReviewImagePath($path);
                return $relative ? asset($relative) : null;
            })
            ->filter()
            ->values()
            ->all();
    }

    protected function normalizeReviewImagePath(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($path, PHP_URL_PATH);
            $path = $parsed ?: $path;
        }

        return ltrim($path, '/');
    }

    protected function buildDetailHighlights(SanPham $product): array
    {
        return [
            [
                'icon' => 'ri-leaf-line',
                'label' => 'Nguồn gốc sạch',
                'value' => $product->XuatXu ?? 'Chứng nhận VietGAP',
            ],
            [
                'icon' => 'ri-truck-line',
                'label' => 'Giao nhanh',
                'value' => 'Trong 2 giờ',
            ],
            [
                'icon' => 'ri-stack-line',
                'label' => 'Tồn kho',
                'value' => ($product->SoLuongTon ?? 0) . ' sản phẩm có sẵn',
            ],
        ];
    }

    protected function buildBreadcrumbs(SanPham $product, ?string $categoryUrl): array
    {
        $breadcrumbs = [
            ['label' => 'Trang chủ', 'url' => route('user.home')],
        ];

        if ($product->loaiSanPham) {
            $breadcrumbs[] = [
                'label' => $product->loaiSanPham->TenLoai,
                'url' => $categoryUrl,
            ];
        } else {
            $breadcrumbs[] = [
                'label' => 'Sản phẩm',
                'url' => route('user.products.index'),
            ];
        }

        $breadcrumbs[] = ['label' => $product->TenSanPham, 'url' => null];

        return $breadcrumbs;
    }
}
