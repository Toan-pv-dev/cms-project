<?php

namespace App\Repositories;

use App\Models\Slide;
use App\Repositories\Interfaces\SlideRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 *  App\Services
 */
class SlideRepository extends BaseRepository implements SlideRepositoryInterface
{
    protected $model;
    public function __construct(
        Slide $model
    ) {
        // dd($model);
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

        if (!empty($condition['language_id'])) {
            $query->where('language_id', $condition['language_id']);
        }
        // dd($query);
        return $query;
    }
    public function getSlideById(int $id = 0, $language_id = 0)

    {
        return $this->model
            ->select([
                'products.id',
                'products.product_catalogue_id',
                'products.image',
                'products.icon',
                'products.album',
                'products.follow',
                'products.publish',
                'products.attributes',
                'products.attributeCatalogue',
                'products.variants',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',

            ])
            ->join('product_language as tb2', 'tb2.product_id', '=', 'products.id')
            ->with(
                [
                    'product_catalogues',
                    'product_variants' => function ($query) use ($language_id) {
                        $query->with(['attributes' => function ($query) use ($language_id) {
                            $query->with(['attribute_language' => function ($query) use ($language_id) {
                                $query->where('language_id', '=', $language_id);
                            }]);
                        }]);
                    }
                ]
            )
            ->where('tb2.language_id', '=', $language_id)
            ->findOrFail($id);
    }
}
