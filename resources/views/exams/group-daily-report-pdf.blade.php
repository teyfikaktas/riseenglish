<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Grup Günlük Rapor</title>
    <style>
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
        }
        * { font-weight: bold; }

        .cover-page {
            height: 100vh;
            display: flex;
            flex-direction: column;
            page-break-after: always;
            padding: 60px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #1a2e5a;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .logo-container { display: flex; align-items: center; }
        .logo { height: 50px; margin-right: 15px; }
        .company-info { display: flex; flex-direction: column; }
        .company-name { color: #1a2e5a; font-size: 16px; margin: 0; }
        .company-details { font-size: 10px; color: #666; margin: 3px 0 0 0; }
        .document-info { text-align: right; }
        .document-title { margin: 0; color: #1a2e5a; font-size: 20px; }
        .document-subtitle { margin: 5px 0 0 0; color: #e63946; font-size: 13px; }
        .document-number { color: #666; font-size: 11px; margin-top: 5px; }

        .motto-container { text-align: center; margin: 40px 0; font-size: 24px; }
        .motto-container .red { color: #e63946; }
        .motto-container .blue { color: #1a2e5a; }

        .cover-details {
            margin: 40px auto;
            border-radius: 12px;
            background-color: rgba(249, 250, 251, 0.95);
            width: 75%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .cover-details-table { width: 100%; border-collapse: collapse; }
        .cover-details-table td { padding: 16px 20px; border-bottom: 1px solid #e5e7eb; }
        .cover-details-table tr:last-child td { border-bottom: none; }
        .cover-details-table td:first-child {
            width: 30%; color: #1a2e5a;
            background-color: rgba(26, 46, 90, 0.05);
        }
        .cover-details-table td:nth-child(2) { color: #333; font-size: 16px; }
        .cover-details-table tr:nth-child(1) td:first-child { border-left: 4px solid #4361ee; }
        .cover-details-table tr:nth-child(2) td:first-child { border-left: 4px solid #3a0ca3; }
        .cover-details-table tr:nth-child(3) td:first-child { border-left: 4px solid #7209b7; }
        .cover-details-table tr:nth-child(4) td:first-child { border-left: 4px solid #f72585; }

        .report-container {
            margin: 30px 40px;
            padding: 10px;
        }
        .section-title {
            border-bottom: 2px solid #1a2e5a;
            padding-bottom: 8px;
            margin-bottom: 20px;
            font-size: 18px;
            color: #1a2e5a;
            text-align: center;
        }

        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.95);
            font-size: 11px;
        }
        .matrix-table th {
            background-color: #1a2e5a;
            color: white;
            padding: 10px 6px;
            text-align: center;
            font-size: 10px;
        }
        .matrix-table th.student-col {
            text-align: left;
            min-width: 120px;
        }
        .matrix-table td {
            padding: 10px 6px;
            border-bottom: 1px solid #eee;
            text-align: center;
            font-size: 12px;
        }
        .matrix-table td.student-name {
            text-align: left;
            font-size: 12px;
        }
        .matrix-table tr:nth-child(even) td {
            background-color: rgba(249, 250, 251, 0.95);
        }

        .score-high { color: #16a34a; }
        .score-mid { color: #ca8a04; }
        .score-low { color: #dc2626; }
        .score-none { color: #dc2626; font-size: 9px; }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            font-size: 11px;
            color: #666;
            text-align: center;
            padding: 15px;
        }
        .footer-logo { height: 40px; margin-bottom: 10px; }
        .bottom-logo { text-align: center; margin-top: 20px; }
        .bottom-logo img { max-width: 600px; height: auto; }

        @page { size: A4 landscape; margin: 0; }
    </style>
</head>
<body>
    @php
        $totalExams = $exams->count();
        $totalStudents = $students->count();
    @endphp

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
                <h1 class="document-title">GRUP GÜNLÜK RAPORU</h1>
                <p class="document-subtitle">{{ $group->name }}</p>
                <p class="document-number">Rapor No: GR-{{ $group->id }}-{{ $date->format('Ymd') }}</p>
            </div>
        </div>

        <div class="motto-container">
            <span class="red">Struggle</span> <span class="blue">Now</span><br>
            <span class="red">Rise</span> <span class="blue">English</span>
        </div>

        <div class="cover-details">
            <table class="cover-details-table">
                <tr>
                    <td>Grup Adı</td>
                    <td>{{ $group->name }}</td>
                </tr>
                <tr>
                    <td>Tarih</td>
                    <td>{{ $date->locale('tr')->isoFormat('D MMMM YYYY, dddd') }}</td>
                </tr>
                <tr>
                    <td>Öğretmen</td>
                    <td>{{ $teacher->name }}</td>
                </tr>
                <tr>
                    <td>Sınav Sayısı</td>
                    <td>{{ $totalExams }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 2. SAYFA - MATRİS TABLOSU -->
    <div class="report-container">
        <div class="section-title">GÜNLÜK SINAV MATRİSİ</div>

        <table class="matrix-table">
            <thead>
                <tr>
                    <th class="student-col">Öğrenci</th>
                    @foreach($exams as $index => $exam)
                        <th>
                            Sınav {{ $index + 1 }}<br>
                            <span style="font-size:9px; font-weight:normal;">{{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td class="student-name">{{ $student->name }}</td>
                        @foreach($exams as $exam)
                            @php
                                $cell = $matrix[$student->id][$exam->id] ?? null;
                            @endphp
                            <td>
                                @if($cell !== null)
                                    <span class="{{ $cell['success_rate'] >= 70 ? 'score-high' : ($cell['success_rate'] >= 50 ? 'score-mid' : 'score-low') }}">
                                        %{{ number_format($cell['success_rate'], 0) }}
                                    </span>
                                    <br>
                                    <span style="font-size:9px; color:#16a34a;">{{ $cell['correct'] }}D</span>
                                    <span style="font-size:9px; color:#dc2626;">{{ $cell['wrong'] }}Y</span>
                                @else
                                    <span class="score-none">GİRMEDİ</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <img src="{{ public_path('images/logo.png') }}" alt="Rise English" class="footer-logo">
            <p>© {{ date('Y') }} Rise English - Tüm Hakları Saklıdır</p>
            <p>Oluşturma Tarihi: {{ now()->locale('tr')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
        </div>

        <div class="bottom-logo">
            <img src="{{ public_path('images/rs.jpg') }}" alt="RS Logo">
        </div>
    </div>
</body>
</html>