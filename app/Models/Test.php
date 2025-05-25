<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'instructions',
        'duration_minutes',
        'difficulty_level',
        'question_count',
        'is_active',
        'is_featured',
        'sort_order'
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'question_count' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Test ile kategoriler arasında many-to-many ilişki
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(TestCategory::class, 'test_category_tests')
                    ->withTimestamps();
    }

    // Test ile sorular arasında many-to-many ilişki
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'question_tests')
                    ->withPivot('order_number')
                    ->withTimestamps()
                    ->orderBy('pivot_order_number');
    }

    // Test sonuçları (userResults ve results aynı şey)
    public function userResults(): HasMany
    {
        return $this->hasMany(UserTestResult::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(UserTestResult::class);
    }

    // Aktif testleri getir
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Öne çıkan testleri getir
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Sıralı testleri getir
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    // Test slug'ından test bul
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    // Kategoriye göre testleri getir
    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('test_category_id', $categoryId);
        });
    }

    // Test istatistikleri
    public function getAverageScoreAttribute()
    {
        return $this->userResults()->completed()->avg('percentage') ?? 0;
    }

    public function getTotalAttemptsAttribute()
    {
        return $this->userResults()->completed()->count();
    }

    // Test süresi kontrolü
    public function hasTimeLimit()
    {
        return !is_null($this->duration_minutes) && $this->duration_minutes > 0;
    }

    // Test zorluk rengini getir
    public function getDifficultyColorAttribute()
    {
        return match($this->difficulty_level) {
            'Kolay' => 'green',
            'Orta' => 'yellow', 
            'Zor' => 'red',
            'Kolay-Orta' => 'blue',
            'Orta-Zor' => 'orange',
            default => 'purple'
        };
    }

    // URL oluştur
    public function getUrlAttribute()
    {
        return route('ogrenci.tests.show', $this->slug);
    }

    public function getStartUrlAttribute()
    {
        return route('ogrenci.tests.start', $this->slug);
    }
}