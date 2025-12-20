<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUser(int $userId): LengthAwarePaginator;

    public function getByStatus(string $status): LengthAwarePaginator;

    public function getOrderStats(): array;

    public function getRevenueByStatus(array $statuses): float;

    public function getWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator;
}
