<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image_path',
        'type_id',
        'category_id',
        'is_free',
        'is_popular',
        'download_count',
        'view_count',
        'file_path'
    ];

    public function type()
    {
        return $this->belongsTo(ResourceType::class, 'type_id');
    }

    public function category()
    {
        return $this->belongsTo(ResourceCategory::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(ResourceTag::class, 'resource_tag', 'resource_id', 'tag_id');
    }
}