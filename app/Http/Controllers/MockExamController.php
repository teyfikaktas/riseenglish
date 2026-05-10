<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MockExam;
use App\Models\WordSet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\GuestExamResult;

class MockExamController extends Controller
{
    public function index(Request $request)
    {
        $query = MockExam::where('teacher_id', auth()->id())
            ->with('wordSet')
            ->withCount('results');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $mockExams = $query->orderBy('is_active', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('mock-exams.index', compact('mockExams'));
    }

    public function create()
    {
        try {
            $userId = auth()->id();

            $categoryTree = \App\Models\WordSetCategory::whereNull('parent_id')
                ->with('allChildren')
                ->orderBy('sort_order')
                ->get();

            $teacherWordSetsRaw = WordSet::where('is_active', 1)
                ->where(function ($query) use ($userId) {
                    $query->where('user_id', 1)
                          ->orWhere('user_id', 36)
                          ->orWhere('user_id', $userId);
                })
                ->withCount('words')
                ->select('id', 'name', 'description', 'color', 'user_id', 'word_count', 'category_id')
                ->orderBy('created_at', 'desc')
                ->get();

            $uncategorizedSets = $teacherWordSetsRaw->whereNull('category_id')->values();
            $categorizedSets   = $teacherWordSetsRaw->whereNotNull('category_id')->groupBy('category_id');

            return view('mock-exams.create', compact(
                'categoryTree', 'categorizedSets', 'uncategorizedSets'
            ));

        } catch (\Exception $e) {
            Log::error('MockExam Create Error: ' . $e->getMessage());
            return back()->with('error', 'Bir hata oluştu');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'              => 'required|string|max:255',
                'description'       => 'nullable|string',
                'start_time'        => 'required|date',
                'time_per_question' => 'required|integer|min:5|max:300',
                'word_set_id'       => 'required|exists:word_sets,id',
            ]);

            $mockExam = MockExam::create([
                'teacher_id'        => auth()->id(),
                'word_set_id'       => $validated['word_set_id'],
                'name'              => $validated['name'],
                'description'       => $validated['description'] ?? null,
                'start_time'        => $validated['start_time'],
                'time_per_question' => $validated['time_per_question'],
                'is_active'         => true,
            ]);

            return redirect()
                ->route('mock-exams.index')
                ->with('success', 'Deneme sınavı oluşturuldu! Kod: ' . $mockExam->code);

        } catch (\Exception $e) {
            Log::error('MockExam Store Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Deneme sınavı oluşturulurken bir hata oluştu.');
        }
    }

    public function guestCheckExam(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $mockExam = MockExam::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (!$mockExam) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veya pasif sınav kodu.',
            ], 404);
        }

        $now = now();

        if ($mockExam->start_time > $now) {
            return response()->json([
                'success'    => false,
                'message'    => 'Sınav henüz başlamadı.',
                'start_time' => $mockExam->start_time,
            ], 403);
        }

        if ($mockExam->start_time < $now->copy()->subMinutes(5)) {
            return response()->json([
                'success' => false,
                'message' => 'Sınav giriş süresi dolmuştur.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sınav aktif, giriş yapabilirsiniz.',
            'exam'    => [
                'id'         => $mockExam->id,
                'name'       => $mockExam->name,
                'start_time' => $mockExam->start_time,
            ],
        ]);
    }

    public function guestSendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $otp = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        Cache::put('guest_otp_' . $request->phone, $otp, now()->addMinutes(5));

        $smsContent = sprintf(
            'Rise English deneme sınavı giriş kodunuz: %s (5 dakika geçerlidir)',
            $otp
        );

        try {
            \App\Services\SmsService::sendSms($request->phone, $smsContent);
            Log::info('Guest OTP gönderildi', ['phone' => $request->phone]);
        } catch (\Exception $e) {
            Log::error('Guest OTP SMS hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'SMS gönderilemedi. Lütfen tekrar deneyin.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Doğrulama kodu gönderildi.',
        ]);
    }

    public function guestVerifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp'   => 'required|string',
        ]);

        $stored = Cache::get('guest_otp_' . $request->phone);

        if (!$stored || $stored !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Kod hatalı veya süresi dolmuş.',
            ], 422);
        }

        Cache::forget('guest_otp_' . $request->phone);

        return response()->json([
            'success' => true,
            'message' => 'Telefon numarası doğrulandı.',
        ]);
    }

    public function guestVerify(Request $request)
    {
        $request->validate([
            'code'  => 'required|string',
            'name'  => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
        ]);

        $mockExam = MockExam::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->with('wordSet.words')
            ->first();

        if (!$mockExam) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veya pasif sınav kodu.',
            ], 404);
        }

        $existing = GuestExamResult::where('mock_exam_id', $mockExam->id)
            ->where('phone', $request->phone)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Bu telefon numarası ile daha önce bu sınava giriş yapılmıştır.',
            ], 409);
        }

        GuestExamResult::create([
            'mock_exam_id' => $mockExam->id,
            'phone'        => $request->phone,
            'name'         => $request->name,
            'email'        => $request->email ?? null,
        ]);

        $words = $mockExam->wordSet->words->shuffle();

        $questions = $words->map(function ($word) use ($words) {
            $wrongOptions = $words
                ->where('id', '!=', $word->id)
                ->shuffle()
                ->take(3)
                ->pluck('definition')
                ->toArray();

            $options = collect($wrongOptions)
                ->push($word->definition)
                ->shuffle()
                ->values()
                ->toArray();

            return [
                'id'             => $word->id,
                'question'       => $word->word,
                'options'        => $options,
                'correct_answer' => $word->definition,
            ];
        });

        return response()->json([
            'success' => true,
            'exam'    => [
                'id'                => $mockExam->id,
                'name'              => $mockExam->name,
                'time_per_question' => $mockExam->time_per_question,
                'total_questions'   => $questions->count(),
                'questions'         => $questions,
            ],
        ]);
    }

    public function guestSubmit(Request $request)
    {
        $request->validate([
            'mock_exam_id' => 'required|exists:mock_exams,id',
            'name'         => 'required|string',
            'phone'        => 'required|string',
            'answers'      => 'required|array',
        ]);

        try {
            $answers         = $request->answers;
            $totalQuestions  = count($answers);
            $correct         = 0;
            $detailedAnswers = [];

            foreach ($answers as $answer) {
                $isCorrect = $answer['selected'] === $answer['correct'];
                if ($isCorrect) $correct++;

                $detailedAnswers[] = [
                    'word_id'    => $answer['word_id'],
                    'selected'   => $answer['selected'],
                    'correct'    => $answer['correct'],
                    'is_correct' => $isCorrect,
                ];
            }

            $successRate = $totalQuestions > 0
                ? round(($correct / $totalQuestions) * 100, 2)
                : 0;

            GuestExamResult::where('mock_exam_id', $request->mock_exam_id)
                ->where('phone', $request->phone)
                ->update([
                    'score'           => $correct,
                    'total_questions' => $totalQuestions,
                    'success_rate'    => $successRate,
                    'answers'         => json_encode($detailedAnswers),
                    'completed_at'    => now(),
                ]);

            return response()->json([
                'success'         => true,
                'score'           => $correct,
                'total_questions' => $totalQuestions,
                'success_rate'    => $successRate,
            ]);

        } catch (\Exception $e) {
            Log::error('GuestExam Submit Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu.',
            ], 500);
        }
    }

    public function downloadReport(MockExam $mockExam)
    {
        if ($mockExam->teacher_id !== auth()->id()) {
            abort(403, 'Bu sınava erişim yetkiniz yok.');
        }

        $enteredResults = $mockExam->results()
            ->whereNotNull('completed_at')
            ->where('score', '>=', 0)
            ->orderByDesc('success_rate')
            ->orderByDesc('score')
            ->get();

        if ($enteredResults->isEmpty()) {
            return back()->with('error', 'Bu deneme sınavına henüz katılan öğrenci yok.');
        }

        $pdf = PDF::loadView('mock-exams.report-pdf', [
            'mockExam'       => $mockExam,
            'enteredResults' => $enteredResults,
            'enteredCount'   => $enteredResults->count(),
            'date'           => $mockExam->start_time,
            'teacher'        => auth()->user(),
        ]);

        $fileName = 'Deneme_Sinav_Raporu_' . $mockExam->code . '_' . $mockExam->start_time->format('d-m-Y') . '.pdf';

        return $pdf->download($fileName);
    }

    public function destroy(MockExam $mockExam)
    {
        if ($mockExam->teacher_id !== auth()->id()) {
            abort(403, 'Bu sınavı silme yetkiniz yok.');
        }

        $mockExam->students()->detach();
        $mockExam->results()->delete();
        $mockExam->delete();

        return redirect()
            ->route('mock-exams.index')
            ->with('success', 'Deneme sınavı silindi!');
    }

    public function toggleActive(MockExam $mockExam)
    {
        if ($mockExam->teacher_id !== auth()->id()) {
            abort(403);
        }

        $mockExam->update(['is_active' => !$mockExam->is_active]);

        return back()->with('success', $mockExam->is_active ? 'Sınav aktif edildi.' : 'Sınav pasif edildi.');
    }
}