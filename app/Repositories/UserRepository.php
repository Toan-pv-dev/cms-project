<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 *  App\Services
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;
    public function __construct(
        User $model
    ) {
        $this->model = $model;
    }
    public function pagination(
        $column = ['*'],
        $condition = [],
        int $perPage = 0,
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
        // dd($query);
        return $query;
    }
}
