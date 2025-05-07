<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


trait QueryScope
{
    public function __construct() {}
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }
    }
    public function scopePublish($query, $keyword)
    {
        if (isset($keyword) && $keyword !== null) {
            if ($keyword != -1) {
                $table = $query->getModel()->getTable();
                $query->where("{$table}.publish", '=', $keyword);
            }
        }
    }
    public function scopeCustomeWhere($query, $where = [])
    {
        if (!empty($where)) {
            // dd($where);
            foreach ($where as $key => $val) {
                $query->where($val[0], $val[1], $val[2]);
            }
        }
        return $query;
    }
    public function scopeCustomeWhereRaw($query, $rawQuery = [])
    {
        // dd($rawQuery);
        if (!empty($rawQuery['whereRaw'])) {

            foreach ($rawQuery['whereRaw'] as $key => $val) {

                $query->whereRaw($val[0], $val[1]);
            }
        }
        // return $query;
    }
    public function scopeRelationCount($query, $relation)
    {
        if (!empty($relation)) {
            foreach ($relation as $val) {
                $query->withCount($val);
                $query->with($val);
            }
        }
        return $query;
    }
    public function scopeRelation($query, $relation)
    {
        if (!empty($relation)) {
            foreach ($relation as $val) {
                $query->with($val);
            }
        }
        return $query;
    }
    public function scopeCustomeJoin($query, $join)
    {
        if (count($join)) {
            foreach ($join as $key => $val) {
                $query->join($val[0], $val[1], $val[2], $val[3]);
            }
        }
        return $query;
    }
    public function scopeCustomeGroupBy($query, $groupBy)
    {
        // dd($groupBy);
        if (!empty($groupBy)) {
            $query->groupBy($groupBy);
        }
        return $query;
    }
    public function scopeCustomeOrderBy($query,  $orderBy)
    {
        // dd($orderBy);
        if (!empty($orderBy)) {
            foreach ($orderBy as $key => $val) {
                $query->orderBy($key, $val);
            }
        }
        return $query;
    }
}
