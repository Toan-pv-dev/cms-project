<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductCatalogue extends Model
{
    protected $table = 'product_catalogues';

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

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_catalogue_product', 'product_catalogue_id', 'product_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'product_catalogue_language', 'product_catalogue_id', 'language_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function product_catalogue_language()
    {
        return $this->hasMany(ProductCatalogueLanguage::class, 'product_catalogue_id', 'id');
    }
    public static function isChildrenNode($id = 0)
    {
        $productCatalogue = ProductCatalogue::find($id);
        if ($productCatalogue->rgt - $productCatalogue->lft !== 1) {
            return false;
        }
        return true;
    }
}
