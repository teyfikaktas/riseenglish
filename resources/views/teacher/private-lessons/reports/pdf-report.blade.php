<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rise English - Ders Raporu</title>
    <style>
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
    
/* Motto stili */
.motto-container {
    position: absolute;
    top: 50px;
    right: 45px;
    text-align: right;
    font-weight: bold;
    font-size: 18px;
    letter-spacing: 0.5px;
}

.motto-container .red {
    color: #e63946;
}

.motto-container .blue {
    color: #1a2e5a;
}
/* Genel sayfa stili */
body {
    font-family: DejaVu Sans, sans-serif;
    margin: 0;
    padding: 0;
    color: #333;
    background-image: url('{{ public_path('images/bgreport.jpg') }}');
    background-size: 100% 100%;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: left top;
    position: relative;
}

/* Kapak sayfası - biraz daha içeri ittik */
.cover-page {
    height: 100vh;
    display: flex;
    flex-direction: column;
    page-break-after: always;
    padding: 60px; /* 30px'ten 60px'e çıkardık - daha fazla içeri iterek */
}

/* Yeni header tasarımı */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #1a2e5a;
    padding-bottom: 15px;
    margin-bottom: 30px;
}

.logo-container {
    display: flex;
    align-items: center;
}

.logo {
    height: 50px;
    margin-right: 15px;
}

.company-info {
    display: flex;
    flex-direction: column;
}

.company-name {
    color: #1a2e5a;
    font-weight: bold;
    font-size: 16px;
    margin: 0;
}

.company-details {
    font-size: 10px;
    color: #666;
    margin: 3px 0 0 0;
}

/* Belge başlığı düzenlemesi */
.document-info {
    text-align: right;
}

