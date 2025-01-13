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
}