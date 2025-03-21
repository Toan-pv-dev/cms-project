<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface UserCatalogueRepositoryInterface
{
    public function findById(int $model_id,  array $column = ['*'], array $relation = []);
    public function detachAllPermissions();
    // public function pagination($column = ['*'], $condition = [], $join = [], int $perPage = 1, array $extend = [], array $relation = []);
}
