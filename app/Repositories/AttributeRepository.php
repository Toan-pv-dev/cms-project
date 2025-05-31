<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Models\AttributeLanguage;
use App\Repositories\Interfaces\AttributeRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 *  App\Services
 */
class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    protected $model;
    public function __construct(
        Attribute $model
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
    public function getAttributeById(int $id = 0, $language_id = 0)
    {

        return $this->model
            ->select([
                'attributes.id',
                'attributes.attribute_catalogue_id',
                'attributes.image',
                'attributes.icon',
                'attributes.album',
                'attributes.follow',
                'attributes.publish',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',

            ])
            ->join('attribute_language as tb2', 'tb2.attribute_id', '=', 'attributes.id')
            ->where('tb2.language_id', '=', $language_id)
            ->find($id);
    }

    public function searchAttributes(string $keyword, array $option, int $language)
    {
        return $this->model->whereHas('attribute_catalogues', function ($query) use ($option) {
            $query->where('attribute_catalogue_id', $option['attributeCatalogueId']);
        })->whereHas('attribute_language', function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        })->get();
    }

    public function findAttributeByCondition(array $attributeIds, int $languageId)
    {
        return $this->model->whereIn('id', $attributeIds)->whereHas('attribute_language', function ($query) use ($languageId) {
            $query->where('language_id', $languageId);
        })->get();
    }
}
