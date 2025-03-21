<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;


class UserCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScope;
    protected $fillable = [
        'name',
        'description',
        'user_catalogue_id',
        'deleted_at',
        'publish'
    ];


    protected $table = 'userCatalogue';
    public function users()
    {
        return $this->hasMany(User::class, 'user_catalogue_id', 'id');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_catalogue_permisson', 'user_catalogue_id', 'permission_id');
    }
}
