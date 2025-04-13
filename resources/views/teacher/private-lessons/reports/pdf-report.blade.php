<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ders Raporu</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 150px;
        }
        h1 {
            color: #2563eb;
            font-size: 24px;
        }
        .details {
            margin-bottom: 20px;
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
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #f3f4f6;
            padding: 8px 10px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .questions-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stat-box {
            width: 24%;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
        }
        .total-box { background-color: #e0f2fe; }
        .correct-box { background-color: #dcfce7; }
        .wrong-box { background-color: #fee2e2; }
        .empty-box { background-color: #f3f4f6; }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-label {
            font-size: 14px;
            color: #6b7280;
        }
        .subject-row {
            margin-bottom: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 10px;
        }
        .subject-name {
            font-weight: bold;
            margin-bottom: 10px;
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
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Özel Ders Raporu</h1>
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
    </div>
    
    @if($report->examResults && $report->examResults->count() > 0)
    <div class="exam-results section">
        <div class="section-title">Çözülen Denemeler</div>
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
        Rapor Oluşturma Tarihi: {{ now()->format('d.m.Y H:i') }}
    </div>
</body>
</html>