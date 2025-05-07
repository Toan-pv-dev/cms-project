<?php

namespace App\Repositories;

use App\Models\ModelName;
use App\Repositories\Interfaces\ModelNameRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 *  App\Services
 */
class ModelNameRepository extends BaseRepository implements ModelNameRepositoryInterface
{
    protected $model;
    public function __construct(
        ModelName $model
    ) {
        // dd($model);
        $this->model = $model;
    }
    public function pagination(
        $column = ['*'],
        $condition = [],
        int $perPage = 1,
        array $extend = [],
        $orderBy = [],
        array $join = [],
        array $relations = [],
        array $rawQuery = [],
    ) {
        $query = $this->model->select($column);
        $query = $query->keyword($condition['keyword'] ?? NULL)
            ->publish($condition['publish'] ?? NULL)
            ->CustomeWhereRaw($rawQuery ?? null)
            ->relationCount($relations ?? null)
            ->customeWhere($condition['where'] ?? null)
            ->customeJoin($join ?? null)
            ->customeGroupBy($extend['groupBy'] ?? null)
            ->customeOrderBy($orderBy ?? null)
            ->paginate($perPage)
            ->withQueryString()
            ->withPath(env('APP_URL') . $extend['path']);
        return $query;
    }
    public function getModelNameById(int $id = 0, $language_id = 0)
    {
        return $this->model
            ->select([
                'modelNames.id',
                'modelNames.modelName_catalogue_id',
                'modelNames.image',
                'modelNames.icon',
                'modelNames.album',
                'modelNames.follow',
                'modelNames.publish',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',

            ])
            ->join('modelName_language as tb2', 'tb2.modelName_id', '=', 'modelNames.id')
            ->with('modelName_catalogues')
            ->where('tb2.language_id', '=', $language_id)
            ->findOrFail($id);
    }
}
