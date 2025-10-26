<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class PrivateLesson extends Model
{
    protected $fillable = [
        'group_id', 
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active',
    ];

    /**
     * Get all sessions for this private lesson
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(PrivateLessonSession::class);
    }
}
