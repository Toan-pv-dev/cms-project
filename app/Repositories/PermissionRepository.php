<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\PermissionRepositoryInterface;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    protected $model;
    public function __construct(
        Permission $model
    ) {
        $this->model = $model;
    }
}
