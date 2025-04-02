<?php

namespace App\Models\PrivateLesson;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivateLessonMaterial extends Model
{
    protected $fillable = [
        'occurrence_id',
        'title',
        'description',
        'file_path',
    ];

    /**
     * Get the occurrence for this material
     */
    public function occurrence(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonOccurrence::class, 'occurrence_id');
    }
}