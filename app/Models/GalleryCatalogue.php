<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class GalleryCatalogue extends Model
{
    protected $table = 'gallery_catalogues';

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

    public function gallerys()
    {
        return $this->belongsTo(Gallery::class, 'gallery_catalogue_gallery', 'gallery_catalogue_id', 'gallery_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'gallery_catalogue_language', 'gallery_catalogue_id', 'language_id')->withPivot('gallery', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function gallery_catalogue_language()
    {
        return $this->hasMany(GalleryCatalogueLanguage::class, 'gallery_catalogue_id', 'id');
    }
    public static function isChildrenNode($id = 0)
    {
        $galleryCatalogue = GalleryCatalogue::find($id);
        if ($galleryCatalogue->rgt - $galleryCatalogue->lft !== 1) {
            return false;
        }
        return true;
    }

    // ModuleTemplate
    // tableGallery
    // module_Gallery
    // relation
    // Relation
    // galleryCatalogue

}
