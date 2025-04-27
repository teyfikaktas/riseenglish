<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TopicCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'order',
        'is_active',
    ];

    /**
     * Get the topics in this category
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }
}