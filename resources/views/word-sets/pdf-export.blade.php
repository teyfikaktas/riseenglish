<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
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

        * {
            font-weight: bold;
        }

        .container {
            padding: 40px 50px;
        }

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

        .document-info {
            text-align: right;
        }

        .document-title {
            margin: 0;
            color: #1a2e5a;
            font-size: 18px;
            font-weight: bold;
        }

        .document-subtitle {
            margin: 5px 0 0 0;
            color: #e63946;
            font-size: 12px;
        }

        .motto-container {
            text-align: center;
            margin: 20px 0 30px 0;
            font-weight: bold;
            font-size: 20px;
        }

        .motto-container .red {
            color: #e63946;
        }

        .motto-container .blue {
            color: #1a2e5a;
        }

        .set-info {
            background-color: rgba(249, 250, 251, 0.95);
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border-left: 4px solid {{ $wordSet->color }};
        }

        .set-name {
            font-size: 18px;
            color: #1a2e5a;
            margin: 0 0 5px 0;
        }

        .set-meta {
            font-size: 11px;
            color: #666;
        }

        .words-table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.95);
            margin-top: 15px;
        }

        .words-table th {
            background-color: #1a2e5a;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 12px;
        }

        .words-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }

        .words-table tr:nth-child(even) td {
            background-color: rgba(249, 250, 251, 0.95);
        }

        .words-table .num {
            width: 40px;
            text-align: center;
            color: #666;
        }

        .words-table .word {
            color: #1a2e5a;
        }

        .words-table .meaning {
            color: #333;
        }

        .words-table .answer-line {
            border-bottom: 1px dotted #999;
            min-width: 150px;
            display: inline-block;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .footer-logo {
            height: 35px;
            margin-bottom: 10px;
        }

        .student-info {
            margin-top: 30px;
            padding: 15px;
            background-color: rgba(249, 250, 251, 0.95);
            border-radius: 8px;
            border: 1px dashed #ccc;
        }

        .student-info-title {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }

        .student-info-line {
            border-bottom: 1px solid #333;
            height: 25px;
            margin-bottom: 10px;
        }

        @page {
            size: A4;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
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
                <h1 class="document-title">KELİME LİSTESİ</h1>
                <p class="document-subtitle">
                    @if($exportType == 'all')
                        Tam Liste
                    @elseif($exportType == 'turkish')
                        Türkçe Anlamlar
                    @else
                        Kelimeler
                    @endif
                </p>
            </div>
        </div>

        <div class="motto-container">
            <span class="red">Struggle</span> <span class="blue">Now</span> · 
            <span class="red">Rise</span> <span class="blue">English</span>
        </div>

        <div class="set-info">
            <h2 class="set-name">{{ $wordSet->name }}</h2>
            <p class="set-meta">
                {{ $words->count() }} kelime · 
                {{ now()->locale('tr')->isoFormat('D MMMM YYYY') }}
            </p>
        </div>

        <table class="words-table">
            <thead>
                <tr>
                    <th class="num">#</th>
                    @if($exportType == 'all')
                        <th>Kelime</th>
                        <th>Türkçe Anlamı</th>
                    @elseif($exportType == 'turkish')
                        <th>Türkçe Anlam</th>
                        <th>Kelime (Yazınız)</th>
                    @else
                        <th>Kelime</th>
                        <th>Türkçe Anlamı (Yazınız)</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($words as $index => $word)
                <tr>
                    <td class="num">{{ $index + 1 }}</td>
                    @if($exportType == 'all')
                        <td class="word">{{ $word->english_word }}</td>
                        <td class="meaning">{{ $word->turkish_meaning }}</td>
                    @elseif($exportType == 'turkish')
                        <td class="meaning">{{ $word->turkish_meaning }}</td>
                        <td><span class="answer-line"></span></td>
                    @else
                        <td class="word">{{ $word->english_word }}</td>
                        <td><span class="answer-line"></span></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($exportType != 'all')
        <div class="student-info">
            <p class="student-info-title">Öğrenci Bilgileri:</p>
            <p style="font-size: 11px; margin-bottom: 5px;">Ad Soyad:</p>
            <div class="student-info-line"></div>
            <p style="font-size: 11px; margin-bottom: 5px;">Tarih:</p>
            <div class="student-info-line"></div>
        </div>
        @endif

        <div class="footer">
            <img src="{{ public_path('images/logo.png') }}" alt="Rise English" class="footer-logo">
            <p>© {{ date('Y') }} Rise English - Tüm Hakları Saklıdır</p>
            <p>Oluşturma Tarihi: {{ now()->locale('tr')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
        </div>
    </div>
</body>
</html>