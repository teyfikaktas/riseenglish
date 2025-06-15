<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestTestAttempt extends Model
{
    protected $fillable = [
        'test_id',
        'session_id',
        'ip_address',
        'user_agent',
        'total_questions',
        'score',
        'correct_answers',
        'wrong_answers',
        'empty_answers',
        'percentage',
        'duration_seconds',
        'status',
        'started_at',
        'completed_at',
        'terminated_at',
        'termination_reason',
        'security_violations',
        'violation_details',
        'answers'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'terminated_at' => 'datetime',
        'violation_details' => 'array',
        'answers' => 'array',
        'percentage' => 'decimal:2'
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isTerminated(): bool
    {
        return $this->status === 'terminated_security';
    }

    public function getSuccessRateAttribute(): string
    {
        if ($this->total_questions === 0) {
            return '0%';
        }
        
        return number_format($this->percentage, 1) . '%';
    }

    public function getDurationFormattedAttribute(): string
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    // Scope'lar
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFromToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }
}