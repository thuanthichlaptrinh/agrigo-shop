<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use App\Models\DanhMuc;
use App\Models\HoatDongNguoiDung;
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

        // Clone query for building supplier options later (category constraints only)
        $supplierOptionsQuery = clone $query;

        if ($subCategoryId) {
            $subCategory = LoaiSanPham::where('TrangThai', 1)->find($subCategoryId);
            if ($subCategory) {
                $categoryName = $subCategory->TenLoai;
                $selectedSubCategoryId = $subCategoryId;
                $selectedCategoryId = $subCategory->IDDanhMuc;
                $query->where('SanPham.IDLoaiSP', $subCategoryId);
                $supplierOptionsQuery->where('SanPham.IDLoaiSP', $subCategoryId);
            }
        } elseif ($categoryId) {
            $category = DanhMuc::where('TrangThai', 1)->find($categoryId);
            if ($category) {
                $categoryName = $category->TenDanhMuc;
                $selectedCategoryId = $categoryId;
                $query->whereHas('loaiSanPham', fn ($q) => $q->where('IDDanhMuc', $categoryId));
                $supplierOptionsQuery->whereHas('loaiSanPham', fn ($q) => $q->where('IDDanhMuc', $categoryId));
            }
        }

        $sortPrice = $request->input('sort_price');
        if (in_array($sortPrice, ['asc', 'desc'], true)) {
            $query->select('SanPham.*');
            
            $discountSubquery = DB::table('KhuyenMai as km')
                ->join('SanPhamKhuyenMai as spkm', 'km.ID', '=', 'spkm.IDKhuyenMai')
                ->whereColumn('spkm.IDSanPham', 'SanPham.ID')
                ->where('km.TrangThai', 1)
                ->where('km.NgayBatDau', '<=', now())
                ->where('km.NgayKetThuc', '>=', now())
                ->selectRaw('CASE WHEN km.LoaiKhuyenMai = "Phần trăm" THEN SanPham.Gia * km.GiaTriGiam / 100 ELSE km.GiaTriGiam END')
                ->orderByRaw('CASE WHEN km.LoaiKhuyenMai = "Phần trăm" THEN SanPham.Gia * km.GiaTriGiam / 100 ELSE km.GiaTriGiam END DESC')
                ->limit(1);

            $query->selectSub($discountSubquery, 'max_discount');
            $query->orderByRaw('(SanPham.Gia - COALESCE(max_discount, 0)) ' . $sortPrice);
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

            if ($request->boolean('only_half_off')) {
                $query->whereHas('khuyenMai', function ($q) use ($promotionScope) {
                    $promotionScope($q);
                    $q->where(function ($inner) {
                        $inner->where('KhuyenMai.LoaiKhuyenMai', 'like', '%phan tram%')
                              ->where('KhuyenMai.GiaTriGiam', '>=', 50);
                    });
                });
            }
        }

        $discountMin = (int) $request->input('discount_min');
        if ($discountMin > 0) {
            $query->whereHas('khuyenMai', function ($q) use ($promotionScope, $discountMin) {
                $promotionScope($q);
                $q->where('KhuyenMai.LoaiKhuyenMai', 'like', '%phan tram%')
                  ->where('KhuyenMai.GiaTriGiam', '>=', $discountMin);
            });
        }

        if ($request->boolean('in_stock')) {
            $query->where('SanPham.SoLuongTon', '>', 0);
        }

        $supplierValue = trim((string) $request->input('supplier'));
        if ($supplierValue !== '') {
            $query->where('SanPham.XuatXu', $supplierValue);
        }

        // Supplier options (distinct origins) based on current category/subcategory constraints
        $supplierOptions = $supplierOptionsQuery
            ->select('SanPham.XuatXu')
            ->whereNotNull('SanPham.XuatXu')
            ->distinct()
            ->orderBy('SanPham.XuatXu')
            ->pluck('SanPham.XuatXu')
            ->filter()
            ->values()
            ->all();

        $products = $query->paginate(12)->withQueryString();
        
        // Lấy danh sách ID sản phẩm yêu thích của user
        $user = auth_user();
        $wishlistProductIds = [];
        if ($user) {
            $wishlistProductIds = HoatDongNguoiDung::where('IDNguoiDung', $user->ID)
                ->where('Loai', 'Yêu thích')
                ->pluck('IDSanPham')
                ->toArray();
        }
        
        $products->getCollection()->transform(function (SanPham $product) use ($wishlistProductIds) {
            return $this->formatProduct($product, $wishlistProductIds);
        });

        return view('user.products.index', [
            'products' => $products,
            'categoryName' => $categoryName,
            'selectedCategoryId' => $selectedCategoryId,
            'selectedSubCategoryId' => $selectedSubCategoryId,
            'supplierOptions' => $supplierOptions,
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

        // Kiểm tra sản phẩm có trong wishlist không
        $user = auth_user();
        $isWishlisted = false;
        if ($user) {
            $isWishlisted = HoatDongNguoiDung::where('IDNguoiDung', $user->ID)
                ->where('IDSanPham', $id)
                ->where('Loai', 'Yêu thích')
                ->exists();
        }
        $productData['is_wishlisted'] = $isWishlisted;

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

    public function toggleWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:SanPham,ID',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thực hiện chức năng này.'], 401);
        }

        $productId = $request->input('product_id');
        $wishlist = HoatDongNguoiDung::where('IDNguoiDung', $user->ID)
            ->where('IDSanPham', $productId)
            ->where('Loai', 'Yêu thích')
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $isWishlisted = false;
            $message = 'Đã xóa khỏi danh sách yêu thích.';
        } else {
            HoatDongNguoiDung::create([
                'IDNguoiDung' => $user->ID,
                'IDSanPham' => $productId,
                'Loai' => 'Yêu thích',
                'Ngay' => now(),
            ]);
            $isWishlisted = true;
            $message = 'Đã thêm vào danh sách yêu thích.';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'is_wishlisted' => $isWishlisted,
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

    protected function formatProduct(SanPham $product, array $wishlistProductIds = []): array
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
            'is_wishlisted' => in_array($product->ID, $wishlistProductIds),
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
        $user = auth_user();
        $wishlistProductIds = [];
        if ($user) {
            $wishlistProductIds = HoatDongNguoiDung::where('IDNguoiDung', $user->ID)
                ->where('Loai', 'Yêu thích')
                ->pluck('IDSanPham')
                ->toArray();
        }

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
            ->map(function (SanPham $item) use ($wishlistProductIds) {
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
                    'is_wishlisted' => in_array($item->ID, $wishlistProductIds),
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

    /**
     * Tìm kiếm sản phẩm
     */
    public function search(Request $request)
    {
        $keyword = trim($request->input('q', ''));
        
        if (empty($keyword)) {
            return redirect()->route('user.products.index');
        }

        // Lưu từ khóa tìm kiếm vào hoạt động người dùng
        $user = auth_user();
        if ($user) {
            HoatDongNguoiDung::logTimKiem($user->ID, $keyword);
        }

        $now = now();
        $promotionScope = function ($query) use ($now) {
            $query->where('KhuyenMai.TrangThai', 1)
                ->where('KhuyenMai.NgayBatDau', '<=', $now)
                ->where('KhuyenMai.NgayKetThuc', '>=', $now);
        };

        $query = SanPham::query()
            ->with(['khuyenMai' => $promotionScope, 'loaiSanPham'])
            ->where('SanPham.TrangThai', 1)
            ->where(function ($q) use ($keyword) {
                $q->where('SanPham.TenSanPham', 'LIKE', "%{$keyword}%")
                  ->orWhere('SanPham.MoTa', 'LIKE', "%{$keyword}%")
                  ->orWhere('SanPham.XuatXu', 'LIKE', "%{$keyword}%");
            });

        // Sắp xếp
        $sortPrice = $request->input('sort_price');
        if (in_array($sortPrice, ['asc', 'desc'], true)) {
            $query->orderBy('SanPham.Gia', $sortPrice);
        } else {
            $query->orderByDesc('SanPham.NgayTao');
        }

        $products = $query->paginate(12)->withQueryString();

        // Lấy danh sách ID sản phẩm yêu thích của user
        $wishlistProductIds = [];
        if ($user) {
            $wishlistProductIds = HoatDongNguoiDung::where('IDNguoiDung', $user->ID)
                ->where('Loai', 'Yêu thích')
                ->pluck('IDSanPham')
                ->toArray();
        }

        $products->getCollection()->transform(function (SanPham $product) use ($wishlistProductIds) {
            return $this->formatProduct($product, $wishlistProductIds);
        });

        return view('user.products.index', [
            'products' => $products,
            'categoryName' => "Kết quả tìm kiếm: \"{$keyword}\"",
            'searchKeyword' => $keyword,
            'selectedCategoryId' => null,
            'selectedSubCategoryId' => null,
        ]);
    }

    /**
     * API lấy từ khóa tìm kiếm gần đây của người dùng
     */
    public function getSearchKeywords(Request $request)
    {
        $user = auth_user();
        $keywords = [];

        if ($user) {
            // Lấy từ khóa tìm kiếm gần đây của người dùng (không trùng lặp)
            $keywords = HoatDongNguoiDung::where('IDNguoiDung', $user->ID)
                ->where('Loai', 'Tìm kiếm')
                ->whereNotNull('TuKhoa')
                ->where('TuKhoa', '!=', '')
                ->select('TuKhoa')
                ->distinct()
                ->orderByDesc('Ngay')
                ->limit(5)
                ->pluck('TuKhoa')
                ->toArray();
        }

        // Nếu chưa có từ khóa hoặc ít hơn 5, bổ sung từ khóa phổ biến
        if (count($keywords) < 5) {
            $popularKeywords = HoatDongNguoiDung::where('Loai', 'Tìm kiếm')
                ->whereNotNull('TuKhoa')
                ->where('TuKhoa', '!=', '')
                ->whereNotIn('TuKhoa', $keywords)
                ->select('TuKhoa', DB::raw('COUNT(*) as count'))
                ->groupBy('TuKhoa')
                ->orderByDesc('count')
                ->limit(5 - count($keywords))
                ->pluck('TuKhoa')
                ->toArray();

            $keywords = array_merge($keywords, $popularKeywords);
        }

        return response()->json([
            'success' => true,
            'keywords' => $keywords,
        ]);
    }
}
