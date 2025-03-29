<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(ResourceCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ResourceCategory::class, 'parent_id');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class, 'category_id');
    }
}