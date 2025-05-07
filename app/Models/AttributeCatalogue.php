<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class AttributeCatalogue extends Model
{
    protected $table = 'attribute_catalogues';

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
        // 'language_id',
    ];

    public function attributes()
    {
        return $this->belongsTo(Attribute::class, 'attribute_catalogue_attribute', 'attribute_catalogue_id', 'attribute_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'attribute_catalogue_language', 'attribute_catalogue_id', 'language_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function attribute_catalogue_language()
    {
        return $this->hasMany(AttributeCatalogueLanguage::class, 'attribute_catalogue_id', 'id');
    }
    public static function isChildrenNode($id = 0)
    {
        $attributeCatalogue = AttributeCatalogue::find($id);
        if ($attributeCatalogue->rgt - $attributeCatalogue->lft !== 1) {
            return false;
        }
        return true;
    }
}
