<?php

namespace App\Repositories;

use App\Models\Language;
use App\Repositories\Interfaces\LanguageRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 *  App\Services
 */
class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
{
    protected $model;
    public function __construct(
        Language $model
    ) {
        // dd($model);
        $this->model = $model;
    }

    // public function findCurrentLanguage()
    // {
    //     return $this->model->select('canonical')->where('current', '=', '1');
    // }
}
