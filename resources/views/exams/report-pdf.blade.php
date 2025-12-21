<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rise English - Sınav Raporu</title>
    <style>
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

        /* Tüm yazılar kalın */
        * {
            font-weight: bold;
        }

        /* Kapak sayfası */
        .cover-page {
            height: 100vh;
            display: flex;
            flex-direction: column;
            page-break-after: always;
            padding: 60px;
        }

        /* Motto stili */
        .motto-container {
            text-align: center;
            margin: 40px 0;
            font-weight: bold;
            font-size: 24px;
        }

        .motto-container .red {
            color: #e63946;
        }

        .motto-container .blue {
            color: #1a2e5a;
        }

        /* Header */
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

        /* Belge başlığı */
        .document-info {
            text-align: right;
        }

        .document-title {
            margin: 0;
            color: #1a2e5a;
            font-size: 20px;
            font-weight: bold;
        }

        .document-subtitle {
            margin: 5px 0 0 0;
            color: #e63946;
            font-size: 13px;
        }

        .document-number {
            color: #666;
            font-size: 11px;
            margin-top: 5px;
        }

        /* Kapak detay tablosu */
        .cover-details {
            margin: 40px auto;
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

        .cover-details-table td:first-child {
            width: 30%;
            color: #1a2e5a;
            font-weight: bold;
            background-color: rgba(26, 46, 90, 0.05);
        }

        .cover-details-table td:nth-child(2) {
            color: #333;
            font-size: 16px;
        }

        /* Renkli vurgu çizgileri */
        .cover-details-table tr:nth-child(1) td:first-child { border-left: 4px solid #4361ee; }
        .cover-details-table tr:nth-child(2) td:first-child { border-left: 4px solid #3a0ca3; }
        .cover-details-table tr:nth-child(3) td:first-child { border-left: 4px solid #7209b7; }
        .cover-details-table tr:nth-child(4) td:first-child { border-left: 4px solid #f72585; }
        .cover-details-table tr:nth-child(5) td:first-child { border-left: 4px solid #e63946; }

        /* İstatistik kutuları - 1. sayfa için */
        .stats-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
            border-spacing: 10px;
        }

        .stat-row {
            display: table-row;
        }

        .stat-box {
            display: table-cell;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            background-color: rgba(249, 250, 251, 0.95);
            border: 2px solid #eee;
            width: 33.33%;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin: 8px 0;
        }

        .stat-label {
            font-size: 11px;
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* İstatistik kutusu renkleri */
        .total-students { 
            border-top: 4px solid #0284c7; 
        }
        .total-students .stat-number { 
            color: #0284c7; 
        }

        .completed { 
            border-top: 4px solid #16a34a; 
        }
        .completed .stat-number { 
            color: #16a34a; 
        }

        .not-completed { 
            border-top: 4px solid #dc2626; 
        }
        .not-completed .stat-number { 
            color: #dc2626; 
        }

        /* Ana rapor container */
        .report-container {
            max-width: 650px;
            margin: 30px 50px;
            padding: 10px;
            position: center;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        /* Bölümler */
        .section {
            margin-bottom: 30px;
            page-break-after: always;
        }

        .section-title {
            border-bottom: 2px solid #1a2e5a;
            padding-bottom: 8px;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #1a2e5a;
            text-align: center;
        }

        /* Sonuç tablosu - 2. sayfa */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: rgba(255, 255, 255, 0.95);
        }

        .results-table th {
            background-color: #1a2e5a;
            color: white;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }

        .results-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.95);
            text-align: center;
        }

        .results-table tr:nth-child(even) td {
            background-color: rgba(249, 250, 251, 0.95);
        }

        /* Sıralama renkleri */
        .rank-1 {
            background-color: #FFD700 !important; /* Altın */
        }

        .rank-2 {
            background-color: #C0C0C0 !important; /* Gümüş */
        }

        .rank-3 {
            background-color: #CD7F32 !important; /* Bronz */
        }

        .rank-badge {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }

        /* Girmedi durumu */
        .not-entered {
            background-color: #fee2e2 !important;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            font-size: 11px;
            color: #666;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 15px;
            border-radius: 5px;
        }

        .footer-logo {
            height: 40px;
            margin-bottom: 10px;
        }

        /* Alt logo */
        .bottom-logo {
            text-align: center;
            margin-top: 20px;
        }

        .bottom-logo img {
            max-width: 600px;
            height: auto;
        }

        /* Yazdırma ayarları */
        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .report-container {
                width: 100%;
                max-width: 100%;
                padding: 40px;
                margin: 40px 0;
            }
        }
    </style>
</head>
<body>
    <!-- KAPAK SAYFASI - 1. SAYFA -->
    <div class="cover-page">
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
                <h1 class="document-title">SINAV RAPORU</h1>
                <p class="document-subtitle">Öğrenci Değerlendirme Belgesi</p>
                <p class="document-number">Rapor No: RE-{{ $exam->id }}-{{ date('Ymd') }}</p>
            </div>
        </div>

        <div class="motto-container">
            <span class="red">Struggle</span> <span class="blue">Now</span><br>
            <span class="red">Rise</span> <span class="blue">English</span>
        </div>

        <div class="cover-details">
            <table class="cover-details-table">
                <tr>
                    <td>Sınav Adı</td>
                    <td>{{ $exam->name }}</td>
                </tr>
                <tr>
                    <td>Tarih</td>
                    <td>{{ \Carbon\Carbon::parse($exam->start_time)->locale('tr')->isoFormat('D MMMM YYYY, dddd') }}</td>
                </tr>
                <tr>
                    <td>Saat</td>
                    <td>{{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }}</td>
                </tr>
                <tr>
                    <td>Öğretmen</td>
                    <td>{{ $exam->teacher->name }}</td>
                </tr>
            </table>
        </div>

        @php
            // Tamamlanmış sınavlar (completed_at dolu)
            $completedResults = $exam->results->whereNotNull('completed_at');
            
            // Hiç girmemiş öğrenciler (exam_results tablosunda kayıt yok)
            $enteredStudentIds = $exam->results->pluck('student_id');
            $notEnteredStudents = $exam->students->whereNotIn('id', $enteredStudentIds);
        @endphp

        <!-- Genel İstatistikler -->
        <div class="stats-grid">
            <div class="stat-row">
                <div class="stat-box total-students">
                    <div class="stat-label">Toplam Öğrenci</div>
                    <div class="stat-number">{{ $exam->students->count() }}</div>
                </div>
                <div class="stat-box completed">
                    <div class="stat-label">Sınava Giren</div>
                    <div class="stat-number">{{ $completedResults->count() }}</div>
                </div>
                <div class="stat-box not-completed">
                    <div class="stat-label">Sınava Girmedi</div>
                    <div class="stat-number">{{ $notEnteredStudents->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. SAYFA - BAŞARI SIRASI -->
    <div class="report-container">
        <div class="section">
            <div class="section-title">BAŞARI SIRASI</div>
            
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Öğrenci Adı</th>
                        <th>Doğru</th>
                        <th>Yanlış</th>
                        <th>Başarı Oranı</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedResults->sortByDesc('score') as $index => $result)
                        @php
                            $correctCount = $result->getCorrectAnswersCount();
                            $wrongCount = $result->getWrongAnswersCount();
                            $successRate = $result->total_questions > 0 ? round(($correctCount / $result->total_questions) * 100) : 0;
                            
                            $rankClass = '';
                            $rankText = '';
                            if ($index == 0) {
                                $rankClass = 'rank-1';
                                $rankText = 'GÜNÜN BİRİNCİSİ';
                            } elseif ($index == 1) {
                                $rankClass = 'rank-2';
                                $rankText = 'GÜNÜN İKİNCİSİ';
                            } elseif ($index == 2) {
                                $rankClass = 'rank-3';
                                $rankText = 'GÜNÜN ÜÇÜNCÜSÜ';
                            }
                        @endphp
                        <tr class="{{ $rankClass }}">
                            <td>
                                {{ $result->student->name }}
                                @if($rankText)
                                    <br><span class="rank-badge">{{ $rankText }}</span>
                                @endif
                            </td>
                            <td>{{ $correctCount }} D</td>
                            <td>{{ $wrongCount }} Y</td>
                            <td>% {{ $successRate }}</td>
                        </tr>
                    @endforeach
                    
                    <!-- Sınava girmeyenler -->
                    @foreach($notEnteredStudents as $student)
                        <tr class="not-entered">
                            <td>{{ $student->name }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>GİRMEDİ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <img src="{{ public_path('images/logo.png') }}" alt="Rise English" class="footer-logo">
            <p>© {{ date('Y') }} Rise English - Tüm Hakları Saklıdır</p>
            <p>Oluşturma Tarihi: {{ now()->locale('tr')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
        </div>

        <!-- Alt logo -->
        <div class="bottom-logo">
            <img src="{{ public_path('images/rs.jpg') }}" alt="RS Logo">
        </div>
    </div>
</body>
</html>repr