<?php

namespace App\Repositories;

use App\Models\FirstModelCatalogue;
use App\Repositories\Interfaces\FirstModelCatalogueRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserService
 *  App\Services
 */
class FirstModelCatalogueRepository extends BaseRepository implements FirstModelCatalogueRepositoryInterface
{
    protected $model;
    public function __construct(
        FirstModelCatalogue $model
    ) {
        // dd($model);
        $this->model = $model;
    }
    public function pagination(
        $column = ['*'],
        $condition = [],
        int $perPage = 1,
        array $extend = [],
        array $orderBy = [],
        $join = [],
        array $relations = [],
        array $rawQuery = [], // đây là mảng chứa các điều kiện where dạng [['column', 'operator', 'value']]
    ) {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%')
                    ->orWhere('description', 'LIKE', '%' . $condition['keyword'] . '%');
            }
            return $query;
        });

        if (isset($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->withCount($relation);
            }
        }

        if (isset($condition['publish']) && $condition['publish'] !== null) {
            if ($condition['publish'] != -1) {
                $query->where('publish', '=', $condition['publish']);
            }
        }

        if (isset($orderBy) && is_array($orderBy) && count($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        }

        if (!empty($join)) {
            foreach ($join as $joinClause) {
                $query->join($joinClause[0], $joinClause[1], $joinClause[2], $joinClause[3]);
            }
        }


        if (!empty($rawQuery)) {
            foreach ($rawQuery as $where) {
                $query->where($where[0], $where[1], $where[2]);
            }
        }

        return $query->paginate($perPage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }

    public function getFirstModelCatalogueById(int $id = 0, $language_id = 0)
    {
        return $this->model
            ->select([
                'firstModel_catalogues.id',
                'firstModel_catalogues.parent_id',
                'firstModel_catalogues.image',
                'firstModel_catalogues.icon',
                'firstModel_catalogues.album',
                'firstModel_catalogues.follow',
                'firstModel_catalogues.publish',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',

            ])
            ->join('firstModel_catalogue_language as tb2', 'tb2.firstModel_catalogue_id', '=', 'firstModel_catalogues.id')
            ->where('tb2.language_id', '=', $language_id)
            ->find($id);
    }
    public function hasTranslation($firstModelCatalogueId, $languageId)
    {
        return $this->model
            ->where('id', $firstModelCatalogueId)
            ->whereHas('languages', function ($query) use ($languageId) {
                $query->where('language_id', $languageId)
                    ->whereNotNull('name')
                    ->where('name', '!=', '');
            })
            ->exists();
    }
}
