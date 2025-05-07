<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class First_ModelCatalogue extends Model
{
    protected $table = 'first_model_catalogues';

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
    ];

    public function first_models()
    {
        return $this->belongsTo(First_Model::class, 'first_model_catalogue_first_model', 'first_model_catalogue_id', 'first_model_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'first_model_catalogue_language', 'first_model_catalogue_id', 'language_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function first_model_catalogue_language()
    {
        return $this->hasMany(First_ModelCatalogueLanguage::class, 'first_model_catalogue_id', 'id');
    }
    public static function isChildrenNode($id = 0)
    {
        $first_modelCatalogue = First_ModelCatalogue::find($id);
        if ($first_modelCatalogue->rgt - $first_modelCatalogue->lft !== 1) {
            return false;
        }
        return true;
    }
}
