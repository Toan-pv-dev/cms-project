<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeCatalogueLanguage extends Model
{
    use HasFactory;
    protected $table = 'attribute_catalogue_language';
    protected $fillable = [
        'language_id',
        'name',
        'description',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'canonical',
    ];
    public function attribute_catalogues()
    {
        return $this->belongsTo(AttributeCatalogue::class, 'attribute_catalogue_language', 'id');
    }
}
