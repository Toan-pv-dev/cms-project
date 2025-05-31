<?php

namespace App\Repositories;

use App\Models\ProductVariant;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 *  App\Services
 */
class ProductVariantRepository extends BaseRepository implements ProductVariantRepositoryInterface
{
    protected $model;
    public function __construct(
        ProductVariant $model
    ) {
        // dd($model);
        $this->model = $model;
    }
}