<?php

namespace App\Repositories;

use App\Models\AttributeCatalogue;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserService
 *  App\Services
 */
class AttributeCatalogueRepository extends BaseRepository implements AttributeCatalogueRepositoryInterface
{
    protected $model;
    public function __construct(
        AttributeCatalogue $model
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

    public function getAttributeCatalogueById(int $id = 0, $language_id = 0)
    {
        return $this->model
            ->select([
                'attribute_catalogues.id',
                'attribute_catalogues.parent_id',
                'attribute_catalogues.image',
                'attribute_catalogues.icon',
                'attribute_catalogues.album',
                'attribute_catalogues.follow',
                'attribute_catalogues.publish',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',

            ])
            ->join('attribute_catalogue_language as tb2', 'tb2.attribute_catalogue_id', '=', 'attribute_catalogues.id')
            ->where('tb2.language_id', '=', $language_id)
            ->find($id);
    }
    public function hasTranslation($attributeCatalogueId, $languageId)
    {
        return $this->model
            ->where('id', $attributeCatalogueId)
            ->whereHas('languages', function ($query) use ($languageId) {
                $query->where('language_id', $languageId)
                    ->whereNotNull('name')
                    ->where('name', '!=', '');
            })
            ->exists();
    }

    public function getAll(int $languageId = 0)
    {
        return $this->model->with(['attribute_catalogue_language' => function ($query) use ($languageId) {
            $query->where('language_id', $languageId);
        },])->get();
    }
}
