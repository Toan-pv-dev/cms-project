<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;

class ModelName extends Model
{
    protected $table = 'modelNames';

    use HasFactory, SoftDeletes, QueryScope;
    protected $fillable = [
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'image',
        'user_id',
        'modelName_catalogue_id'
    ];


    public function languages()
    {
        return $this->belongsToMany(Language::class, 'modelName_language', 'modelName_id', 'language_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function modelName_catalogues()
    {
        return $this->belongsToMany(ModelNameCatalogue::class, 'modelName_catalogue_modelName', 'modelName_id', 'modelName_catalogue_id');
    }
}
