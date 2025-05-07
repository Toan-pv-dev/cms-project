<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface RouterRepositoryInterface
{
    public function forceDeleteByWhere(array $condition = []);
    // public function pagination($column = ['*'], $condition = [], $join = [], int $perPage = 1, array $extend = [], array $relation = []);
}
