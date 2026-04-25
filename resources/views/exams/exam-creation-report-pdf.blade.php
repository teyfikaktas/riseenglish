<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Rise English - Sınav Oluşturma Raporu</title>
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

        .cover-page { min-height: 100vh; padding: 60px; }

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
        .document-date { margin: 5px 0 0 0; color: #e63946; font-size: 16px; }

        .motto-container { text-align: center; margin: 30px 0; font-size: 20px; }
        .motto-container .red { color: #e63946; }
        .motto-container .blue { color: #1a2e5a; }

        /* 3'lü istatistik */
        .stats-grid {
            display: table;
            width: 90%;
            margin: 20px auto;
            border-spacing: 10px;
        }
        .stat-row { display: table-row; }
        .stat-box {
            display: table-cell;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            background-color: rgba(249, 250, 251, 0.95);
            border: 2px solid #eee;
            width: 33%;
        }
        .stat-number { font-size: 28px; margin: 8px 0; }
        .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; }

        .both-stat { border-top: 4px solid #f59e0b; }
        .both-stat .stat-number { color: #f59e0b; }

        .self-stat { border-top: 4px solid #16a34a; }
        .self-stat .stat-number { color: #16a34a; }

        .teacher-stat { border-top: 4px solid #1a2e5a; }
        .teacher-stat .stat-number { color: #1a2e5a; }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.95);
        }
        .results-table th {
            background-color: #1a2e5a;
            color: white;
            padding: 12px;
            text-align: center;
            font-size: 13px;
        }
        .results-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
            background-color: rgba(255, 255, 255, 0.95);
            text-align: center;
        }
        .results-table tr:nth-child(even) td { background-color: rgba(249, 250, 251, 0.95); }
        .student-name { text-align: left !important; }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            color: white;
        }
        .badge-both    { background-color: #f59e0b; }
        .badge-self    { background-color: #16a34a; }
        .badge-teacher { background-color: #1a2e5a; }

        .footer {
            margin-top: 40px;
            padding: 15px;
            border-top: 2px solid #ddd;
            font-size: 11px;
            color: #666;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
        }
        .footer-logo { height: 40px; margin-bottom: 10px; }
        .bottom-logo { text-align: center; margin-top: 20px; }
        .bottom-logo img { max-width: 600px; height: auto; }

        @page { size: A4; margin: 0; }
    </style>
</head>
<body>
    <div class="cover-page">
        <div class="header">
            <div class="logo-container">
                <img src="{{ public_path('images/logo.png') }}" alt="Rise English" class="logo">
                <div class="company-info">
                    <p class="company-name">RISE ENGLISH</p>
                    <p class="company-details">Profesyonel Dil Eğitimi</p>
                    <p class="company-details">www.risenglish.com</p>
                </div>
            </div>
            <div class="document-info">
                <h1 class="document-title">SINAV OLUŞTURMA RAPORU</h1>
                <p class="document-date">{{ $date->locale('tr')->isoFormat('D MMMM YYYY, dddd') }}</p>
            </div>
        </div>

        <div class="motto-container">
            <span class="red">Struggle</span> <span class="blue">Now</span> · 
            <span class="red">Rise</span> <span class="blue">English</span>
        </div>

        {{-- 3'lü istatistik --}}
        <div class="stats-grid">
            <div class="stat-row">
                <div class="stat-box both-stat">
                    <div class="stat-label">Her İkisi</div>
                    <div class="stat-number">{{ $bothCount }}</div>
                </div>
                <div class="stat-box self-stat">
                    <div class="stat-label">Kendi Oluşturdu</div>
                    <div class="stat-number">{{ $selfCount }}</div>
                </div>
                <div class="stat-box teacher-stat">
                    <div class="stat-label">Öğretmen Oluşturdu</div>
                    <div class="stat-number">{{ $teacherCount }}</div>
                </div>
            </div>
        </div>

        {{-- Detay Tablo --}}
        <table class="results-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">Öğrenci</th>
                    <th style="width: 15%;">Kendi (Adet)</th>
                    <th style="width: 15%;">Öğretmen (Adet)</th>
                    <th style="width: 30%;">Durum</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="student-name">{{ $row['student_name'] }}</td>
                        <td>{{ $row['self_created_count'] }}</td>
                        <td>{{ $row['teacher_created_count'] }}</td>
                        <td>
                            @if($row['status_class'] === 'both')
                                <span class="badge badge-both">★ Her İkisi</span>
                            @elseif($row['status_class'] === 'self-created')
                                <span class="badge badge-self">✓ Öğrenci Oluşturdu</span>
                            @else
                                <span class="badge badge-teacher">⚙ Öğretmen Oluşturdu</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <img src="{{ public_path('images/logo.png') }}" alt="Rise English" class="footer-logo">
            <p>© {{ date('Y') }} Rise English - Tüm Hakları Saklıdır</p>
            <p>Oluşturma: {{ now()->locale('tr')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
        </div>

        <div class="bottom-logo">
            <img src="{{ public_path('images/rs.jpg') }}" alt="RS Logo">
        </div>
    </div>
</body>
</html>