.document-title {
    margin: 0;
    color: #1a2e5a;
    font-size: 20px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.document-subtitle {
    margin: 5px 0 0 0;
    color: #e63946;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.document-number {
    color: #666;
    font-size: 11px;
    margin-top: 5px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

/* Geliştirilmiş kapak detay tablosu */
.cover-details {
    margin: 40px auto;
    border: none;
    border-radius: 12px;
    padding: 0;
    background-color: rgba(249, 250, 251, 0.95);
    width: 75%;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.cover-details-table {
    width: 100%;
    border-collapse: collapse;
}

.cover-details-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #e5e7eb;
}

.cover-details-table tr:last-child td {
    border-bottom: none;
}

.cover-details-table tr {
    transition: background-color 0.3s;
}

.cover-details-table tr:hover {
    background-color: rgba(26, 46, 90, 0.05);
}

.cover-details-table td:first-child {
    font-weight: 600;
    width: 30%;
    color: #1a2e5a;
    display: flex;
    align-items: center;
    position: relative;
    background-color: rgba(26, 46, 90, 0.05);
}

.cover-details-table td:nth-child(2) {
    font-weight: 500;
    color: #333;
    font-size: 16px;
}

.cover-details-icon {
    margin-right: 12px;
    color: #e63946;
    width: 24px;
    height: 24px;
    text-align: center;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: rgba(230, 57, 70, 0.1);
    padding: 12px;
}

/* Renkli vurgu çizgisi */
.cover-details-table tr:nth-child(1) td:first-child {
    border-left: 4px solid #4361ee;
}

.cover-details-table tr:nth-child(2) td:first-child {
    border-left: 4px solid #3a0ca3;
}

.cover-details-table tr:nth-child(3) td:first-child {
    border-left: 4px solid #7209b7;
}

.cover-details-table tr:nth-child(4) td:first-child {
    border-left: 4px solid #f72585;
}

.cover-details-table tr:nth-child(5) td:first-child {
    border-left: 4px solid #e63946;
}

/* Ana rapor sayfası - genişliği azalttık */
.report-container {
    max-width: 650px; /* 750px'ten 670px'e düşürdük - içeriği ortaya doğru itmek için */
    margin:30px 50px; /* Üstten ve alttan boşluğu artırdık */
    padding: 10px; /* İç boşluğu artırdık */
    position: center;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.details {
    margin-bottom: 25px;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 15px;
    background-color: #f9fafb;
}

.details-table {
    width: 100%;
    border-collapse: collapse;
}

.details-table td {
    padding: 8px;
    border-bottom: 1px solid #eee;
}

.details-table td:first-child {
    font-weight: bold;
    width: 30%;
    color: #1a2e5a;
}

.section {
    margin-bottom: 25px;
}

/* Küçültülmüş ve düzenlenmiş bölüm başlıkları */
.section-title {
    border-bottom: 2px solid #1a2e5a;
    padding-bottom: 6px;
    margin-bottom: 15px;
    font-size: 15px;
    font-weight: bold;
    display: flex;
    align-items: center;
    color: #1a2e5a;
}

.section-title.pros {
    color: #e63946;
    border-bottom-color: #e63946;
}

.section-title.cons {
    color: #1a2e5a;
    border-bottom-color: #1a2e5a;
}

.section-title i {
    margin-right: 8px;
}

/* Çözülen sorular - daha kompakt */
.questions-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-direction: row;
    flex-wrap: nowrap;
    gap: 8px;
}

.stat-box {
    flex: 1;
    min-width: 0;
    padding: 12px;
    text-align: center;
    border-radius: 5px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    background-color: #fff;
    border: 1px solid #eee;
    position: relative;
}

.stat-icon {
    position: absolute;
    top: 8px;
    right: 8px;
    opacity: 0.2;
    font-size: 20px;
}

.total-box { border-top: 3px solid #0284c7; }
.correct-box { border-top: 3px solid #16a34a; }
.wrong-box { border-top: 3px solid #dc2626; }
.empty-box { border-top: 3px solid #6b7280; }

.total-box .stat-icon { color: #0284c7; }
.correct-box .stat-icon { color: #16a34a; }
.wrong-box .stat-icon { color: #dc2626; }
.empty-box .stat-icon { color: #6b7280; }

.stat-number {
    font-size: 24px;
    font-weight: bold;
    margin: 8px 0;
}

.total-box .stat-number { color: #0284c7; }
.correct-box .stat-number { color: #16a34a; }
.wrong-box .stat-number { color: #dc2626; }
.empty-box .stat-number { color: #6b7280; }

.stat-label {
    font-size: 12px;
    color: #6b7280;
}

.subject-row {
    margin-bottom: 12px;
    border: 1px solid #eee;
    border-radius: 5px;
    padding: 10px;
    background: white;
}

.subject-name {
    font-weight: bold;
    margin-bottom: 8px;
    color: #1a2e5a;
    padding-bottom: 6px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    font-size: 13px;
}

.subject-name i {
    margin-right: 6px;
    opacity: 0.7;
}

.subject-stats {
    display: flex;
    justify-content: space-between;
    flex-direction: row;
    gap: 8px;
}

.subject-stat {
    flex: 1;
    text-align: center;
    padding: 6px;
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.subject-stat i {
    margin-right: 4px;
}

.correct-stat { background-color: #dcfce7; color: #166534; }
.wrong-stat { background-color: #fee2e2; color: #991b1b; }
.empty-stat { background-color: #f3f4f6; color: #6b7280; }

.content-box {
    background-color: #f9fafb;
    padding: 14px;
    border-radius: 5px;
    margin-bottom: 15px;
    border: 1px solid #eee;
    font-size: 13px;
    line-height: 1.5;
}

.content-box.pros {
    border-left: 4px solid #e63946;
}

.content-box.cons {
    border-left: 4px solid #1a2e5a;
}

.chart-container {
    text-align: center;
    margin: 12px 0;
    background: white;
    padding: 12px;
    border-radius: 5px;
    border: 1px solid #eee;
}

.chart-image {
    max-width: 100%;
    height: auto;
    margin: 0 auto;
    display: block;
}

/* Footer düzenlemesi */
.footer {
    margin-top: 35px;
    padding-top: 15px;
    border-top: 1px solid #ddd;
    font-size: 11px;
    color: #666;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.footer-left {
    text-align: left;
    display: flex;
    align-items: center;
}

.footer-right {
    text-align: right;
    display: flex;
    align-items: center;
}

.footer-icon {
    margin-right: 4px;
}

.logo-footer {
    height: 35px;
    margin-right: 8px;
}

/* İmza alanı - daha şık tasarım */
.stamps-container {
    margin-top: 35px;
    padding-top: 20px;
    border-top: 1px dashed #ccc;
}

.stamps {
    display: table;
    width: 100%;
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 15px;
}

.stamp-row {
    display: table-row;
}

.stamp {
    display: table-cell;
    text-align: center;
    padding: 15px;
    border: 1px solid #eee;
    border-radius: 5px;
    background-color: #f9fafb;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    width: 50%;
}

.stamp-title {
    font-weight: bold;
    margin-bottom: 15px;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.student-stamp .stamp-title {
    color: #e63946;
}

.teacher-stamp .stamp-title {
    color: #1a2e5a;
}

.stamp-line {
    height: 1px;
    margin: 0 auto 35px auto;
    width: 70%;
}

.student-stamp .stamp-line {
    background-color: #e63946;
}

.teacher-stamp .stamp-line {
    background-color: #1a2e5a;
}

.stamp-name {
    font-weight: bold;
    margin-bottom: 4px;
    color: #333;
}

.stamp-role {
    font-style: italic;
    color: #666;
    font-size: 11px;
}

.text-center {
    text-align: center;
}

.no-data-msg {
    text-align: center;
    padding: 18px;
    background-color: #f3f4f6;
    border-radius: 5px;
    color: #6b7280;
    font-style: italic;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}

.no-data-msg i {
    margin-right: 8px;
}

/* Sayfa numarası */
@page {
    size: A4;
    margin: 0;
    counter-increment: page;
}

.page-number:after {
    content: "Sayfa " counter(page);
}

@media print {
    body {
        margin: 0;
        padding: 0;
    }
    
    .report-container {
        width: 100%;
        max-width: 100%;
        padding: 40px; /* Yazdırma için de iç boşluğu artırdık */
        margin: 40px 0; /* Yazdırma için kenarlarda boşluk bıraktık */
    }
    
    .page-break {
        page-break-after: always;
    }
}
    </style>
</head>
<body>
    <!-- KAPAK SAYFASI -->
    <div class="cover-page">
        <div class="motto-container">
            <span class="red">Struggle</span> <span class="blue">Now</span><br>
            <span class="red">Rise</span> <span class="blue">English</span>
        </div>
        <div class="header">
            <div class="logo-container">
                <img src="{{ public_path('images/logo.png') }}" alt="Rise English Logo" class="logo">
                <div class="company-info">
                    <p class="company-name">RISE ENGLISH</p>
                    <p class="company-details">Profesyonel Dil Eğitimi</p>
                    <p class="company-details">www.risenglish.com</p>
                </div>
            </div>
            <div class="document-info">
                <h1 class="document-title"><i class="fas fa-file-alt" style="margin-right: 8px;"></i>ÖZEL DERS RAPORU</h1>
                <p class="document-subtitle"><i class="fas fa-user-graduate" style="margin-right: 5px;"></i>Öğrenci Değerlendirme Belgesi</p>
                <p class="document-number"><i class="fas fa-hashtag" style="margin-right: 5px;"></i>Rapor No: RE-{{ $report->id }}-{{ date('Ymd') }}</p>
            </div>
        </div>
        
<!-- Simgesiz Geliştirilmiş Kapak Detayları -->
<div class="cover-details">
    <table class="cover-details-table">
        <tr>
            <td>Ders</td>
            <td>{{ $session->privateLesson->name }}</td>
        </tr>
        <tr>
            <td>Tarih</td>
            <td>{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</td>
        </tr>
        <tr>
            <td>Saat</td>
            <td>{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</td>
        </tr>
        <tr>
            <td>Ögrenci</td>
            <td>{{ $session->student->name }}</td>
        </tr>
        <tr>
            <td>Ögretmen</td>
            <td>{{ $session->teacher->name }}</td>
        </tr>
    </table>
</div>
    </div>

    <!-- ANA RAPOR SAYFASI -->
    <div class="report-container">
        <div class="questions section">
            <div class="section-title"><i class="fas fa-tasks"></i>Çözülen Sorular</div>
            
            @if($report->questions_solved > 0)
            <div class="questions-stats">
                <div class="stat-box total-box">
                    <i class="fas fa-list-ol stat-icon"></i>
                    <div class="stat-label">Toplam Soru</div>
                    <div class="stat-number">{{ $report->questions_solved }}</div>
                </div>
                <div class="stat-box correct-box">
                    <i class="fas fa-check-circle stat-icon"></i>
                    <div class="stat-label">Doğru</div>
                    <div class="stat-number">{{ $report->questions_correct }}</div>
                </div>
                <div class="stat-box wrong-box">
                    <i class="fas fa-times-circle stat-icon"></i>
                    <div class="stat-label">Yanlış</div>
                    <div class="stat-number">{{ $report->questions_wrong }}</div>
                </div>
                <div class="stat-box empty-box">
                    <i class="fas fa-minus-circle stat-icon"></i>
                    <div class="stat-label">Boş</div>
                    <div class="stat-number">{{ $report->questions_unanswered }}</div>
                </div>
            </div>
            
            <!-- Ana sorular için grafik -->
            <div class="chart-container">
                <img class="chart-image" src="{{ $mainChartImage }}" alt="Çözülen Sorular Grafiği">
            </div>
            @else
            <div class="no-data-msg">
                <i class="fas fa-info-circle"></i><p>Bu derste soru çözümü yapılmamıştır.</p>
            </div>
            @endif
        </div>
        
        @if($report->examResults && $report->examResults->count() > 0)
        <div class="exam-results section">
            <div class="section-title">
                @if($report->content_type == 'deneme')
                    <i class="fas fa-file-alt"></i>Çözülen Denemeler
                @else
                    <i class="fas fa-pencil-alt"></i>Soru Çözüm
                @endif
            </div>
            
            <!-- Denemeler için çubuk grafik -->
            <div class="chart-container">
                <img class="chart-image" src="{{ $subjectsChartImage }}" alt="Sonuçlar Grafiği">
            </div>
            
            <!-- Denemeler detay tablosu -->
            @foreach($report->examResults as $examResult)
                <div class="subject-row">
                    <div class="subject-name"><i class="fas fa-book"></i>{{ $examResult->subject_name }}</div>
                    <div class="subject-stats">
                        <div class="subject-stat correct-stat">
                            <i class="fas fa-check"></i><strong>{{ $examResult->questions_correct }}</strong> Doğru
                        </div>
                        <div class="subject-stat wrong-stat">
                            <i class="fas fa-times"></i><strong>{{ $examResult->questions_wrong }}</strong> Yanlış
                        </div>
                        <div class="subject-stat empty-stat">
                            <i class="fas fa-minus"></i><strong>{{ $examResult->questions_unanswered }}</strong> Boş
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
        
        @if($report->pros)
        <div class="section">
            <div class="section-title pros"><i class="fas fa-plus-circle"></i>Artıları</div>
            <div class="content-box pros">
                {!! nl2br(e($report->pros)) !!}
            </div>
        </div>
        @endif
        
        @if($report->cons)
        <div class="section">
            <div class="section-title cons"><i class="fas fa-minus-circle"></i>Eksileri</div>
            <div class="content-box cons">
                {!! nl2br(e($report->cons)) !!}
            </div>
        </div>
        @endif
        
        @if($report->participation)
        <div class="section">
            <div class="section-title"><i class="fas fa-hand-paper"></i>Derse Katılım</div>
            <div class="content-box">
                {!! nl2br(e($report->participation)) !!}
            </div>
        </div>
        @endif
        
        @if($report->teacher_notes)
        <div class="section">
            <div class="section-title"><i class="fas fa-sticky-note"></i>Öğretmen Notları</div>
            <div class="content-box">
                {!! nl2br(e($report->teacher_notes)) !!}
            </div>
        </div>
        @endif
        
        <!-- İmza alanı - Tablo yapısı ile düzeltilmiş yan yana tasarım -->
        <div class="stamps-container">
            <div class="stamps">
                <div class="stamp-row">
                    <div class="stamp student-stamp">
                        <div class="stamp-title"><i class="fas fa-user-graduate" style="margin-right: 5px;"></i>ÖĞRENCİ</div>
                        <div class="stamp-line"></div>
                        <div class="stamp-name">{{ $session->student->name }}</div>
                        <div class="stamp-role">Öğrenci</div>
                    </div>
                    
                    <div class="stamp teacher-stamp">
                        <div class="stamp-title"><i class="fas fa-chalkboard-teacher" style="margin-right: 5px;"></i>ÖĞRETMEN</div>
                        <div class="stamp-line"></div>
                        <div class="stamp-name">{{ $session->teacher->name }}</div>
                        <div class="stamp-role">Eğitmen</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-left">
                <img src="{{ public_path('images/logo.png') }}" alt="Rise English Logo" class="logo-footer">
                <span>{{ date('Y') }} Rise English - Tüm Hakları Saklıdır</span>
            </div>
            <div class="footer-right">
                <i class="fas fa-file-alt footer-icon"></i><span class="page-number"></span> | <i class="fas fa-calendar-alt footer-icon"></i> Oluşturma: {{ now()->format('d.m.Y H:i') }}
            </div>
            <!-- rs.jpg resmini sayfanın en altına ekleyin -->
            <div style="text-align: center; margin-top: 20px;">
                <img src="{{ public_path('images/rs.jpg') }}" alt="RS Logo" style="max-width: 600   px; height: auto;">
            </div>
        </div>
</body>
</html>