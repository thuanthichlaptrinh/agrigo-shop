<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VaiTro;
use App\Support\Traits\AccentInsensitiveSearch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VaiTroController extends Controller
{
    use AccentInsensitiveSearch;

    /**
     * Danh sách vai trò.
     */
    public function index(Request $request)
    {
        $query = VaiTro::withCount('nguoiDung');

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
                        $inner->whereRaw($this->accentInsensitiveColumn('VaiTro.TenVaiTro') . ' LIKE ?', [$pattern])
                            ->orWhereRaw($this->accentInsensitiveColumn('VaiTro.MoTa') . ' LIKE ?', [$pattern]);
                    });
                }
            });
        }

        $sortBy = $request->input('sort_by', 'ID');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSorts = ['ID', 'TenVaiTro', 'nguoi_dung_count'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'ID';
        }
        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }

        if ($sortBy === 'nguoi_dung_count') {
            $query->orderBy('nguoi_dung_count', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $perPage = (int) $request->input('per_page', 10);
        $perPageOptions = [5, 10, 15, 20];
        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $roles = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => VaiTro::count(),
            'used' => VaiTro::has('nguoiDung')->count(),
            'empty' => VaiTro::doesntHave('nguoiDung')->count(),
        ];

        return view('admin.roles.index', compact('roles', 'perPageOptions', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenVaiTro' => ['required', 'string', 'max:100', Rule::unique('VaiTro', 'TenVaiTro')],
            'MoTa' => ['nullable', 'string', 'max:255'],
        ], [
            'TenVaiTro.required' => 'Vui lòng nhập tên vai trò',
            'TenVaiTro.unique' => 'Tên vai trò đã tồn tại',
        ]);

        VaiTro::create($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Đã thêm vai trò.');
    }

    public function update(Request $request, $id)
    {
        $role = VaiTro::findOrFail($id);
        $validated = $request->validate([
            'TenVaiTro' => ['required', 'string', 'max:100', Rule::unique('VaiTro', 'TenVaiTro')->ignore($role->ID, 'ID')],
            'MoTa' => ['nullable', 'string', 'max:255'],
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Đã cập nhật vai trò.');
    }

    public function destroy($id)
    {
        $role = VaiTro::withCount('nguoiDung')->findOrFail($id);

        if ($role->nguoi_dung_count > 0) {
            return back()->with('error', 'Không thể xóa vì còn người dùng đang sử dụng vai trò này.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Đã xóa vai trò.');
    }

    public function show($id)
    {
        $role = VaiTro::withCount('nguoiDung')->findOrFail($id);

        if (!request()->wantsJson()) {
            return redirect()->route('admin.roles.index');
        }

        return response()->json($role);
    }
}
