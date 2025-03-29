<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function resources()
    {
        return $this->belongsToMany(Resource::class, 'resource_tag', 'tag_id', 'resource_id');
    }
}