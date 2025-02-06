<?php

namespace App\Repositories;

use App\Models\Router;
// use App\Models\Province;
use App\Repositories\BaseRepository;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
class RouterRepository extends BaseRepository
{
    protected $model;
    public function __construct(
        Router $model
    ) {
        $this->model = $model;
    }
}
