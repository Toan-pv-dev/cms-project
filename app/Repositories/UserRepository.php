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
    public function pagination($column = ['*'], $condition = [], int $perPage = 1, array $extend = [], array $relations = [], array $orderBy = [], $join = [])
    {
        // dd($condition['publish']);
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%')
                    ->orWhere('email', 'LIKE', '%' . $condition['keyword'] . '%')
                    ->orWhere('address', 'LIKE', '%' . $condition['keyword'] . '%')
                    ->orWhere('phone', 'LIKE', '%' . $condition['keyword'] . '%');
            }
            // dd($condition);
            if (isset($condition['publish']) && $condition['publish'] !== null) {
                if ($condition['publish'] != -1) {
                    $query->where('publish', '=', $condition['publish']);
                }
            }
            return $query;
        })->with('userCatalogue');

        // echo 1;
        // die();
        if (!empty($join)) {
            $query->join(...$join);
        }
        // $user = User::with('userCatalogue')->find(1); // Eager load userCatalogue
        // if ($user->relationLoaded('userCatalogue')) {
        //     echo "UserCatalogue đã được nạp.";
        //     die();
        // } else {
        //     echo "UserCatalogue chưa được nạp.";
        //     die();
        // }
        // dd($query);
        return $query->paginate($perPage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }
}
