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
// PDF Export - Tümü (Kelime + Türkçe)
public function exportPdfAll(WordSet $wordSet)
{
    if ($wordSet->user_id !== Auth::id()) {
        abort(403);
    }

    $words = $wordSet->userWords()->orderBy('created_at', 'desc')->get();
    $exportType = 'all';
    $title = $wordSet->name . ' - Kelime Listesi';

    $pdf = \PDF::loadView('word-sets.pdf-export', compact('wordSet', 'words', 'exportType', 'title'));
    $pdf->setPaper('a4');

    return $pdf->download('kelime_listesi_' . $wordSet->id . '.pdf');
}

// PDF Export - Sadece Türkçe
public function exportPdfTurkish(WordSet $wordSet)
{
    if ($wordSet->user_id !== Auth::id()) {
        abort(403);
    }

    $words = $wordSet->userWords()->orderBy('created_at', 'desc')->get();
    $exportType = 'turkish';
    $title = $wordSet->name . ' - Türkçe Anlamlar';

    $pdf = \PDF::loadView('word-sets.pdf-export', compact('wordSet', 'words', 'exportType', 'title'));
    $pdf->setPaper('a4');

    return $pdf->download('turkce_anlamlar_' . $wordSet->id . '.pdf');
}

// PDF Export - Sadece Kelime
public function exportPdfEnglish(WordSet $wordSet)
{
    if ($wordSet->user_id !== Auth::id()) {
        abort(403);
    }

    $words = $wordSet->userWords()->orderBy('created_at', 'desc')->get();
    $exportType = 'english';
    $title = $wordSet->name . ' - Kelimeler';

    $pdf = \PDF::loadView('word-sets.pdf-export', compact('wordSet', 'words', 'exportType', 'title'));
    $pdf->setPaper('a4');

    return $pdf->download('kelimeler_' . $wordSet->id . '.pdf');
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

    public function importExcel(Request $request, WordSet $wordSet)
{
    // Kullanıcının kendi seti mi kontrol et
    if ($wordSet->user_id !== Auth::id()) {
        abort(403);
    }

    $request->validate([
        'excel_file' => 'required|mimes:xlsx,xls,csv|max:5120'
    ]);

    try {
        $file = $request->file('excel_file');
        $excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->path());
        $sheet = $excel->getActiveSheet();
        $rows = $sheet->toArray();

        $importedCount = 0;
        $skippedCount = 0;
        $errors = [];
        $debugLog = [];

        foreach ($rows as $index => $row) {
            // Başlık satırını atla
            if ($index === 0) {
                $debugLog[] = "Satır 1 (Başlık) atlandı";
                continue;
            }

            // Null kontrol - her hücre kontrol et
            $lang = isset($row[0]) ? trim((string)$row[0]) : '';
            $english_word = isset($row[1]) ? trim((string)$row[1]) : '';
            $turkish_meaning = isset($row[2]) ? trim((string)$row[2]) : '';
            $word_type = isset($row[3]) ? trim((string)$row[3]) : null;

            // Boş satırları atla
            if (empty($english_word) && empty($turkish_meaning)) {
                $debugLog[] = "Satır " . ($index + 1) . " boş, atlandı";
                continue;
            }

            try {
                // Validasyon
                if (empty($english_word)) {
                    $errors[] = "Satır " . ($index + 1) . ": Kelime boş!";
                    $skippedCount++;
                    continue;
                }

                if (empty($turkish_meaning)) {
                    $errors[] = "Satır " . ($index + 1) . ": Anlamı boş!";
                    $skippedCount++;
                    continue;
                }

                // Dil kontrol - boşsa default "en"
                if (empty($lang) || !in_array(strtolower($lang), ['en', 'de'])) {
                    $lang = 'en';
                } else {
                    $lang = strtolower($lang);
                }

                // Aynı kelime var mı kontrol et
                if ($wordSet->userWords()->where('english_word', $english_word)->exists()) {
                    $debugLog[] = "Satır " . ($index + 1) . ": '{$english_word}' zaten var, atlandı";
                    $skippedCount++;
                    continue;
                }

                // words tablosuna ekle
                Word::create([
                    'word' => $english_word,
                    'definition' => $turkish_meaning,
                    'lang' => $lang,
                    'category' => $wordSet->id,
                    'difficulty' => 'beginner',
                    'is_active' => true
                ]);

                // user_words tablosuna ekle
                $wordSet->userWords()->create([
                    'english_word' => $english_word,
                    'turkish_meaning' => $turkish_meaning,
                    'word_type' => $word_type ?: null,
                ]);

                $debugLog[] = "Satır " . ($index + 1) . ": '{$english_word}' ✅ eklendi";
                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = "Satır " . ($index + 1) . " ({$english_word}): " . $e->getMessage();
                $skippedCount++;
            }
        }

        // Word count'u güncelle
        $wordSet->update(['word_count' => $wordSet->userWords()->count()]);

        $message = "{$importedCount} kelime başarıyla eklendi!";
        if ($skippedCount > 0) {
            $message .= " ({$skippedCount} atlandı)";
        }

        // Session'a debug log ekle
        session()->flash('import_debug', $debugLog);
        
        return back()->with('success', $message)->with('import_errors', $errors);
    } catch (\Exception $e) {
        \Log::error('Excel Import Error: ' . $e->getMessage());
        return back()->withErrors(['excel_file' => 'Excel dosyası okunurken hata oluştu: ' . $e->getMessage()]);
    }
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