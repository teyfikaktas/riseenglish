<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_category_id',
        'question_text',
        'question_type',
        'question_image',
        'options',
        'correct_answer',
        'difficulty_level',
        'points',
        'explanation',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'points' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // İlişkiler
    public function category(): BelongsTo
    {
        return $this->belongsTo(QuestionCategory::class, 'question_category_id');
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, 'question_tests')
                    ->withPivot('order_number')
                    ->withTimestamps()
                    ->orderBy('pivot_order_number');
    }

    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class)->orderBy('order_number');
    }

    public function correctChoice(): HasOne
    {
        return $this->hasOne(Choice::class)->where('is_correct', true);
    }

    public function userAnswers(): HasMany
    {
        return $this->hasMany(UserTestAnswer::class);
    }

    // Scope'lar
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('question_type', $type);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('question_category_id', $categoryId);
    }

    public function scopeByTest($query, $testId)
    {
        return $query->whereHas('tests', function ($q) use ($testId) {
            $q->where('test_id', $testId);
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function scopeWithChoices($query)
    {
        return $query->with(['choices' => function ($q) {
            $q->orderBy('order_number');
        }]);
    }

    // Yardımcı metodlar
    public function isCorrectAnswer($choiceId): bool
    {
        $correctChoice = $this->correctChoice;
        return $correctChoice && $correctChoice->id == $choiceId;
    }

    public function getCorrectChoiceId()
    {
        $correctChoice = $this->correctChoice;
        return $correctChoice ? $correctChoice->id : null;
    }

    public function hasImage(): bool
    {
        return !empty($this->question_image);
    }

    public function getImageUrl(): ?string
    {
        return $this->question_image ? asset('storage/' . $this->question_image) : null;
    }

    public function getDifficultyColor(): string
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

    public function getPointsValue(): int
    {
        return $this->points ?? 1;
    }

    // Attribute'ler
    public function getDifficultyColorAttribute(): string
    {
        return $this->getDifficultyColor();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->getImageUrl();
    }

    public function getCorrectChoiceIdAttribute()
    {
        return $this->getCorrectChoiceId();
    }

    // Statik metodlar
    public static function getQuestionTypes(): array
    {
        return [
            'multiple_choice' => 'Çoktan Seçmeli',
            'true_false' => 'Doğru/Yanlış',
            'fill_blank' => 'Boşluk Doldurma',
            'matching' => 'Eşleştirme',
            'ordering' => 'Sıralama'
        ];
    }

    public static function getDifficultyLevels(): array
    {
        return [
            'Kolay' => 'Kolay',
            'Orta' => 'Orta',
            'Zor' => 'Zor',
            'Kolay-Orta' => 'Kolay-Orta',
            'Orta-Zor' => 'Orta-Zor'
        ];
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($question) {
            // Soru silinirken seçenekleri de sil
            $question->choices()->delete();
        });
    }
}