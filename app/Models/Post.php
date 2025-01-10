<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;

class Post extends Model
{
    protected $table = 'posts';

    use HasFactory, SoftDeletes, QueryScope;
    protected $fillable = [
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'image',
        'user_id',
        'post_catalogue_id'
    ];


    public function languages()
    {
        return $this->belongsToMany(Language::class, 'post_language', 'post_id', 'language_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function post_catalogues()
    {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_post', 'post_id', 'post_catalogue_id');
    }
}
