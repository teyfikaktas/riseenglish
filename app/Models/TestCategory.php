<?php
// app/Models/TestCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TestCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'difficulty_level',
        'color',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Test kategorisi ile testler arasında many-to-many ilişki
    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, 'test_category_tests')
                    ->withTimestamps();
    }

    // Test kategorisi ile sorular arasında many-to-many ilişki
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'question_test_categories')
                    ->withTimestamps();
    }

    // Aktif kategorileri getir
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Sıralı kategorileri getir
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Kategori slug'ından kategori bul
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }
}