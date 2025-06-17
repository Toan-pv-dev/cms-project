<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Language extends Model
{
    protected $table = 'languages';

    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'canonical',
        'publish',
        'user_id',
        'image',
        'current'
    ];


    public function postCatalogue()
    {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_language', 'language_id', 'post_catalogue_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function productCatalogue()
    {
        return $this->belongsToMany(ProductCatalogue::class, 'product_catalogue_language', 'language_id', 'product_catalogue_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function galleryCatalogue()
    {
        return $this->belongsToMany(GalleryCatalogue::class, 'gallery_catalogue_language', 'language_id', 'gallery_catalogue_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function AttributeCatalogue()
    {
        return $this->belongsToMany(AttributeCatalogue::class, 'attribute_catalogue_language', 'language_id', 'attribute_catalogue_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function attribute()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_language', 'language_id', 'attribute_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function product_variant()
    {
        return $this->belongsToMany(Product::class, 'product_variant_language', 'language_id', 'product_variant_id')->withPivot('name')->withTimestamps();
    }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_language', 'language_id', 'menu_id')->withPivot('name', 'canonical')->withTimestamps();
    }
}
