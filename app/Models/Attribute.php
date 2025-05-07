<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;

class Attribute extends Model
{
    protected $table = 'attributes';

    use HasFactory, SoftDeletes, QueryScope;
    protected $fillable = [
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'image',
        'user_id',
        'attribute_catalogue_id'
    ];


    public function languages()
    {
        return $this->belongsToMany(Language::class, 'attribute_language', 'attribute_id', 'language_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function attribute_catalogues()
    {
        return $this->belongsToMany(AttributeCatalogue::class, 'attribute_catalogue_attribute', 'attribute_id', 'attribute_catalogue_id');
    }
    public function attribute_language()
    {
        return $this->hasMany(AttributeLanguage::class, 'attribute_id', 'id');
    }
}
