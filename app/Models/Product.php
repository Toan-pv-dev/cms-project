<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;

class Product extends Model
{
    protected $table = 'products';

    use HasFactory, SoftDeletes, QueryScope;
    protected $fillable = [
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'image',
        'user_id',
        'product_catalogue_id'
    ];


    public function languages()
    {
        return $this->belongsToMany(Language::class, 'product_language', 'product_id', 'language_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function product_catalogues()
    {
        return $this->belongsToMany(ProductCatalogue::class, 'product_catalogue_product', 'product_id', 'product_catalogue_id');
    }
}
