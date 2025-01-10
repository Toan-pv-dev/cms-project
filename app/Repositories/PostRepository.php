<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 *  App\Services
 */
class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    protected $model;
    public function __construct(
        Post $model
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
    public function getPostById(int $id = 0, $language_id = 0)
    {
        return $this->model
            ->select([
                'posts.id',
                'posts.post_catalogue_id',
                'posts.image',
                'posts.icon',
                'posts.album',
                'posts.follow',
                'posts.publish',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',

            ])
            ->join('post_language as tb2', 'tb2.post_id', '=', 'posts.id')
            ->with('post_catalogues')
            ->where('tb2.language_id', '=', $language_id)
            ->findOrFail($id);
    }
}
