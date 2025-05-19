<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChainActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chain_progress_id',
        'teacher_id',
        'content',
        'file_path',
        'file_name',
        'file_type',
        'activity_date',
        'is_adjustment' // Bu alan eksikti, ekledik
    ];

    protected $casts = [
        'activity_date' => 'date',
        'is_adjustment' => 'boolean' // Bu da eksikti, ekledik
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function chainProgress()
    {
        return $this->belongsTo(ChainProgress::class);
    }
    
}