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
    public function pagination(
        $column = ['*'],
        array $condition = [],
        int $perPage = 1,
        array $extend = [],
        array $orderBy = [],
        array $join = [],
        array $relations = [],
        array $rawQuery = [],
    );
    public function updateByWhereIn($whereInField = '', $whereIn = [], $payload = []);
    public function createPivot($model, array $payload = [], string $relation = '');
}
