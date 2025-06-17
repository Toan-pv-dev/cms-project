<?php

namespace App\Models;

use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory, QueryScope;

    protected $table = 'systems';
    protected $fillable = [
        'language_id',
        'user_id',
        'keyword',
        'content',
    ];
}
