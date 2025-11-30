<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WordSet;
use App\Models\User;
use App\Models\Exam;

class ExamController extends Controller
{
    public function create()
    {
        try {
            $userId = auth()->id();
            
            // Word setleri çek
            $wordSets = WordSet::where('is_active', 1)
                ->where(function($query) use ($userId) {
                    $query->where('user_id', 1)
                          ->orWhere('user_id', 36)
                          ->orWhere('user_id', $userId);
                })
                ->withCount('words')
                ->select('id', 'name', 'description', 'color', 'word_count', 'user_id')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($set) use ($userId) {
                    return [
                        'id' => $set->id,
                        'name' => $set->name,
                        'description' => $set->description,
                        'color' => $set->color,
                        'word_count' => $set->words_count ?? $set->word_count,
                        'is_my_set' => $set->user_id == $userId,
                    ];
                });
            
            // Öğrencileri çek
            $students = User::role('ogrenci')
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();
            
            return view('exams.create', compact('wordSets', 'students'));
            
        } catch (\Exception $e) {
            \Log::error('Exam Create Error: ' . $e->getMessage());
            return back()->with('error', 'Bir hata oluştu');
        }
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'exam_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'time_per_question' => 'required|integer|min:5|max:300', // ✅ DEĞİŞTİ
                'word_sets' => 'required|array|min:1',
                'word_sets.*' => 'exists:word_sets,id',
                'students' => 'required|array|min:1',
                'students.*' => 'exists:users,id',
            ]);

            $exam = Exam::create([
                'teacher_id' => auth()->id(),
                'name' => $validated['exam_name'],
                'description' => $validated['description'],
                'start_time' => $validated['start_time'],
                'time_per_question' => $validated['time_per_question'], // ✅ DEĞİŞTİ
                'is_active' => true,
            ]);

            $exam->wordSets()->attach($validated['word_sets']);
            $exam->students()->attach($validated['students']);

            return redirect()
                ->route('word-sets.index')
                ->with('success', 'Sınav başarıyla oluşturuldu!');

        } catch (\Exception $e) {
            \Log::error('Exam Store Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Sınav oluşturulurken bir hata oluştu');
        }
    }
}