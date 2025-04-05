<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class PrivateLessonTeacherRole extends Model
{
    protected $fillable = [
        'user_id',
        'can_teach_private',
        'can_teach_group',
    ];

    /**
     * Get the user for this teacher role
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}