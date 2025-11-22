<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhatKy;
use App\Models\NguoiDung;
use App\Support\Traits\AccentInsensitiveSearch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NhatKyController extends Controller
{
    use AccentInsensitiveSearch;

    protected $types = ['Hệ thống', 'Quản trị', 'Người dùng'];
    protected $results = ['Thành công', 'Thất bại'];

    public function index(Request $request)
    {
        $query = NhatKy::with('nguoiDung');

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
                        $inner->whereRaw($this->accentInsensitiveColumn('NhatKy.HanhDong') . ' LIKE ?', [$pattern])
                            ->orWhereRaw($this->accentInsensitiveColumn('NhatKy.DuLieuCu') . ' LIKE ?', [$pattern])
                            ->orWhereRaw($this->accentInsensitiveColumn('NhatKy.DuLieuMoi') . ' LIKE ?', [$pattern]);
                    });
                }
            });
        }

        if ($request->filled('user_id')) {
            $query->where('IDNguoiDung', $request->input('user_id'));
        }

        if ($request->filled('type')) {
            $query->where('Loai', $request->input('type'));
        }

        if ($request->filled('result')) {
            $query->where('KetQua', $request->input('result'));
        }

        if ($request->filled('date_from')) {
            if ($from = $this->parseDate($request->input('date_from'))) {
                $query->where('ThoiGian', '>=', $from->startOfDay());
            }
        }

        if ($request->filled('date_to')) {
            if ($to = $this->parseDate($request->input('date_to'))) {
                $query->where('ThoiGian', '<=', $to->endOfDay());
            }
        }

        $sortBy = $request->input('sort_by', 'ThoiGian');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSorts = ['ID', 'ThoiGian'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'ThoiGian';
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

        $logs = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => NhatKy::count(),
            'success' => NhatKy::where('KetQua', 'Thành công')->count(),
            'failed' => NhatKy::where('KetQua', 'Thất bại')->count(),
        ];

        $users = NguoiDung::orderBy('TenNguoiDung')->get(['ID', 'TenNguoiDung']);

        return view('admin.logs.index', [
            'logs' => $logs,
            'perPageOptions' => $perPageOptions,
            'stats' => $stats,
            'users' => $users,
            'types' => $this->types,
            'results' => $this->results,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->rules($request);
        $payload = $this->preparePayload($validated, $request, true);

        NhatKy::create($payload);

        return redirect()->route('admin.logs.index')->with('success', 'Đã thêm nhật ký.');
    }

    public function update(Request $request, $id)
    {
        $log = NhatKy::findOrFail($id);
        $validated = $this->rules($request);
        $payload = $this->preparePayload($validated, $request);

        $log->update($payload);

        return redirect()->route('admin.logs.index')->with('success', 'Đã cập nhật nhật ký.');
    }

    public function destroy($id)
    {
        $log = NhatKy::findOrFail($id);
        $log->delete();

        return redirect()->route('admin.logs.index')->with('success', 'Đã xóa nhật ký.');
    }

    public function show($id)
    {
        $log = NhatKy::with('nguoiDung')->findOrFail($id);

        if (!request()->wantsJson()) {
            return redirect()->route('admin.logs.index');
        }

        return response()->json($log);
    }

    protected function rules(Request $request): array
    {
        return $request->validate([
            'IDNguoiDung' => ['nullable', 'exists:NguoiDung,ID'],
            'HanhDong' => ['required', 'string', 'max:255'],
            'Loai' => ['required', 'in:' . implode(',', $this->types)],
            'DuLieuCu' => ['nullable', 'string'],
            'DuLieuMoi' => ['nullable', 'string'],
            'DiaChiIP' => ['nullable', 'string', 'max:100'],
            'TrinhDuyet' => ['nullable', 'string', 'max:255'],
            'KetQua' => ['required', 'in:' . implode(',', $this->results)],
            'ThoiGian' => ['nullable', 'date'],
        ], [
            'HanhDong.required' => 'Vui lòng nhập hành động',
            'Loai.required' => 'Vui lòng chọn loại nhật ký',
            'KetQua.required' => 'Vui lòng chọn kết quả',
        ]);
    }

    protected function preparePayload(array $validated, Request $request, bool $fillDefaults = false): array
    {
        if ($fillDefaults) {
            if (empty($validated['DiaChiIP'])) {
                $validated['DiaChiIP'] = $request->ip();
            }
            if (empty($validated['TrinhDuyet'])) {
                $validated['TrinhDuyet'] = $request->userAgent();
            }
        }

        if (array_key_exists('ThoiGian', $validated)) {
            if ($validated['ThoiGian']) {
                $validated['ThoiGian'] = Carbon::parse($validated['ThoiGian']);
            } else {
                unset($validated['ThoiGian']);
            }
        }

        return $validated;
    }

    protected function parseDate(?string $value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
