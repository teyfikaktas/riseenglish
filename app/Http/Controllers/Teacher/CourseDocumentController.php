<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CourseDocumentController extends Controller
{
    /**
     * Öğretmenin tüm kurslarına ait belgeleri listele
     */
public function listAllDocuments()
{
    try {
        $teacher = Auth::user();
        
        // Öğretmenin aktif kurslarını getir
        $courses = Course::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();
        
        // Her kurs için belgelerle birlikte bilgileri topla
        $courseDocuments = [];
        
        foreach ($courses as $course) {
            $documents = $course->documents()
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
        
        return view('teacher.documents.list', compact('courseDocuments', 'courses'));
    } catch (\Exception $e) {
        Log::error('Belge listeleme hatası: ' . $e->getMessage());
        return redirect()->route('ogretmen.panel')
            ->with('error', 'Belgeler listelenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
    }
}
    
    /**
     * Kursa ait belgeleri listele
     */
    public function index($courseId)
    {
        try {
            $teacher = Auth::user();

            // Kursun bu öğretmene ait olduğunu kontrol et
            $course = Course::where('id', $courseId)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            // Kurs belgelerini getir
            $documents = $course->documents()
                ->orderBy('created_at', 'desc')
                ->get();

            return view('teacher.documents.index', compact('course', 'documents'));
        } catch (\Exception $e) {
            Log::error('Belge listeleme hatası: ' . $e->getMessage());
            return redirect()->route('ogretmen.panel')
                ->with('error', 'Belgeler listelenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }

    /**
     * Belge ekleme formu
     */
    public function create($courseId)
    {
        try {
            $teacher = Auth::user();

            // Kursun bu öğretmene ait olduğunu kontrol et
            $course = Course::where('id', $courseId)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            return view('teacher.documents.create', compact('course'));
        } catch (\Exception $e) {
            Log::error('Belge ekleme formu hatası: ' . $e->getMessage());
            return redirect()->route('ogretmen.panel')
                ->with('error', 'Belge ekleme formu yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }

    /**
     * Belge kaydetme
     */
    public function store(Request $request, $courseId)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'required|file|max:20480', // 20MB maksimum
                'students_can_download' => 'nullable|boolean',
            ]);

            $teacher = Auth::user();

            // Kursun bu öğretmene ait olduğunu kontrol et
            $course = Course::where('id', $courseId)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            // Dosya yükleme işlemi
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('course_documents', $fileName, 'public');
            $fileSize = ceil($file->getSize() / 1024); // KB cinsinden dosya boyutu

            // Belge veritabanı kaydı
            $document = new CourseDocument([
                'course_id' => $course->id,
                'uploaded_by' => $teacher->id,
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $fileSize,
                'is_active' => true,
                'students_can_download' => $request->has('students_can_download'),
            ]);

            $document->save();

            return redirect()->route('ogretmen.documents.index', $course->id)
                ->with('success', 'Belge başarıyla yüklendi.');
        } catch (\Exception $e) {
            Log::error('Belge yükleme hatası: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Belge yüklenirken bir hata oluştu: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Belge düzenleme formu
     */
    public function edit($courseId, $documentId)
    {
        try {
            $teacher = Auth::user();

            // Kursun bu öğretmene ait olduğunu kontrol et
            $course = Course::where('id', $courseId)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            // Belgenin bu kursa ait olduğunu kontrol et
            $document = CourseDocument::where('id', $documentId)
                ->where('course_id', $course->id)
                ->firstOrFail();

            return view('teacher.documents.edit', compact('course', 'document'));
        } catch (\Exception $e) {
            Log::error('Belge düzenleme formu hatası: ' . $e->getMessage());
            return redirect()->route('ogretmen.documents.index', $courseId)
                ->with('error', 'Belge düzenleme formu yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }

    /**
     * Belge güncelleme
     */
    public function update(Request $request, $courseId, $documentId)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'nullable|file|max:20480', // 20MB maksimum
                'is_active' => 'nullable|boolean',
                'students_can_download' => 'nullable|boolean',
            ]);

            $teacher = Auth::user();

            // Kursun bu öğretmene ait olduğunu kontrol et
            $course = Course::where('id', $courseId)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            // Belgenin bu kursa ait olduğunu kontrol et
            $document = CourseDocument::where('id', $documentId)
                ->where('course_id', $course->id)
                ->firstOrFail();

            // Belge bilgilerini güncelle
            $document->title = $request->title;
            $document->description = $request->description;
            $document->is_active = $request->has('is_active');
            $document->students_can_download = $request->has('students_can_download');

            // Eğer yeni dosya yüklendiyse
            if ($request->hasFile('file')) {
                // Eski dosyayı sil
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }

                // Yeni dosyayı yükle
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('course_documents', $fileName, 'public');
                $fileSize = ceil($file->getSize() / 1024); // KB cinsinden dosya boyutu

                // Belge dosya bilgilerini güncelle
                $document->file_path = $filePath;
                $document->file_name = $file->getClientOriginalName();
                $document->file_type = $file->getClientMimeType();
                $document->file_size = $fileSize;
            }

            $document->save();

            return redirect()->route('ogretmen.documents.index', $course->id)
                ->with('success', 'Belge başarıyla güncellendi.');
        } catch (\Exception $e) {
            Log::error('Belge güncelleme hatası: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Belge güncellenirken bir hata oluştu: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Belge silme
     */
    public function destroy($courseId, $documentId)
    {
        try {
            $teacher = Auth::user();

            // Kursun bu öğretmene ait olduğunu kontrol et
            $course = Course::where('id', $courseId)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            // Belgenin bu kursa ait olduğunu kontrol et
            $document = CourseDocument::where('id', $documentId)
                ->where('course_id', $course->id)
                ->firstOrFail();

            // Dosyayı diskten sil
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Veritabanından kaydı sil
            $document->delete();

            return redirect()->route('ogretmen.documents.index', $course->id)
                ->with('success', 'Belge başarıyla silindi.');
        } catch (\Exception $e) {
            Log::error('Belge silme hatası: ' . $e->getMessage());
            return redirect()->route('ogretmen.documents.index', $courseId)
                ->with('error', 'Belge silinirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }

    /**
     * Belge indirme
     */
    public function download($courseId, $documentId)
    {
        try {
            $teacher = Auth::user();

            // Kursun bu öğretmene ait olduğunu kontrol et
            $course = Course::where('id', $courseId)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            // Belgenin bu kursa ait olduğunu kontrol et
            $document = CourseDocument::where('id', $documentId)
                ->where('course_id', $course->id)
                ->firstOrFail();

            // Dosya var mı kontrol et
            if (!Storage::disk('public')->exists($document->file_path)) {
                return redirect()->route('ogretmen.documents.index', $course->id)
                    ->with('error', 'Belge dosyası bulunamadı.');
            }

            // Dosyayı indir
            return Storage::disk('public')->download(
                $document->file_path,
                $document->file_name,
                ['Content-Type' => $document->file_type]
            );
        } catch (\Exception $e) {
            Log::error('Belge indirme hatası: ' . $e->getMessage());
            return redirect()->route('ogretmen.documents.index', $courseId)
                ->with('error', 'Belge indirilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }
}