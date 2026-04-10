<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'user_id',
        'starts_at',
        'expires_at',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at'  => 'datetime',
            'expires_at' => 'datetime',
            'is_active'  => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Membership şu an geçerli mi?
     */
    public function isValid(): bool
    {
        return $this->is_active && $this->expires_at->isFuture();
    }

    /**
     * Kalan gün sayısı
     */
    public function remainingDays(): int
    {
        if (!$this->isValid()) return 0;
        return (int) now()->diffInDays($this->expires_at, false);
    }

    /**
     * Süresi dolmuşları getir
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Aktif ve geçerli olanları getir
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)->where('expires_at', '>', now());
    }
}