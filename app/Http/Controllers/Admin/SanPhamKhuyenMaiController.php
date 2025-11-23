<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use App\Models\LoaiSanPham;
use App\Models\SanPham;
use App\Models\SanPhamKhuyenMai;
use App\Support\Traits\AccentInsensitiveSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SanPhamKhuyenMaiController extends Controller
{
    use AccentInsensitiveSearch;

    public function index(Request $request)
    {
        $query = SanPhamKhuyenMai::with(['sanPham.loaiSanPham', 'khuyenMai']);

        if ($search = trim((string) $request->input('search'))) {
            $keywords = array_filter(preg_split('/\s+/', $search));
            $query->where(function ($outer) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $normalized = $this->normalizeKeyword($keyword);
                    if ($normalized === '') {
                        continue;
                    }
                    $pattern = "%{$normalized}%";
                    $outer->where(function ($inner) use ($pattern) {
                        $inner->whereHas('sanPham', function ($productQuery) use ($pattern) {
                            $productQuery->whereRaw($this->accentInsensitiveColumn('SanPham.TenSanPham') . ' LIKE ?', [$pattern]);
                        })->orWhereHas('khuyenMai', function ($promoQuery) use ($pattern) {
                            $promoQuery->whereRaw($this->accentInsensitiveColumn('KhuyenMai.TenKhuyenMai') . ' LIKE ?', [$pattern]);
                        });
                    });
                }
            });
        }

        if ($request->filled('promotion_id')) {
            $query->where('IDKhuyenMai', $request->promotion_id);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('sanPham', function ($productQuery) use ($request) {
                $productQuery->where('IDLoaiSP', $request->category_id);
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            $query->whereHas('khuyenMai', function ($promoQuery) use ($status) {
                $now = now();
                if ($status === 'active') {
                    $promoQuery->where('TrangThai', 1)
                        ->where('NgayBatDau', '<=', $now)
                        ->where('NgayKetThuc', '>=', $now);
                } elseif ($status === 'upcoming') {
                    $promoQuery->where('TrangThai', 1)
                        ->where('NgayBatDau', '>', $now);
                } elseif ($status === 'expired') {
                    $promoQuery->where('NgayKetThuc', '<', $now);
                } elseif ($status === 'inactive') {
                    $promoQuery->where('TrangThai', 0);
                }
            });
        }

        if ($request->filled('price_min')) {
            $query->whereHas('sanPham', function ($productQuery) use ($request) {
                $productQuery->where('Gia', '>=', (float) $request->price_min);
            });
        }

        if ($request->filled('price_max')) {
            $query->whereHas('sanPham', function ($productQuery) use ($request) {
                $productQuery->where('Gia', '<=', (float) $request->price_max);
            });
        }

        $sortBy = $request->input('sort_by', 'recent');
        $sortDirection = $request->input('sort_direction', 'desc');
        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }

        switch ($sortBy) {
            case 'product_name':
                $query->orderByRaw('(SELECT TenSanPham FROM SanPham WHERE SanPham.ID = SanPhamKhuyenMai.IDSanPham) ' . $sortDirection);
                break;
            case 'promotion_end':
                $query->orderByRaw('(SELECT NgayKetThuc FROM KhuyenMai WHERE KhuyenMai.ID = SanPhamKhuyenMai.IDKhuyenMai) ' . $sortDirection);
                break;
            case 'price':
                $query->orderByRaw('(SELECT Gia FROM SanPham WHERE SanPham.ID = SanPhamKhuyenMai.IDSanPham) ' . $sortDirection);
                break;
            default:
                $query->orderBy('NgayTao', $sortDirection);
        }

        $perPageOptions = [10, 20, 30, 50];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $promotedProducts = $query->paginate($perPage)->withQueryString();
        $promotedProducts->getCollection()->transform(function (SanPhamKhuyenMai $item) {
            $item->GiaGoc = $item->sanPham->Gia ?? 0;
            $item->GiaKhuyenMai = $this->calculateDiscountedPrice($item->sanPham->Gia ?? 0, $item->khuyenMai);
            $item->discount_text = $this->formatDiscountText($item->khuyenMai);
            $item->promotion_state = $this->resolvePromotionStatus($item->khuyenMai);
            return $item;
        });

        $now = now();
        $stats = [
            'total' => SanPhamKhuyenMai::count(),
            'active' => SanPhamKhuyenMai::whereHas('khuyenMai', function ($q) use ($now) {
                $q->where('TrangThai', 1)
                    ->where('NgayBatDau', '<=', $now)
                    ->where('NgayKetThuc', '>=', $now);
            })->count(),
            'upcoming' => SanPhamKhuyenMai::whereHas('khuyenMai', function ($q) use ($now) {
                $q->where('TrangThai', 1)->where('NgayBatDau', '>', $now);
            })->count(),
            'uniqueProducts' => SanPhamKhuyenMai::distinct('IDSanPham')->count('IDSanPham'),
        ];

        $promotionOptions = KhuyenMai::orderBy('TenKhuyenMai')->get([
            'ID',
            'TenKhuyenMai',
            'LoaiKhuyenMai',
            'GiaTriGiam',
            'GiamToiDa',
            'NgayBatDau',
            'NgayKetThuc',
            'TrangThai'
        ]);

        $productOptions = SanPham::where('TrangThai', 1)
            ->orderBy('TenSanPham')
            ->get(['ID', 'TenSanPham', 'Gia', 'HinhAnh']);

        $categories = LoaiSanPham::orderBy('TenLoai')->get(['ID', 'TenLoai']);

        return view('admin.product-promotions.index', [
            'promotedProducts' => $promotedProducts,
            'promotionOptions' => $promotionOptions,
            'productOptions' => $productOptions,
            'perPageOptions' => $perPageOptions,
            'stats' => $stats,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'IDSanPham' => ['required', 'exists:SanPham,ID'],
            'IDKhuyenMai' => ['required', 'exists:KhuyenMai,ID'],
            'GhiChu' => ['nullable', 'string', 'max:500'],
        ]);

        $exists = SanPhamKhuyenMai::where('IDSanPham', $validated['IDSanPham'])
            ->where('IDKhuyenMai', $validated['IDKhuyenMai'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Sản phẩm đã nằm trong chương trình khuyến mãi này.');
        }

        SanPhamKhuyenMai::create([
            'IDSanPham' => $validated['IDSanPham'],
            'IDKhuyenMai' => $validated['IDKhuyenMai'],
            'GhiChu' => $validated['GhiChu'] ?? null,
            'NgayTao' => now(),
        ]);

        return redirect()->route('admin.product-promotions.index')->with('success', 'Đã thêm sản phẩm vào khuyến mãi.');
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'bulk_products' => ['required', 'array', 'min:1'],
            'bulk_products.*' => ['integer', 'exists:SanPham,ID'],
            'bulk_promotion' => ['required', 'exists:KhuyenMai,ID'],
            'bulk_note' => ['nullable', 'string', 'max:500'],
        ], [
            'bulk_products.required' => 'Vui lòng chọn ít nhất một sản phẩm.',
        ]);

        $count = 0;
        foreach (array_unique($validated['bulk_products']) as $productId) {
            $already = SanPhamKhuyenMai::where('IDSanPham', $productId)
                ->where('IDKhuyenMai', $validated['bulk_promotion'])
                ->exists();
            if ($already) {
                continue;
            }

            SanPhamKhuyenMai::create([
                'IDSanPham' => $productId,
                'IDKhuyenMai' => $validated['bulk_promotion'],
                'GhiChu' => $validated['bulk_note'],
                'NgayTao' => now(),
            ]);
            $count++;
        }

        return redirect()->route('admin.product-promotions.index')
            ->with('success', $count > 0 ? "Đã gắn {$count} sản phẩm vào khuyến mãi." : 'Các sản phẩm đã tồn tại trong khuyến mãi này.');
    }

    public function show($productId, $promotionId)
    {
        $record = SanPhamKhuyenMai::with(['sanPham', 'khuyenMai'])
            ->where('IDSanPham', $productId)
            ->where('IDKhuyenMai', $promotionId)
            ->firstOrFail();

        if (!request()->wantsJson()) {
            return redirect()->route('admin.product-promotions.index');
        }

        return response()->json([
            'IDSanPham' => $record->IDSanPham,
            'IDKhuyenMai' => $record->IDKhuyenMai,
            'GhiChu' => $record->GhiChu,
            'san_pham' => $record->sanPham,
            'khuyen_mai' => $record->khuyenMai,
            'promotion_state' => $this->resolvePromotionStatus($record->khuyenMai),
            'gia_goc' => $record->sanPham->Gia ?? 0,
            'gia_khuyen_mai' => $this->calculateDiscountedPrice($record->sanPham->Gia ?? 0, $record->khuyenMai),
        ]);
    }

    public function update(Request $request, $productId, $promotionId)
    {
        $record = SanPhamKhuyenMai::where('IDSanPham', $productId)
            ->where('IDKhuyenMai', $promotionId)
            ->firstOrFail();

        $validated = $request->validate([
            'IDKhuyenMai' => ['required', 'exists:KhuyenMai,ID'],
            'GhiChu' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($record, $validated, $promotionId) {
            if ((int) $validated['IDKhuyenMai'] !== (int) $promotionId) {
                $exists = SanPhamKhuyenMai::where('IDSanPham', $record->IDSanPham)
                    ->where('IDKhuyenMai', $validated['IDKhuyenMai'])
                    ->exists();
                if ($exists) {
                    throw ValidationException::withMessages([
                        'IDKhuyenMai' => 'Sản phẩm đã thuộc chương trình khuyến mãi được chọn.'
                    ]);
                }
                $record->delete();
                SanPhamKhuyenMai::create([
                    'IDSanPham' => $record->IDSanPham,
                    'IDKhuyenMai' => $validated['IDKhuyenMai'],
                    'GhiChu' => $validated['GhiChu'],
                    'NgayTao' => now(),
                ]);
            } else {
                $record->update(['GhiChu' => $validated['GhiChu']]);
            }
        });

        return redirect()->route('admin.product-promotions.index')->with('success', 'Đã cập nhật khuyến mãi cho sản phẩm.');
    }

    public function destroy($productId, $promotionId)
    {
        $record = SanPhamKhuyenMai::where('IDSanPham', $productId)
            ->where('IDKhuyenMai', $promotionId)
            ->firstOrFail();

        $record->delete();

        return redirect()->route('admin.product-promotions.index')->with('success', 'Đã gỡ sản phẩm khỏi khuyến mãi.');
    }

    protected function calculateDiscountedPrice(float $originalPrice, ?KhuyenMai $promotion): float
    {
        if (!$promotion) {
            return round(max(0, $originalPrice), 2);
        }
        $discount = 0;
        if ($promotion->LoaiKhuyenMai === 'Phần trăm') {
            $discount = $originalPrice * ($promotion->GiaTriGiam / 100);
            if ($promotion->GiamToiDa) {
                $discount = min($discount, $promotion->GiamToiDa);
            }
        } else {
            $discount = $promotion->GiaTriGiam;
        }

        $final = max(0, $originalPrice - $discount);

        return round($final, 2);
    }

    protected function resolvePromotionStatus(?KhuyenMai $promotion): array
    {
        if (!$promotion) {
            return ['label' => 'Không xác định', 'badge' => 'badge-muted'];
        }
        $now = now();
        if (!$promotion->TrangThai) {
            return ['label' => 'Tạm khóa', 'badge' => 'badge-muted'];
        }

        if ($promotion->NgayBatDau && $promotion->NgayBatDau->isFuture()) {
            return ['label' => 'Sắp diễn ra', 'badge' => 'badge-upcoming'];
        }

        if ($promotion->NgayKetThuc && $promotion->NgayKetThuc->isPast()) {
            return ['label' => 'Đã kết thúc', 'badge' => 'badge-expired'];
        }

        return ['label' => 'Đang chạy', 'badge' => 'badge-active'];
    }

    protected function formatDiscountText(?KhuyenMai $promotion): string
    {
        if (!$promotion) {
            return 'Không rõ ưu đãi';
        }
        if ($promotion->LoaiKhuyenMai === 'Phần trăm') {
            $value = rtrim(rtrim(number_format($promotion->GiaTriGiam, 2, '.', ''), '0'), '.');
            $text = "Giảm {$value}%";
            if ($promotion->GiamToiDa) {
                $text .= ' (tối đa ' . number_format($promotion->GiamToiDa, 0, ',', '.') . ' đ)';
            }
            return $text;
        }

        return 'Giảm ' . number_format($promotion->GiaTriGiam, 0, ',', '.') . ' đ';
    }
}
