<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveProducts(): LengthAwarePaginator;

    public function getFeaturedProducts(int $limit = 8): \Illuminate\Database\Eloquent\Collection;

    public function getByCategory(int $categoryId): LengthAwarePaginator;

    public function search(string $keyword): LengthAwarePaginator;

    public function getProductStats(): array;

    public function decrementStock(int $productId, int $quantity): bool;

    public function incrementStock(int $productId, int $quantity): bool;
}
