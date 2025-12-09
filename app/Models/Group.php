<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'teacher_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Grubun öğretmeni
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Gruptaki öğrenciler
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'group_user')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }
}