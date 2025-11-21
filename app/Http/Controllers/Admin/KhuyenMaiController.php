<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use App\Support\Traits\AccentInsensitiveSearch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KhuyenMaiController extends Controller
{
    use AccentInsensitiveSearch;

    /**
     * Danh sách khuyến mãi với bộ lọc và phân trang.
     */
    public function index(Request $request)
    {
        $query = KhuyenMai::withCount('sanPham');

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
                        $inner->whereRaw($this->accentInsensitiveColumn('KhuyenMai.TenKhuyenMai') . ' LIKE ?', [$pattern])
                            ->orWhereRaw($this->accentInsensitiveColumn('KhuyenMai.MoTa') . ' LIKE ?', [$pattern]);
                    });
                }
            });
        }

        if ($type = $request->input('type')) {
            $query->where('LoaiKhuyenMai', $type);
        }

        if ($status = $request->input('status')) {
            $now = Carbon::now();
            if ($status === 'active') {
                $query->where('TrangThai', 1)
                    ->where('NgayBatDau', '<=', $now)
                    ->where('NgayKetThuc', '>=', $now);
            } elseif ($status === 'upcoming') {
                $query->where('TrangThai', 1)
                    ->where('NgayBatDau', '>', $now);
            } elseif ($status === 'expired') {
                $query->where('NgayKetThuc', '<', $now);
            } elseif ($status === 'inactive') {
                $query->where('TrangThai', 0);
            }
        }

        if ($request->filled('start_from')) {
            $query->whereDate('NgayBatDau', '>=', $request->input('start_from'));
        }

        if ($request->filled('end_to')) {
            $query->whereDate('NgayKetThuc', '<=', $request->input('end_to'));
        }

        $sortBy = $request->input('sort_by', 'ID');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSorts = ['ID', 'TenKhuyenMai', 'NgayBatDau', 'NgayKetThuc'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'ID';
        }
        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }
        $query->orderBy($sortBy, $sortDirection);

        $perPage = (int) $request->input('per_page', 10);
        $perPageOptions = [5, 10, 15, 20];
        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $promotions = $query->paginate($perPage)->withQueryString();

        $now = Carbon::now();
        $stats = [
            'total' => KhuyenMai::count(),
            'active' => KhuyenMai::where('TrangThai', 1)->where('NgayBatDau', '<=', $now)->where('NgayKetThuc', '>=', $now)->count(),
            'upcoming' => KhuyenMai::where('TrangThai', 1)->where('NgayBatDau', '>', $now)->count(),
            'expired' => KhuyenMai::where('NgayKetThuc', '<', $now)->count(),
        ];

        return view('admin.promotions.index', compact('promotions', 'perPageOptions', 'stats'));
    }

    /**
     * Tạo khuyến mãi mới.
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        KhuyenMai::create($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Thêm khuyến mãi thành công!');
    }

    /**
     * Cập nhật khuyến mãi.
     */
    public function update(Request $request, $id)
    {
        $promotion = KhuyenMai::findOrFail($id);
        $validated = $this->validateData($request, $promotion->ID);
        $promotion->update($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    /**
     * Xóa khuyến mãi nếu chưa gắn sản phẩm.
     */
    public function destroy($id)
    {
        $promotion = KhuyenMai::withCount('sanPham')->findOrFail($id);

        if ($promotion->san_pham_count > 0) {
            return back()->with('error', 'Không thể xóa vì đang áp dụng cho sản phẩm.');
        }

        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Xóa khuyến mãi thành công!');
    }

    /**
     * Trả về thông tin khuyến mãi dạng JSON.
     */
    public function show($id)
    {
        $promotion = KhuyenMai::withCount('sanPham')->findOrFail($id);

        if (!request()->wantsJson()) {
            return redirect()->route('admin.promotions.index');
        }

        return response()->json($promotion);
    }

    /**
     * Rule dùng chung.
     */
    protected function validateData(Request $request, int $ignoreId = 0): array
    {
        return $request->validate([
            'TenKhuyenMai' => ['required', 'string', 'max:255', Rule::unique('KhuyenMai', 'TenKhuyenMai')->ignore($ignoreId, 'ID')],
            'MoTa' => ['nullable', 'string', 'max:1000'],
            'LoaiKhuyenMai' => ['required', Rule::in(['Phần trăm', 'Tiền mặt'])],
            'GiaTriGiam' => ['required', 'numeric', 'min:1'],
            'GiamToiDa' => ['nullable', 'numeric', 'min:0'],
            'NgayBatDau' => ['required', 'date'],
            'NgayKetThuc' => ['required', 'date', 'after_or_equal:NgayBatDau'],
            'TrangThai' => ['required', 'boolean'],
        ], [
            'TenKhuyenMai.required' => 'Vui lòng nhập tên chương trình',
            'LoaiKhuyenMai.required' => 'Chọn loại khuyến mãi',
            'GiaTriGiam.min' => 'Giá trị giảm phải lớn hơn 0',
            'NgayKetThuc.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu',
        ]);
    }
}
