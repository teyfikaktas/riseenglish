<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionTopic extends Model
{
    protected $fillable = [
        'session_id',
        'topic_id',
        'notes',
        'created_by',
    ];

    /**
     * Get the session this topic belongs to
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonSession::class, 'session_id');
    }

    /**
     * Get the topic
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the user who created this record
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}