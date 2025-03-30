
<?php

// app/Models/Idiom.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idiom extends Model
{
    use HasFactory;

    protected $fillable = [
        'english_phrase',
        'turkish_translation',
        'example_sentence_1',
        'example_sentence_2',
        'image_path',
        'is_active',
        'display_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_date' => 'date',
    ];
}
