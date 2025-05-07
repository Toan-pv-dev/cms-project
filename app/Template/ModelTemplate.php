<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class ModelNameCatalogue extends Model
{
    protected $table = 'model_name_catalogues';

    use HasFactory, SoftDeletes;
    protected $fillable = [
        'parent_id',
        'lft',
        'rgt',
        'level',
        'user_id',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'image',
        'follow',
    ];

    public function model_names()
    {
        return $this->belongsTo(ModelName::class, 'model_name_catalogue_model_name', 'model_name_catalogue_id', 'model_name_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'model_name_catalogue_language', 'model_name_catalogue_id', 'language_id')->withPivot('model_name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function model_name_catalogue_language()
    {
        return $this->hasMany(ModelNameCatalogueLanguage::class, 'model_name_catalogue_id', 'id');
    }
    public static function isChildrenNode($id = 0)
    {
        $model_nameCatalogue = ModelNameCatalogue::find($id);
        if ($model_nameCatalogue->rgt - $model_nameCatalogue->lft !== 1) {
            return false;
        }
        return true;
    }

    // ModuleTemplate
    // tableModelName
    // module_ModelName
    // relation
    // Relation
    // model_nameCatalogue

}
