<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Pro Öğrenciler - {{ $group->name }}</title>
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

        .group-title {
            text-align: center;
            margin-bottom: 5px;
        }
        .group-title .group-name {
            font-size: 22px;
            color: #1a2e5a;
        }
        .group-title .group-date {
            font-size: 12px;
            color: #666;
            display: block;
            margin-top: 4px;
        }

        .section-title {
            border-bottom: 2px solid #1a2e5a;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 16px;
            color: #1a2e5a;
            text-align: center;
        }

        .summary-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .summary-box .badge {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            margin: 0 8px;
        }
        .badge-pro {
            background-color: #7c3aed;
            color: #fff;
        }
        .badge-not-pro {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .badge-total {
            background-color: #1a2e5a;
            color: #fff;
        }

        .student-table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.95);
            font-size: 12px;
        }
        .student-table th {
            background-color: #1a2e5a;
            color: white;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
        }
        .student-table th.center {
            text-align: center;
        }
        .student-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
            color: #000;
        }
        .student-table tr:nth-child(even) td {
            background-color: rgba(249, 250, 251, 0.95);
        }

        .pro-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 11px;
            text-align: center;
        }
        .pro-yes {
            background-color: #ede9fe;
            color: #6d28d9;
        }
        .pro-no {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .membership-info {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            color: #666;
            text-align: center;
            padding: 10px;
        }

        @page { size: A4 portrait; margin: 0; }
    </style>
</head>
<body>
    @php
        $proStudents = $students->filter(fn($s) => $s->activeMembership);
        $notProStudents = $students->filter(fn($s) => !$s->activeMembership);
    @endphp

    <div class="report-container">
        {{-- HEADER --}}
        <table class="report-header-table">
            <tr>
                <td class="header-left">
                    <img src="{{ public_path('images/logo.png') }}" alt="Rise English">
                    <span>RISE ENGLISH</span>
                </td>
            </tr>
        </table>

        {{-- GRUP ADI --}}
        <div class="group-title">
            <span class="group-name">{{ mb_strtoupper($group->name, 'UTF-8') }}</span>
            <span class="group-date">{{ now()->locale('tr')->isoFormat('D MMMM YYYY') }}</span>
        </div>

        <div class="section-title">PRO ÖĞRENCİ DURUMU</div>

        {{-- ÖZET --}}
        <div class="summary-box">
            <span class="badge badge-pro">{{ $proStudents->count() }} Pro</span>
            <span class="badge badge-not-pro">{{ $notProStudents->count() }} Pro Değil</span>
            <span class="badge badge-total">{{ $students->count() }} Toplam</span>
        </div>

        {{-- TABLO --}}
        <table class="student-table">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>ÖĞRENCİ</th>
                    <th class="center" style="width: 120px;">DURUM</th>
                    <th class="center" style="width: 140px;">BİTİŞ TARİHİ</th>
                    <th class="center" style="width: 100px;">KALAN GÜN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students->sortByDesc(fn($s) => $s->activeMembership ? 1 : 0)->values() as $index => $student)
                    @php
                        $membership = $student->activeMembership;
                        $isPro = $membership !== null;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ mb_strtoupper($student->name, 'UTF-8') }}</td>
                        <td style="text-align: center;">
                            @if($isPro)
                                <span class="pro-badge pro-yes">PRO ★</span>
                            @else
                                <span class="pro-badge pro-no">Pro değil</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($isPro)
                                {{ $membership->expires_at->locale('tr')->isoFormat('D MMM YYYY') }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($isPro)
                                {{ $membership->remainingDays() }} gün
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($group->teacher)
            <div class="footer">
                <p>Öğretmen: {{ $group->teacher->name }}</p>
            </div>
        @endif

        <div class="footer">
            <p>© {{ date('Y') }} Rise English - Tüm Hakları Saklıdır</p>
        </div>
    </div>
</body>
</html>