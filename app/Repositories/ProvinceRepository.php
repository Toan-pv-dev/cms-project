<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Province;
use App\Repositories\Interfaces\ProvinceRepositoryInterface;

/**
 * Class UserService
 *  App\Services
 */
class ProvinceRepository extends BaseRepository implements ProvinceRepositoryInterface
{
    protected $model;
    public function __construct(
        Province $model
    ) {
        $this->model = $model;
    }
    // public function findById(int $id, array $array, array $ht);
    // public function findById($id, array $columns = ['*'], array $relations = []){

    // };
}
