<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\QueryScope;

class Slide extends Model
{
    use HasFactory, QueryScope;
    protected $table = 'slides';
    protected $fillable = [
        'name',
        'keyword',
        'description',
        'item',
        'short_code',
        'setting',
        'publish',
    ];
    protected $casts = [
        'setting' => 'json',
        'item' => 'json',
    ];
}
