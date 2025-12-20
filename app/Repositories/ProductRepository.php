<?php

namespace App\Repositories;

use App\Models\SanPham;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Support\Traits\AccentInsensitiveSearch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    use AccentInsensitiveSearch;

    protected function model(): string
    {
        return SanPham::class;
    }

    public function getActiveProducts(): LengthAwarePaginator
    {
        return $this->query
            ->where('TrangThai', 1)
            ->with(['loaiSanPham', 'nhaCungCap'])
            ->orderBy('NgayTao', 'desc')
            ->paginate(12);
    }

    public function getFeaturedProducts(int $limit = 8): Collection
    {
        $result = $this->query
            ->where('TrangThai', 1)
            ->where('NoiBat', 1)
            ->with(['loaiSanPham'])
            ->orderBy('NgayTao', 'desc')
            ->limit($limit)
            ->get();
        
        $this->resetQuery();
        return $result;
    }

    public function getByCategory(int $categoryId): LengthAwarePaginator
    {
        return $this->query
            ->where('TrangThai', 1)
            ->where('IDLoaiSP', $categoryId)
            ->with(['loaiSanPham', 'nhaCungCap'])
            ->orderBy('NgayTao', 'desc')
            ->paginate(12);
    }

    public function search(string $keyword): LengthAwarePaginator
    {
        $keywords = array_filter(preg_split('/\s+/', trim($keyword)));
        
        $query = $this->query->where('TrangThai', 1);
        
        foreach ($keywords as $word) {
            $normalized = $this->normalizeKeyword($word);
            if ($normalized === '') continue;
            
            $pattern = "%{$normalized}%";
            $query->where(function ($q) use ($pattern) {
                $q->whereRaw($this->accentInsensitiveColumn('TenSanPham') . ' LIKE ?', [$pattern])
                  ->orWhereRaw($this->accentInsensitiveColumn('MoTa') . ' LIKE ?', [$pattern]);
            });
        }
        
        return $query->with(['loaiSanPham'])->paginate(12);
    }

    public function getProductStats(): array
    {
        return [
            'total' => SanPham::count(),
            'active' => SanPham::where('TrangThai', 1)->count(),
            'featured' => SanPham::where('NoiBat', 1)->count(),
            'inStock' => SanPham::where('SoLuongTon', '>', 0)->count(),
            'outOfStock' => SanPham::where('SoLuongTon', '<=', 0)->count(),
        ];
    }

    public function decrementStock(int $productId, int $quantity): bool
    {
        $product = $this->find($productId);
        if (!$product) return false;
        
        $product->decrement('SoLuongTon', $quantity);
        return true;
    }

    public function incrementStock(int $productId, int $quantity): bool
    {
        $product = $this->find($productId);
        if (!$product) return false;
        
        $product->increment('SoLuongTon', $quantity);
        return true;
    }

    public function getWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->query->with(['loaiSanPham', 'nhaCungCap'])->withCount('hinhAnh');

        if (!empty($filters['search'])) {
            $keywords = array_filter(preg_split('/\s+/', trim($filters['search'])));
            foreach ($keywords as $word) {
                $normalized = $this->normalizeKeyword($word);
                if ($normalized === '') continue;
                
                $pattern = "%{$normalized}%";
                $query->where(function ($q) use ($pattern) {
                    $q->whereRaw($this->accentInsensitiveColumn('SanPham.TenSanPham') . ' LIKE ?', [$pattern])
                      ->orWhereRaw($this->accentInsensitiveColumn('SanPham.MoTa') . ' LIKE ?', [$pattern]);
                });
            }
        }

        if (!empty($filters['category'])) {
            $query->where('IDLoaiSP', $filters['category']);
        }

        if (!empty($filters['supplier'])) {
            $query->where('IDNhaCungCap', $filters['supplier']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('TrangThai', $filters['status']);
        }

        if (isset($filters['featured']) && $filters['featured'] !== '') {
            $query->where('NoiBat', $filters['featured']);
        }

        if (!empty($filters['stock'])) {
            match ($filters['stock']) {
                'out' => $query->where('SoLuongTon', '<=', 0),
                'low' => $query->whereBetween('SoLuongTon', [1, 20]),
                'in' => $query->where('SoLuongTon', '>', 20),
                default => null
            };
        }

        if (!empty($filters['price_min'])) {
            $query->where('Gia', '>=', (float) $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $query->where('Gia', '<=', (float) $filters['price_max']);
        }

        $sortBy = $filters['sort_by'] ?? 'NgayTao';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $allowedSorts = ['NgayTao', 'Gia', 'TenSanPham', 'SoLuongTon', 'LuotBan'];
        
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'NgayTao';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'desc';
        
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage)->withQueryString();
    }
}
