<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;



class MenuCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScope;
    protected $table = "menu_catalogues";
    protected $fillable = [
        'name',
        'keyword',
        'publish',
    ];
}
