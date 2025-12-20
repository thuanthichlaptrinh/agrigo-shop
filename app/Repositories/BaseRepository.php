<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;
    protected Builder $query;

    public function __construct()
    {
        $this->model = $this->makeModel();
        $this->query = $this->model->newQuery();
    }

    abstract protected function model(): string;

    protected function makeModel(): Model
    {
        return app($this->model());
    }

    protected function resetQuery(): void
    {
        $this->query = $this->model->newQuery();
    }

    public function all(): Collection
    {
        $result = $this->query->get();
        $this->resetQuery();
        return $result;
    }

    public function find(int $id): ?Model
    {
        $result = $this->query->find($id);
        $this->resetQuery();
        return $result;
    }

    public function findOrFail(int $id): Model
    {
        $result = $this->query->findOrFail($id);
        $this->resetQuery();
        return $result;
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->find($id);
        return $record ? $record->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $record = $this->find($id);
        return $record ? $record->delete() : false;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $result = $this->query->paginate($perPage);
        $this->resetQuery();
        return $result;
    }

    public function findBy(string $field, mixed $value): ?Model
    {
        $result = $this->query->where($field, $value)->first();
        $this->resetQuery();
        return $result;
    }

    public function findAllBy(string $field, mixed $value): Collection
    {
        $result = $this->query->where($field, $value)->get();
        $this->resetQuery();
        return $result;
    }

    public function with(array $relations): self
    {
        $this->query = $this->query->with($relations);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->query = $this->query->orderBy($column, $direction);
        return $this;
    }

    public function where(string $column, mixed $operator, mixed $value = null): self
    {
        $this->query = $this->query->where($column, $operator, $value);
        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        $this->query = $this->query->whereIn($column, $values);
        return $this;
    }

    public function count(): int
    {
        $result = $this->query->count();
        $this->resetQuery();
        return $result;
    }
}
