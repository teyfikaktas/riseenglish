<?php

namespace App\Http\Controllers;

use App\Models\CourseDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicDocumentController extends Controller
{
    /**
     * Herkese açık belgeyi göster
     */
    public function show($token)
    {
        // Token'a göre belgeyi bul
        $document = CourseDocument::where('public_token', $token)
            ->where('is_public', true)
            ->where('is_active', true)
            ->firstOrFail();

        // Dosya var mı kontrol et
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Belge dosyası bulunamadı.');
        }

        // Görüntülenme sayısını arttır (isteğe bağlı)
        // $document->increment('view_count');

        // Dosya türüne göre işlem yap
        $filePath = Storage::disk('public')->path($document->file_path);
        $mimeType = $document->file_type;

        // Eğer PDF, resim veya metin dosyası ise tarayıcıda göster
        if (in_array($mimeType, [
            'application/pdf',
            'image/jpeg', 'image/png', 'image/gif',
            'text/plain', 'text/html'
        ])) {
            return response()->file($filePath, ['Content-Type' => $mimeType]);
        }

        // Diğer dosya türleri için indirme işlemi
        return Storage::disk('public')->download(
            $document->file_path,
            $document->file_name,
            ['Content-Type' => $document->file_type]
        );
    }
}