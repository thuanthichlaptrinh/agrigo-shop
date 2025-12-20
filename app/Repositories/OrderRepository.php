<?php

namespace App\Repositories;

use App\Models\DonHang;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected function model(): string
    {
        return DonHang::class;
    }

    public function getByUser(int $userId): LengthAwarePaginator
    {
        return $this->query
            ->where('IDNguoiDung', $userId)
            ->with(['chiTiet.sanPham', 'voucher'])
            ->orderBy('NgayDat', 'desc')
            ->paginate(10);
    }

    public function getByStatus(string $status): LengthAwarePaginator
    {
        return $this->query
            ->where('TrangThai', $status)
            ->with(['nguoiDung', 'voucher'])
            ->orderBy('NgayDat', 'desc')
            ->paginate(10);
    }

    public function getOrderStats(): array
    {
        return [
            'total' => DonHang::count(),
            'pending' => DonHang::where('TrangThai', 'Chờ xác nhận')->count(),
            'confirmed' => DonHang::where('TrangThai', 'Đã xác nhận')->count(),
            'shipping' => DonHang::where('TrangThai', 'Đang giao')->count(),
            'completed' => DonHang::where('TrangThai', 'Đã giao')->count(),
            'cancelled' => DonHang::where('TrangThai', 'Đã hủy')->count(),
        ];
    }

    public function getRevenueByStatus(array $statuses): float
    {
        return (float) DonHang::whereIn('TrangThai', $statuses)->sum('TongThanhToan');
    }

    public function getWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->query->with(['nguoiDung', 'voucher'])->withCount('chiTiet');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('MaDonHang', 'LIKE', "%{$search}%")
                  ->orWhere('TenNguoiNhan', 'LIKE', "%{$search}%")
                  ->orWhere('SDT', 'LIKE', "%{$search}%")
                  ->orWhere('DiaChi', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('TrangThai', $filters['status']);
        }

        if (!empty($filters['payment_method'])) {
            $query->where('PhuongThucTT', $filters['payment_method']);
        }

        if (!empty($filters['min_total'])) {
            $query->where('TongThanhToan', '>=', (float) $filters['min_total']);
        }

        if (!empty($filters['max_total'])) {
            $query->where('TongThanhToan', '<=', (float) $filters['max_total']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('NgayDat', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('NgayDat', '<=', $filters['date_to']);
        }

        $sortBy = $filters['sort_by'] ?? 'NgayDat';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $allowedSorts = ['NgayDat', 'TongThanhToan', 'MaDonHang'];
        
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'NgayDat';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'desc';
        
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage)->withQueryString();
    }
}
