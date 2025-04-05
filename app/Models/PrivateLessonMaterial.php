<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivateLessonMaterial extends Model
{
    protected $fillable = [
        'session_id',
        'title',
        'description',
        'file_path',
    ];

    /**
     * Get the session for this material
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonSession::class, 'session_id');
    }
}