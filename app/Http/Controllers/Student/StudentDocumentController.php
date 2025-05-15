<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StudentDocumentController extends Controller
{
    /**
     * Öğrencinin tüm kurslarına ait belgeleri listele
     */
    public function listAllDocuments()
    {
        try {
            $user = Auth::user();
            
            // Öğrencinin kayıtlı olduğu aktif kursları getir
            $enrolledCourses = $user->enrolledCourses()
                ->wherePivot('status_id', 1) // Active status_id (onaylanmış)
                ->where('is_active', true)
                ->with('teacher') // İlişkili modelleri yükle
                ->get();
            
            // Her kurs için aktif belgeleri getir
            $courseDocuments = [];
            
            foreach ($enrolledCourses as $course) {
                $documents = $course->documents()
                    ->where('is_active', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                if ($documents->count() > 0) {
                    $courseDocuments[] = [
                        'course' => $course,
                        'documents' => $documents,
                        'count' => $documents->count()
                    ];
                }
            }
            
            return view('student.documents.list', compact('courseDocuments', 'enrolledCourses'));
        } catch (\Exception $e) {
            Log::error('Öğrenci belge listeleme hatası: ' . $e->getMessage());
            return redirect()->route('ogrenci.kurslarim')
                ->with('error', 'Belgeler listelenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }
    
    /**
     * Kursa ait belgeleri listele (Öğrenci için)
     */
    public function index($slug)
    {
        try {
            $user = Auth::user();
            $course = Course::where('slug', $slug)->firstOrFail();
            
            // Öğrencinin kursa kayıtlı olup olmadığını kontrol et (aktif kayıt)
            $enrollment = $user->enrolledCourses()
                ->where('course_id', $course->id)
                ->wherePivot('status_id', 1) // Active status_id
                ->first();
            
            if (!$enrollment) {
                return redirect()->route('ogrenci.kurslarim')
                    ->with('error', 'Bu kursa erişim izniniz bulunmamaktadır.');
            }
            
            // Kursa ait aktif ve öğrencilerin görebileceği belgeleri getir
            $documents = $course->documents()
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('student.documents.index', compact('course', 'documents'));
        } catch (\Exception $e) {
            Log::error('Öğrenci belge listeleme hatası: ' . $e->getMessage());
            return redirect()->route('ogrenci.kurslarim')
                ->with('error', 'Belgeler listelenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }
    
    /**
     * Belge indirme (Öğrenci için)
     */
    public function download($slug, $documentId)
    {
        try {
            $user = Auth::user();
            $course = Course::where('slug', $slug)->firstOrFail();
            
            // Öğrencinin kursa kayıtlı olup olmadığını kontrol et (aktif kayıt)
            $enrollment = $user->enrolledCourses()
                ->where('course_id', $course->id)
                ->wherePivot('status_id', 1) // Active status_id
                ->first();
            
            if (!$enrollment) {
                return redirect()->route('ogrenci.kurslarim')
                    ->with('error', 'Bu kursa erişim izniniz bulunmamaktadır.');
            }
            
            // Belgenin bu kursa ait olduğunu ve öğrencilerin indirmesine izin verildiğini kontrol et
            $document = CourseDocument::where('id', $documentId)
                ->where('course_id', $course->id)
                ->where('is_active', true)
                ->where('students_can_download', true)
                ->firstOrFail();
            
            // Dosya var mı kontrol et
            if (!Storage::disk('public')->exists($document->file_path)) {
                return redirect()->route('ogrenci.documents.index', $course->slug)
                    ->with('error', 'Belge dosyası bulunamadı.');
            }
            
            // Dosyayı indir
            return Storage::disk('public')->download(
                $document->file_path,
                $document->file_name,
                ['Content-Type' => $document->file_type]
            );
        } catch (\Exception $e) {
            Log::error('Öğrenci belge indirme hatası: ' . $e->getMessage());
            return redirect()->route('ogrenci.documents.index', $slug)
                ->with('error', 'Belge indirilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }
}