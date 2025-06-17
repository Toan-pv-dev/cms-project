<?php

namespace App\Repositories;

// use App\Models\Base;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Interfaces\BaseRepositoryInterface;


/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function pagination(
        $column = ['*'],
        array $condition = [],
        int $perPage = 1,
        array $extend = [],
        array $orderBy = [],
        array $join = [],
        array $relations = [],
        array $rawQuery = [],
    ) {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {});

        if (isset($join) && is_array($join) && count($join)) {
            foreach ($join as $key => $val) {
                $query->join($val[0], $val[1], $val[2], $val[3]);
            }
        }
        if (isset($condition['where']) &&  count($condition['where'])) {
            foreach ($condition['where'] as $key => $val) {
                $query->where($val[0], $val[1], $val[2]);
            }
        }
        // dd($query);
        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }

    public function create(array $payload = [])
    {
        // dd($payload, $this->model->getTable());
        // dd($payload);

        $model = $this->model->create($payload);

        // dd($model);
        return $model;
    }

    public function createBatch($payload = [])
    {
        $now = now();
        foreach ($payload as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }
        return $this->model->insert($payload);
    }

    public function update(int $id = 0, array $payload = [])
    {
        $model = $this->findById($id);

        if ($model) {
            $model->update($payload);
        }
        return $model;
    }


    public function all(array $relation = [])
    {
        return $this->model->with($relation)->get();
    }

    public function findById(int $model_id,  array $column = ['*'], array $relation = [])
    {

        return $this->model->select($column)->with($relation)->find($model_id);
    }

    public function getByLanguageId($languageId)
    {
        return $this->model->where('language_id', $languageId)->get();
    }

    public function delete(int $id = 0)
    {

        // dd($this->model);
        return $this->findById($id)->delete();
    }


    public function updateByWhereIn($whereInField = '', $whereIn = [], $payload = [])
    {
        $this->model->whereIn($whereInField, $whereIn)->update($payload);
    }

    public function findByCondition(array $condition = [], $flag = false, $relation = [], $orderBy = '', $direction = '')
    {
        $query = $this->model->newQuery();

        // Sửa: Kiểm tra mảng có phần tử hay không thay vì isset
        if (!empty($condition)) {
            foreach ($condition as $val) {
                $query->where($val[0], $val[1], $val[2]);
            }
        }

        $query->with($relation);

        if (!empty($orderBy)) {
            $query->orderBy($orderBy, $direction);
        }

        // dd($query->toSql(), $query->getBindings());
        return $flag ? $query->get() : $query->first();
    }

    public function findByLanguageAndKeyword($languageId, $keyword, $relationTable = '', $flag = false)
    {
        $query = $this->model->newQuery();

        $query->whereHas('languages', function ($q) use ($languageId, $keyword, $relationTable) {
            $q->where('language_id', $languageId);
            $q->where($relationTable . '.name', 'LIKE', '%' . $keyword . '%');
        });

        $query->with(['languages']);

        return ($flag) ? $query->first() : $query->get();
    }

    public function forceDelete(int $id = 0)
    {
        return $this->findById($id)->forceDelete();
    }

    public function forceDeleteByWhere(array $condition = [])
    {
        $query = $this->model->newQuery();
        foreach ($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->forceDelete();
    }

    public function createPivot($model, array $payload = [], string $relation = '')
    {

        return $model->{$relation}()->attach($model->id, $payload);
    }

    public function updateOrInsert(array $payload = [], $condition = [])
    {
        // dd($payload);
        $model = $this->model->updateOrCreate($condition, $payload);
        return $model;
    }

    public function createTranslatePivot($model, array $payload = [], string $relation = '', string $relation_id = '')
    {
        // dd($model->{$relation}()->attach($relation_id, $payload));
        return $model->{$relation}()->attach($relation_id, $payload);
    }

    public function updateByWhere($condition = [], array $payload = [])
    {
        // dd($condition);
        $query = $this->model->newQuery();
        foreach ($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->update($payload);
    }
}
