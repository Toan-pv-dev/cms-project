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
    public function pagination($column = ['*'], $condition = [], $join = [], int $perPage = 1, array $extend = [], array $relations = [], array $orderBy = [])
    {
        // dd($condition['publish']);
        // dd($this->$model);
        // dd($relations);
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%')
                    ->orWhere('description', 'LIKE', '%' . $condition['keyword'] . '%');
                // ->orWhere('address', 'LIKE', '%' . $condition['keyword'] . '%')
                // ->orWhere('phone', 'LIKE', '%' . $condition['keyword'] . '%');
            }
            if (isset($condition['publish']) && $condition['publish'] !== null) {
                if ($condition['publish'] != -1) {
                    $query->where('publish', '=', $condition['publish']);
                }
            }
            return $query;
        });
        if (isset($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->withCount($relation);
            }
        }

        if (!empty($join)) {
            $query->join(...$join);
        }
        // echo 1;
        // die();
        // dd($relations);
        return $query->paginate($perPage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }
}
