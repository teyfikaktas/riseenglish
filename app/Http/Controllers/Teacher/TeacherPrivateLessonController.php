<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PrivateLessonSession;
use App\Models\PrivateLesson;
use App\Models\PrivateLessonHomework;
use App\Models\PrivateLessonHomeworkSubmission;
use App\Models\User;
use App\Models\Subject;
use App\Models\PrivateLessonExamResult;
use App\Models\PrivateLessonReport;
use Illuminate\Support\Facades\DB; // Bu satırı ekleyin
use App\Models\PrivateLessonHomeworkSubmissionFile; // en üstte

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\PrivateLessonMaterial;
use Illuminate\Support\Facades\Storage;

class TeacherPrivateLessonController extends Controller
{

    /**
     * Öğretmenin aktif/planlanmış özel ders seanslarını gösterir
     */
    public function index()
    {
        $teacherId = Auth::id();

        $sessions = PrivateLessonSession::with(['privateLesson', 'student'])
            ->where('teacher_id', $teacherId)
            ->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('teacher.private-lessons.index', compact('sessions'));
    }
    public function calendar()
    {
        return view('teacher.private-lessons.calendar');
    }
    /**
 * Öğretmenin kendi verdiği tüm ödevleri listeler
 */
public function allHomeworks()
{
    $teacherId = Auth::id();

    // Öğretmenin seanslarına bağlı tüm ödevleri alıyoruz
    $homeworks = PrivateLessonHomework::with([
            'session.privateLesson',
            'session.student',
            'submissions'
        ])
        ->whereHas('session', function($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })
        ->orderBy('due_date', 'asc')
        ->get();

    return view('teacher.private-lessons.homeworks', compact('homeworks'));
}
    public function downloadSubmissionFile($homeworkId, $fileId)
    {
        $file = PrivateLessonHomeworkSubmissionFile::with('submission.homework')
            ->findOrFail($fileId);
    
        if ($file->submission->homework->id !== (int) $homeworkId) {
            abort(403);
        }
    
        return Storage::disk('local')
                      ->download($file->file_path, $file->original_filename);
    }
        /**
     * Dersi tamamlamayı geri alır (status = 'approved' olarak set eder)
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function undoCompleteLesson(int $id)
    {
        // Sadece kendi dersini değiştirebilsin
        $session = PrivateLessonSession::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        if ($session->status !== 'completed') {
            return redirect()->back()->with('info', 'Bu ders zaten tamamlanmamış durumda.');
        }

        // Eskiden hangi durumdaydı diye loglamak istersen not ekleyebilirsin.
        // Burada varsayılan olarak 'approved' durumuna çeviriyoruz
        $session->status = 'approved';
        $session->save();

        return redirect()->back()->with('success', 'Ders tamamlanma durumu geri alındı.');
    }

/**
 * Generate PDF report for a lesson
 *
 * @param int $id Session ID
 * @return \Illuminate\Http\Response
 */
public function generatePdfReport($id)
{
    $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
        ->where('teacher_id', Auth::id())
        ->findOrFail($id);
    
    // Get the report
    $report = PrivateLessonReport::with('examResults')
        ->where('session_id', $id)
        ->firstOrFail();
    
    // For main questions chart - prevent divide by zero
    $hasQuestionData = ($report->questions_solved > 0);
    $mainChartData = [
        'labels' => ['Doğru', 'Yanlış', 'Boş'],
        'datasets' => [
            [
                'data' => [$report->questions_correct, $report->questions_wrong, $report->questions_unanswered],
                'backgroundColor' => ['#10b981', '#ef4444', '#9ca3af']
            ]
        ]
    ];
    
    // Generate main chart image - Güvenli şekilde
    if ($report->questions_solved > 0) {
        $mainChartImage = $this->generateChartImage($mainChartData, 'pie', 500, 300);
    } else {
        $mainChartImage = $this->generateEmptyChart('Çözülen Soru Verisi Yok', 500, 300);
    }
    
    // For subjects chart - only process if we have exam results
    $hasSubjectData = ($report->examResults && $report->examResults->count() > 0);
    $subjectChartData = [];
    
    if ($hasSubjectData) {
        $subjects = [];
        $correctData = [];
        $wrongData = [];
        $unansweredData = [];
        
        foreach ($report->examResults as $examResult) {
            $subjects[] = $examResult->subject_name;
            $correctData[] = $examResult->questions_correct;
            $wrongData[] = $examResult->questions_wrong;
            $unansweredData[] = $examResult->questions_unanswered;
        }
        
        $subjectChartData = [
            'labels' => $subjects,
            'datasets' => [
                [
                    'label' => 'Doğru',
                    'data' => $correctData,
                    'backgroundColor' => '#10b981'
                ],
                [
                    'label' => 'Yanlış',
                    'data' => $wrongData,
                    'backgroundColor' => '#ef4444'
                ],
                [
                    'label' => 'Boş',
                    'data' => $unansweredData,
                    'backgroundColor' => '#9ca3af'
                ]
            ]
        ];
        
        // Generate subjects chart only if there is data
        $subjectsChartImage = $this->generateChartImage($subjectChartData, 'bar', 600, 400);
    } else {
        // Create an empty chart when no exam results
        $subjectsChartImage = $this->generateEmptyChart('Sonuç Verisi Yok', 600, 400);
    }
    
    // Generate PDF view
    $pdf = \PDF::loadView('teacher.private-lessons.reports.pdf-report', 
        compact('session', 'report', 'mainChartImage', 'subjectsChartImage', 'hasQuestionData', 'hasSubjectData'));
    
    // Set PDF options
    $pdf->setPaper('a4');
    
    // Download the PDF
    return $pdf->download('rise_english_ders_raporu_' . $session->id . '.pdf');
}
/**
 * Hızlı konu ekleme (not olmadan)
 */
public function quickAddTopic(Request $request, $sessionId)
{
    try {
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $session = PrivateLessonSession::where('id', $sessionId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        // Aynı konu daha önce eklendi mi kontrol et (isteğe bağlı)
        // $existingTopic = \App\Models\SessionTopic::where('session_id', $sessionId)
        //     ->where('topic_id', $validated['topic_id'])
        //     ->first();
        // 
        // if ($existingTopic) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Bu konu zaten eklenmiş'
        //     ], 400);
        // }

        \App\Models\SessionTopic::create([
            'session_id' => $sessionId,
            'topic_id' => $validated['topic_id'],
            'notes' => $validated['notes'] ?? ''
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Konu başarıyla eklendi'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Konu eklenirken hata oluştu: ' . $e->getMessage()
        ], 400);
    }
}
// TeacherPrivateLessonController.php'ye eklenecek
public function addTopicToSession(Request $request, $sessionId)
{
    try {
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $session = PrivateLessonSession::where('id', $sessionId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        // Aynı konu daha önce eklendi mi kontrol et
        $existingTopic = \App\Models\SessionTopic::where('session_id', $sessionId)
            ->where('topic_id', $validated['topic_id'])
            ->first();

        if ($existingTopic) {
            return response()->json([
                'success' => false,
                'message' => 'Bu konu zaten eklenmiş'
            ], 400);
        }

        \App\Models\SessionTopic::create([
            'session_id' => $sessionId,
            'topic_id' => $validated['topic_id'],
            'notes' => $validated['notes'] ?? ''
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Konu başarıyla eklendi'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Konu eklenirken hata oluştu: ' . $e->getMessage()
        ], 400);
    }
}
/**
 * Generate an empty chart with a message
 * 
 * @param string $message Message to display
 * @param int $width Image width
 * @param int $height Image height
 * @return string Base64 encoded image
 */
private function generateEmptyChart($message, $width = 500, $height = 300)
{
    // Boş bir resim oluştur
    $image = imagecreatetruecolor($width, $height);
    
    // Anti-aliasing'i etkinleştir
    imageantialias($image, true);
    
    // Arka plan rengi beyaz olsun
    $white = imagecolorallocate($image, 255, 255, 255);
    imagefill($image, 0, 0, $white);
    
    // Yazı rengi
    $black = imagecolorallocate($image, 0, 0, 0);
    $lightGray = imagecolorallocate($image, 220, 220, 220);
    
    // Bebas Neue fontunu yükle
    $font = public_path('BebasNeue-Regular.ttf');
    
    // Basit bir çerçeve çiz
    imagerectangle($image, 0, 0, $width-1, $height-1, $lightGray);
    
    // Mesajı ortala
    $fontSize = 16;
    
    // Font dosyası yoksa veya TrueType fontu kullanılamıyorsa yerleşik GD fontunu kullan
    if (!file_exists($font)) {
        // GD yerleşik fontu
        $messageWidth = strlen($message) * 8; // Tahmini genişlik (8 piksel/karakter)
        $textX = ($width - $messageWidth) / 2;
        $textY = $height / 2;
        
        imagestring($image, 5, $textX, $textY, $message, $black);
    } else {
        // TrueType font metninin boyutlarını hesapla
        $textBox = imagettfbbox($fontSize, 0, $font, $message);
        $textWidth = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        
        $textX = ($width - $textWidth) / 2;
        $textY = ($height + $textHeight) / 2;
        
        // Mesajı yaz
        imagettftext($image, $fontSize, 0, $textX, $textY, $black, $font, $message);
    }
    
    // Basit bir filigran ekle
    $watermarkGrey = imagecolorallocatealpha($image, 0, 0, 0, 110);
    $watermarkText = "RISE ENGLISH";
    
    if (!file_exists($font)) {
        // GD yerleşik fontu ile filigran
        $watermarkWidth = strlen($watermarkText) * 8;
        $watermarkX = ($width - $watermarkWidth) / 2;
        $watermarkY = $height / 2 + 30;
        
        imagestring($image, 5, $watermarkX, $watermarkY, $watermarkText, $watermarkGrey);
    } else {
        // TrueType font ile filigran
        $watermarkFontSize = 30;
        $watermarkBox = imagettfbbox($watermarkFontSize, -45, $font, $watermarkText);
        $watermarkWidth = abs($watermarkBox[4] - $watermarkBox[0]);
        
        $watermarkX = ($width - $watermarkWidth) / 2;
        $watermarkY = $height / 2 + 50;
        
        imagettftext($image, $watermarkFontSize, -45, $watermarkX, $watermarkY, $watermarkGrey, $font, $watermarkText);
    }
    
    // Resmi buffer'a yaz
    ob_start();
    imagepng($image);
    $imageData = ob_get_clean();
    
    // Belleği temizle
    imagedestroy($image);
    
    // Base64 ile encode et
    return 'data:image/png;base64,' . base64_encode($imageData);
}
/**
 * Generate a chart image and return it as base64 encoded string
 *
 * @param array $chartData The chart data
 * @param string $type Chart type (pie, bar, etc)
 * @param int $width Image width
 * @param int $height Image height
 * @return string Base64 encoded image
 */
private function generateChartImage($chartData, $type = 'pie', $width = 500, $height = 300)
{
    // Bebas Neue fontunu yükle
    $font = public_path('BebasNeue-Regular.ttf');
    
    // Boş bir resim oluştur
    $image = imagecreatetruecolor($width, $height);
    
    // Anti-aliasing'i etkinleştir
    imageantialias($image, true);
    
    // Arka plan rengi beyaz olsun
    $white = imagecolorallocate($image, 255, 255, 255);
    imagefill($image, 0, 0, $white);
    
    // Daha zarif bir görünüm için grafiğin arka planında hafif bir desen ekleyelim
    $whitePattern = imagecolorallocatealpha($image, 240, 240, 240, 70);
    
    // Grafiğe arka plan deseni çizelim
    for ($i = 0; $i < $width; $i += 10) {
        for ($j = 0; $j < $height; $j += 10) {
            // Dama deseni çizimi - daha zarif bir görünüm için
            if (($i + $j) % 20 == 0) {
                imagefilledrectangle($image, $i, $j, $i + 5, $j + 5, $whitePattern);
            }
        }
    }
    
    // Zarif bir kenarlık ekleyelim
    $borderColor = imagecolorallocatealpha($image, 26, 46, 90, 40);
    imagerectangle($image, 0, 0, $width-1, $height-1, $borderColor);
    imagerectangle($image, 1, 1, $width-2, $height-2, $borderColor);
    
    // İkinci bir iç çerçeve ekleyelim
    $innerBorderColor = imagecolorallocatealpha($image, 26, 46, 90, 60);
    imagerectangle($image, 5, 5, $width-6, $height-6, $innerBorderColor);
    
    // Pasta grafiği için implementasyon
    if ($type == 'pie' && isset($chartData['datasets'][0]['data'])) {
        $data = $chartData['datasets'][0]['data'];
        $colors = $chartData['datasets'][0]['backgroundColor'];
        
        $total = array_sum($data);
        $centerX = $width / 2;
        $centerY = $height / 2;
        $radius = min($width, $height) / 2 - 40; // Biraz küçültüldü
        
        // SIFIRA BÖLÜNME KONTROLÜ - Toplam 0 ise veri yok mesajı göster
        if ($total <= 0) {
            return $this->generateEmptyChart("Veri yok", $width, $height);
        }
        
        $startAngle = 0;
        
        // İlk olarak pasta dilimlerini çiz
        foreach ($data as $i => $value) {
            // SIFIRA BÖLÜNME KONTROLÜ
            if ($total > 0 && $value > 0) {
                $sliceAngle = ($value / $total) * 360;
                
                // Renkleri çevir
                $colorHex = str_replace('#', '', $colors[$i % count($colors)]);
                $r = hexdec(substr($colorHex, 0, 2));
                $g = hexdec(substr($colorHex, 2, 2));
                $b = hexdec(substr($colorHex, 4, 2));
                $color = imagecolorallocate($image, $r, $g, $b);
                
                // Pasta dilimini çiz
                imagefilledarc($image, $centerX, $centerY, $radius * 2, $radius * 2, $startAngle, $startAngle + $sliceAngle, $color, IMG_ARC_PIE);
                
                $startAngle += $sliceAngle;
            }
        }
        
        // Pasta dilimlerine zarif bir gölge ekleyelim
        $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 80);
        imagefilledarc($image, $centerX + 3, $centerY + 3, $radius * 2, $radius * 2, 0, 360, $shadowColor, IMG_ARC_PIE);
        
        // Pasta grafiğinin etrafını siyah bir çember ile çerçevele
        $black = imagecolorallocate($image, 0, 0, 0);
        imageellipse($image, $centerX, $centerY, $radius * 2, $radius * 2, $black);
        
        // Renklerin ne anlama geldiğini göstermek için lejant ekle
        $legends = ['Dogru', 'Yanlis', 'Bos']; // Türkçe karakter sorunu için basitleştirildi
        $legendHeight = 25;
        $legendY = $height - $legendHeight * count($legends) - 10;
        
        // Lejant için arka plan ekleyelim
        $legendBgColor = imagecolorallocatealpha($image, 255, 255, 255, 20);
        $legendBgWidth = 150;
        $legendBgHeight = count($legends) * $legendHeight + 10;
        $legendBgX = $width - $legendBgWidth - 10;
        $legendBgY = $legendY - 5;
        
        imagefilledrectangle($image, $legendBgX, $legendBgY, 
                           $legendBgX + $legendBgWidth, $legendBgY + $legendBgHeight, 
                           $legendBgColor);
        imagerectangle($image, $legendBgX, $legendBgY, 
                      $legendBgX + $legendBgWidth, $legendBgY + $legendBgHeight, 
                      $innerBorderColor);
        
        for ($i = 0; $i < count($legends); $i++) {
            // Renk kutusu çiz
            $colorHex = str_replace('#', '', $colors[$i % count($colors)]);
            $r = hexdec(substr($colorHex, 0, 2));
            $g = hexdec(substr($colorHex, 2, 2));
            $b = hexdec(substr($colorHex, 4, 2));
            $color = imagecolorallocate($image, $r, $g, $b);
            
            $boxX = $width - 140;
            $boxY = $legendY + ($i * $legendHeight);
            
            imagefilledrectangle($image, $boxX, $boxY, $boxX + 15, $boxY + 15, $color);
            imagerectangle($image, $boxX, $boxY, $boxX + 15, $boxY + 15, $black);
            
            // Lejant metnini ekle - Sıfıra bölünme kontrolü ile
            $percentValue = $total > 0 ? round(($data[$i] / $total) * 100) : 0;
            $legendText = $legends[$i] . ': ' . $data[$i] . ' (' . $percentValue . '%)';
            
            // TrueType font ile lejant metnini yaz
            $fontSize = 10;
            imagettftext($image, $fontSize, 0, $boxX + 25, $boxY + 12, $black, $font, $legendText);
        }
        
        // Merkeze toplam değeri göster - şık bir arka plan ile
        $totalText = "Toplam: $total";
        $fontSize = 12;
        
        // TrueType font metninin boyutlarını hesapla
        $textBox = imagettfbbox($fontSize, 0, $font, $totalText);
        $textWidth = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        
        $textX = $centerX - ($textWidth / 2);
        $textY = $centerY + ($textHeight / 2);
        
        // Zemini temizle - yuvarlak bir arka plan ekleyelim
        $bgRadius = max($textWidth, $textHeight) + 15;
        $bgColor = imagecolorallocatealpha($image, 255, 255, 255, 15);
        imagefilledellipse($image, $centerX, $centerY, $bgRadius, $bgRadius, $bgColor);
        imageellipse($image, $centerX, $centerY, $bgRadius, $bgRadius, $innerBorderColor);
        
        // TrueType font ile toplam değeri yaz
        imagettftext($image, $fontSize, 0, $textX, $textY, $black, $font, $totalText);
    }
    
    // Çubuk grafiği için implementasyon (yan yana 3 çubuk şeklinde)
    if ($type == 'bar' && isset($chartData['datasets'])) {
        $datasets = $chartData['datasets'];
        $labels = $chartData['labels'];
        
        if (empty($labels)) {
            // Veri yoksa boş grafik döndür
            return $this->generateEmptyChart("Veri yok", $width, $height);
        }
        
        // Maksimum değeri bul
        $maxValue = 0;
        foreach ($datasets as $dataset) {
            if (!empty($dataset['data'])) {
                $maxValue = max($maxValue, max($dataset['data']));
            }
        }
        $maxValue = ceil($maxValue * 1.1); // %10 marj ekle
        
        // Sıfır kontrolü - Maksimum değer 0 ise boş bir grafik döndür
        if ($maxValue <= 0) {
            return $this->generateEmptyChart("Veri yok veya tüm değerler sıfır", $width, $height);
        }
        
        // Grafik bölgesini oluştur
        $margin = 60; // Sol marjini artırdık
        $graphWidth = $width - (2 * $margin);
        $graphHeight = $height - (2 * $margin);
        
        // Arka plan ve çerçeve
        $lightGray = imagecolorallocate($image, 245, 245, 245);
        $gray = imagecolorallocate($image, 200, 200, 200);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Grafik alanı için zarif bir arka plan
        imagefilledrectangle($image, $margin, $margin, $width - $margin, $height - $margin, $lightGray);
        imagerectangle($image, $margin, $margin, $width - $margin, $height - $margin, $gray);
        
        // İç çerçeve için hafif bir gölge efekti
        $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 80);
        imagefilledrectangle($image, $margin + 3, $margin + 3, $width - $margin + 3, $height - $margin + 3, $shadowColor);
        
        // Y ekseni çizgisi
        imageline($image, $margin, $margin, $margin, $height - $margin, $black);
        
        // X ekseni çizgisi
        imageline($image, $margin, $height - $margin, $width - $margin, $height - $margin, $black);
        
        // Y ekseni bölünme çizgileri ve değerleri
        $steps = 5;
        for ($i = 0; $i <= $steps; $i++) {
            $y = $height - $margin - ($i * $graphHeight / $steps);
            $value = ceil($i * $maxValue / $steps);
            
            // Yatay çizgi
            imageline($image, $margin - 5, $y, $width - $margin, $y, $gray);
            
            // Y değeri
            $valueText = (string)$value;
            $fontSize = 9;
            
            // TrueType font metninin boyutlarını hesapla
            $textBox = imagettfbbox($fontSize, 0, $font, $valueText);
            $textWidth = abs($textBox[4] - $textBox[0]);
            
            // Y değerleri için daha zarif bir görünüm
            $labelBgColor = imagecolorallocatealpha($image, 255, 255, 255, 30);
            imagefilledrectangle($image, $margin - $textWidth - 10, $y - 10, $margin - 5, $y + 10, $labelBgColor);
            imagettftext($image, $fontSize, 0, $margin - $textWidth - 5, $y + 4, $black, $font, $valueText);
        }
        
        // Her bir konu için gruplandırılmış çubuklar oluştur
        $barCount = count($labels);
        $datasetCount = count($datasets);
        
        // Toplam grup genişliği ve çubuk genişliği
        $groupWidth = $graphWidth / $barCount; 
        $barWidth = ($groupWidth * 0.6) / $datasetCount; // Her grup içinde %60 alan kullan
        $barPadding = ($groupWidth * 0.4) / ($datasetCount + 1); // Gruplar arasında %40 boşluk
        
        // Lejant oluştur (üst kısımda) - şık bir arka plan ekleyelim
        $legendTexts = ['Dogru', 'Yanlis', 'Bos']; // Türkçe karakter sorunu için basitleştirildi
        $legendX = $width - 200;
        $legendY = 20;
        
        // Lejant arka planı
        $legendBgWidth = 180;
        $legendBgHeight = (count($legendTexts) * 20) + 10;
        $legendBgColor = imagecolorallocatealpha($image, 255, 255, 255, 30);
        
        imagefilledrectangle($image, $legendX - 5, $legendY - 5, 
                          $legendX + $legendBgWidth, $legendY + $legendBgHeight, 
                          $legendBgColor);
        imagerectangle($image, $legendX - 5, $legendY - 5, 
                     $legendX + $legendBgWidth, $legendY + $legendBgHeight, 
                     $innerBorderColor);
        
        for ($i = 0; $i < count($legendTexts) && $i < count($datasets); $i++) {
            // Renk kutusu
            $colorHex = str_replace('#', '', $datasets[$i]['backgroundColor']);
            $r = hexdec(substr($colorHex, 0, 2));
            $g = hexdec(substr($colorHex, 2, 2));
            $b = hexdec(substr($colorHex, 4, 2));
            $color = imagecolorallocate($image, $r, $g, $b);
            
            imagefilledrectangle($image, $legendX, $legendY + ($i * 20), $legendX + 15, $legendY + 15 + ($i * 20), $color);
            imagerectangle($image, $legendX, $legendY + ($i * 20), $legendX + 15, $legendY + 15 + ($i * 20), $black);
            
            // TrueType font ile lejant metnini yaz
            $fontSize = 10;
            imagettftext($image, $fontSize, 0, $legendX + 20, $legendY + 12 + ($i * 20), $black, $font, $legendTexts[$i]);
        }
        
        // Çubukları çiz
        for ($i = 0; $i < $barCount; $i++) {
            $groupX = $margin + ($i * $groupWidth) + $barPadding;
            
            // Konu başlığı (X ekseni etiketi)
            $labelText = $this->simplifyText($labels[$i]); // Türkçe karakter sorunu için basitleştir
            
            // TrueType font metninin boyutlarını hesapla
            $fontSize = 8;
            $textBox = imagettfbbox($fontSize, 0, $font, $labelText);
            $textWidth = abs($textBox[4] - $textBox[0]);
            
            $labelX = $groupX + ($groupWidth / 2) - ($textWidth / 2);
            
            // X ekseni etiketleri için daha zarif bir görünüm
            $xLabelBgColor = imagecolorallocatealpha($image, 255, 255, 255, 30);
            imagefilledrectangle($image, $labelX - 5, $height - $margin + 2, 
                              $labelX + $textWidth + 5, $height - $margin + 20, 
                              $xLabelBgColor);
            
            // Daraltılmış metin için karakter sayısını sınırla
            $maxChars = 10; // Maksimum karakter sayısı
            $displayText = strlen($labelText) > $maxChars ? substr($labelText, 0, $maxChars) . '..' : $labelText;
            
            // TrueType font ile X ekseni etiketini yaz
            imagettftext($image, $fontSize, 0, $labelX, $height - $margin + 14, $black, $font, $displayText);
            
            // Her veri seti için çubukları çiz
            for ($j = 0; $j < $datasetCount; $j++) {
                $value = $datasets[$j]['data'][$i];
                
                // Sıfıra bölme hatası kontrolü - Eğer maksimum değer 0 ise, barHeight 0 olacak
                $barHeight = $maxValue > 0 ? ($value / $maxValue) * $graphHeight : 0;
                
                // Rengi çevir
                $colorHex = str_replace('#', '', $datasets[$j]['backgroundColor']);
                $r = hexdec(substr($colorHex, 0, 2));
                $g = hexdec(substr($colorHex, 2, 2));
                $b = hexdec(substr($colorHex, 4, 2));
                $color = imagecolorallocate($image, $r, $g, $b);
                
                // Çubuk konumu
                $barX = $groupX + ($j * ($barWidth + $barPadding));
                $barY = $height - $margin - $barHeight;
                
                // Çubuğa hafif bir gölge ekleyelim
                $barShadowColor = imagecolorallocatealpha($image, 0, 0, 0, 70);
                imagefilledrectangle($image, $barX + 2, $barY + 2, 
                                  $barX + $barWidth + 2, $height - $margin + 2, 
                                  $barShadowColor);
                
                // Çubuğu çiz
                imagefilledrectangle($image, $barX, $barY, $barX + $barWidth, $height - $margin, $color);
                imagerectangle($image, $barX, $barY, $barX + $barWidth, $height - $margin, $black);
                
                // Değeri çubuğun üzerine yaz (sadece yeterince büyükse)
                if ($barHeight > 15) {
                    $valueText = (string)$value;
                    
                    // TrueType font metninin boyutlarını hesapla
                    $fontSize = 8;
                    $textBox = imagettfbbox($fontSize, 0, $font, $valueText);
                    $textWidth = abs($textBox[4] - $textBox[0]);
                    
                    $textX = $barX + ($barWidth / 2) - ($textWidth / 2);
                    
                    // Değerlere zarif bir arka plan ekleyelim
                    $valueBgColor = imagecolorallocatealpha($image, 255, 255, 255, 60);
                    imagefilledrectangle($image, $textX - 2, $barY + 2, 
                                      $textX + $textWidth + 2, $barY + 15, 
                                      $valueBgColor);
                    
                    // TrueType font ile değeri yaz
                    imagettftext($image, $fontSize, 0, $textX, $barY + 10, $black, $font, $valueText);
                }
            }
        }
    }
    
    // Köşelere zarif küçük süsler ekleyelim
    $decorColor = imagecolorallocatealpha($image, 26, 46, 90, 70);
    
    // Sol üst köşe
    imagefilledarc($image, 10, 10, 20, 20, 180, 270, $decorColor, IMG_ARC_PIE);
    
    // Sağ üst köşe
    imagefilledarc($image, $width-10, 10, 20, 20, 270, 0, $decorColor, IMG_ARC_PIE);
    
    // Sol alt köşe
    imagefilledarc($image, 10, $height-10, 20, 20, 90, 180, $decorColor, IMG_ARC_PIE);
    
    // Sağ alt köşe
    imagefilledarc($image, $width-10, $height-10, 20, 20, 0, 90, $decorColor, IMG_ARC_PIE);
    
    // Basit bir filigran ekle - daha zarif ve modern bir görünüm
    $watermarkGrey = imagecolorallocatealpha($image, 26, 46, 90, 110);
    $watermarkText = "RISE ENGLISH";
    
    // TrueType font ile filigran
    $watermarkFontSize = 30;
    $watermarkBox = imagettfbbox($watermarkFontSize, -45, $font, $watermarkText);
    $watermarkWidth = abs($watermarkBox[4] - $watermarkBox[0]);
    
    $watermarkX = ($width - $watermarkWidth) / 2;
    $watermarkY = $height / 2 + 50;
    
    imagettftext($image, $watermarkFontSize, -45, $watermarkX, $watermarkY, $watermarkGrey, $font, $watermarkText);
    
    // Resmi buffer'a yaz
    ob_start();
    imagepng($image);
    $imageData = ob_get_clean();
    
    // Belleği temizle
    imagedestroy($image);
    
    // Base64 ile encode et
    return 'data:image/png;base64,' . base64_encode($imageData);
}
/**
     * Mevcut derse yeni seanslar ekle
     *
     * @param \Illuminate\Http\Request $request
     * @param int $lessonId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeNewSession(Request $request, $lessonId)
    {
        try {
            $teacherId = Auth::id();
    
            // Dersi ve öğretmen yetkisini doğrula
            $lesson = PrivateLesson::findOrFail($lessonId);
            $sessionCheck = PrivateLessonSession::where('private_lesson_id', $lessonId)
                ->where('teacher_id', $teacherId)
                ->first();
            if (! $sessionCheck) {
                return redirect()
                    ->route('ogretmen.private-lessons.index')
                    ->with('error', 'Bu derse erişim yetkiniz bulunmuyor.');
            }
    
            // Form verilerini doğrula - validation kurallarını oluştur
            $rules = [
                'day_of_week' => 'required|integer|min:0|max:6',
                'start_date'  => 'required|date',
                'start_time'  => 'required|date_format:H:i',
                'end_time'    => 'required|date_format:H:i|after:start_time',
                'location'    => 'nullable|string|max:255',
                'notes'       => 'nullable|string',
            ];
    
            // Eğer birden fazla seans seçeneği işaretlenmişse, end_date validasyonunu ekle
            if ($request->has('is_multi_session')) {
                $rules['end_date'] = 'required|date|after_or_equal:start_date';
            }
    
            $validated = $request->validate($rules);
    
            // Eğer is_multi_session belirtilmemişse başlangıç tarihi aynı zamanda bitiş tarihi
            if (!$request->has('is_multi_session')) {
                $validated['end_date'] = $validated['start_date'];
            }
    
            // Öğrenci ve varsayılan konum
            $studentId       = $sessionCheck->student_id;
            $defaultLocation = $sessionCheck->location;
    
            // Tarihleri Carbon ile al
            $start = Carbon::parse($validated['start_date']);
            $end   = Carbon::parse($validated['end_date']);
            $dow   = (int) $validated['day_of_week']; // 0 = Pazar, 1 = Pazartesi, …
    
            // İlk seansın, o hafta içindeki hedef güne denk gelen tarihi
            $current = $start->copy();
            if ($current->dayOfWeek !== $dow) {
                $daysToAdd = ($dow - $current->dayOfWeek + 7) % 7;
                $current->addDays($daysToAdd);
            }
    
            $created = 0;
            $skipped = [];
    
            // Döngü: başlangıç ≤ son tarih
            while ($current->lte($end)) {
                $dateStr = $current->format('Y-m-d');
    
                // İsteğe bağlı: çakışma kontrolü
                $conflict = $this->checkLessonConflict(
                    $teacherId,
                    $dow,
                    $validated['start_time'],
                    $validated['end_time'],
                    $dateStr,
                    null
                );
    
                if ($conflict) {
                    $skipped[] = $current->format('d.m.Y');
                } else {
                    PrivateLessonSession::create([
                        'private_lesson_id' => $lessonId,
                        'teacher_id'        => $teacherId,
                        'student_id'        => $studentId,
                        'day_of_week'       => $dow,
                        'start_date'        => $dateStr,
                        'start_time'        => $validated['start_time'],
                        'end_time'          => $validated['end_time'],
                        'location'          => $validated['location'] ?? $defaultLocation,
                        'fee'               => $lesson->price,
                        'payment_status'    => 'pending',
                        'paid_amount'       => 0,
                        'status'            => 'approved',
                        'notes'             => $validated['notes'],
                    ]);
                    $created++;
                }
    
                // Haftalık ileri
                $current->addWeek();
            }
    
            // Başarı mesajı
            $message = "Başarıyla {$created} seans eklendi.";
            if (! empty($skipped)) {
                $message .= ' Çakışma nedeniyle atlanan tarihler: ' . implode(', ', $skipped);
            }
    
            return redirect()
                ->route('ogretmen.private-lessons.showLesson', $lessonId)
                ->with('success', $message);
    
        } catch (\Exception $e) {
            Log::error("Yeni seans ekleme hatası: {$e->getMessage()} (Satır {$e->getLine()} Dosya {$e->getFile()})");
            return redirect()->back()
                ->with('error', 'Bir hata oluştu: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Tek bir seansı veya aynı gün ve saatteki tüm gelecekteki seansları siler
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $sessionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroySession(Request $request, $sessionId)
    {
        try {
            $teacherId = Auth::id();

            // Yalnızca o öğretmene ait seansı al
            $session = PrivateLessonSession::where('id', $sessionId)
                ->where('teacher_id', $teacherId)
                ->firstOrFail();

            $lessonId    = $session->private_lesson_id;
            $scope       = $request->input('delete_scope', 'this_only');

            if ($scope === 'all_future') {
                // Bu ve sonraki aynı gün/saat seansları sil
                $toDelete = PrivateLessonSession::where('private_lesson_id', $lessonId)
                    ->where('teacher_id', $teacherId)
                    ->where('day_of_week', $session->day_of_week)
                    ->where('start_time', $session->start_time)
                    ->where('start_date', '>=', $session->start_date)
                    ->get();

                $count = $toDelete->count();
                foreach ($toDelete as $s) {
                    $s->delete();
                }

                $message = "{$count} seans başarıyla silindi.";
            } else {
                // Sadece bu seansı sil
                $session->delete();
                $message = "Seans başarıyla silindi.";
            }

            return redirect()->route('ogretmen.private-lessons.index')
            ->with('success', $message);

        } catch (\Exception $e) {
            Log::error("Seans silme hatası: {$e->getMessage()} (Satır {$e->getLine()})");
            return redirect()->back()
                ->with('error', 'Seans silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

/**
 * Ders ekle formunu göster
 *
 * @param int $lessonId
 * @return \Illuminate\View\View
 */
public function showAddSession($lessonId)
{
    $teacherId = Auth::id();
    
    // Dersi kontrol et ve öğretmenin bu derse erişim yetkisini doğrula
    $lesson = PrivateLesson::findOrFail($lessonId);
    
    // Bu derse ait bir seans olup olmadığını kontrol et
    $sessionCheck = PrivateLessonSession::where('private_lesson_id', $lessonId)
        ->where('teacher_id', $teacherId)
        ->first();
    
    if (!$sessionCheck) {
        return redirect()->route('ogretmen.private-lessons.index')
            ->with('error', 'Bu derse erişim yetkiniz bulunmuyor.');
    }
    
    // Öğrenci bilgisini seanslardan al
    $student = $sessionCheck->student;
    
    // Bu derse ait en son seansın tarihini bul
    $lastSession = PrivateLessonSession::where('private_lesson_id', $lessonId)
        ->orderBy('start_date', 'desc')
        ->first();
    
    $lastSessionDate = $lastSession ? $lastSession->start_date : null;
    
    return view('teacher.private-lessons.add-session', compact('lesson', 'student', 'lastSessionDate'));
}
/**
 * Türkçe karakterleri ASCII karakterlere dönüştürür ve metni kısaltır
 *
 * @param string $text
 * @return string
 */
private function simplifyText($text)
{
    $turkishChars = ['ç', 'Ç', 'ğ', 'Ğ', 'ı', 'İ', 'ö', 'Ö', 'ş', 'Ş', 'ü', 'Ü'];
    $latinChars = ['c', 'C', 'g', 'G', 'i', 'I', 'o', 'O', 's', 'S', 'u', 'U'];
    
    $text = str_replace($turkishChars, $latinChars, $text);
    
    // Kısaltma yap (20 karakterden uzunsa)
    if (strlen($text) > 20) {
        $text = substr($text, 0, 20) . '..';
    }
    
    return $text;
}
/**
 * Türkçe karakterleri ASCII karakterlere dönüştürür
 *
 * @param string $text
 * @return string
 */
private function transliterateText($text)
{
    $turkishChars = ['ç', 'Ç', 'ğ', 'Ğ', 'ı', 'İ', 'ö', 'Ö', 'ş', 'Ş', 'ü', 'Ü'];
    $latinChars = ['c', 'C', 'g', 'G', 'i', 'I', 'o', 'O', 's', 'S', 'u', 'U'];
    
    return str_replace($turkishChars, $latinChars, $text);
}
    /**
 * Dersin tüm seanslarını göster (Lesson bazlı)
 */
public function showLesson($lessonId)
{
    $teacherId = Auth::id();
    
    // Dersi getir
    $lesson = PrivateLesson::findOrFail($lessonId);
    
    // Bu derse ait tüm seansları getir
    $sessions = PrivateLessonSession::with(['student'])
        ->where('private_lesson_id', $lessonId)
        ->where('teacher_id', $teacherId)
        ->orderBy('start_date', 'asc')
        ->get();
    
    // Öğrenci bilgisini ilk seanstan al
    $student = $sessions->first()->student ?? null;
    
    return view('teacher.private-lessons.lesson', compact('lesson', 'sessions', 'student'));
}
/**
 * Show the form for adding homework to a lesson
 *
 * @param int $id
 * @return \Illuminate\View\View
 */
/**
 * Show the form for adding homework to a lesson
 *
 * @param int $id
 * @return \Illuminate\View\View
 */
public function showAddHomework($id)
{
    $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student', 'homeworks'])
        ->where('teacher_id', Auth::id())
        ->findOrFail($id);
    
    return view('teacher.private-lessons.add-homework', compact('session'));
}

/**
 * Store a newly created homework
 *
 * @param \Illuminate\Http\Request $request
 * @param int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function storeHomework(Request $request, $id)
{
    $session = PrivateLessonSession::where('teacher_id', Auth::id())->findOrFail($id);
    
    // Validate the input
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'due_date' => 'required|date|after:today',
        'file' => 'nullable|file|max:10240', // 10MB max
    ]);
    
    try {
        // 🔥 Grup dersi mi kontrol et
        $isGroupLesson = $session->group_id !== null;
        
        // 🔥 Grup dersiyse tüm öğrencileri al
        if ($isGroupLesson) {
            $sessions = $session->groupSessions()->with('student')->get();
        } else {
            $sessions = collect([$session]);
        }
        
        $homeworkData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
        ];
        
        // Handle file upload if provided
        $filePath = null;
        $originalName = null;
        
        if ($request->hasFile('file')) {
            $originalName = $request->file('file')->getClientOriginalName();
            $uniqueFileName = uniqid() . '_' . time() . '.' . $request->file('file')->getClientOriginalExtension();
            
            $filePath = $request->file('file')->storeAs(
                'lessons/homeworks', 
                $uniqueFileName, 
                'local'
            );
        }
        
        $createdHomeworks = [];
        
        // 🔥 Her öğrenci için ödev oluştur
        foreach ($sessions as $studentSession) {
            $studentHomeworkData = array_merge($homeworkData, [
                'session_id' => $studentSession->id,
            ]);
            
            if ($filePath) {
                $studentHomeworkData['file_path'] = $filePath;
                $studentHomeworkData['original_filename'] = $originalName;
            }
            
            // Create homework
            $homework = PrivateLessonHomework::create($studentHomeworkData);
            $createdHomeworks[] = $homework;
        }
        
        // 🔥 SMS gönder (tüm öğrencilere)
        $smsResult = $this->sendHomeworkSMS($session, $createdHomeworks[0]);
        
        $studentCount = count($sessions);
        $smsMessage = $isGroupLesson 
            ? "✅ Grup dersi için {$studentCount} öğrenciye ödev başarıyla eklendi."
            : "Ödev başarıyla eklendi.";
        
        if (is_array($smsResult) && isset($smsResult['success']) && $smsResult['success']) {
            $totalSms = $smsResult['total_sent'] ?? 0;
            $smsMessage .= " {$totalSms} SMS bilgilendirmesi gönderildi.";
        } else {
            $smsMessage .= " Ancak SMS gönderiminde sorun oluştu.";
        }
        
        return redirect()->route('ogretmen.private-lessons.session.show', $id)
            ->with('success', $smsMessage);
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Ödev eklenirken bir hata oluştu: ' . $e->getMessage())
            ->withInput();
    }
}
/**
 * Show the form for creating a new lesson report
 *
 * @param int $id Session ID
 * @return \Illuminate\View\View
 */
public function showCreateReport($id)
{
    $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
        ->where('teacher_id', Auth::id())
        ->findOrFail($id);
    
    // Check if the lesson is completed
    if ($session->status !== 'completed') {
        return redirect()->route('ogretmen.private-lessons.session.show', $id)
            ->with('error', 'Ders tamamlanmadan rapor oluşturulamaz.');
    }
    
    // Get a list of subjects for the exam results dropdown
    $subjects = Subject::where('is_active', true)->orderBy('name')->get();
    
    // Check if a report already exists for this session
    $existingReport = PrivateLessonReport::where('session_id', $id)->first();
    if ($existingReport) {
        return redirect()->route('ogretmen.private-lessons.session.editReport', $id)
            ->with('info', 'Bu ders için zaten bir rapor oluşturulmuş. Raporu düzenleyebilirsiniz.');
    }
    
    return view('teacher.private-lessons.create-report', compact('session', 'subjects'));
}

/**
 * Store a newly created lesson report
 *
 * @param \Illuminate\Http\Request $request
 * @param int $id Session ID
 * @return \Illuminate\Http\RedirectResponse
 */
public function storeReport(Request $request, $id)
{
    $session = PrivateLessonSession::where('teacher_id', Auth::id())->findOrFail($id);
    
    // Check if the lesson is completed
    if ($session->status !== 'completed') {
        return redirect()->route('ogretmen.private-lessons.session.show', $id)
            ->with('error', 'Ders tamamlanmadan rapor oluşturulamaz.');
    }
    
    // Validate the input
    $validated = $request->validate([
        'questions_solved' => 'required|integer|min:0',
        'questions_correct' => 'required|integer|min:0',
        'questions_wrong' => 'required|integer|min:0',
        'questions_unanswered' => 'required|integer|min:0',
        'pros' => 'nullable|string',
        'cons' => 'nullable|string',
        'participation' => 'nullable|string',
        'teacher_notes' => 'nullable|string',
        'content_type' => 'required|in:deneme,soru_cozum',
        'exam_subjects' => 'nullable|array',
        'exam_subjects.*' => 'nullable|exists:subjects,id',
        'exam_correct' => 'nullable|array',
        'exam_correct.*' => 'nullable|integer|min:0',
        'exam_wrong' => 'nullable|array',
        'exam_wrong.*' => 'nullable|integer|min:0',
        'exam_unanswered' => 'nullable|array',
        'exam_unanswered.*' => 'nullable|integer|min:0',
    ]);
    
    try {
        // Start a transaction
        DB::beginTransaction();
        
        // Create the report
        $report = PrivateLessonReport::create([
            'session_id' => $session->id,
            'questions_solved' => $validated['questions_solved'],
            'questions_correct' => $validated['questions_correct'],
            'questions_wrong' => $validated['questions_wrong'],
            'questions_unanswered' => $validated['questions_unanswered'],
            'pros' => $validated['pros'],
            'cons' => $validated['cons'],
            'participation' => $validated['participation'],
            'teacher_notes' => $validated['teacher_notes'],
            'content_type' => $validated['content_type'], // Yeni eklenen alan
        ]);
        
        // Create exam results if any
        if (isset($validated['exam_subjects']) && is_array($validated['exam_subjects'])) {
            foreach ($validated['exam_subjects'] as $key => $subjectId) {
                if (empty($subjectId)) continue;
                
                $subject = Subject::findOrFail($subjectId);
                
                PrivateLessonExamResult::create([
                    'report_id' => $report->id,
                    'subject_id' => $subjectId,
                    'subject_name' => $subject->name,
                    'questions_correct' => $validated['exam_correct'][$key] ?? 0,
                    'questions_wrong' => $validated['exam_wrong'][$key] ?? 0,
                    'questions_unanswered' => $validated['exam_unanswered'][$key] ?? 0,
                ]);
            }
        }
        
        // Commit the transaction
        DB::commit();
        
        return redirect()->route('ogretmen.private-lessons.session.show', $id)
            ->with('success', 'Ders raporu başarıyla oluşturuldu.');
            
    } catch (\Exception $e) {
        // Rollback in case of error
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', 'Rapor oluşturulurken bir hata oluştu: ' . $e->getMessage())
            ->withInput();
    }
}

/**
 * Update the specified lesson report
 *
 * @param \Illuminate\Http\Request $request
 * @param int $id Session ID
 * @return \Illuminate\Http\RedirectResponse
 */

/**
 * Show the form for editing a lesson report
 *
 * @param int $id Session ID
 * @return \Illuminate\View\View
 */
public function editReport($id)
{
    $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
        ->where('teacher_id', Auth::id())
        ->findOrFail($id);
    
    // Get the existing report
    $report = PrivateLessonReport::with('examResults')
        ->where('session_id', $id)
        ->firstOrFail();
    
    // Get a list of subjects for the exam results dropdown
    $subjects = Subject::where('is_active', true)->orderBy('name')->get();
    
    return view('teacher.private-lessons.edit-report', compact('session', 'report', 'subjects'));
}

public function updateReport(Request $request, $id)
{
    $session = PrivateLessonSession::where('teacher_id', Auth::id())->findOrFail($id);
    
    // Find the report
    $report = PrivateLessonReport::where('session_id', $id)->firstOrFail();
    
    // Validate the input
    $validated = $request->validate([
        'questions_solved' => 'required|integer|min:0',
        'questions_correct' => 'required|integer|min:0',
        'questions_wrong' => 'required|integer|min:0',
        'questions_unanswered' => 'required|integer|min:0',
        'pros' => 'nullable|string',
        'cons' => 'nullable|string',
        'participation' => 'nullable|string',
        'teacher_notes' => 'nullable|string',
        'content_type' => 'required|in:deneme,soru_cozum',
        'exam_subjects' => 'nullable|array',
        'exam_subjects.*' => 'nullable|exists:subjects,id',
        'exam_correct' => 'nullable|array',
        'exam_correct.*' => 'nullable|integer|min:0',
        'exam_wrong' => 'nullable|array',
        'exam_wrong.*' => 'nullable|integer|min:0',
        'exam_unanswered' => 'nullable|array',
        'exam_unanswered.*' => 'nullable|integer|min:0',
    ]);
    
    try {
        // Start a transaction
        DB::beginTransaction();
        
        // Update the report
        $report->update([
            'questions_solved' => $validated['questions_solved'],
            'questions_correct' => $validated['questions_correct'],
            'questions_wrong' => $validated['questions_wrong'],
            'questions_unanswered' => $validated['questions_unanswered'],
            'pros' => $validated['pros'],
            'cons' => $validated['cons'],
            'participation' => $validated['participation'],
            'teacher_notes' => $validated['teacher_notes'],
            'content_type' => $validated['content_type'], // Yeni eklenen alan
        ]);
        
        // Delete existing exam results
        PrivateLessonExamResult::where('report_id', $report->id)->delete();
        
        // Create new exam results if any
        if (isset($validated['exam_subjects']) && is_array($validated['exam_subjects'])) {
            foreach ($validated['exam_subjects'] as $key => $subjectId) {
                if (empty($subjectId)) continue;
                
                $subject = Subject::findOrFail($subjectId);
                
                PrivateLessonExamResult::create([
                    'report_id' => $report->id,
                    'subject_id' => $subjectId,
                    'subject_name' => $subject->name,
                    'questions_correct' => $validated['exam_correct'][$key] ?? 0,
                    'questions_wrong' => $validated['exam_wrong'][$key] ?? 0,
                    'questions_unanswered' => $validated['exam_unanswered'][$key] ?? 0,
                ]);
            }
        }
        
        // Commit the transaction
        DB::commit();
        
        return redirect()->route('ogretmen.private-lessons.session.show', $id)
            ->with('success', 'Ders raporu başarıyla güncellendi.');
            
    } catch (\Exception $e) {
        // Rollback in case of error
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', 'Rapor güncellenirken bir hata oluştu: ' . $e->getMessage())
            ->withInput();
    }
}


/**
 * Show a lesson report
 *
 * @param int $id Session ID
 * @return \Illuminate\View\View
 */
public function showReport($id)
{
    $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
        ->where('teacher_id', Auth::id())
        ->findOrFail($id);
    
    // Get the report
    $report = PrivateLessonReport::with('examResults')
        ->where('session_id', $id)
        ->firstOrFail();
    
    return view('teacher.private-lessons.show-report', compact('session', 'report'));
}

/**
 * Delete a lesson report
 *
 * @param int $id Session ID
 * @return \Illuminate\Http\RedirectResponse
 */
public function deleteReport($id)
{
    try {
        $session = PrivateLessonSession::where('teacher_id', Auth::id())->findOrFail($id);
        
        // Find the report
        $report = PrivateLessonReport::where('session_id', $id)->firstOrFail();
        
        // Delete exam results first (cascade delete would work too if defined in migration)
        PrivateLessonExamResult::where('report_id', $report->id)->delete();
        
        // Delete the report
        $report->delete();
        
        return redirect()->route('ogretmen.private-lessons.session.show', $id)
            ->with('success', 'Ders raporu başarıyla silindi.');
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Rapor silinirken bir hata oluştu: ' . $e->getMessage());
    }
}
/**
 * Ödev eklendiğinde SMS gönderme metodu
 */
private function sendHomeworkSMS($session, $homework)
{
    try {
        $dueDate = Carbon::parse($homework->due_date)->format('d.m.Y');
        
        Log::info("Ödev SMS gönderimi için hazırlık yapılıyor. Ödev ID: " . $homework->id);
        
        $smsResults = [];
        
        // 🔥 Grup dersi mi kontrol et
        $isGroupLesson = $session->group_id !== null;
        
        // 🔥 Grup dersiyse tüm öğrencileri al, değilse sadece bu öğrenci
        if ($isGroupLesson) {
            $sessions = $session->groupSessions()->with('student')->get();
        } else {
            $sessions = collect([$session]);
        }
        
        // 🔥 Her öğrenci için SMS gönder
        foreach ($sessions as $studentSession) {
            $student = $studentSession->student;
            
            if (!$student) {
                continue;
            }
            
            $studentName = $student->name;
            $studentPhone = $student->phone;
            $parentPhone = $student->parent_phone_number;
            $parentPhone2 = $student->parent_phone_number_2;
            
            // Öğrenciye SMS
            if ($studentPhone) {
                try {
                    $studentSmsContent = "Sayın Öğrenci, özel dersinize yeni bir ödev eklendi. Son teslim tarihi: {$dueDate}. Ödev: {$homework->title}. Ödevinizi Risenglish üzerinden eklemeyi unutmayınız.";
                    
                    Log::info("ÖĞRENCİ ÖDEV SMS GÖNDERME - Öğrenci: {$studentName}, Telefon: {$studentPhone}");
                    
                    $studentResult = \App\Services\SmsService::sendSms($studentPhone, $studentSmsContent);
                    
                    Log::info("ÖĞRENCİ ÖDEV SMS SONUCU: " . json_encode($studentResult));
                    
                    $smsResults[] = [
                        'recipient' => "Öğrenci: {$studentName}",
                        'phone' => $studentPhone,
                        'result' => $studentResult
                    ];
                } catch (\Exception $e) {
                    Log::error("Öğrenci {$studentName} ödev SMS gönderiminde HATA: " . $e->getMessage());
                    $smsResults[] = [
                        'recipient' => "Öğrenci: {$studentName}",
                        'phone' => $studentPhone,
                        'result' => ['success' => false, 'message' => $e->getMessage()]
                    ];
                }
            }
            
            // Veli SMS içeriği
            $parentSmsContent = "Sayın Veli, {$studentName} için özel derse yeni bir ödev eklendi. Son teslim tarihi: {$dueDate}. Ödev: {$homework->title}";
            
            // 1. Veliye SMS
            if ($parentPhone) {
                try {
                    Log::info("VELİ-1 ÖDEV SMS GÖNDERME - Öğrenci: {$studentName}, Telefon: {$parentPhone}");
                    
                    $parentResult = \App\Services\SmsService::sendSms($parentPhone, $parentSmsContent);
                    
                    Log::info("VELİ-1 ÖDEV SMS SONUCU: " . json_encode($parentResult));
                    
                    $smsResults[] = [
                        'recipient' => "Veli-1: {$studentName}",
                        'phone' => $parentPhone,
                        'result' => $parentResult
                    ];
                } catch (\Exception $e) {
                    Log::error("Veli-1 {$studentName} ödev SMS gönderiminde HATA: " . $e->getMessage());
                    $smsResults[] = [
                        'recipient' => "Veli-1: {$studentName}",
                        'phone' => $parentPhone,
                        'result' => ['success' => false, 'message' => $e->getMessage()]
                    ];
                }
            }
            
            // 2. Veliye SMS
            if ($parentPhone2) {
                try {
                    Log::info("VELİ-2 ÖDEV SMS GÖNDERME - Öğrenci: {$studentName}, Telefon: {$parentPhone2}");
                    
                    $parent2Result = \App\Services\SmsService::sendSms($parentPhone2, $parentSmsContent);
                    
                    Log::info("VELİ-2 ÖDEV SMS SONUCU: " . json_encode($parent2Result));
                    
                    $smsResults[] = [
                        'recipient' => "Veli-2: {$studentName}",
                        'phone' => $parentPhone2,
                        'result' => $parent2Result
                    ];
                } catch (\Exception $e) {
                    Log::error("Veli-2 {$studentName} ödev SMS gönderiminde HATA: " . $e->getMessage());
                    $smsResults[] = [
                        'recipient' => "Veli-2: {$studentName}",
                        'phone' => $parentPhone2,
                        'result' => ['success' => false, 'message' => $e->getMessage()]
                    ];
                }
            }
        }
        
        // En az bir başarılı gönderim var mı kontrol et
        $anySuccess = false;
        foreach ($smsResults as $result) {
            if (isset($result['result']['success']) && $result['result']['success']) {
                $anySuccess = true;
                break;
            }
        }
        
        return [
            'success' => $anySuccess,
            'results' => $smsResults,
            'total_sent' => count($smsResults)
        ];
        
    } catch (\Exception $e) {
        Log::error("Ödev SMS gönderimi ana işleminde hata: " . $e->getMessage());
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
/**
 * View all homeworks for a session
 *
 * @param int $id
 * @return \Illuminate\View\View
 */
public function viewHomeworks($id)
{
    $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student', 'homeworks'])
        ->where('teacher_id', Auth::id())
        ->findOrFail($id);
    
    return view('teacher.private-lessons.homeworks', compact('session'));
}

/**
 * Show the delete confirmation page for a session
 *
 * @param int $id
 * @return \Illuminate\View\View
 */
public function confirmDeleteSession($id)
{
    try {
        // Ders seansını bul
        $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
            ->where('id', $id)
            ->where('teacher_id', Auth::id()) // Sadece öğretmenin kendi derslerini silmesine izin ver
            ->firstOrFail();
        
        return view('teacher.private-lessons.delete-session', compact('session'));
        
    } catch (\Exception $e) {
        Log::error("Ders silme sayfası yüklenirken hata: " . $e->getMessage());
        return redirect()->route('ogretmen.private-lessons.index')
        ->with('error', 'Ders bilgileri yüklenirken bir hata oluştu: ' . $e->getMessage());
    }
}
/**
 * Delete homework and all associated submissions and files
 *
 * @param int $homeworkId
 * @return \Illuminate\Http\RedirectResponse
 */
public function deleteHomework($homeworkId)
{
    try {
        // Find the homework with its submissions and files
        $homework = PrivateLessonHomework::with(['submissions.files'])
            ->findOrFail($homeworkId);
        
        // Check if the homework belongs to a session taught by this teacher
        $session = PrivateLessonSession::where('id', $homework->session_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();
        
        // Start a database transaction
        DB::beginTransaction();
        
        // Delete all submission files from storage and database
        foreach ($homework->submissions as $submission) {
            foreach ($submission->files as $file) {
                // Delete the physical file if it exists
                if (!empty($file->file_path) && Storage::disk('local')->exists($file->file_path)) {
                    Storage::disk('local')->delete($file->file_path);
                }
                // Delete the file record
                $file->delete();
            }
            // Delete the submission record
            $submission->delete();
        }
        
        // Delete homework file if exists
        if (!empty($homework->file_path) && Storage::disk('local')->exists($homework->file_path)) {
            Storage::disk('local')->delete($homework->file_path);
        }
        
        // Delete the homework
        $homework->delete();
        
        // Commit the transaction
        DB::commit();
        
        // Create activity log
        $logMessage = "Ödev silindi: '{$homework->title}' - Öğrenci: {$session->student->name}";
        Log::info($logMessage, ['user_id' => Auth::id(), 'homework_id' => $homeworkId]);
        
        return redirect()->back()
            ->with('success', 'Ödev ve tüm bağlantılı teslimler başarıyla silindi.');
            
    } catch (\Exception $e) {
        // Rollback in case of error
        DB::rollBack();
        
        Log::error('Ödev silme hatası: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Ödev silinirken bir hata oluştu: ' . $e->getMessage());
    }
}

public function viewHomeworkSubmissions($homeworkId)
{
    $homework = PrivateLessonHomework::with([
        'session.student',
        'submissions.student',
        'submissions.files'
    ])->findOrFail($homeworkId);
    
    // 🔥 Grup dersi mi kontrol et
    $isGroupLesson = $homework->session->group_id !== null;
    
    // 🔥 Grup dersiyse TÜM öğrencilerin ödevlerini ve teslimlerini al
    if ($isGroupLesson) {
        $groupSessions = $homework->session->groupSessions()->with('student')->get();
        $sessionIds = $groupSessions->pluck('id')->toArray();
        
        // Aynı başlıklı tüm ödevleri al
        $allHomeworks = PrivateLessonHomework::with([
            'session.student',
            'submissions.student',
            'submissions.files'
        ])
        ->whereIn('session_id', $sessionIds)
        ->where('title', $homework->title)
        ->get();
        
        // Her öğrenci için ödev ve teslim bilgisini hazırla
        $studentData = [];
        foreach ($allHomeworks as $hw) {
            $studentData[] = [
                'homework' => $hw,
                'student' => $hw->session->student,
                'submission' => $hw->submissions->first(), // En son teslim
                'submission_count' => $hw->submissions->count()
            ];
        }
    } else {
        // Bireysel ders
        $studentData = [[
            'homework' => $homework,
            'student' => $homework->session->student,
            'submission' => $homework->submissions->first(),
            'submission_count' => $homework->submissions->count()
        ]];
    }

    return view('teacher.private-lessons.homework-submissions', compact('homework', 'isGroupLesson', 'studentData'));
}
/**
 * Download homework file
 *
 * @param int $homeworkId
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */
public function downloadHomework($homeworkId)
{
    try {
        $homework = PrivateLessonHomework::findOrFail($homeworkId);
        
        // Check if the homework belongs to a session taught by this teacher
        $session = PrivateLessonSession::where('id', $homework->session_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();
        
        // Check if file exists
        if (empty($homework->file_path) || !Storage::disk('local')->exists($homework->file_path)) {
            return abort(404, 'Dosya bulunamadı veya silinmiş.');
        }
        
        // Generate download name
        $downloadName = $homework->title . '.' . pathinfo($homework->file_path, PATHINFO_EXTENSION);
        
        // Download the file
        return Storage::disk('local')->download($homework->file_path, $downloadName);
        
    } catch (\Exception $e) {
        Log::error('Ödev indirme hatası: ' . $e->getMessage());
        return back()->with('error', 'Dosya indirilirken bir hata oluştu: ' . $e->getMessage());
    }
}

/**
 * View a specific homework submission
 *
 * @param int $submissionId
 * @return \Illuminate\View\View
 */
public function viewSubmission($submissionId)
{
    $submission = PrivateLessonHomeworkSubmission::with(['homework.session', 'student'])
        ->findOrFail($submissionId);
    
    // Check if the submission belongs to a session taught by this teacher
    $session = PrivateLessonSession::where('id', $submission->homework->session_id)
        ->where('teacher_id', Auth::id())
        ->firstOrFail();
    
    return view('teacher.private-lessons.submission-detail', compact('submission', 'session'));
}

/**
 * Grade a homework submission
 *
 * @param \Illuminate\Http\Request $request
 * @param int $submissionId
 * @return \Illuminate\Http\RedirectResponse
 */
public function gradeSubmission(Request $request, $submissionId)
{
    try {
        $submission = PrivateLessonHomeworkSubmission::with(['homework.session', 'student'])
            ->findOrFail($submissionId);
        
        // Check if the submission belongs to a session taught by this teacher
        $session = PrivateLessonSession::where('id', $submission->homework->session_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();
        
        // Validate the input
        $validated = $request->validate([
            'feedback' => 'nullable|string',
            'score' => 'nullable|numeric|min:0|max:100',
        ]);
        
        Log::info("Ödev değerlendirme başladı", [
            'submission_id' => $submissionId,
            'teacher_id' => Auth::id(),
            'data' => $validated
        ]);
        
        // Update the submission
        $submission->update([
            'teacher_feedback' => $validated['feedback'],
            'score' => $validated['score'],
        ]);
        
        Log::info("Ödev değerlendirmesi güncellendi", ['submission_id' => $submissionId]);
        
        // 🔥 SMS gönder - submission ile birlikte student bilgisini de gönder
        $smsResult = $this->sendGradeSMS($session, $submission);
        
        $smsMessage = 'Ödev değerlendirmesi başarıyla kaydedildi.';
        if (is_array($smsResult) && isset($smsResult['success']) && $smsResult['success']) {
            $smsMessage .= " SMS bilgilendirmesi gönderildi.";
        } else {
            $smsMessage .= " Ancak SMS gönderiminde sorun oluştu.";
        }
        
        return redirect()->back()
            ->with('success', $smsMessage);
            
    } catch (\Exception $e) {
        Log::error("Ödev değerlendirme HATASI", [
            'submission_id' => $submissionId,
            'teacher_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Değerlendirme kaydedilirken bir hata oluştu: ' . $e->getMessage())
            ->withInput();
    }
}

/**
 * Ödev değerlendirildiğinde SMS gönderme metodu
 */
private function sendGradeSMS($session, $submission)
{
    try {
        // 🔥 Submission'a ait öğrenciyi al (her submission'ın kendi öğrencisi var)
        $student = $submission->student;
        
        if (!$student) {
            Log::warning("SMS gönderilemedi: Öğrenci bulunamadı. Submission ID: " . $submission->id);
            return ['success' => false, 'message' => 'Öğrenci bulunamadı'];
        }
        
        $studentName = $student->name ?? 'Öğrenci';
        $studentPhone = $student->phone;
        $homeworkTitle = $submission->homework ? $submission->homework->title : 'ödev';
        $score = $submission->score;
        
        // Veli telefon numaralarını al
        $parentPhone = $student->parent_phone_number ?? null;
        $parentPhone2 = $student->parent_phone_number_2 ?? null;
        
        Log::info("Ödev değerlendirme SMS gönderimi hazırlık", [
            'submission_id' => $submission->id,
            'student_id' => $student->id,
            'student_name' => $studentName,
            'score' => $score
        ]);
        
        // SMS sonuçlarını takip et
        $smsResults = [];
        
        // Öğrenci için SMS içeriği
        if ($studentPhone) {
            try {
                $studentSmsContent = "Sayın {$studentName}, özel ders için teslim ettiğiniz \"{$homeworkTitle}\" başlıklı ödeviniz değerlendirildi. Puanınız: {$score}/100. Detaylı sonuçları Risenglish üzerinden görebilirsiniz.";
                
                Log::info("ÖĞRENCİ DEĞERLENDIRME SMS", [
                    'phone' => $studentPhone,
                    'content' => $studentSmsContent
                ]);
                
                $studentResult = \App\Services\SmsService::sendSms($studentPhone, $studentSmsContent);
                
                Log::info("ÖĞRENCİ SMS SONUCU", ['result' => $studentResult]);
                
                $smsResults[] = [
                    'recipient' => 'Öğrenci',
                    'phone' => $studentPhone,
                    'result' => $studentResult
                ];
            } catch (\Exception $e) {
                Log::error("Öğrenci SMS hatası", ['error' => $e->getMessage()]);
                $smsResults[] = [
                    'recipient' => 'Öğrenci',
                    'phone' => $studentPhone,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        }
        
        // Veli için SMS içeriği
        $parentSmsContent = "Sayın Veli, {$studentName}'in özel ders için teslim ettiği \"{$homeworkTitle}\" başlıklı ödevi değerlendirildi. Puanı: {$score}/100";
        
        // Veli-1'e SMS
        if ($parentPhone) {
            try {
                Log::info("VELİ-1 SMS", ['phone' => $parentPhone, 'content' => $parentSmsContent]);
                
                $parentResult = \App\Services\SmsService::sendSms($parentPhone, $parentSmsContent);
                
                Log::info("VELİ-1 SMS SONUCU", ['result' => $parentResult]);
                
                $smsResults[] = [
                    'recipient' => 'Veli-1',
                    'phone' => $parentPhone,
                    'result' => $parentResult
                ];
            } catch (\Exception $e) {
                Log::error("Veli-1 SMS hatası", ['error' => $e->getMessage()]);
                $smsResults[] = [
                    'recipient' => 'Veli-1',
                    'phone' => $parentPhone,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        }
        
        // Veli-2'ye SMS
        if ($parentPhone2) {
            try {
                Log::info("VELİ-2 SMS", ['phone' => $parentPhone2, 'content' => $parentSmsContent]);
                
                $parent2Result = \App\Services\SmsService::sendSms($parentPhone2, $parentSmsContent);
                
                Log::info("VELİ-2 SMS SONUCU", ['result' => $parent2Result]);
                
                $smsResults[] = [
                    'recipient' => 'Veli-2',
                    'phone' => $parentPhone2,
                    'result' => $parent2Result
                ];
            } catch (\Exception $e) {
                Log::error("Veli-2 SMS hatası", ['error' => $e->getMessage()]);
                $smsResults[] = [
                    'recipient' => 'Veli-2',
                    'phone' => $parentPhone2,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        }
        
        // En az bir başarılı gönderim var mı
        $anySuccess = false;
        foreach ($smsResults as $result) {
            if (isset($result['result']['success']) && $result['result']['success']) {
                $anySuccess = true;
                break;
            }
        }
        
        return [
            'success' => $anySuccess,
            'results' => $smsResults
        ];
        
    } catch (\Exception $e) {
        Log::error("SMS gönderim hatası", ['error' => $e->getMessage()]);
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
/**
 * Download homework submission file
 *
 * @param int $submissionId
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */
public function downloadSubmission($submissionId)
{
    try {
        $submission = PrivateLessonHomeworkSubmission::with(['homework.session', 'student'])
            ->findOrFail($submissionId);
        
        // Check if the submission belongs to a session taught by this teacher
        $session = PrivateLessonSession::where('id', $submission->homework->session_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();
        
        // Check if file exists
        if (empty($submission->file_path) || !Storage::disk('local')->exists($submission->file_path)) {
            return abort(404, 'Dosya bulunamadı veya silinmiş.');
        }
        
        // Generate download name
        $downloadName = $submission->student->name . '_' . $submission->homework->title . '.' . 
                       pathinfo($submission->file_path, PATHINFO_EXTENSION);
        
        // Download the file
        return Storage::disk('local')->download($submission->file_path, $downloadName);
        
    } catch (\Exception $e) {
        Log::error('Ödev teslimi indirme hatası: ' . $e->getMessage());
        return back()->with('error', 'Dosya indirilirken bir hata oluştu: ' . $e->getMessage());
    }
}
/**
 * Ders düzenleme formunu göster (tüm seansları değil)
 */
public function editLesson($lessonId)
{
    $teacherId = Auth::id();
    
    // Dersi getir
    $lesson = PrivateLesson::findOrFail($lessonId);
    
    // Bu derse ait bir seans getir (öğrenci bilgisi için)
    $session = PrivateLessonSession::where('private_lesson_id', $lessonId)
        ->where('teacher_id', $teacherId)
        ->first();
    
    // Öğrenci listesini çekelim
    $students = User::role('ogrenci')->get();
    
    return view('teacher.private-lessons.edit-lesson', compact('lesson', 'session', 'students'));
}
/**
 * Özel dersin aktiflik durumunu değiştirir
 * 
 * @param int $lessonId Özel ders ID
 * @return \Illuminate\Http\RedirectResponse
 */
public function toggleLessonActive($lessonId)
{
    try {
        $teacherId = Auth::id();
        
        // Dersi getir
        $lesson = PrivateLesson::findOrFail($lessonId);
        
        // Öğretmenin yetkisi var mı kontrol et
        $sessionCheck = PrivateLessonSession::where('private_lesson_id', $lessonId)
            ->where('teacher_id', $teacherId)
            ->first();
            
        if (!$sessionCheck) {
            return redirect()->back()
                ->with('error', 'Bu dersi değiştirme yetkiniz bulunmuyor.');
        }
        
        // Aktiflik durumunu değiştir
        $lesson->is_active = !$lesson->is_active;
        $lesson->save();
        
        $status = $lesson->is_active ? 'aktif' : 'pasif';
        return redirect()->back()
            ->with('success', "Ders başarıyla {$status} duruma getirildi.");
            
    } catch (\Exception $e) {
        Log::error("Ders durumu değiştirme hatası: " . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
    }
}
public function updateLesson(Request $request, $lessonId)
{
    try {
        $teacherId = Auth::id();

        $lesson = PrivateLesson::findOrFail($lessonId);

        $sessions = PrivateLessonSession::where('private_lesson_id', $lessonId)
            ->where('teacher_id', $teacherId)
            ->get();

        $validated = $request->validate([
            'lesson_name' => 'required|string|max:255',
            'student_id' => 'required|exists:users,id',
            'fee' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:approved,cancelled',
            'notes' => 'nullable|string',
            'day_of_week' => 'nullable|integer|min:0|max:6',
            'start_time' => 'nullable',
            'end_time' => 'nullable|after:start_time', // Bitiş saati doğrulaması eklendi
            'skip_past_sessions' => 'required|boolean',
            'update_all_times' => 'required|boolean'
        ]);

        $lesson->update([
            'name' => $validated['lesson_name'],
            'price' => $validated['fee']
        ]);

        $updateTimes = $validated['update_all_times'];
        $newDayOfWeek = $validated['day_of_week'] ?? null;
        $newStartTime = $validated['start_time'] ?? null;
        $newEndTime = $validated['end_time'] ?? null; // Bitiş saati değişkeni eklendi

        // Carbon gün isimleri dizisi
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        foreach ($sessions as $session) {
            if ($validated['skip_past_sessions'] &&
                strtotime($session->start_date . ' ' . $session->start_time) < time()) {
                continue;
            }

            $updateData = [
                'student_id' => $validated['student_id'],
                'fee' => $validated['fee'],
                'location' => $validated['location'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null
            ];

            if ($updateTimes) {
                $oldDate = Carbon::parse($session->start_date);

                if (!is_null($newDayOfWeek) && !is_null($newStartTime)) {
                    $dayName = $days[$newDayOfWeek];
                    $newDate = $oldDate->copy()->next($dayName);
                    
                    // Bitiş saati kontrolü: ya gönderilen değer ya da başlangıçtan 1 saat sonrası
                    $endTime = !is_null($newEndTime) ? $newEndTime : 
                               Carbon::parse($newStartTime)->addHour()->format('H:i');

                    $updateData['day_of_week'] = $newDayOfWeek;
                    $updateData['start_date'] = $newDate->format('Y-m-d');
                    $updateData['start_time'] = $newStartTime;
                    $updateData['end_time'] = $endTime;
                } elseif (!is_null($newDayOfWeek)) {
                    $dayName = $days[$newDayOfWeek];
                    $newDate = $oldDate->copy()->next($dayName);

                    $updateData['day_of_week'] = $newDayOfWeek;
                    $updateData['start_date'] = $newDate->format('Y-m-d');
                } elseif (!is_null($newStartTime)) {
                    // Bitiş saati kontrolü: ya gönderilen değer ya da başlangıçtan 1 saat sonrası
                    $endTime = !is_null($newEndTime) ? $newEndTime : 
                               Carbon::parse($newStartTime)->addHour()->format('H:i');

                    $updateData['start_time'] = $newStartTime;
                    $updateData['end_time'] = $endTime;
                } elseif (!is_null($newEndTime)) {
                    // Sadece bitiş saati değiştirilirse
                    $updateData['end_time'] = $newEndTime;
                }

                // Çakışma kontrolü
                if (isset($updateData['day_of_week']) || isset($updateData['start_time']) || isset($updateData['end_time'])) {
                    $conflictExists = $this->checkLessonConflict(
                        $teacherId,
                        $updateData['day_of_week'] ?? $session->day_of_week,
                        $updateData['start_time'] ?? $session->start_time,
                        $updateData['end_time'] ?? $session->end_time,
                        $updateData['start_date'] ?? $session->start_date,
                        $session->id
                    );

                    if ($conflictExists) {
                        return redirect()->back()
                            ->with('error', 'Seans saatinde çakışma var! Lütfen önce diğer dersi iptal edin veya başka bir saat seçin.')
                            ->withInput();
                    }
                }
            }

            $session->update($updateData);
        }

        return redirect()->route('ogretmen.private-lessons.showLesson', $lessonId)
            ->with('success', 'Özel ders başarıyla güncellendi.');

    } catch (\Exception $e) {
        Log::error("Ders güncelleme hatası: " . $e->getMessage());
        Log::error("Hata satırı: " . $e->getLine());
        Log::error("Hata dosyası: " . $e->getFile());
        Log::error("Hata izi: " . $e->getTraceAsString());

        return redirect()->back()
            ->with('error', 'Bir hata oluştu: ' . $e->getMessage())
            ->withInput();
    }
}
    /**
 * Show the form for adding materials to a lesson
 *
 * @param int $id
 * @return \Illuminate\View\View
 */
public function showAddMaterial($id)
{
    $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student', 'materials'])
        ->where('teacher_id', Auth::id())
        ->findOrFail($id);
    
    // Check if the lesson is completed
    // if ($session->status !== 'completed') {
    //     return redirect()->route('ogretmen.private-lessons.session.show', $id)
    //         ->with('error', 'Ders tamamlanmadan materyal eklenemez.');
    // }
    
    return view('teacher.private-lessons.add-material', compact('session'));
}

/**
 * Store a newly created material
 *
 * @param \Illuminate\Http\Request $request
 * @param int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function storeMaterial(Request $request, $id)
{
    $session = PrivateLessonSession::where('teacher_id', Auth::id())->findOrFail($id);
    
    // Validate the input
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'material_file' => 'required|file|max:10240', // 10MB max
    ]);
    
    try {
        // Benzersiz bir dosya adı oluşturun
        $originalName = $request->file('material_file')->getClientOriginalName();
        $fileExtension = $request->file('material_file')->getClientOriginalExtension();
        $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExtension;
        
        // Dosyayı local disk'e kaydedin (bu private bir klasöre kaydedecek)
        $filePath = $request->file('material_file')->storeAs(
            'lessons/materials', 
            $uniqueFileName, 
            'local'  // zaten private klasörü gösteriyor
        );
        
        // Veritabanı kaydını oluşturun
        $material = PrivateLessonMaterial::create([
            'session_id' => $session->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'original_filename' => $originalName,
        ]);
        
        // Her zaman SMS göndermek için
        $smsResult = $this->sendMaterialSMS($session, $material);
        
        $smsMessage = 'Ders materyali başarıyla eklendi.';
        if (is_array($smsResult) && isset($smsResult['success']) && $smsResult['success']) {
            $smsMessage .= " SMS bilgilendirmesi gönderildi.";
        } else {
            $smsMessage .= " Ancak SMS gönderiminde sorun oluştu.";
        }
        
        return redirect()->route('ogretmen.private-lessons.session.show', $id)
            ->with('success', $smsMessage);
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Materyal eklenirken bir hata oluştu: ' . $e->getMessage())
            ->withInput();
    }
}

/**
 * Materyal eklendiğinde SMS gönderme metodu
 */
private function sendMaterialSMS($session, $material)
{
    try {
        // Temel bilgileri hazırla
        $studentName = $session->student ? $session->student->name : 'Öğrenci';
        $studentPhone = $session->student ? $session->student->phone : null;
        
        // Veli telefon numaralarını al
        $parentPhone = null;
        $parentPhone2 = null;
        
        if ($session->student && $session->student->parent_phone_number) {
            $parentPhone = $session->student->parent_phone_number;
        }
        
        if ($session->student && $session->student->parent_phone_number_2) {
            $parentPhone2 = $session->student->parent_phone_number_2;
        }
        
        // Log kayıtları
        Log::info("Materyal SMS gönderimi için hazırlık yapılıyor. Materyal ID: " . $material->id);
        
        // SMS sonuçlarını takip et
        $smsResults = [];
        
        // Öğrenci için SMS içeriği - değiştirildi
        if ($studentPhone) {
            try {
                $studentSmsContent = "Sayın Öğrenci, özel dersinize yeni bir materyal eklendi: {$material->title}. Risenglish üzerinden erişebilirsiniz.";
                
                Log::info("ÖĞRENCİ MATERYAL SMS GÖNDERME - Telefon: {$studentPhone}, İçerik: {$studentSmsContent}");
                
                // Öğrenciye SMS gönder
                $studentResult = \App\Services\SmsService::sendSms($studentPhone, $studentSmsContent);
                
                Log::info("ÖĞRENCİ MATERYAL SMS SONUCU: " . json_encode($studentResult));
                
                $smsResults[] = [
                    'recipient' => 'Öğrenci',
                    'phone' => $studentPhone,
                    'result' => $studentResult
                ];
            } catch (\Exception $e) {
                Log::error("Öğrenci materyal SMS gönderiminde HATA: " . $e->getMessage());
                $smsResults[] = [
                    'recipient' => 'Öğrenci',
                    'phone' => $studentPhone,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        }
        
        // Veli için SMS içeriği - değiştirildi
        $parentSmsContent = "Sayın Veli, {$studentName} için özel derse yeni bir materyal eklendi: {$material->title}";
        
        // 1. Veliye SMS gönder
        if ($parentPhone) {
            try {
                Log::info("VELİ-1 MATERYAL SMS GÖNDERME - Telefon: {$parentPhone}, İçerik: {$parentSmsContent}");
                
                $parentResult = \App\Services\SmsService::sendSms($parentPhone, $parentSmsContent);
                
                Log::info("VELİ-1 MATERYAL SMS SONUCU: " . json_encode($parentResult));
                
                $smsResults[] = [
                    'recipient' => 'Veli-1',
                    'phone' => $parentPhone,
                    'result' => $parentResult
                ];
            } catch (\Exception $e) {
                Log::error("Veli-1 materyal SMS gönderiminde HATA: " . $e->getMessage());
                $smsResults[] = [
                    'recipient' => 'Veli-1',
                    'phone' => $parentPhone,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        }
        
        // 2. Veliye SMS gönder
        if ($parentPhone2) {
            try {
                Log::info("VELİ-2 MATERYAL SMS GÖNDERME - Telefon: {$parentPhone2}, İçerik: {$parentSmsContent}");
                
                $parent2Result = \App\Services\SmsService::sendSms($parentPhone2, $parentSmsContent);
                
                Log::info("VELİ-2 MATERYAL SMS SONUCU: " . json_encode($parent2Result));
                
                $smsResults[] = [
                    'recipient' => 'Veli-2',
                    'phone' => $parentPhone2,
                    'result' => $parent2Result
                ];
            } catch (\Exception $e) {
                Log::error("Veli-2 materyal SMS gönderiminde HATA: " . $e->getMessage());
                $smsResults[] = [
                    'recipient' => 'Veli-2',
                    'phone' => $parentPhone2,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        }
        
        // En az bir başarılı gönderim var mı kontrol et
        $anySuccess = false;
        foreach ($smsResults as $result) {
            if (isset($result['result']['success']) && $result['result']['success']) {
                $anySuccess = true;
                break;
            }
        }
        
        return [
            'success' => $anySuccess,
            'results' => $smsResults
        ];
        
    } catch (\Exception $e) {
        Log::error("Materyal SMS gönderimi ana işleminde hata: " . $e->getMessage());
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
/**
 * Delete a material
 *
 * @param int $materialId
 * @return \Illuminate\Http\RedirectResponse
 */
public function deleteMaterial($materialId)
{
    try {
        $material = PrivateLessonMaterial::findOrFail($materialId);
        
        // Check if the material belongs to a session taught by this teacher
        $session = PrivateLessonSession::where('id', $material->session_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();
        
        // Delete the file
        if (\Storage::disk('public')->exists($material->file_path)) {
            \Storage::disk('public')->delete($material->file_path);
        }
        
        // Delete the record
        $material->delete();
        
        return redirect()->route('ogretmen.private-lessons.session.show', $session->id)
            ->with('success', 'Materyal başarıyla silindi.');
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Materyal silinirken bir hata oluştu: ' . $e->getMessage());
    }
}
/**
 * Dersi tamamla
 *
 * @param int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function completeLesson($id)
{
    try {
        // Ders kaydını bul
        $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
            ->findOrFail($id);
        
        // Ders durumunu zaten tamamlanmış mı kontrol et
        if ($session->status === 'completed') {
            return redirect()->back()->with('info', 'Bu ders zaten tamamlanmış durumda.');
        }
        
        // 🔥 Grup dersi mi kontrol et
        $isGroupLesson = $session->group_id !== null;
        
        if ($isGroupLesson) {
            // Grup dersiyse TÜM session'ları tamamla
            $groupSessions = PrivateLessonSession::where('group_id', $session->group_id)
                ->where('start_date', $session->start_date)
                ->where('start_time', $session->start_time)
                ->get();
            
            foreach ($groupSessions as $groupSession) {
                $groupSession->status = 'completed';
                $groupSession->save();
            }
            
            // Her öğrenci için SMS gönder
            $allSmsResults = [];
            foreach ($groupSessions as $groupSession) {
                $smsResult = $this->sendCompletionSMS($groupSession);
                $allSmsResults[] = $smsResult;
            }
            
            // Toplu sonuç mesajı
            $successCount = 0;
            foreach ($allSmsResults as $result) {
                if (is_array($result) && isset($result['success']) && $result['success']) {
                    $successCount++;
                }
            }
            
            $smsMessage = 'Grup dersi başarıyla tamamlandı! ';
            if ($successCount > 0) {
                $smsMessage .= "{$successCount} öğrenciye SMS bilgilendirmesi gönderildi.";
            } else {
                $smsMessage .= "Ancak SMS gönderiminde sorun oluştu.";
            }
            
        } else {
            // Bireysel ders
            $session->status = 'completed';
            $session->save();
            
            // SMS gönderimi
            $smsResult = $this->sendCompletionSMS($session);
            
            $smsMessage = 'Ders başarıyla tamamlandı!';
            
            if (is_array($smsResult)) {
                if (isset($smsResult['success']) && $smsResult['success']) {
                    $sessionNumber = isset($smsResult['session_number']) ? $smsResult['session_number'] : '';
                    $smsMessage .= " {$sessionNumber}. seans SMS bilgilendirmesi gönderildi.";
                } else {
                    $smsMessage .= " Ancak SMS gönderiminde sorun oluştu.";
                }
            } else {
                if ($smsResult === true) {
                    $smsMessage .= " SMS bilgilendirmesi gönderildi.";
                } else {
                    $smsMessage .= " Ancak SMS gönderiminde sorun oluştu.";
                }
            }
        }
        
        return redirect()->back()->with('success', $smsMessage);
        
    } catch (\Exception $e) {
        Log::error("Ders tamamlama hatası", ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Ders tamamlanırken bir hata oluştu: ' . $e->getMessage());
    }
}

/**
 * Ders tamamlandığında SMS gönderme fonksiyonu
 */
private function sendCompletionSMS($session)
{
    try {
        // 🔥 Session'a ait öğrenciyi al
        $student = $session->student;
        
        if (!$student) {
            Log::warning("SMS gönderilemedi: Öğrenci bulunamadı. Session ID: " . $session->id);
            return ['success' => false, 'message' => 'Öğrenci bulunamadı'];
        }
        
        // Seans numarasını hesapla - sadece iptal edilmemiş seansları dahil et
        $sessionNumber = PrivateLessonSession::where('private_lesson_id', $session->private_lesson_id)
            ->where('student_id', $student->id) // 🔥 Bu öğrencinin seansları
            ->where('status', '!=', 'cancelled')
            ->where('start_date', '<=', $session->start_date)
            ->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->search(function($item) use ($session) {
                return $item->id === $session->id;
            }) + 1;
        
        $studentName = $student->name ?? 'Öğrenci';
        $studentPhone = $student->phone;
        $parentPhone = $student->parent_phone_number ?? null;
        $parentPhone2 = $student->parent_phone_number_2 ?? null;
        $lessonDate = Carbon::parse($session->start_date)->format('d.m.Y');
        
        Log::info("Ders tamamlama SMS hazırlık", [
            'session_id' => $session->id,
            'student_id' => $student->id,
            'student_name' => $studentName,
            'session_number' => $sessionNumber
        ]);
        
        $smsResults = [];
        
        // Öğrenci SMS
        if ($studentPhone) {
            try {
                $studentSmsContent = "Sayın {$studentName}, {$lessonDate} tarihli {$sessionNumber}. ders seansınız tamamlanmıştır.";
                
                Log::info("ÖĞRENCİ SMS", ['phone' => $studentPhone, 'content' => $studentSmsContent]);
                
                $studentResult = \App\Services\SmsService::sendSms($studentPhone, $studentSmsContent);
                
                Log::info("ÖĞRENCİ SMS SONUCU", ['result' => $studentResult]);
                
                $smsResults[] = [
                    'recipient' => 'Öğrenci',
                    'phone' => $studentPhone,
                    'result' => $studentResult
                ];
            } catch (\Exception $e) {
                Log::error("Öğrenci SMS hatası", ['error' => $e->getMessage()]);
                $smsResults[] = [
                    'recipient' => 'Öğrenci',
                    'phone' => $studentPhone,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        }
        
        // Veli SMS
        $parentSmsContent = "Sayın Veli, {$studentName}'in {$lessonDate} tarihli {$sessionNumber}. ders seansı tamamlanmıştır.";
        
        // Veli-1
        if ($parentPhone) {
            try {
                Log::info("VELİ-1 SMS", ['phone' => $parentPhone, 'content' => $parentSmsContent]);
                
                $parentResult = \App\Services\SmsService::sendSms($parentPhone, $parentSmsContent);
                
                Log::info("VELİ-1 SMS SONUCU", ['result' => $parentResult]);
                
                $smsResults[] = [
                    'recipient' => 'Veli-1',
                    'phone' => $parentPhone,
                    'result' => $parentResult
                ];
            } catch (\Exception $e) {
                Log::error("Veli-1 SMS hatası", ['error' => $e->getMessage()]);
                $smsResults[] = [
                    'recipient' => 'Veli-1',
                    'phone' => $parentPhone,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        }
        
        // Veli-2
        if ($parentPhone2) {
            try {
                Log::info("VELİ-2 SMS", ['phone' => $parentPhone2, 'content' => $parentSmsContent]);
                
                $parent2Result = \App\Services\SmsService::sendSms($parentPhone2, $parentSmsContent);
                
                Log::info("VELİ-2 SMS SONUCU", ['result' => $parent2Result]);
                
                $smsResults[] = [
                    'recipient' => 'Veli-2',
                    'phone' => $parentPhone2,
                    'result' => $parent2Result
                ];
            } catch (\Exception $e) {
                Log::error("Veli-2 SMS hatası", ['error' => $e->getMessage()]);
                $smsResults[] = [
                    'recipient' => 'Veli-2',
                    'phone' => $parentPhone2,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        }
        
        // En az bir başarılı gönderim var mı
        $anySuccess = false;
        foreach ($smsResults as $result) {
            if (isset($result['result']['success']) && $result['result']['success']) {
                $anySuccess = true;
                break;
            }
        }
        
        return [
            'success' => $anySuccess,
            'results' => $smsResults,
            'session_number' => $sessionNumber
        ];
        
    } catch (\Exception $e) {
        Log::error("SMS gönderim hatası", ['error' => $e->getMessage()]);
        return [
            'success' => false,
            'message' => $e->getMessage(),
            'session_number' => 0
        ];
    }
}
/**
 * Ders materyalini indir
 *
 * @param int $id Materyal ID
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */
public function downloadMaterial($id)
{
    try {
        // Materyali bul
        $material = PrivateLessonMaterial::findOrFail($id);
        
        // Materyal hangi derse ait, derse erişim yetkisi kontrolü
        $session = PrivateLessonSession::findOrFail($material->session_id);
        
        // Yetkilendirme kontrolü: Sadece dersin öğretmeni, öğrencisi veya admin erişebilir
        if (Auth::id() != $session->teacher_id && 
            Auth::id() != $session->student_id && 
            !Auth::user()->hasRole('admin')) {
            return abort(403, 'Bu materyali indirme yetkiniz bulunmuyor.');
        }
        
        // Dosyanın var olduğunu kontrol et
        if (!Storage::disk('local')->exists($material->file_path)) {
            return abort(404, 'Dosya bulunamadı veya silinmiş.');
        }
        
        // Dosya adını oluştur 
        $originalFileName = pathinfo($material->file_path, PATHINFO_FILENAME);
        $extension = pathinfo($material->file_path, PATHINFO_EXTENSION);
        $downloadName = $material->title . '.' . $extension;
        
        // Dosyayı indir
        return Storage::disk('local')->download($material->file_path, $downloadName);
        
    } catch (\Exception $e) {
        Log::error('Materyal indirme hatası: ' . $e->getMessage());
        return back()->with('error', 'Dosya indirilirken bir hata oluştu: ' . $e->getMessage());
    }
}
/**
 * Tek bir ders seansının detaylarını göster
 *
 * @param int $id
 * @return \Illuminate\View\View
 */
public function showSession($id)
{
    try {
        // Veritabanından ders bilgilerini çek
        $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
            ->findOrFail($id);
        
        // Ders durumları için renkler ve etiketler
        $statuses = [
            'pending' => 'Beklemede',
            'approved' => 'Onaylandı',
            'active' => 'Aktif',
            'rejected' => 'Reddedildi',
            'cancelled' => 'İptal Edildi',
            'completed' => 'Tamamlandı',
            'scheduled' => 'Planlandı',
        ];
        
        // Şu anki zamanı kontrol et (ders tamamlandı mı vs. için)
        $currentTime = now();
        $lessonEndTime = Carbon::parse($session->start_date . ' ' . $session->end_time);
        $isLessonCompleted = $session->status === 'completed';
        $isLessonPassed = $currentTime->isAfter($lessonEndTime);
        
        return view('teacher.private-lessons.session', compact('session', 'statuses', 'isLessonCompleted', 'isLessonPassed'));
        
    } catch (\Exception $e) {
        // Hata durumunda
        Log::error("Ders bilgileri yüklenirken hata: " . $e->getMessage());
        return redirect()->route('ogretmen.private-lessons.index')
            ->with('error', 'Ders detayları yüklenirken bir hata oluştu: ' . $e->getMessage());
    }
}
/**
 * Ödev teslimi için geri bildirim kaydet
 */
public function submitHomeworkFeedback(Request $request, $id)
{
    try {
        $submission = PrivateLessonHomeworkSubmission::with('homework.session')
            ->findOrFail($id);
        
        // Sadece kendi dersinin ödevine geri bildirim verebilsin
        if ($submission->homework->session->teacher_id !== Auth::id()) {
            abort(403, 'Bu ödeve geri bildirim verme yetkiniz yok.');
        }
        
        $validated = $request->validate([
            'feedback' => 'nullable|string',
            'grade' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:pending,reviewed,approved,rejected'
        ]);
        
        $submission->update([
            'teacher_feedback' => $validated['feedback'],
            'grade' => $validated['grade'],
            'status' => $validated['status'],
            'reviewed_at' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Geri bildirim başarıyla kaydedildi.');
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Geri bildirim kaydedilirken hata oluştu: ' . $e->getMessage());
    }
}
/**
 * Ders tamamlandığında SMS gönderme fonksiyonu
 */
/**
 * Ders tamamlandığında SMS gönderme fonksiyonu
 */
private function sendCompletionSMS($session)
{
    try {
        // Seans numarasını hesapla - sadece iptal edilmemiş seansları dahil et
        $sessionNumber = PrivateLessonSession::where('private_lesson_id', $session->private_lesson_id)
            ->where('status', '!=', 'cancelled') // İptal edilmiş dersleri hariç tut
            ->where('start_date', '<=', $session->start_date)
            ->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->search(function($item) use ($session) {
                return $item->id === $session->id;
            }) + 1; // 0-bazlı indekse +1 ekleyerek 1-bazlı numaralandırma yapıyoruz
        
        // Temel bilgileri hazırla
        $studentName = $session->student ? $session->student->name : 'Öğrenci';
        $studentPhone = $session->student ? $session->student->phone : null;
        
        // Veli telefon numaralarını al
        $parentPhone = null;
        $parentPhone2 = null;
        
        if ($session->student && $session->student->parent_phone_number) {
            $parentPhone = $session->student->parent_phone_number;
        }
        
        if ($session->student && $session->student->parent_phone_number_2) {
            $parentPhone2 = $session->student->parent_phone_number_2;
        }
        
        $lessonDate = Carbon::parse($session->start_date)->format('d.m.Y');
        
        // Log kayıtları
        Log::info("SMS gönderimi için hazırlık yapılıyor. Ders ID: " . $session->id);
        Log::info("Bu dersin {$sessionNumber}. seansı tamamlandı (iptal edilenler hariç).");
        
        // SMS sonuçlarını takip et
        $smsResults = [];
        
        // Öğrenci için SMS içeriği - kısaltılmış
        if ($studentPhone) {
            try {
                $studentSmsContent = "Sayın Öğrenci, {$lessonDate} tarihli {$sessionNumber}. ders seansınız tamamlanmıştır.";
                
                Log::info("ÖĞRENCİ SMS GÖNDERME BAŞLATILIYOR - Telefon: {$studentPhone}, İçerik: {$studentSmsContent}");
                
                // Öğrenciye SMS gönder
                $studentResult = \App\Services\SmsService::sendSms($studentPhone, $studentSmsContent);
                
                Log::info("ÖĞRENCİ SMS SONUCU: " . json_encode($studentResult));
                
                $smsResults[] = [
                    'recipient' => 'Öğrenci',
                    'phone' => $studentPhone,
                    'result' => $studentResult
                ];
            } catch (\Exception $e) {
                Log::error("Öğrenci SMS gönderiminde HATA: " . $e->getMessage());
                $smsResults[] = [
                    'recipient' => 'Öğrenci',
                    'phone' => $studentPhone,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        }
        
        // Veli için SMS içeriği - kısaltılmış
        $parentSmsContent = "Sayın Veli, {$studentName}'in {$lessonDate} tarihli {$sessionNumber}. ders seansı tamamlanmıştır.";
        
        // 1. Veliye SMS gönder
        if ($parentPhone) {
            try {
                Log::info("VELİ-1 SMS GÖNDERME BAŞLATILIYOR - Telefon: {$parentPhone}, İçerik: {$parentSmsContent}");
                
                $parentResult = \App\Services\SmsService::sendSms($parentPhone, $parentSmsContent);
                
                Log::info("VELİ-1 SMS SONUCU: " . json_encode($parentResult));
                
                $smsResults[] = [
                    'recipient' => 'Veli-1',
                    'phone' => $parentPhone,
                    'result' => $parentResult
                ];
            } catch (\Exception $e) {
                Log::error("Veli-1 SMS gönderiminde HATA: " . $e->getMessage());
                $smsResults[] = [
                    'recipient' => 'Veli-1',
                    'phone' => $parentPhone,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        } else {
            Log::warning("Veli-1 telefon numarası bulunamadı. Öğrenci ID: " . ($session->student ? $session->student->id : 'N/A'));
        }
        
        // 2. Veliye SMS gönder
        if ($parentPhone2) {
            try {
                Log::info("VELİ-2 SMS GÖNDERME BAŞLATILIYOR - Telefon: {$parentPhone2}, İçerik: {$parentSmsContent}");
                
                $parent2Result = \App\Services\SmsService::sendSms($parentPhone2, $parentSmsContent);
                
                Log::info("VELİ-2 SMS SONUCU: " . json_encode($parent2Result));
                
                $smsResults[] = [
                    'recipient' => 'Veli-2',
                    'phone' => $parentPhone2,
                    'result' => $parent2Result
                ];
            } catch (\Exception $e) {
                Log::error("Veli-2 SMS gönderiminde HATA: " . $e->getMessage());
                $smsResults[] = [
                    'recipient' => 'Veli-2',
                    'phone' => $parentPhone2,
                    'result' => ['success' => false, 'message' => $e->getMessage()]
                ];
            }
        } else {
            Log::warning("Veli-2 telefon numarası bulunamadı. Öğrenci ID: " . ($session->student ? $session->student->id : 'N/A'));
        }
        
        // Sonuçları logla
        foreach ($smsResults as $result) {
            $status = isset($result['result']['success']) && $result['result']['success'] ? 'Başarılı' : 'Başarısız';
            $message = isset($result['result']['message']) ? $result['result']['message'] : 'Bilinmeyen sonuç';
            
            Log::info("SMS gönderimi ({$result['recipient']} - {$result['phone']}): {$status} - {$message}");
        }
        
        // En az bir başarılı gönderim var mı kontrol et
        $anySuccess = false;
        foreach ($smsResults as $result) {
            if (isset($result['result']['success']) && $result['result']['success']) {
                $anySuccess = true;
                break;
            }
        }
        
        return [
            'success' => $anySuccess,
            'results' => $smsResults,
            'session_number' => $sessionNumber
        ];
        
    } catch (\Exception $e) {
        Log::error("SMS gönderimi ana işleminde hata: " . $e->getMessage());
        Log::error("Hata detayı: " . $e->getTraceAsString());
        return [
            'success' => false,
            'message' => $e->getMessage(),
            'session_number' => 0
        ];
    }
}/**
     * Öğretmenin henüz onaylamadığı (pending) özel ders taleplerini listeler
     */
    public function pendingRequests()
    {
        $teacherId = Auth::id();

        $pendingSessions = PrivateLessonSession::with(['privateLesson', 'student'])
            ->where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('teacher.private-lessons.pending', compact('pendingSessions'));
    }

    /**
     * Yeni özel ders oluşturma formunu gösterir
     */
    public function create()
    {
        // Öğrenci listesini çekelim
        $students = User::role('ogrenci')->get();
        
        return view('teacher.private-lessons.create', compact('students'));
    }

/**
 * Yeni özel dersi kaydeder
 */
public function store(Request $request)
{
    try {
        $teacherId = Auth::id();
        
        $isGroupLesson = $request->has('is_group_lesson') && $request->is_group_lesson == '1';
        
        $rules = [
            'lesson_name' => 'required|string|max:255',
            'fee' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|array|min:1',
            'days.*' => 'required|integer|min:0|max:6',
            'start_times' => 'required|array|min:1',
            'start_times.*' => 'required',
            'end_times' => 'required|array|min:1',
            'end_times.*' => 'required',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:approved,cancelled',
            'notes' => 'nullable|string'
        ];
        
        if ($isGroupLesson) {
            $rules['student_ids'] = 'required|array|min:1';
            $rules['student_ids.*'] = 'exists:users,id';
        } else {
            $rules['student_id'] = 'required|exists:users,id';
        }
        
        $validated = $request->validate($rules);
        
        $studentIds = $isGroupLesson ? $validated['student_ids'] : [$validated['student_id']];
        
        Log::info("Store started for teacher: $teacherId, data: " . json_encode($validated));

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        Log::info("Date range: {$startDate->toDateString()} to {$endDate->toDateString()}");

        $createdSessionsIds = [];
        $skippedSessions = [];
        
        $groupId = $isGroupLesson ? 'group_' . time() . '_' . uniqid() : null;

        DB::beginTransaction();
        
        foreach ($studentIds as $studentId) {
            $privateLesson = PrivateLesson::create([
                'group_id' => $groupId,
                'name' => $validated['lesson_name'],
                'price' => $validated['fee'],
                'teacher_id' => $teacherId,
                'student_id' => $studentId,
                'is_active' => true,
                'created_by' => $teacherId,
                'has_recurring_sessions' => true
            ]);

            for ($i = 0; $i < count($validated['days']); $i++) {
                $dayOfWeek = (int)$validated['days'][$i];
                $startTime = $validated['start_times'][$i];
                $endTime = $validated['end_times'][$i];

                if (empty($endTime)) {
                    $startTimeParts = explode(':', $startTime);
                    $startHour = (int)$startTimeParts[0];
                    $startMinute = (int)$startTimeParts[1];
                    
                    $endMinute = $startMinute + 45;
                    $endHour = $startHour;
                    
                    if ($endMinute >= 60) {
                        $endHour += 1;
                        $endMinute -= 60;
                    }
                    
                    if ($endHour >= 24) {
                        $endHour = 23;
                        $endMinute = 59;
                    }
                    
                    $endTime = sprintf("%02d:%02d", $endHour, $endMinute);
                }

                $firstSessionDate = clone $startDate;
                $currentDayOfWeek = (int)$firstSessionDate->format('w');
                if ($currentDayOfWeek != $dayOfWeek) {
                    $daysUntilTargetDay = ($dayOfWeek - $currentDayOfWeek + 7) % 7;
                    $firstSessionDate->addDays($daysUntilTargetDay);
                }

                Log::info("Day $dayOfWeek, First session date: {$firstSessionDate->toDateString()}");

                if ($firstSessionDate > $endDate) {
                    Log::info("Skipped day $dayOfWeek: First session date exceeds end date.");
                    if ($studentId === $studentIds[0]) {
                        $skippedSessions[] = "Gün: $dayOfWeek, Tarih: {$firstSessionDate->toDateString()} (Bitiş tarihinden sonra)";
                    }
                    continue;
                }

                $sessionDate = clone $firstSessionDate;

                while ($sessionDate <= $endDate) {
                    $conflictExists = $this->checkLessonConflict(
                        $teacherId,
                        $dayOfWeek,
                        $startTime,
                        $endTime,
                        $sessionDate->format('Y-m-d'),
                        null
                    );

                    if ($conflictExists) {
                        if ($studentId === $studentIds[0]) {
                            $skippedSessions[] = "{$sessionDate->format('d.m.Y')} - Çakışma var";
                        }
                        Log::info("Conflict detected for {$sessionDate->toDateString()} at $startTime-$endTime");
                        $sessionDate->addWeek();
                        continue;
                    }

                    $session = PrivateLessonSession::create([
                        'private_lesson_id' => $privateLesson->id,
                        'group_id' => $groupId,
                        'teacher_id' => $teacherId,
                        'student_id' => $studentId,
                        'day_of_week' => $dayOfWeek,
                        'start_date' => $sessionDate->format('Y-m-d'),
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'location' => $validated['location'] ?? null,
                        'fee' => $validated['fee'],
                        'payment_status' => 'pending',
                        'paid_amount' => 0,
                        'status' => $validated['status'],
                        'is_recurring' => true,
                        'notes' => $validated['notes'] ?? null
                    ]);

                    $createdSessionsIds[] = $session->id;
                    Log::info("Session created: ID {$session->id}, Date: {$sessionDate->toDateString()}, Time: $startTime-$endTime");

                    $sessionDate->addWeek();
                }
            }
        }
        
        if ($isGroupLesson && $groupId) {
            foreach ($studentIds as $studentId) {
                DB::table('lesson_group_students')->insert([
                    'group_id' => $groupId,
                    'student_id' => $studentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::commit();

        $sessionCount = count($createdSessionsIds);
        if ($sessionCount == 0) {
            $errorMessage = 'Belirtilen tarih aralığında uygun ders saati bulunamadı.';
            if (!empty($skippedSessions)) {
                $errorMessage .= ' Atlanan seanslar: ' . implode(', ', $skippedSessions);
            }
            return redirect()->route('ogretmen.private-lessons.create')
                ->with('error', $errorMessage)
                ->withInput();
        }

        $studentCount = count($studentIds);
        $sessionsPerStudent = $sessionCount / $studentCount;
        
        if ($isGroupLesson) {
            $successMessage = "✅ Grup dersi başarıyla oluşturuldu! {$studentCount} öğrenci için {$sessionsPerStudent} seans planlandı. (Toplam {$sessionCount} kayıt)";
        } else {
            $successMessage = "Özel ders planı başarıyla oluşturuldu. Toplam {$sessionCount} seans planlandı.";
        }
        
        if (!empty($skippedSessions)) {
            $successMessage .= ' Atlanan seanslar: ' . implode(', ', $skippedSessions);
        }

        return redirect()->route('ogretmen.private-lessons.index')
            ->with('success', $successMessage);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Store failed: " . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Hata: ' . $e->getMessage())
            ->withInput();
    }
}
/**
     * Ders çakışması kontrolü yapar
     */
    private function checkLessonConflict($teacherId, $dayOfWeek, $startTime, $endTime, $date, $excludeSessionId = null)
    {
        return false;
    }

    /**
     * Ders çakışması kontrolü için API
     */
    public function checkLessonConflictApi(Request $request)
    {
        $teacherId = Auth::id();
        $dayOfWeek = $request->input('day');
        $startTime = $request->input('time');
        $date = $request->input('date');
        $excludeSessionId = $request->input('exclude');
        
        // Bitiş saatini hesapla (başlangıçtan 1 saat sonra)
        $startTimeParts = explode(':', $startTime);
        $endHour = (int)$startTimeParts[0] + 1;
        $endTime = ($endHour >= 24 ? 23 : $endHour) . ':' . $startTimeParts[1];
        
        $conflict = $this->checkLessonConflict(
            $teacherId,
            $dayOfWeek,
            $startTime,
            $endTime,
            $date,
            $excludeSessionId
        );
        
        return response()->json(['conflict' => $conflict]);
    }

    /**
     * Özel ders detaylarını gösterir
     */
    public function show($id)
    {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
        $session = PrivateLessonSession::with(['privateLesson', 'student'])
            ->where('teacher_id', $teacherId)
            ->findOrFail($id);
        
        return view('teacher.private-lessons.show', compact('session'));
    }

/**
 * Özel ders düzenleme formunu gösterir
 */
public function edit($id)
{
    $teacherId = Auth::id();
    
    // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
    $session = PrivateLessonSession::with(['privateLesson', 'student'])
        ->where('teacher_id', $teacherId)
        ->findOrFail($id);
    
    // Öğrenci listesini çekelim
    $students = User::role('ogrenci')->get();
    
    // Ders geçmiş tarihli olup olmadığını kontrol et (bilgi amaçlı)
    $isPastSession = strtotime($session->start_date . ' ' . $session->start_time) < time();
    
    return view('teacher.private-lessons.edit', compact('session', 'students', 'isPastSession'));
}

/**
 * Özel dersi günceller
 */
public function update(Request $request, $id)
{
    try {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
        $session = PrivateLessonSession::where('teacher_id', $teacherId)->findOrFail($id);
        
        // Ders geçmiş tarihli mi kontrol et (bilgi amaçlı)
        $isPastSession = strtotime($session->start_date . ' ' . $session->start_time) < time();
        
        // Tüm validasyon kurallarını her durum için uygula
        $rules = [
            'student_id' => 'required|exists:users,id',
            'fee' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:approved,cancelled',
            'notes' => 'nullable|string',
            'update_all_sessions' => 'sometimes|boolean',
            'conflict_confirmed' => 'sometimes|boolean',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time'
        ];
        
        $validated = $request->validate($rules);
        
        // Bitiş saati belirtilmemişse, başlangıç saatine 45 dakika ekle
        if (!isset($validated['end_time']) || empty($validated['end_time'])) {
            $startTime = Carbon::parse($validated['start_time']);
            $endTime = (clone $startTime)->addMinutes(45);
            $validated['end_time'] = $endTime->format('H:i');
        }
        
        // Tüm alanları güncelleyebilir (geçmiş ders olsa bile)
        $sessionUpdateData = [
            'student_id' => $validated['student_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_date' => $validated['start_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'fee' => $validated['fee'],
            'payment_status' => $validated['payment_status'],
            'paid_amount' => $validated['payment_status'] == 'paid' ? $validated['fee'] : 0,
            'location' => $validated['location'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null
        ];
        
        // Çakışma kontrolü - sadece conflict_confirmed yoksa yap
        if (!$request->has('conflict_confirmed')) {
            // Ders çakışması kontrolü
            $conflictExists = $this->checkLessonConflict(
                $teacherId,
                $validated['day_of_week'],
                $validated['start_time'],
                $validated['end_time'],
                $validated['start_date'],
                $id
            );
            
            // Çakışma varsa, formu hata mesajı ile geri döndür
            if ($conflictExists) {
                return redirect()->back()
                    ->with('warning', 'Seçilen gün ve saatte başka bir dersiniz bulunmaktadır. Yine de devam etmek istiyorsanız "Güncelle" butonuna tekrar basın.')
                    ->with('conflict_detected', true)
                    ->withInput();
            }
        }
        
        // PrivateLesson bilgilerini güncelle (fiyatı)
        $privateLesson = PrivateLesson::findOrFail($session->private_lesson_id);
        $privateLesson->update([
            'price' => $validated['fee']
        ]);
        
        // Eğer tüm gelecek seansları güncelle seçeneği aktifse
        if (isset($validated['update_all_sessions']) && $validated['update_all_sessions'] == 1) {
            // Sadece gelecek seansları güncelle
            $today = Carbon::now()->startOfDay();
            
            // Aynı derse ait gelecekteki tüm seansları bul
            $futureSessions = PrivateLessonSession::where('private_lesson_id', $session->private_lesson_id)
                ->where('teacher_id', $teacherId)
                ->where('start_date', '>=', $today->format('Y-m-d'))
                ->get();
            
            foreach ($futureSessions as $futureSession) {
                // Bu session ise zaten güncellenecek
                if ($futureSession->id == $session->id) {
                    $futureSession->update($sessionUpdateData);
                    continue;
                }
                
                // Gelecek seansları güncelle
                $futureUpdateData = [
                    'student_id' => $validated['student_id'],
                    'fee' => $validated['fee'],
                    'location' => $validated['location'] ?? null,
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? null
                ];
                
                // Tarih/saat değişikliği yapıldıysa, tüm gelecek derslerin gününü güncelle
                // Eski ve yeni gün arasındaki farkı hesapla
                $dayDiff = (int)$validated['day_of_week'] - (int)$session->day_of_week;
                
                // Eğer gün değişmişse, bu dersin tarihini de güncelle
                if ($dayDiff != 0) {
                    $newDate = Carbon::parse($futureSession->start_date)->addDays($dayDiff);
                    $futureUpdateData['day_of_week'] = $validated['day_of_week'];
                    $futureUpdateData['start_date'] = $newDate->format('Y-m-d');
                }
                
                // Saat değişikliği
                if ($validated['start_time'] != $session->start_time) {
                    $futureUpdateData['start_time'] = $validated['start_time'];
                    $futureUpdateData['end_time'] = $validated['end_time']; // 45 dakika sonrasını ayarlar
                }
                
                // Bu session eklendikten sonra her bir gelecek seans için çakışma kontrolü yap
                if (!$request->has('conflict_confirmed')) {
                    $futureConflictExists = $this->checkLessonConflict(
                        $teacherId,
                        $futureUpdateData['day_of_week'] ?? $futureSession->day_of_week,
                        $futureUpdateData['start_time'] ?? $futureSession->start_time,
                        $futureUpdateData['end_time'] ?? $futureSession->end_time,
                        $futureUpdateData['start_date'] ?? $futureSession->start_date,
                        $futureSession->id
                    );
                    
                    if ($futureConflictExists) {
                        // Çakışma varsa bir not ekle ve bu seansı atla
                        $notWarning = "\n[SİSTEM NOTU: " . date('d.m.Y', strtotime($futureSession->start_date)) . 
                                     " tarihli seans için çakışma tespit edildi ve güncellenmedi]";
                        
                        $futureSession->update([
                            'notes' => ($futureSession->notes ? $futureSession->notes . $notWarning : $notWarning)
                        ]);
                        
                        continue; // Bu seansı atla ve bir sonrakine geç
                    }
                }
                
                // Çakışma yoksa veya kullanıcı çakışmayı onayladıysa güncelle
                $futureSession->update($futureUpdateData);
            }
            
            return redirect()->route('ogretmen.private-lessons.index')
                ->with('success', 'Bütün gelecek seanslar başarıyla güncellendi. Çakışan seanslar için notlar eklenmiştir.');
        }
        
        // Sadece seçilen seansı güncelle
        $session->update($sessionUpdateData);
        
        return redirect()->route('ogretmen.private-lessons.index')
            ->with('success', 'Özel ders seansı başarıyla güncellendi.');
        
    } catch (\Exception $e) {
        // Hatayı göster ve form verilerini geri doldur
        return redirect()->back()
            ->with('error', 'Hata: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Özel dersi siler
     */
    public function destroy($id)
    {
        try {
            $teacherId = Auth::id();
            
            // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
            $session = PrivateLessonSession::where('teacher_id', $teacherId)->findOrFail($id);
            
            // Geçmiş tarihli ders ise silmeye izin verme
            if (strtotime($session->start_date . ' ' . $session->start_time) < time()) {
                return redirect()->back()
                    ->with('error', 'Geçmiş tarihli dersler silinemez. Bunun yerine durumunu "İptal Edildi" olarak işaretleyebilirsiniz.');
            }
            
            // Bu tekil bir seans mı yoksa bir serinin parçası mı kontrol et
            $isPartOfSeries = PrivateLessonSession::where('private_lesson_id', $session->private_lesson_id)
                                ->where('id', '!=', $session->id)
                                ->exists();
            
            // Eğer bu bir serinin son seansı ise, PrivateLesson kaydını da sil
            if (!$isPartOfSeries) {
                PrivateLesson::where('id', $session->private_lesson_id)->delete();
            }
            
            // Dersi sil
            $session->delete();
            
            // Başarılı bir şekilde sildiğimizde mesaj ver ve listeye yönlendir
            return redirect()->route('ogretmen.private-lessons.index')
                ->with('success', 'Özel ders seansı başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Birden fazla seansı sil (aynı ders serisine ait tüm gelecek seanslar)
     */
    public function destroyMultiple(Request $request, $id)
    {
        try {
            $teacherId = Auth::id();
            
            // Referans seansı getir
            $referenceSession = PrivateLessonSession::where('teacher_id', $teacherId)->findOrFail($id);
            
            // Silinecek seansların kapsamını doğrula
            $deleteScope = $request->input('delete_scope', 'this_only');
            
            if ($deleteScope == 'this_only') {
                // Sadece bu seansı sil
                $referenceSession->delete();
                $message = 'Seçilen seans başarıyla silindi.';
            } 
            else if ($deleteScope == 'all_future') {
                // Bu ve gelecekteki tüm seansları sil
                $today = Carbon::now()->startOfDay();
                
                // Bu dersin gelecekteki tüm seanslarını bul
                $futureSessions = PrivateLessonSession::where('private_lesson_id', $referenceSession->private_lesson_id)
                    ->where('teacher_id', $teacherId)
                    ->where('start_date', '>=', $today->format('Y-m-d'))
                    ->get();
                
                foreach ($futureSessions as $session) {
                    $session->delete();
                }
                
                // Kalan seans var mı kontrol et
                $remainingSessions = PrivateLessonSession::where('private_lesson_id', $referenceSession->private_lesson_id)
                    ->exists();
                
                // Eğer tüm seanslar silindiyse, PrivateLesson kaydını da sil
                if (!$remainingSessions) {
                    PrivateLesson::where('id', $referenceSession->private_lesson_id)->delete();
                }
                
                $message = 'Bu ve gelecekteki tüm seanslar başarıyla silindi.';
            } 
            else if ($deleteScope == 'all') {
                // Bu dersin tüm seanslarını sil
                $allSessions = PrivateLessonSession::where('private_lesson_id', $referenceSession->private_lesson_id)
                    ->where('teacher_id', $teacherId)
                    ->get();
                
                foreach ($allSessions as $session) {
                    $session->delete();
                }
                
                // PrivateLesson kaydını da sil
                PrivateLesson::where('id', $referenceSession->private_lesson_id)->delete();
                
                $message = 'Bu derse ait tüm seanslar başarıyla silindi.';
            }
            
            return redirect()->route('ogretmen.private-lessons.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Özel ders talebini onaylar
     */
    public function approve($id)
    {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait ve beklemede olanları
        $session = PrivateLessonSession::where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->findOrFail($id);
        
        // Dersin durumunu aktif olarak güncelle
        $session->update(['status' => 'active']);
        
        // Başarılı bir şekilde onayladığımızda mesaj ver ve listeye yönlendir
        return redirect()->route('ogretmen.private-lessons.pendingRequests')
            ->with('success', 'Özel ders talebi başarıyla onaylandı.');
    }

    /**
     * Özel ders talebini reddeder
     */
    public function reject($id)
    {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait ve beklemede olanları
        $session = PrivateLessonSession::where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->findOrFail($id);
        
        // Durumu reddedildi olarak işaretle
        $session->update(['status' => 'cancelled']);
        
        // Başarılı bir şekilde reddettiğimizde mesaj ver ve listeye yönlendir
        return redirect()->route('ogretmen.private-lessons.pendingRequests')
            ->with('success', 'Özel ders talebi reddedildi.');
    }
    
    /**
     * Ödeme durumunu günceller
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
        $session = PrivateLessonSession::where('teacher_id', $teacherId)->findOrFail($id);
        
        // Form verilerini doğrulama
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid',
            'payment_notes' => 'nullable|string'
        ]);
        
        // Ödeme durumunu güncelle
        $session->update([
            'payment_status' => $validated['payment_status'],
            'paid_amount' => $validated['payment_status'] == 'paid' ? $session->fee : 0,
            'payment_date' => $validated['payment_status'] == 'paid' ? now() : null,
            'notes' => $session->notes . "\n\nÖdeme Durumu Güncelleme (" . now()->format('d.m.Y H:i') . "): " . 
                      ($validated['payment_notes'] ?? 'Ödeme durumu güncellendi: ' . $validated['payment_status'])
        ]);
        
        // Başarılı bir şekilde güncellediğimizde mesaj ver ve detay sayfasına yönlendir
        return redirect()->route('ogretmen.private-lessons.show', $id)
            ->with('success', 'Ödeme durumu başarıyla güncellendi.');
    }

/**
 * Dersi iptal et
 */
public function cancelLesson($id)
{
    $teacherId = Auth::id();
    
    // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
    $session = PrivateLessonSession::where('teacher_id', $teacherId)->findOrFail($id);
    
    // Dersin durumunu iptal edildi olarak güncelle
    $session->update([
        'status' => 'cancelled',
        'notes' => $session->notes . "\n\nDers İptal (" . now()->format('d.m.Y H:i') . "): Öğretmen tarafından iptal edildi."
    ]);
    
    // Başarılı bir şekilde iptal ettiğimizde mesaj ver ve listeye yönlendir
    return redirect()->route('ogretmen.private-lessons.index')
        ->with('success', 'Ders başarıyla iptal edildi.');
}
}