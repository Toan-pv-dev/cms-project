<?php

namespace App\Repositories;

use App\Models\Menu;
// use App\Models\Province;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\MenuRepositoryInterface;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    protected $model;
    public function __construct(
        Menu $model
    ) {
        $this->model = $model;
    }
}
