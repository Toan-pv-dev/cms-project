<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScope;


class Generate extends Model
{
    use HasFactory, QueryScope;
    protected $table = 'generates';
}
