<?php

namespace App\Support\Logging;

use App\Models\NhatKy;
use Illuminate\Http\Request;

class ActivityLogger
{
    /**
     * Ghi nhận một dòng nhật ký chung.
     */
    public function log(
        ?int $userId,
        string $action,
        string $type = 'Người dùng',
        $oldData = null,
        $newData = null,
        string $result = 'Thành công',
        ?Request $request = null
    ): NhatKy {
        $request = $request ?: request();

        return NhatKy::create([
            'IDNguoiDung' => $userId,
            'HanhDong' => $action,
            'Loai' => $type,
            'DuLieuCu' => $this->normalizePayload($oldData),
            'DuLieuMoi' => $this->normalizePayload($newData),
            'DiaChiIP' => optional($request)->ip(),
            'TrinhDuyet' => optional($request)->userAgent(),
            'KetQua' => $result,
        ]);
    }

    public function logUserAction(?int $userId, string $action, $oldData = null, $newData = null, string $result = 'Thành công'): NhatKy
    {
        return $this->log($userId, $action, 'Người dùng', $oldData, $newData, $result);
    }

    public function logAdminAction(?int $userId, string $action, $oldData = null, $newData = null, string $result = 'Thành công'): NhatKy
    {
        return $this->log($userId, $action, 'Quản trị', $oldData, $newData, $result);
    }

    public function logSystemAction(string $action, $oldData = null, $newData = null, string $result = 'Thành công'): NhatKy
    {
        return $this->log(null, $action, 'Hệ thống', $oldData, $newData, $result);
    }

    protected function normalizePayload($data): ?string
    {
        if ($data === null) {
            return null;
        }

        if (is_string($data)) {
            return $data;
        }

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
