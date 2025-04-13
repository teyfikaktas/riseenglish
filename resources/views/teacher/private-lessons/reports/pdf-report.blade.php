<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ders Raporu</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
            color: #333;
            background-color: #f8fafc;
        }
        
        .header {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #1a2e5a 0%, #283b6a 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            border-bottom: 4px solid #e63946;
        }
        
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        
        h1 {
            color: white;
            font-size: 26px;
            margin: 0;
            font-weight: bold;
        }
        
        .details {
            margin-bottom: 25px;
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
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
        
        .section-title {
            background-color: #1a2e5a;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .questions-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .stat-box {
            width: 22%;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
        }
        
        .total-box { 
            background-color: #e0f2fe; 
            border-top: 3px solid #0284c7;
        }
        
        .correct-box { 
            background-color: #dcfce7; 
            border-top: 3px solid #16a34a;
        }
        
        .wrong-box { 
            background-color: #fee2e2; 
            border-top: 3px solid #dc2626;
        }
        
        .empty-box { 
            background-color: #f3f4f6; 
            border-top: 3px solid #6b7280;
        }
        
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
            border-radius: 8px;
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
        }
        
        .subject-stat {
            text-align: center;
            padding: 8px;
            border-radius: 4px;
            width: 30%;
        }
        
        .correct-stat { background-color: #dcfce7; color: #166534; }
        .wrong-stat { background-color: #fee2e2; color: #991b1b; }
        .empty-stat { background-color: #f3f4f6; color: #6b7280; }
        
        .content-box {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #1a2e5a;
        }
        
        .chart-container {
            text-align: center;
            margin: 20px 0;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .chart-image {
            max-width: 100%;
            height: auto;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: white;
            background-color: #1a2e5a;
            padding: 15px;
            border-radius: 8px;
            border-top: 4px solid #e63946;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Rise English Logo" class="logo">
        <h1>ÖZEL DERS RAPORU</h1>
    </div>
    
    <div class="details section">
        <div class="section-title">Ders Bilgileri</div>
        <table class="details-table">
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
    
    <div class="footer">
        <div>© {{ date('Y') }} Rise English - Tüm Hakları Saklıdır</div>
        <div style="margin-top: 5px">Rapor Oluşturma Tarihi: {{ now()->format('d.m.Y H:i') }}</div>
    </div>
</body>
</html>