<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rise English - Günlük Sınav Raporu</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 40px;
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

        .document-info {
            text-align: right;
        }

        .document-title {
            margin: 0;
            color: #1a2e5a;
            font-size: 20px;
            font-weight: bold;
        }

        .document-date {
            margin: 5px 0 0 0;
            color: #e63946;
            font-size: 16px;
        }

        /* Motto */
        .motto-container {
            text-align: center;
            margin: 30px 0;
            font-size: 20px;
        }

        .motto-container .red {
            color: #e63946;
        }

        .motto-container .blue {
            color: #1a2e5a;
        }

        /* Sonuç tablosu */
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

        .results-table tr:nth-child(even) td {
            background-color: rgba(249, 250, 251, 0.95);
        }

        .student-name {
            text-align: left !important;
        }

        .exam-name {
            text-align: left !important;
            font-size: 11px;
            color: #666;
        }

        /* Sıralama renkleri */
        .rank-1 {
            background-color: #FFD700 !important;
        }

        .rank-2 {
            background-color: #C0C0C0 !important;
        }

        .rank-3 {
            background-color: #CD7F32 !important;
        }

        /* Girmedi durumu */
        .not-entered {
            background-color: #fee2e2 !important;
        }

        .not-entered td {
            background-color: #fee2e2 !important;
            color: #dc2626;
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

        @page {
            size: A4;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('images/logo.png') }}" alt="Rise English Logo" class="logo">
            <div class="company-info">
                <p class="company-name">RISE ENGLISH</p>
                <p class="company-details">Profesyonel Dil Eğitimi</p>
            </div>
        </div>
        <div class="document-info">
            <h1 class="document-title">GÜNLÜK SINAV RAPORU</h1>
            <p class="document-date">{{ $date->locale('tr')->isoFormat('D MMMM YYYY, dddd') }}</p>
        </div>
    </div>

    <div class="motto-container">
        <span class="red">Struggle</span> <span class="blue">Now</span> · 
        <span class="red">Rise</span> <span class="blue">English</span>
    </div>

    @php
        // O günün tüm sonuçlarını çek
        $allResults = \App\Models\ExamResult::whereHas('exam', function($q) use ($date, $teacher) {
            $q->whereDate('start_time', $date->toDateString())
              ->where('teacher_id', $teacher->id);
        })
        ->with(['student', 'exam'])
        ->get()
        ->sortByDesc(function($result) {
            $total = $result->correct_count + $result->wrong_count;
            return $total > 0 ? ($result->correct_count / $total) * 100 : 0;
        });
        
        // Sınava girmeyenleri bul
        $notEnteredStudents = collect();
        foreach($exams as $exam) {
            $enteredIds = $exam->results->pluck('student_id')->toArray();
            foreach($exam->students as $student) {
                if (!in_array($student->id, $enteredIds)) {
                    $notEnteredStudents->push([
                        'student' => $student,
                        'exam' => $exam
                    ]);
                }
            }
        }
    @endphp

    <table class="results-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Öğrenci</th>
                <th style="width: 25%;">Sınav</th>
                <th style="width: 10%;">Doğru</th>
                <th style="width: 10%;">Yanlış</th>
                <th style="width: 20%;">Başarı</th>
            </tr>
        </thead>
        <tbody>
            @php $rank = 1; @endphp
            @foreach($allResults as $result)
                @php
                    $total = $result->correct_count + $result->wrong_count;
                    $successRate = $total > 0 ? round(($result->correct_count / $total) * 100) : 0;
                    
                    $rowClass = '';
                    if ($rank == 1) $rowClass = 'rank-1';
                    elseif ($rank == 2) $rowClass = 'rank-2';
                    elseif ($rank == 3) $rowClass = 'rank-3';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>{{ $rank }}</td>
                    <td class="student-name">{{ $result->student->name }}</td>
                    <td class="exam-name">{{ $result->exam->name }}</td>
                    <td>{{ $result->correct_count }}</td>
                    <td>{{ $result->wrong_count }}</td>
                    <td>%{{ $successRate }}</td>
                </tr>
                @php $rank++; @endphp
            @endforeach

            {{-- Sınava girmeyenler --}}
            @foreach($notEnteredStudents as $item)
                <tr class="not-entered">
                    <td>-</td>
                    <td class="student-name">{{ $item['student']->name }}</td>
                    <td class="exam-name">{{ $item['exam']->name }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>GİRMEDİ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <img src="{{ public_path('images/logo.png') }}" alt="Rise English" class="footer-logo">
        <p>© {{ date('Y') }} Rise English - Tüm Hakları Saklıdır</p>
        <p>Oluşturma: {{ now()->locale('tr')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
    </div>
</body>
</html>