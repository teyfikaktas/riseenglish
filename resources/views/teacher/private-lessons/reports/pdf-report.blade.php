<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rise English - Ders Raporu</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #fff;
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
        }
        
        .document-subtitle {
            margin: 5px 0 0 0;
            color: #e63946;
            text-align: right;
            font-size: 14px;
        }
        
        .document-number {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
            text-align: right;
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
        
        .section-title {
            color: #1a2e5a;
            border-bottom: 2px solid #1a2e5a;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: bold;
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
        }
        
        .total-box { border-top: 3px solid #0284c7; }
        .correct-box { border-top: 3px solid #16a34a; }
        .wrong-box { border-top: 3px solid #dc2626; }
        .empty-box { border-top: 3px solid #6b7280; }
        
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
        }
        
        .correct-stat { background-color: #dcfce7; color: #166534; }
        .wrong-stat { background-color: #fee2e2; color: #991b1b; }
        .empty-stat { background-color: #f3f4f6; color: #6b7280; }
        
        .content-box {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #1a2e5a;
            border: 1px solid #eee;
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
        }
        
        .footer-right {
            text-align: right;
        }
        
        /* İmza alanı düzenleme - YENİ VE DÜZELTILMIŞ */
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
            color: #1a2e5a;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .stamp-line {
            height: 1px;
            background-color: #1a2e5a;
            margin: 0 auto 40px auto;
            width: 70%;
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
        
        @page {
            size: A4;
            margin: 20mm 15mm;
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
        }
    </style>
</head>
<body>
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
                <h1 class="document-title">ÖZEL DERS RAPORU</h1>
                <p class="document-subtitle">Öğrenci Değerlendirme Belgesi</p>
                <p class="document-number">Rapor No: RE-{{ $report->id }}-{{ date('Ymd') }}</p>
            </div>
        </div>
        
        <div class="cover-details">
            <table class="cover-details-table">
                <tr>
                    <td>Ders:</td>
                    <td>{{ $session->privateLesson->name }}</td>
                </tr>
                <tr>
                    <td>Tarih:</td>
                    <td>{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</td>
                </tr>
                <tr>
                    <td>Saat:</td>
                    <td>{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</td>
                </tr>
                <tr>
                    <td>Öğrenci:</td>
                    <td>{{ $session->student->name }}</td>
                </tr>
                <tr>
                    <td>Öğretmen:</td>
                    <td>{{ $session->teacher->name }}</td>
                </tr>
            </table>
        </div>
        
        <div class="decorative-element"></div>
    </div>

    <!-- ANA RAPOR SAYFASI -->
    <div class="report-container">
        <div class="questions section">
            <div class="section-title">Çözülen Sorular</div>
            <div class="questions-stats">
                <div class="stat-box total-box">
                    <div class="stat-label">Toplam Soru</div>
                    <div class="stat-number">{{ $report->questions_solved }}</div>
                </div>
                <div class="stat-box correct-box">
                    <div class="stat-label">Doğru</div>
                    <div class="stat-number">{{ $report->questions_correct }}</div>
                </div>
                <div class="stat-box wrong-box">
                    <div class="stat-label">Yanlış</div>
                    <div class="stat-number">{{ $report->questions_wrong }}</div>
                </div>
                <div class="stat-box empty-box">
                    <div class="stat-label">Boş</div>
                    <div class="stat-number">{{ $report->questions_unanswered }}</div>
                </div>
            </div>
            
            <!-- Ana sorular için grafik -->
            <div class="chart-container">
                <img class="chart-image" src="{{ $mainChartImage }}" alt="Çözülen Sorular Grafiği">
            </div>
        </div>
        
        @if($report->examResults && $report->examResults->count() > 0)
        <div class="exam-results section">
            <div class="section-title">Çözülen Denemeler</div>
            
            <!-- Denemeler için çubuk grafik -->
            <div class="chart-container">
                <img class="chart-image" src="{{ $subjectsChartImage }}" alt="Deneme Sonuçları Grafiği">
            </div>
            
            <!-- Denemeler detay tablosu -->
            @foreach($report->examResults as $examResult)
                <div class="subject-row">
                    <div class="subject-name">{{ $examResult->subject_name }}</div>
                    <div class="subject-stats">
                        <div class="subject-stat correct-stat">
                            <strong>{{ $examResult->questions_correct }}</strong> Doğru
                        </div>
                        <div class="subject-stat wrong-stat">
                            <strong>{{ $examResult->questions_wrong }}</strong> Yanlış
                        </div>
                        <div class="subject-stat empty-stat">
                            <strong>{{ $examResult->questions_unanswered }}</strong> Boş
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
        
        @if($report->pros)
        <div class="section">
            <div class="section-title">Artıları</div>
            <div class="content-box">
                {!! nl2br(e($report->pros)) !!}
            </div>
        </div>
        @endif
        
        @if($report->cons)
        <div class="section">
            <div class="section-title">Eksileri</div>
            <div class="content-box">
                {!! nl2br(e($report->cons)) !!}
            </div>
        </div>
        @endif
        
        @if($report->participation)
        <div class="section">
            <div class="section-title">Derse Katılım</div>
            <div class="content-box">
                {!! nl2br(e($report->participation)) !!}
            </div>
        </div>
        @endif
        
        <!-- İmza alanı - Tablo yapısı ile düzeltilmiş yan yana tasarım -->
        <div class="stamps-container">
            <div class="stamps">
                <div class="stamp-row">
                    <div class="stamp">
                        <div class="stamp-title">ÖĞRENCİ</div>
                        <div class="stamp-line"></div>
                        <div class="stamp-name">{{ $session->student->name }}</div>
                        <div class="stamp-role">Öğrenci</div>
                    </div>
                    
                    <div class="stamp">
                        <div class="stamp-title">ÖĞRETMEN</div>
                        <div class="stamp-line"></div>
                        <div class="stamp-name">{{ $session->teacher->name }}</div>
                        <div class="stamp-role">Eğitmen</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-left">
                © {{ date('Y') }} Rise English - Tüm Hakları Saklıdır
            </div>
            <div class="footer-right">
                Rapor Oluşturma Tarihi: {{ now()->format('d.m.Y H:i') }}
            </div>
        </div>
    </div>
</body>
</html>