<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WordSetCategory extends Model
{
    protected $fillable = ['user_id', 'parent_id', 'name', 'color', 'sort_order'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(WordSetCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(WordSetCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    public function wordSets(): HasMany
    {
        return $this->hasMany(WordSet::class, 'category_id');
    }

    // Tüm ağacı döner (root'tan itibaren)
    public static function tree(int $userId)
    {
        return self::where('user_id', $userId)
            ->whereNull('parent_id')
            ->with('allChildren.wordSets')
            ->orderBy('sort_order')
            ->get();
    }

    // Bu kategorinin tüm üst zinciri (breadcrumb için)
    public function ancestors(): array
    {
        $ancestors = [];
        $category = $this;

        while ($category->parent_id !== null) {
            $category = $category->parent;
            array_unshift($ancestors, $category);
        }

        return $ancestors;
    }

    // Root kategori mi?
    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    // Leaf (alt kategorisi yok) mu?
    public function isLeaf(): bool
    {
        return $this->children()->count() === 0;
    }
}