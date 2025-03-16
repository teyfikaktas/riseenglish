<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentType extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    // Bu tipe ait değerlendirmeler
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'type_id');
    }
}