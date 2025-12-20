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
            background-color: #f9fafb;
        }

        /* Kapak sayfası */
        .cover-page {
            height: 100vh;
            display: flex;
            flex-direction: column;
            page-break-after: always;
            padding: 60px;
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

        /* Motto */
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

        /* Ana rapor container */
        .report-container {
            max-width: 750px;
            margin: 30px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
        }

        /* Bölümler */
        .section {
            margin-bottom: 30px;
        }

        .section-title {
            border-bottom: 2px solid #1a2e5a;
            padding-bottom: 8px;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: bold;
            color: #1a2e5a;
        }

        /* İstatistik kutuları */
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
            background-color: #f9fafb;
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

        /* Sonuç tablosu */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .results-table th {
            background-color: #1a2e5a;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 13px;
        }

        .results-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .results-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .results-table tr:hover {
            background-color: #f3f4f6;
        }

        /* Badge stilleri */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* Sıralama rozetleri */
        .rank-badge {
            display: inline-block;
            width: 28px;
            height: 28px;
            line-height: 28px;
            text-align: center;
            border-radius: 50%;
            font-weight: bold;
            font-size: 13px;
        }

        .rank-1 { 
            background-color: #ffd700; 
            color: #000; 
        }
        
        .rank-2 { 
            background-color: #c0c0c0; 
            color: #000; 
        }
        
        .rank-3 { 
            background-color: #cd7f32; 
            color: #fff; 
        }
        
        .rank-other { 
            background-color: #e5e7eb; 
            color: #6b7280; 
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            font-size: 11px;
            color: #666;
            text-align: center;
        }

        .footer-logo {
            height: 40px;
            margin-bottom: 10px;
        }

        /* Boş durum */
        .empty-state {
            text-align: center;
            padding: 40px;
            background-color: #f9fafb;
            border-radius: 8px;
            border: 2px dashed #ddd;
        }

        .empty-state-icon {
            font-size: 48px;
            color: #9ca3af;
            margin-bottom: 15px;
        }

        .empty-state-text {
            color: #6b7280;
            font-size: 14px;
            font-style: italic;
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
        }
    </style>
</head>
<body>
    <!-- KAPAK SAYFASI -->
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
                    <td>Açıklama</td>
                    <td>{{ $exam->description ?: 'Açıklama yok' }}</td>
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
    </div>

    <!-- ANA RAPOR SAYFASI -->
    <div class="report-container">
        @php
            // Tamamlanmış sınavlar (completed_at dolu)
            $completedResults = $exam->results->whereNotNull('completed_at');
            
            // Hiç girmemiş öğrenciler (exam_results tablosunda kayıt yok)
            $enteredStudentIds = $exam->results->pluck('student_id');
            $notEnteredStudents = $exam->students->whereNotIn('id', $enteredStudentIds);
        @endphp

        <!-- Genel İstatistikler -->
        <div class="section">
            <div class="section-title">📊 GENEL İSTATİSTİKLER</div>
            
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

        <!-- SINAVI TAMAMLAYANLAR -->
        @if($completedResults->count() > 0)
            <div class="section">
                <div class="section-title">🏆 SINAV SONUÇLARI</div>
                
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Öğrenci Adı</th>
                            <th style="width: 100px; text-align: center;">Doğru</th>
                            <th style="width: 100px; text-align: center;">Yanlış</th>
                            <th style="width: 100px; text-align: center;">Boş</th>
                            <th style="width: 100px; text-align: center;">Puan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedResults->sortByDesc('score') as $result)
                            <tr>
                                <td><strong>{{ $result->student->name }}</strong></td>
                                <td style="text-align: center; color: #16a34a; font-weight: bold;">
                                    {{ $result->getCorrectAnswersCount() }}
                                </td>
                                <td style="text-align: center; color: #dc2626; font-weight: bold;">
                                    {{ $result->getWrongAnswersCount() }}
                                </td>
                                <td style="text-align: center; color: #6b7280; font-weight: bold;">
                                    {{ $result->total_questions - $result->getCorrectAnswersCount() - $result->getWrongAnswersCount() }}
                                </td>
                                <td style="text-align: center; font-size: 16px; font-weight: bold;">
                                    {{ number_format($result->score, 0) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- HİÇ GİRMEYENLER -->
        @if($notEnteredStudents->count() > 0)
            <div class="section">
                <div class="section-title">❌ SINAVA GİRMEYEN ÖĞRENCİLER</div>
                
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Öğrenci Adı</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notEnteredStudents as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- BOŞ DURUM -->
        @if($completedResults->count() == 0 && $notEnteredStudents->count() == 0)
            <div class="section">
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <p class="empty-state-text">Bu sınava henüz hiçbir öğrenci atanmamıştır.</p>
                </div>
            </div>
        @endif

        <div class="footer">
            <img src="{{ public_path('images/logo.png') }}" alt="Rise English" class="footer-logo">
            <p><strong>© {{ date('Y') }} Rise English</strong> - Tüm Hakları Saklıdır</p>
            <p>Oluşturma Tarihi: {{ now()->locale('tr')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
        </div>
    </div>
</body>
</html>