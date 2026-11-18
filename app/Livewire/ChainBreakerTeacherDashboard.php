<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ChainProgress;
use App\Models\ChainActivity;
use App\Models\ExamResult;
use App\Models\Word;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ChainBreakerTeacherDashboard extends Component
{
    use WithPagination;
    
    public $selectedStudent = null;
    public $searchTerm = '';
    public $showActivityDetail = false;
    public $selectedDate = null;
    public $selectedActivities = [];
    public $selectedStudentQuizResults = [];
    public $selectedQuizStudent = null;
    public $selectedExamResult = null;
    public $selectedExamAnswerDetails = [];
    public $showQuizResults = false;
    public $adjustDays = 0;
    public $adjustReason = '';
    
    protected $queryString = ['searchTerm'];

    public function mount()
    {
        // Öğretmen kontrolü
        if (!Auth::user()->hasRole('ogretmen')) {
            abort(403);
        }
    }

    public function selectStudent($studentId)
    {
        $this->selectedStudent = User::with('chainProgress', 'chainActivities')->find($studentId);
        $this->showActivityDetail = false;
        $this->selectedDate = null;
    }

    public function viewDateActivities($date)
    {
        $this->selectedDate = $date;
        $this->showActivityDetail = true;
        
        if ($this->selectedStudent) {
            $this->selectedActivities = ChainActivity::where('user_id', $this->selectedStudent->id)
                ->whereDate('activity_date', $date)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function viewStudentQuizResults($studentId)
    {
        $student = User::role('ogrenci')->findOrFail($studentId);

        $this->selectedStudentQuizResults = ExamResult::with(['exam.wordSets'])
            ->where('student_id', $student->id)
            ->whereNotNull('completed_at')
            ->whereHas('exam.wordSets')
            ->orderByDesc('completed_at')
            ->limit(100)
            ->get();
        $this->selectedQuizStudent = $student;
        $this->selectedExamResult = null;
        $this->selectedExamAnswerDetails = [];
        $this->showQuizResults = true;
    }

    public function viewExamResultDetails($resultId)
    {
        $result = ExamResult::with(['exam.wordSets'])
            ->where('student_id', $this->selectedQuizStudent->id)
            ->whereNotNull('completed_at')
            ->whereHas('exam.wordSets')
            ->findOrFail($resultId);

        $answers = collect($result->answers ?? []);
        $words = Word::whereIn('id', $answers->pluck('word_id')->filter()->unique())
            ->get()
            ->keyBy('id');

        $this->selectedExamAnswerDetails = $answers->map(function ($answer) use ($words) {
            $word = $words->get($answer['word_id'] ?? null);

            return [
                'question_number' => $answer['question_number'] ?? null,
                'english' => $word?->word,
                'selected_answer' => $answer['selected_answer'] ?? null,
                'correct_answer' => $word?->definition,
                'is_correct' => (bool) ($answer['is_correct'] ?? false),
                'time_taken' => $answer['time_taken'] ?? null,
            ];
        })->all();

        $this->selectedExamResult = $result;
    }

    public function closeExamResultDetails()
    {
        $this->selectedExamResult = null;
        $this->selectedExamAnswerDetails = [];
    }

    public function closeQuizResultsModal()
    {
        $this->showQuizResults = false;
        $this->selectedStudentQuizResults = [];
        $this->selectedQuizStudent = null;
        $this->closeExamResultDetails();
    }

public function adjustStudentDays()
{
    $this->validate([
        'adjustDays' => 'required|integer|between:-365,365',
        'adjustReason' => 'required|string|min:5|max:255'
    ]);

    $progress = $this->selectedStudent->chainProgress;
    
    if (!$progress) {
        $progress = ChainProgress::create([
            'user_id' => $this->selectedStudent->id,
            'days_completed' => 0,
            'current_streak' => 0,
            'longest_streak' => 0
        ]);
    }

    // Eski değeri kaydet
    $oldDayCount = $progress->days_completed;

    // Gün sayısını ayarla
    $newDayCount = max(0, $oldDayCount + $this->adjustDays);
    
    $progress->days_completed = $newDayCount;
    $progress->current_streak = $newDayCount;
    $progress->longest_streak = max($progress->longest_streak, $newDayCount);
    $progress->save();

    // Log kaydı oluştur
    ChainActivity::create([
        'user_id' => $this->selectedStudent->id,
        'chain_progress_id' => $progress->id,
        'teacher_id' => Auth::id(),
        'content' => "Öğretmen tarafından gün sayısı ayarlandı: {$this->adjustDays} gün ({$this->adjustReason})",
        'activity_date' => now(),
        'is_adjustment' => true
    ]);

    $this->resetAdjustmentForm();
    
    // Daha detaylı bir başarı mesajı
    $actionText = $this->adjustDays > 0 
        ? $this->adjustDays . " gün eklendi" 
        : abs($this->adjustDays) . " gün çıkarıldı";
        
    $this->dispatch('show-success', [
        'message' => "Gün sayısı başarıyla güncellendi! ($actionText: $oldDayCount → $newDayCount)"
    ]);
    
    // Özel animasyon için olay gönder
    $this->dispatch('student-days-updated', [
        'studentId' => $this->selectedStudent->id,
        'oldCount' => $oldDayCount,
        'newCount' => $newDayCount
    ]);
    
    $this->selectStudent($this->selectedStudent->id); // Refresh data
}

    public function resetAdjustmentForm()
    {
        $this->adjustDays = 0;
        $this->adjustReason = '';
    }

    public function getStudentsProperty()
    {
        return User::role('ogrenci')
            ->with(['chainProgress', 'chainActivities' => function($query) {
                $query->latest()->limit(5);
            }])
            ->when($this->searchTerm, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('surname', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->orderByDesc(
                ChainProgress::select('days_completed')
                    ->whereColumn('user_id', 'users.id')
                    ->limit(1)
            )
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.chain-breaker-teacher-dashboard', [
            'students' => $this->students
        ]);
    }
    public function closeStudentModal()
{
    $this->selectedStudent = null;
    $this->showActivityDetail = false;
    $this->selectedDate = null;
    $this->selectedActivities = [];
    $this->resetAdjustmentForm();
}

public function closeActivityModal()
{
    $this->showActivityDetail = false;
    $this->selectedDate = null;
    $this->selectedActivities = [];
}
}