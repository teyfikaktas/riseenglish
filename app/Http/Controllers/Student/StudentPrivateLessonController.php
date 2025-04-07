<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PrivateLesson;
use App\Models\PrivateLessonSession;
use App\Models\PrivateLessonHomework;
use App\Models\PrivateLessonHomeworkSubmission;
use App\Models\PrivateLessonMaterial;
use Carbon\Carbon;

class StudentPrivateLessonController extends Controller
{
    /**
     * Öğrencinin tüm özel derslerini listeler
     */
    public function index()
    {
        $studentId = Auth::id();
        
        // Öğrencinin tüm derslerini ve seanslarını getir
        $sessions = PrivateLessonSession::with(['privateLesson', 'teacher'])
            ->where('student_id', $studentId)
            ->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
            
        // Dersleri private_lesson_id'ye göre grupla
        $groupedSessions = $sessions->groupBy('private_lesson_id');
        
        return view('student.private-lessons.index', compact('groupedSessions'));
    }
    
    /**
     * Özel dersin detaylarını gösterir
     */
    public function showLesson($lessonId)
    {
        $studentId = Auth::id();
        
        // Dersi getir
        $lesson = PrivateLesson::findOrFail($lessonId);
        
        // Bu derse ait tüm seansları getir (sadece bu öğrenciye ait olanları)
        $sessions = PrivateLessonSession::with(['teacher'])
            ->where('private_lesson_id', $lessonId)
            ->where('student_id', $studentId)
            ->orderBy('start_date', 'asc')
            ->get();
            
        if ($sessions->isEmpty()) {
            abort(403, 'Bu derse erişim izniniz bulunmuyor.');
        }
        
        // Tamamlanmış dersleri filtrele
        $completedSessions = $sessions->where('status', 'completed');
        
        // Öğretmen bilgisini ilk seanstan al
        $teacher = $sessions->first()->teacher ?? null;
        
        return view('student.private-lessons.lesson', compact('lesson', 'sessions', 'completedSessions', 'teacher'));
    }
    
