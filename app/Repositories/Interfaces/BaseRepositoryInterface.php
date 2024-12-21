<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all();
    public function findById(int $model_ids,  array $column = ['*'], array $relation = []);
    public function create(array $payload = []);
    public function update(int $id, array $payload = []);
    public function delete(int $id = 0);
    public function pagination($column = ['*'], $condition = [], int $perPage = 1, array $extend = [], array $relations = [], array $orderBy = [], $join = []);
    public function updateByWhereIn($whereInField = '', $whereIn = [], $payload = []);
    public function createTranslatePivot($model, array $payload = []);
}
