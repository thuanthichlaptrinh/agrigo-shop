<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    /**
     * Liệt kê voucher với tìm kiếm, lọc, sắp xếp, phân trang.
     */
    public function index(Request $request)
    {
        $query = Voucher::query();

        if ($search = $request->input('search')) {
            $query->where('MaVoucher', 'like', "%{$search}%");
        }

        if ($type = $request->input('type')) {
            $query->where('Loai', $type);
        }

        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->whereColumn('SoLuong', '>', 'DaDung')
                    ->where('NgayKetThuc', '>=', Carbon::now());
            } elseif ($status === 'expired') {
                $query->where('NgayKetThuc', '<', Carbon::now());
            } elseif ($status === 'out') {
                $query->whereColumn('SoLuong', '<=', 'DaDung');
            }
        }

        if ($request->filled('valid_to')) {
            $query->whereDate('NgayKetThuc', '<=', $request->input('valid_to'));
        }

        $sortBy = $request->input('sort_by', 'ID');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSorts = ['ID', 'MaVoucher', 'GiaTri', 'SoLuong', 'NgayKetThuc'];
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

        $vouchers = $query->paginate($perPage)->withQueryString();

        $now = Carbon::now();
        $stats = [
            'total' => Voucher::count(),
            'active' => Voucher::whereColumn('SoLuong', '>', 'DaDung')->where('NgayKetThuc', '>=', $now)->count(),
            'expired' => Voucher::where('NgayKetThuc', '<', $now)->count(),
        ];

        return view('admin.vouchers.index', compact('vouchers', 'perPageOptions', 'stats'));
    }

    /**
     * Thêm voucher mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $validated['DaDung'] = 0;

        Voucher::create($validated);

        return redirect()->route('admin.vouchers.index')->with('success', 'Thêm voucher thành công!');
    }

    /**
     * Thêm nhiều voucher cùng lúc.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'VoucherData' => ['required', 'string', 'max:10000'],
        ], [
            'VoucherData.required' => 'Vui lòng nhập danh sách voucher',
        ]);

        $lines = collect(preg_split("/\r\n|\r|\n/", $request->input('VoucherData')))
            ->map(fn ($line) => trim($line))
            ->filter();

        if ($lines->isEmpty()) {
            return back()->withInput()->with('error', 'Danh sách trống hoặc không hợp lệ.');
        }

        $created = 0;
        $skipped = [];

        foreach ($lines as $line) {
            $parts = array_map('trim', explode('|', $line));
            $code = $parts[0] ?? '';
            if ($code === '' || Voucher::where('MaVoucher', $code)->exists()) {
                $skipped[] = $line;
                continue;
            }

            $type = $parts[1] ?? 'Cố định';
            $value = (float) ($parts[2] ?? 0);
            $qty = (int) ($parts[3] ?? 0);
            $end = $parts[4] ?? null;

            if ($value <= 0 || $qty <= 0 || !$end) {
                $skipped[] = $line;
                continue;
            }

            Voucher::create([
                'MaVoucher' => $code,
                'Loai' => $type,
                'GiaTri' => $value,
                'SoLuong' => $qty,
                'NgayKetThuc' => Carbon::parse($end),
                'DonToiThieu' => ($parts[5] ?? '') !== '' ? (float) $parts[5] : null,
                'GiamToiDa' => ($parts[6] ?? '') !== '' ? (float) $parts[6] : null,
                'DaDung' => 0,
            ]);
            $created++;
        }

        if ($created === 0) {
            return redirect()->route('admin.vouchers.index')
                ->with('error', 'Không có voucher nào được thêm. Kiểm tra lại dữ liệu.')
                ->with('skipped', $skipped);
        }

        $message = "Đã thêm {$created} voucher.";
        if (!empty($skipped)) {
            $message .= ' Một số dòng bị bỏ qua.';
        }

        return redirect()->route('admin.vouchers.index')->with('success', $message)->with('skipped', $skipped);
    }

    /**
     * Cập nhật voucher.
     */
    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $validated = $request->validate($this->rules($voucher->ID), $this->messages());
        $voucher->update($validated);

        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật voucher thành công!');
    }

    /**
     * Xóa voucher khi chưa dùng.
     */
    public function destroy($id)
    {
        $voucher = Voucher::withCount('donHang')->findOrFail($id);

        if ($voucher->don_hang_count > 0) {
            return back()->with('error', 'Không thể xóa voucher đã gắn với đơn hàng.');
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Xóa voucher thành công!');
    }

    /**
     * Chi tiết voucher JSON.
     */
    public function show($id)
    {
        $voucher = Voucher::withCount('donHang')->findOrFail($id);

        if (!request()->wantsJson()) {
            return redirect()->route('admin.vouchers.index');
        }

        return response()->json([
            'ID' => $voucher->ID,
            'MaVoucher' => $voucher->MaVoucher,
            'Loai' => $voucher->Loai,
            'GiaTri' => $voucher->GiaTri,
            'GiamToiDa' => $voucher->GiamToiDa,
            'DonToiThieu' => $voucher->DonToiThieu,
            'SoLuong' => $voucher->SoLuong,
            'DaDung' => $voucher->DaDung,
            'NgayKetThuc' => optional($voucher->NgayKetThuc)->format('Y-m-d'),
            'don_hang_count' => $voucher->don_hang_count,
        ]);
    }

    /**
     * Rule chung.
     */
    protected function rules(int $ignoreId = 0): array
    {
        return [
            'MaVoucher' => ['required', 'string', 'max:50', Rule::unique('Voucher', 'MaVoucher')->ignore($ignoreId, 'ID')],
            'Loai' => ['required', 'in:Cố định,Phần trăm'],
            'GiaTri' => ['required', 'numeric', 'min:1'],
            'GiamToiDa' => ['nullable', 'numeric', 'min:0'],
            'DonToiThieu' => ['nullable', 'numeric', 'min:0'],
            'SoLuong' => ['required', 'integer', 'min:1'],
            'DaDung' => ['nullable', 'integer', 'min:0'],
            'NgayKetThuc' => ['required', 'date'],
        ];
    }

    protected function messages(): array
    {
        return [
            'MaVoucher.required' => 'Vui lòng nhập mã voucher',
            'MaVoucher.unique' => 'Mã voucher đã tồn tại',
            'Loai.in' => 'Loại voucher phải là Cố định hoặc Phần trăm',
            'GiaTri.min' => 'Giá trị phải lớn hơn 0',
            'SoLuong.min' => 'Số lượng phải lớn hơn 0',
            'NgayKetThuc.required' => 'Vui lòng chọn ngày kết thúc',
        ];
    }
}
