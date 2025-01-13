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

        $model = $this->model->create($payload);
        // dd($model);
        return $model;
    }

    public function update(int $id = 0, array $payload = [])
    {

        $model = $this->findById($id);
        // dd($id);
        return $model->update($payload);
        // Update the model instance with the provided data
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findById(int $model_id,  array $column = ['*'], array $relation = [])
    {
        return $this->model->select($column)->with($relation)->findOrFail($model_id);
    }

    public function delete(int $id = 0)
    {
        return $this->findById($id)->delete();
    }


    public function updateByWhereIn($whereInField = '', $whereIn = [], $payload = [])
    {
        $this->model->whereIn($whereInField, $whereIn)->update($payload);
    }
    public function createPivot($model, array $payload = [], string $relation = '')
    {
        // dd($payload);

        return $model->{$relation}()->attach($model->id, $payload);
    }
    public function updateByWhere($condition = [], array $payload = [])
    {
        // dd($condition);
        $query = $this->model->withTrashed();
        foreach ($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->update($payload);
    }
}