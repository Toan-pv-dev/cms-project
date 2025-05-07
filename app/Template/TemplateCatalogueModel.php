<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class {ModuleTemplate} extends Model
{
    protected $table = '{tableName}';

    use HasFactory, SoftDeletes;
    protected $fillable = [
        'parent_id',
        'lft',
        'rgt',
        'level',
        'user_id',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'image',
    ];

    public function {relation}s()
    {
        return $this->belongsTo({Relation}::class, '{module_Name}_{relation}', '{module_Name}_id', '{relation}_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, '{module_Name}_language', '{module_Name}_id', 'language_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }
    public function {module_Name}_language()
    {
        return $this->hasMany({ModuleTemplate}Language::class, '{module_Name}_id', 'id');
    }
    public static function isChildrenNode($id = 0)
    {
        $moduleTemplate = {ModuleTemplate}::find($id);
        if ($moduleTemplate->rgt - $moduleTemplate->lft !== 1) {
            return false;
        }
        return true;
    }

    // ModuleTemplate 
    // tableName
    // module_Name
    // relation
    // Relation
    // moduleTemplate
    
}