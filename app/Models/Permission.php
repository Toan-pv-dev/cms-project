<?php

namespace App\Models;

use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Permission extends Model
{
    use HasFactory, QueryScope;
    protected $table = 'permission';

    protected $fillable = [
        'name',
        'canonical',
    ];
    public function permissions()
    {
        return $this->belongsToMany(UserCatalogue::class, 'user_catalogue_permisson', 'permission_id', 'user_catalogue_id');
    }
}
