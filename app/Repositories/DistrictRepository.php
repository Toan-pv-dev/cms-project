<?php

namespace App\Repositories;

use App\Models\District;
// use App\Models\Province;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\DistrictRepositoryInterface;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
class DistrictRepository extends BaseRepository implements DistrictRepositoryInterface
{
    protected $model;
    public function __construct(
        District $model
    ) {
        $this->model = $model;
    }
    public function findDistrictByProvinceId(int $province_code)
    {
        return $this->model->where('province_code', '=', $province_code)->get();
    }
}
