<?php
// app/Models/TestCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

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

    // Test kategorisi ile sorular arasında doğru ilişki
    // Kategori -> Testler -> Sorular (hasManyThrough kullanarak)
    public function questions()
    {
        return $this->hasManyThrough(
            Question::class,
            Test::class,
            'id', // tests tablosundaki foreign key
            'id', // questions tablosundaki foreign key
            'id', // test_categories tablosundaki local key
            'id'  // tests tablosundaki local key
        )->join('test_category_tests', function($join) {
            $join->on('test_category_tests.test_id', '=', 'tests.id')
                 ->where('test_category_tests.test_category_id', '=', $this->id ?? 0);
        })->join('question_tests', 'question_tests.test_id', '=', 'tests.id')
          ->where('question_tests.question_id', '=', DB::raw('questions.id'));
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

    // Accessor'lar - Manuel hesaplama
    public function getTestsCountAttribute()
    {
        if (!array_key_exists('tests_count', $this->attributes)) {
            $this->attributes['tests_count'] = $this->tests()->count();
        }
        return $this->attributes['tests_count'];
    }

    public function getQuestionsCountAttribute()
    {
        if (!array_key_exists('questions_count', $this->attributes)) {
            // Manuel SQL ile hesapla
            $count = DB::table('test_category_tests')
                ->join('question_tests', 'test_category_tests.test_id', '=', 'question_tests.test_id')
                ->where('test_category_tests.test_category_id', $this->id)
                ->count();
            
            $this->attributes['questions_count'] = $count;
        }
        return $this->attributes['questions_count'];
    }

    public function getActiveTestsCountAttribute()
    {
        return $this->tests()->where('is_active', true)->count();
    }

    // Helper method - tüm kategorilerin soru sayılarını hesapla
    public static function withQuestionCounts()
    {
        return static::select('test_categories.*')
            ->selectSub(
                DB::table('test_category_tests')
                    ->join('question_tests', 'test_category_tests.test_id', '=', 'question_tests.test_id')
                    ->whereColumn('test_category_tests.test_category_id', 'test_categories.id')
                    ->selectRaw('COUNT(*)'),
                'questions_count'
            )
            ->selectSub(
                DB::table('test_category_tests')
                    ->whereColumn('test_category_tests.test_category_id', 'test_categories.id')
                    ->selectRaw('COUNT(DISTINCT test_category_tests.test_id)'),
                'tests_count'
            );
    }
}