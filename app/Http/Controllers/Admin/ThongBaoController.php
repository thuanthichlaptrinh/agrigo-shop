<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\ThongBao;
use App\Support\Traits\AccentInsensitiveSearch;
use Illuminate\Http\Request;

class ThongBaoController extends Controller
{
    use AccentInsensitiveSearch;

    /**
     * Danh sách thông báo với bộ lọc.
     */
    public function index(Request $request)
    {
        $query = ThongBao::with('nguoiDung');

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
                        $inner->whereRaw($this->accentInsensitiveColumn('ThongBao.TieuDe') . ' LIKE ?', [$pattern])
                            ->orWhereRaw($this->accentInsensitiveColumn('ThongBao.NoiDung') . ' LIKE ?', [$pattern]);
                    });
                }
            });
        }

        if ($request->filled('user_id')) {
            $query->where('IDNguoiDung', $request->input('user_id'));
        }

        if ($type = $request->input('type')) {
            $query->where('Loai', $type);
        }

        if ($read = $request->input('read_status')) {
            if ($read === 'read') {
                $query->where('DaXem', 1);
            } elseif ($read === 'unread') {
                $query->where('DaXem', 0);
            }
        }

        $sortBy = $request->input('sort_by', 'NgayTao');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSorts = ['ID', 'NgayTao'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'NgayTao';
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

        $notifications = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => ThongBao::count(),
            'read' => ThongBao::where('DaXem', 1)->count(),
            'unread' => ThongBao::where('DaXem', 0)->count(),
        ];

        $users = NguoiDung::orderBy('TenNguoiDung')->get(['ID', 'TenNguoiDung']);

        return view('admin.notifications.index', compact('notifications', 'perPageOptions', 'stats', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $this->rules($request);
        $validated['DaXem'] = $request->boolean('DaXem', false);

        ThongBao::create($validated);

        return redirect()->route('admin.notifications.index')->with('success', 'Đã thêm thông báo.');
    }

    public function update(Request $request, $id)
    {
        $notification = ThongBao::findOrFail($id);
        $validated = $this->rules($request);
        $validated['DaXem'] = $request->boolean('DaXem', false);

        $notification->update($validated);

        return redirect()->route('admin.notifications.index')->with('success', 'Đã cập nhật thông báo.');
    }

    public function destroy($id)
    {
        $notification = ThongBao::findOrFail($id);
        $notification->delete();

        return redirect()->route('admin.notifications.index')->with('success', 'Đã xóa thông báo.');
    }

    public function show($id)
    {
        $notification = ThongBao::with('nguoiDung')->findOrFail($id);

        if (!request()->wantsJson()) {
            return redirect()->route('admin.notifications.index');
        }

        return response()->json($notification);
    }

    protected function rules(Request $request): array
    {
        return $request->validate([
            'IDNguoiDung' => ['nullable', 'exists:NguoiDung,ID'],
            'TieuDe' => ['required', 'string', 'max:255'],
            'NoiDung' => ['required', 'string'],
            'Loai' => ['nullable', 'string', 'max:100'],
            'DaXem' => ['nullable', 'boolean'],
            'LinkLienKet' => ['nullable', 'string', 'max:255'],
        ], [
            'TieuDe.required' => 'Vui lòng nhập tiêu đề',
            'NoiDung.required' => 'Vui lòng nhập nội dung',
        ]);
    }
}