    /**
     * Ders seansının detaylarını gösterir
     */
    public function showSession($id)
    {
        $studentId = Auth::id();
        
        // Belirtilen seansı getir (yalnızca bu öğrenciye ait ise)
        $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'homeworks', 'materials'])
            ->where('student_id', $studentId)
            ->findOrFail($id);
            
        // Durumlar için renkler ve etiketler
        $statuses = [
            'pending' => 'Beklemede',
            'approved' => 'Onaylandı',
            'active' => 'Aktif',
            'rejected' => 'Reddedildi',
            'cancelled' => 'İptal Edildi',
            'completed' => 'Tamamlandı',
            'scheduled' => 'Planlandı',
        ];
        
        return view('student.private-lessons.session', compact('session', 'statuses'));
    }
    
    /**
     * Ders materyalinin detaylarını gösterir ve indirilmesini sağlar
     */
    public function downloadMaterial($id)
    {
        try {
            $studentId = Auth::id();
            
            // Materyali bul
            $material = PrivateLessonMaterial::findOrFail($id);
            
            // Materyalin ait olduğu dersin bu öğrenciye ait olduğunu kontrol et
            $session = PrivateLessonSession::where('id', $material->session_id)
                ->where('student_id', $studentId)
                ->firstOrFail();
                
            // Dosyanın var olduğunu kontrol et
            if (!Storage::disk('local')->exists($material->file_path)) {
                return abort(404, 'Dosya bulunamadı veya silinmiş.');
            }
            
            // Dosya adını oluştur
            $downloadName = $material->title . '.' . pathinfo($material->file_path, PATHINFO_EXTENSION);
            
            // Dosyayı indir
            return Storage::disk('local')->download($material->file_path, $downloadName);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Dosya indirilirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Ders materyallerini listele
     */
    public function materials()
    {
        $studentId = Auth::id();
        
        // Öğrencinin tüm derslerinin seanslarını getir
        $sessions = PrivateLessonSession::where('student_id', $studentId)
            ->where('status', 'completed')
            ->pluck('id');
            
        // Bu seanslara ait tüm materyalleri getir
        $materials = PrivateLessonMaterial::with(['session.privateLesson', 'session.teacher'])
            ->whereIn('session_id', $sessions)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('student.private-lessons.materials', compact('materials'));
    }
    
    /**
     * Tüm ödevleri listele
     */
    public function homeworks()
    {
        $studentId = Auth::id();
        
        // Öğrencinin tüm derslerinin seanslarını getir
        $sessions = PrivateLessonSession::where('student_id', $studentId)
            ->where('status', 'completed')
            ->pluck('id');
            
        // Bu seanslara ait tüm ödevleri getir
        $homeworks = PrivateLessonHomework::with([
                'session.privateLesson', 
                'session.teacher', 
                'submissions' => function($query) use ($studentId) {
                    $query->where('student_id', $studentId);
                }
            ])
            ->whereIn('session_id', $sessions)
            ->orderBy('due_date', 'asc')
            ->paginate(15);
            
        return view('student.private-lessons.homeworks', compact('homeworks'));
    }
    
    /**
     * Ödev detayını göster
     */
    public function showHomework($id)
    {
        $studentId = Auth::id();
        
        // Ödev bilgilerini getir
        $homework = PrivateLessonHomework::with([
                'session.privateLesson', 
                'session.teacher',
                'submissions' => function($query) use ($studentId) {
                    $query->where('student_id', $studentId);
                }
            ])
            ->findOrFail($id);
            
        // Öğrenci bu derse ait mi kontrol et
        $session = PrivateLessonSession::where('id', $homework->session_id)
            ->where('student_id', $studentId)
            ->firstOrFail();
            
        // Öğrencinin daha önce teslim ettiği ödev var mı kontrol et
        $submission = $homework->submissions->first();
        
        return view('student.private-lessons.homework-detail', compact('homework', 'submission'));
    }
    
    /**
     * Ödev dosyasını indir
     */
    public function downloadHomework($id)
    {
        try {
            $studentId = Auth::id();
            
            // Ödev bilgilerini getir
            $homework = PrivateLessonHomework::findOrFail($id);
            
            // Öğrenci bu derse ait mi kontrol et
            $session = PrivateLessonSession::where('id', $homework->session_id)
                ->where('student_id', $studentId)
                ->firstOrFail();
                
            // Dosyanın var olduğunu kontrol et
            if (empty($homework->file_path) || !Storage::disk('local')->exists($homework->file_path)) {
                return abort(404, 'Dosya bulunamadı veya silinmiş.');
            }
            
            // Dosya adını oluştur
            $downloadName = $homework->title . '.' . pathinfo($homework->file_path, PATHINFO_EXTENSION);
            
            // Dosyayı indir
            return Storage::disk('local')->download($homework->file_path, $downloadName);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Dosya indirilirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Ödev teslim et
     */
    public function submitHomework(Request $request, $id)
    {
        $studentId = Auth::id();
        
        // Ödev bilgilerini getir
        $homework = PrivateLessonHomework::findOrFail($id);
        
        // Öğrenci bu derse ait mi kontrol et
        $session = PrivateLessonSession::where('id', $homework->session_id)
            ->where('student_id', $studentId)
            ->firstOrFail();
            
        // Son teslim tarihini kontrol et
        $dueDate = Carbon::parse($homework->due_date);
        $now = Carbon::now();
        $isLate = $now->isAfter($dueDate);
        
        // Verileri doğrula
        $validated = $request->validate([
            'content' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB max
        ]);
        
        try {
            // Önceki teslimi bul (varsa)
            $submission = PrivateLessonHomeworkSubmission::where('homework_id', $homework->id)
                ->where('student_id', $studentId)
                ->first();
                
            // Eğer önceki bir teslim varsa dosyasını sil
            if ($submission && !empty($submission->file_path) && Storage::disk('local')->exists($submission->file_path)) {
                Storage::disk('local')->delete($submission->file_path);
            }
            
            // Dosya yükle
            $originalName = $request->file('file')->getClientOriginalName();
            $uniqueFileName = uniqid() . '_' . time() . '.' . $request->file('file')->getClientOriginalExtension();
            
            $filePath = $request->file('file')->storeAs(
                'lessons/homework_submissions', 
                $uniqueFileName, 
                'local'
            );
            
            // Teslim verilerini hazırla
            $submissionData = [
                'homework_id' => $homework->id,
                'student_id' => $studentId,
                'content' => $validated['content'] ?? null,
                'file_path' => $filePath,
                'original_filename' => $originalName,
                'is_late' => $isLate,
                'submission_date' => $now,
            ];
            
            // Yeni teslim oluştur veya mevcut olanı güncelle
            if ($submission) {
                $submission->update($submissionData);
            } else {
                PrivateLessonHomeworkSubmission::create($submissionData);
            }
            
            return redirect()->route('ogrenci.private-lessons.homework', $id)
                ->with('success', 'Ödev başarıyla teslim edildi.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ödev teslim edilirken bir hata oluştu: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
 * Tamamlanan dersleri listele
 */
public function completed(Request $request)
{
    $studentId = Auth::id();
    
    // Query builder başlat
    $query = PrivateLessonSession::with(['privateLesson', 'teacher', 'materials', 'homeworks'])
        ->where('student_id', $studentId)
        ->where('status', 'completed');
        
    // Tarih filtreleri
    if ($request->has('date_from') && !empty($request->date_from)) {
        $query->where('start_date', '>=', $request->date_from);
    }
    
    if ($request->has('date_to') && !empty($request->date_to)) {
        $query->where('start_date', '<=', $request->date_to);
    }
    
    // Arama filtresi
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->whereHas('privateLesson', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        });
    }
    
    // Sıralama ve sayfalama
    $completedSessions = $query->orderBy('start_date', 'desc')
        ->paginate(10)
        ->withQueryString();
        
    return view('ogrenci.private-lessons.completed', compact('completedSessions'));
}
    /**
     * Teslim edilen ödevi indir
     */
    public function downloadSubmission($id)
    {
        try {
            $studentId = Auth::id();
            
            // Teslim bilgilerini getir
            $submission = PrivateLessonHomeworkSubmission::with(['homework'])
                ->where('student_id', $studentId)
                ->findOrFail($id);
                
            // Dosyanın var olduğunu kontrol et
            if (empty($submission->file_path) || !Storage::disk('local')->exists($submission->file_path)) {
                return abort(404, 'Dosya bulunamadı veya silinmiş.');
            }
            
            // Dosya adını oluştur
            $downloadName = 'Teslim-' . $submission->homework->title . '.' . pathinfo($submission->file_path, PATHINFO_EXTENSION);
            
            // Dosyayı indir
            return Storage::disk('local')->download($submission->file_path, $downloadName);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Dosya indirilirken bir hata oluştu: ' . $e->getMessage());
        }
    }
}