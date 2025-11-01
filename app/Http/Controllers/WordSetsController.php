<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Models\WordSet;
use App\Models\UserWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WordSetsController extends Controller
{
    // Ana sayfa - Kelime setlerini listele
    public function index()
    {
        $wordSets = WordSet::where('user_id', Auth::id())
            ->withCount('userWords')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('word-sets.index', compact('wordSets'));
    }

    // Yeni set oluşturma sayfası
    public function create()
    {
        return view('word-sets.create');
    }

    // Yeni set kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        WordSet::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
        ]);

        return redirect()->route('word-sets.index')
            ->with('success', 'Kelime seti başarıyla oluşturuldu!');
    }

    // Set düzenleme sayfası
    public function edit(WordSet $wordSet)
    {
        // Kullanıcının kendi seti mi kontrol et
        if ($wordSet->user_id !== Auth::id()) {
            abort(403);
        }

        return view('word-sets.edit', compact('wordSet'));
    }

    // Set güncelleme
    public function update(Request $request, WordSet $wordSet)
    {
        // Kullanıcının kendi seti mi kontrol et
        if ($wordSet->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        $wordSet->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
        ]);

        return redirect()->route('word-sets.index')
            ->with('success', 'Kelime seti başarıyla güncellendi!');
    }

    // Set silme
    public function destroy(WordSet $wordSet)
    {
        // Kullanıcının kendi seti mi kontrol et
        if ($wordSet->user_id !== Auth::id()) {
            abort(403);
        }

        $wordSet->delete();

        return redirect()->route('word-sets.index')
            ->with('success', 'Kelime seti başarıyla silindi!');
    }

    // Set detayı ve kelimeler
    public function show(WordSet $wordSet)
    {
        // Kullanıcının kendi seti mi kontrol et
        if ($wordSet->user_id !== Auth::id()) {
            abort(403);
        }

        $words = $wordSet->userWords()->orderBy('created_at', 'desc')->get();

        return view('word-sets.show', compact('wordSet', 'words'));
    }
    // Kelime ekleme
public function addWord(Request $request, WordSet $wordSet)
{
    // Kullanıcının kendi seti mi kontrol et
    if ($wordSet->user_id !== Auth::id()) {
        abort(403);
    }

    $request->validate([
        'lang' => 'required|in:en,de',
        'english_word' => 'required|string|max:255',
        'turkish_meaning' => 'required|string|max:255',
        'word_type' => 'nullable|string|max:50'
    ]);

    // Aynı kelime var mı kontrol et
    if ($wordSet->userWords()->where('english_word', $request->english_word)->exists()) {
        return back()->withErrors(['english_word' => 'Bu kelime zaten bu sette mevcut!']);
    }

    // words tablosuna ekle (dil bilgisi ile)
    Word::create([
        'word' => $request->english_word,
        'definition' => $request->turkish_meaning,
        'lang' => $request->lang, // Kullanıcının seçtiği dil
        'category' => $wordSet->id,
        'difficulty' => 'beginner',
        'is_active' => true
    ]);

    // user_words tablosuna ekle
    $wordSet->userWords()->create([
        'english_word' => $request->english_word,
        'turkish_meaning' => $request->turkish_meaning,
        'word_type' => $request->word_type,
    ]);

    // Word count güncelle
    $wordSet->update(['word_count' => $wordSet->words()->count()]);

    $langName = $request->lang === 'en' ? 'İngilizce' : 'Almanca';
    return back()->with('success', "Kelime başarıyla eklendi! ({$langName})");
}

// Kelime silme
public function deleteWord(WordSet $wordSet, UserWord $userWord)
{
    // Kullanıcının kendi seti mi kontrol et
    if ($wordSet->user_id !== Auth::id() || $userWord->word_set_id !== $wordSet->id) {
        abort(403);
    }

    // words tablosundan sil
    Word::where('category', $wordSet->id)
        ->where('word', $userWord->english_word)
        ->delete();

    // user_words tablosundan sil
    $userWord->delete();

    // Word count'u güncelle
    $wordSet->update(['word_count' => $wordSet->words()->count()]);

    return back()->with('success', 'Kelime başarıyla silindi!');
}
}