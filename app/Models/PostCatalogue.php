<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class PostCatalogue extends Model
{
    protected $table = 'post_catalogues';

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
        'image'
    ];


    public function languages()
    {
        return $this->belongsToMany(Language::class, 'post_catalogue_language', 'post_catalogue_id', 'language_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function post_catalogue_language()
    {
        return $this->hasMany(PostCatalogueLanguage::class, 'post_catalogue_id', 'id');
    }
    public static function isChildrenNode($id = 0)
    {
        $postCatalogue = PostCatalogue::find($id);
        if ($postCatalogue->rgt - $postCatalogue->lft !== 1) {
            return false;
        }
        return true;
    }
}
