<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rise English - Ders Raporu</title>
    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

        /* Arka plan deseni ve sayfa stilini iyileştiren CSS */
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #fff;
            position: relative;
            background-image: linear-gradient(rgba(26, 46, 90, 0.02) 1px, transparent 1px), 
                              linear-gradient(90deg, rgba(26, 46, 90, 0.02) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Köşe süslemeleri - Değiştirildi: Bir kırmızı, bir lacivert */
        .page-decoration {
            position: fixed;
            width: 100px;
            height: 100px;
            z-index: -1;
        }

        .top-left-decoration {
            top: 0;
            left: 0;
            border-top: 10px solid #1a2e5a; /* Lacivert */
            border-left: 10px solid #1a2e5a;
            border-top-left-radius: 10px;
        }

        .top-right-decoration {
            top: 0;
            right: 0;
            border-top: 10px solid #e63946; /* Kırmızı */
            border-right: 10px solid #e63946;
            border-top-right-radius: 10px;
        }

        .bottom-left-decoration {
            bottom: 0;
            left: 0;
            border-bottom: 10px solid #e63946; /* Kırmızı */
            border-left: 10px solid #e63946;
            border-bottom-left-radius: 10px;
        }

        .bottom-right-decoration {
            bottom: 0;
            right: 0;
            border-bottom: 10px solid #1a2e5a; /* Lacivert */
            border-right: 10px solid #1a2e5a;
            border-bottom-right-radius: 10px;
        }

        /* Sayfa kenarlarında dekoratif çizgi */
        .page-border {
            position: fixed;
            z-index: -1;
            border: 1px solid rgba(26, 46, 90, 0.1);
            top: 15mm;
            left: 15mm;
            right: 15mm;
            bottom: 15mm;
            pointer-events: none;
        }

        /* İkinci çerçeve - biraz daha içeride */
        .inner-border {
            position: fixed;
            z-index: -1;
            border: 1px solid rgba(26, 46, 90, 0.07);
            top: 20mm;
            left: 20mm;
            right: 20mm;
            bottom: 20mm;
            pointer-events: none;
        }

        /* Logo filigranı geliştirme - opaklık artırıldı */
        .logo-watermark {
            content: "";
            position: fixed;
            top: 50%;
            left: 50%;
            width: 350px;
            height: 350px;
            transform: translate(-50%, -50%);
            background-image: url('{{ public_path('images/logo.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            z-index: -1;
            pointer-events: none;
            filter: grayscale(100%);
        }

        /* Kapak sayfası */
        .cover-page {
            height: 100vh;
            display: flex;
            flex-direction: column;
            page-break-after: always;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #1a2e5a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo-section {
            display: flex;
            align-items: center;
        }

        .logo {
            max-width: 120px;
            height: auto;
        }

        .company-info {
            margin-left: 15px;
            border-left: 1px solid #ccc;
            padding-left: 15px;
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
            margin: 5px 0 0 0;
        }

        .document-title {
            margin: 0;
            color: #1a2e5a;
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .document-subtitle {
            margin: 5px 0 0 0;
            color: #e63946;
            text-align: right;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .document-number {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
            text-align: right;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        /* Kapak sayfası detay tablosu */
        .cover-details {
            margin: 50px auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #f9fafb;
            width: 80%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .cover-details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cover-details-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .cover-details-table td:first-child {
            font-weight: bold;
            width: 30%;
            color: #1a2e5a;
            display: flex;
            align-items: center;
        }

        .cover-details-icon {
            margin-right: 10px;
            color: #1a2e5a;
            width: 20px;
            text-align: center;
        }

        /* Dekoratif elemanlar */
        .decorative-element {
            margin-top: auto;
            height: 50px;
            background: linear-gradient(90deg, #1a2e5a, #3a71c9, #1a2e5a);
            opacity: 0.8;
        }

        /* Ana rapor sayfası */
        .report-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
        }

        .details {
            margin-bottom: 30px;
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
            margin-bottom: 30px;
        }

        /* Bölüm başlıkları - Artılar kırmızı, Eksiler mavi */
        .section-title {
            border-bottom: 2px solid #1a2e5a;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            color: #1a2e5a; /* Varsayılan renk */
        }

        .section-title.pros {
            color: #e63946; /* Artılar için kırmızı */
            border-bottom-color: #e63946;
        }

        .section-title.cons {
            color: #1a2e5a; /* Eksiler için lacivert */
            border-bottom-color: #1a2e5a;
        }

        .section-title i {
            margin-right: 10px;
        }

        /* Çözülen sorular */
        .questions-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-direction: row; /* Yan yana düzenleme */
            flex-wrap: nowrap; /* Kesinlikle satır kırma yok */
            gap: 10px; /* Kutular arası boşluk */
        }

        .stat-box {
            flex: 1; /* Eşit genişlikte dağılım */
            min-width: 0; /* Taşmayı önle */
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border: 1px solid #eee;
            position: relative;
        }

        .stat-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0.2;
            font-size: 24px;
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
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }

        .total-box .stat-number { color: #0284c7; }
        .correct-box .stat-number { color: #16a34a; }
        .wrong-box .stat-number { color: #dc2626; }
        .empty-box .stat-number { color: #6b7280; }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
        }

        .subject-row {
            margin-bottom: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 10px;
            background: white;
        }

        .subject-name {
            font-weight: bold;
            margin-bottom: 10px;
            color: #1a2e5a;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }

        .subject-name i {
            margin-right: 8px;
            opacity: 0.7;
        }

        .subject-stats {
            display: flex;
            justify-content: space-between;
            flex-direction: row; /* Yan yana düzenleme */
            gap: 10px; /* Elemanlar arası boşluk */
        }

        .subject-stat {
            flex: 1; /* Eşit genişlikte dağıtım */
            text-align: center;
            padding: 8px;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .subject-stat i {
            margin-right: 5px;
        }

        .correct-stat { background-color: #dcfce7; color: #166534; }
        .wrong-stat { background-color: #fee2e2; color: #991b1b; }
        .empty-stat { background-color: #f3f4f6; color: #6b7280; }

        .content-box {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #eee;
        }

        /* Artılar ve Eksiler için özel içerik kutuları */
        .content-box.pros {
            border-left: 4px solid #e63946; /* Artılar için kırmızı */
        }

        .content-box.cons {
            border-left: 4px solid #1a2e5a; /* Eksiler için lacivert */
        }

        .chart-container {
            text-align: center;
            margin: 20px 0;
            background: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #eee;
        }

        .chart-image {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
            display: block;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            display: flex;
            justify-content: space-between;
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
            margin-right: 5px;
        }

        /* İmza alanı düzenleme - Öğrenci kırmızı, Öğretmen mavi */
        .stamps-container {
            margin-top: 40px;
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
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .student-stamp .stamp-title {
            color: #e63946; /* Öğrenci için kırmızı */
        }

        .teacher-stamp .stamp-title {
            color: #1a2e5a; /* Öğretmen için lacivert */
        }

        .stamp-line {
            height: 1px;
            margin: 0 auto 40px auto;
            width: 70%;
        }

        .student-stamp .stamp-line {
            background-color: #e63946; /* Öğrenci için kırmızı */
        }

        .teacher-stamp .stamp-line {
            background-color: #1a2e5a; /* Öğretmen için lacivert */
        }

        .stamp-name {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .stamp-role {
            font-style: italic;
            color: #666;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .no-data-msg {
            text-align: center;
            padding: 20px;
            background-color: #f3f4f6;
            border-radius: 5px;
            color: #6b7280;
            font-style: italic;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .no-data-msg i {
            margin-right: 8px;
        }

        /* Köşe ikonları */
        .corner-icon {
            position: fixed;
            font-size: 24px;
            color: rgba(26, 46, 90, 0.15);
            z-index: -1;
        }

        .top-left-icon {
            top: 30px;
            left: 30px;
            color: rgba(26, 46, 90, 0.15); /* Lacivert */
        }

        .top-right-icon {
            top: 30px;
            right: 30px;
            color: rgba(230, 57, 70, 0.15); /* Kırmızı */
        }

        .bottom-left-icon {
            bottom: 30px;
            left: 30px;
            color: rgba(230, 57, 70, 0.15); /* Kırmızı */
        }

        .bottom-right-icon {
            bottom: 30px;
            right: 30px;
            color: rgba(26, 46, 90, 0.15); /* Lacivert */
        }

        /* Arka plan ikonları - Sabit konumlar */
        .bg-icon {
            position: fixed;
            z-index: -3;
            opacity: 0.03;
            font-size: 22px;
            color: #1a2e5a;
        }

        .icon1 { top: 15%; left: 10%; }
        .icon2 { top: 30%; left: 80%; }
        .icon3 { top: 45%; left: 20%; }
        .icon4 { top: 60%; left: 75%; }
        .icon5 { top: 75%; left: 15%; }
        .icon6 { top: 85%; left: 85%; }
        .icon7 { top: 25%; left: 50%; }
        .icon8 { top: 50%; left: 35%; }
        .icon9 { top: 70%; left: 60%; }
        .icon10 { top: 90%; left: 40%; }
        .icon11 { top: 40%; left: 90%; }
        .icon12 { top: 10%; left: 30%; }

        /* Sayfa numarası */
        @page {
            size: A4;
            margin: 20mm 15mm;
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
                padding: 0;
                margin: 0;
            }
            
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <!-- Dekoratif kenar ve köşe elemanları -->
    <div class="page-border"></div>
    <div class="inner-border"></div>
    <div class="page-decoration top-left-decoration"></div>
    <div class="page-decoration top-right-decoration"></div>
    <div class="page-decoration bottom-left-decoration"></div>
    <div class="page-decoration bottom-right-decoration"></div>
    
    <!-- Logo filigranı -->
    <div class="logo-watermark"></div>
    
    <!-- Köşe ikonları -->
    <div class="corner-icon top-left-icon"><i class="fas fa-graduation-cap"></i></div>
    <div class="corner-icon top-right-icon"><i class="fas fa-book"></i></div>
    <div class="corner-icon bottom-left-icon"><i class="fas fa-globe"></i></div>
    <div class="corner-icon bottom-right-icon"><i class="fas fa-language"></i></div>
    
    <!-- Arka plan ikonları - Sabit konumlar -->
    <div class="bg-icon icon1"><i class="fas fa-book"></i></div>
    <div class="bg-icon icon2"><i class="fas fa-graduation-cap"></i></div>
    <div class="bg-icon icon3"><i class="fas fa-language"></i></div>
    <div class="bg-icon icon4"><i class="fas fa-globe"></i></div>
    <div class="bg-icon icon5"><i class="fas fa-pencil-alt"></i></div>
    <div class="bg-icon icon6"><i class="fas fa-check-circle"></i></div>
    <div class="bg-icon icon7"><i class="fas fa-award"></i></div>
    <div class="bg-icon icon8"><i class="fas fa-comment"></i></div>
    <div class="bg-icon icon9"><i class="fas fa-star"></i></div>
    <div class="bg-icon icon10"><i class="fas fa-crown"></i></div>
    <div class="bg-icon icon11"><i class="fas fa-brain"></i></div>
    <div class="bg-icon icon12"><i class="fas fa-lightbulb"></i></div>

    <!-- KAPAK SAYFASI -->
    <div class="cover-page">
        <div class="header">
            <div class="logo-section">
                <img src="{{ public_path('images/logo.png') }}" alt="Rise English Logo" class="logo">
                <div class="company-info">
                    <p class="company-name">RISE ENGLISH</p>
                    <p class="company-details">Profesyonel Dil Eğitimi</p>
                    <p class="company-details">www.risenglish.com</p>
                </div>
            </div>
            <div>
                <h1 class="document-title"><i class="fas fa-file-alt" style="margin-right: 10px;"></i>ÖZEL DERS RAPORU</h1>
                <p class="document-subtitle"><i class="fas fa-user-graduate" style="margin-right: 5px;"></i>Öğrenci Değerlendirme Belgesi</p>
                <p class="document-number"><i class="fas fa-hashtag" style="margin-right: 5px;"></i>Rapor No: RE-{{ $report->id }}-{{ date('Ymd') }}</p>
            </div>
        </div>
        
        <div class="cover-details">
            <table class="cover-details-table">
                <tr>
                    <td><i class="fas fa-chalkboard-teacher cover-details-icon"></i>Ders:</td>
                    <td>{{ $session->privateLesson->name }}</td>
                </tr>
                <tr>
                    <td><i class="fas fa-calendar-alt cover-details-icon"></i>Tarih:</td>
                    <td>{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</td>
                </tr>
                <tr>
                    <td><i class="fas fa-clock cover-details-icon"></i>Saat:</td>
                    <td>{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</td>
                </tr>
                <tr>
                    <td><i class="fas fa-user cover-details-icon"></i>Öğrenci:</td>
                    <td>{{ $session->student->name }}</td>
                </tr>
                <tr>
                    <td><i class="fas fa-user-tie cover-details-icon"></i>Öğretmen:</td>
                    <td>{{ $session->teacher->name }}</td>
                </tr>
            </table>
        </div>
        
        <div class="decorative-element"></div>
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
                        <div class="stamp-title"><i class="fas fa-user-graduate"></i>ÖĞRENCİ</div>
                        <div class="stamp-line"></div>
                        <div class="stamp-name">{{ $session->student->name }}</div>
                        <div class="stamp-role">Öğrenci</div>
                    </div>
                    
                    <div class="stamp teacher-stamp">
                        <div class="stamp-title"><i class="fas fa-chalkboard-teacher"></i>ÖĞRETMEN</div>
                        <div class="stamp-line"></div>
                        <div class="stamp-name">{{ $session->teacher->name }}</div>
                        <div class="stamp-role">Eğitmen</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-left">
                <i class="fas fa-copyright footer-icon"></i> {{ date('Y') }} Rise English - Tüm Hakları Saklıdır
            </div>
            <div class="footer-right">
                <i class="fas fa-file-alt footer-icon"></i><span class="page-number"></span> | <i class="fas fa-calendar-alt footer-icon"></i> Oluşturma: {{ now()->format('d.m.Y H:i') }}
            </div>
        </div>
    </div>
</body>
</html>