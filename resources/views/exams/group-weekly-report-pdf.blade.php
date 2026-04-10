<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Grup Haftalık Rapor</title>
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

        /* RAPOR SAYFASI */
        .report-container {
            margin: 30px 40px;
            padding: 10px;
        }

        .report-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .report-header-table td {
            vertical-align: middle;
            padding: 0;
            border: none;
        }
        .report-header-table .header-left {
            text-align: left;
        }
        .report-header-table .header-left img {
            height: 40px;
            vertical-align: middle;
            margin-right: 10px;
        }
        .report-header-table .header-left span {
            color: #1a2e5a;
            font-size: 14px;
            vertical-align: middle;
        }

        .date-range-title {
            text-align: center;
            margin-bottom: 5px;
        }
        .date-range-title .range-text {
            font-size: 20px;
            color: #e63946;
        }

        .section-title {
            border-bottom: 2px solid #1a2e5a;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 16px;
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
        .matrix-table th .day-name {
            font-size: 11px;
            display: block;
        }
        .matrix-table th .day-date {
            font-size: 9px;
            font-weight: normal;
            display: block;
            margin-top: 2px;
        }
        .matrix-table td {
            padding: 10px 6px;
            border-bottom: 1px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #000;
        }
        .matrix-table td.student-name {
            text-align: left;
            font-size: 12px;
            color: #1a2e5a;
        }
        .matrix-table tr:nth-child(even) td {
            background-color: rgba(249, 250, 251, 0.95);
        }
        .matrix-table td.total-col {
            background-color: rgba(26, 46, 90, 0.08);
        }

        .correct-text {
            color: #000;
            font-size: 14px;
        }
        .wrong-text {
            color: #000;
            font-size: 11px;
        }
        .total-correct {
            color: #1a2e5a;
            font-size: 14px;
        }
        .total-wrong {
            color: #e63946;
            font-size: 11px;
        }
        .score-none {
            color: #e63946;
            font-size: 10px;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            color: #666;
            text-align: center;
            padding: 10px;
        }

        @page { size: A4 landscape; margin: 0; }
    </style>
</head>
<body>
    @php
        $dayNames = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
    @endphp

    <!-- HAFTALIK SINAV SONUÇLARI -->
    <div class="report-container">
        <table class="report-header-table">
            <tr>
                <td class="header-left">
                    <img src="{{ public_path('images/logo.png') }}" alt="Rise English">
                    <span>RISE ENGLISH</span>
                </td>
            </tr>
        </table>

        <div class="date-range-title">
            <span class="range-text">
                {{ $startDate->locale('tr')->isoFormat('D MMMM') }} - {{ $endDate->locale('tr')->isoFormat('D MMMM YYYY') }} Arası
            </span>
        </div>

        <div class="section-title">HAFTALIK SINAV SONUÇLARI</div>

        <table class="matrix-table">
            <thead>
                <tr>
                    <th class="student-col">ÖĞRENCİ</th>
                    @foreach($days as $i => $day)
                        <th>
                            <span class="day-name">{{ $dayNames[$i] }}</span>
                            <span class="day-date">{{ $day['date']->locale('tr')->isoFormat('D MMM') }}</span>
                        </th>
                    @endforeach
                    <th style="background-color:#e63946;">TOPLAM</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    @php
                        $weeklyCorrect = 0;
                        $weeklyWrong = 0;
                        $hasAnyExam = false;
                    @endphp
                    <tr>
                        <td class="student-name">{{ mb_strtoupper($student->name, 'UTF-8') }}</td>
                        @foreach($days as $dayData)
                            @php
                                $cell = $matrix[$student->id][$dayData['key']] ?? null;
                            @endphp
                            <td>
                                @if($cell !== null)
                                    <span class="correct-text">{{ $cell['correct'] }}</span>
                                    <br>
                                    <span class="wrong-text">{{ $cell['wrong'] }}</span>
                                    @php
                                        $weeklyCorrect += $cell['correct'];
                                        $weeklyWrong += $cell['wrong'];
                                        $hasAnyExam = true;
                                    @endphp
                                @else
                                    <span class="score-none">GİRMEDİ</span>
                                @endif
                            </td>
                        @endforeach
                        <td class="total-col">
                            @if($hasAnyExam)
                                <span class="total-correct">{{ $weeklyCorrect }} D</span>
                                <br>
                                <span class="total-wrong">{{ $weeklyWrong }} Y</span>
                            @else
                                <span class="score-none">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>© {{ date('Y') }} Rise English - Tüm Hakları Saklıdır</p>
        </div>
    </div>
</body>
</html>