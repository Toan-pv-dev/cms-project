<?php

namespace App\Repositories;

use App\Models\ProductVariantLanguage;
use App\Repositories\Interfaces\ProductVariantLanguageRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 *  App\Services
 */
class ProductVariantLanguageRepository extends BaseRepository implements ProductVariantLanguageRepositoryInterface
{
    protected $model;
    public function __construct(
        ProductVariantLanguage $model
    ) {
        // dd($model);
        $this->model = $model;
    }
}