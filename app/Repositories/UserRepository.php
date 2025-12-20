<?php

namespace App\Repositories;

use App\Models\NguoiDung;
use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository
{
    protected function model(): string
    {
        return NguoiDung::class;
    }

    public function findByEmail(string $email): ?NguoiDung
    {
        return $this->findBy('Email', $email);
    }

    public function findByPhone(string $phone): ?NguoiDung
    {
        return $this->findBy('SDT', $phone);
    }

    public function getActiveUsers(): Collection
    {
        return $this->query
            ->where('TrangThai', 1)
            ->with('vaiTro')
            ->get();
    }

    public function getCustomers(): LengthAwarePaginator
    {
        return $this->query
            ->where('IDVaiTro', '!=', 1) // Không phải Admin
            ->with('vaiTro')
            ->orderBy('NgayTao', 'desc')
            ->paginate(15);
    }

    public function getUserStats(): array
    {
        return [
            'total' => NguoiDung::count(),
            'active' => NguoiDung::where('TrangThai', 1)->count(),
            'inactive' => NguoiDung::where('TrangThai', 0)->count(),
            'admins' => NguoiDung::where('IDVaiTro', 1)->count(),
            'customers' => NguoiDung::where('IDVaiTro', '!=', 1)->count(),
        ];
    }

    public function getWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query->with('vaiTro');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('TenNguoiDung', 'LIKE', "%{$search}%")
                  ->orWhere('Email', 'LIKE', "%{$search}%")
                  ->orWhere('SDT', 'LIKE', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('TrangThai', $filters['status']);
        }

        if (!empty($filters['role'])) {
            $query->where('IDVaiTro', $filters['role']);
        }

        $query->orderBy('NgayTao', 'desc');

        return $query->paginate($perPage)->withQueryString();
    }
}
