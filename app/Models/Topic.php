<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    protected $fillable = [
        'topic_category_id',
        'name',
        'description',
        'level',
        'order',
        'is_active',
    ];

    /**
     * Get the category this topic belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TopicCategory::class, 'topic_category_id');
    }

    /**
     * Get the session topics that reference this topic
     */
    public function sessionTopics(): HasMany
    {
        return $this->hasMany(SessionTopic::class);
    }
}