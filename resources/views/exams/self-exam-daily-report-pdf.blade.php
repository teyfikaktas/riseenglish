<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Kendi Sınavım - {{ $student->name }}</title>
    <style>
        @page { margin: 24px 28px; }
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: 10.5px; color: #2c3e50; margin: 0; }

        .header { border-bottom: 2px solid #6c5ce7; padding-bottom: 8px; margin-bottom: 14px; }
        .header h1 { font-size: 17px; margin: 0 0 3px 0; color: #6c5ce7; }
        .header .student { font-size: 14px; font-weight: bold; margin: 2px 0; }
        .header .meta { font-size: 10px; color: #555; }

        .status { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; margin-left: 6px; }
        .st-done    { background: #d4efdf; color: #1e8449; }
        .st-partial { background: #fdebd0; color: #b9770e; }
        .st-none    { background: #fadbd8; color: #c0392b; }

        .summary { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin: 0 -6px 16px -6px; }
        .summary td { width: 20%; text-align: center; padding: 9px 4px; border-radius: 6px; }
        .summary .num { font-size: 17px; font-weight: bold; display: block; }
        .summary .lbl { font-size: 9px; }
        .s-total { background: #ecebfd; color: #4b3fc7; }
        .s-done  { background: #e3f7ec; color: #1e8449; }
        .s-none  { background: #fde8e8; color: #c0392b; }
        .s-cw    { background: #f4f6f8; color: #34495e; }
        .s-avg   { background: #e8f1fb; color: #1f618d; }

        table.list { width: 100%; border-collapse: collapse; }
        table.list th { background: #f4f6f8; font-size: 9.5px; text-align: left; padding: 6px; border-bottom: 1px solid #d5dbe1; }
        table.list td { padding: 6px; border-bottom: 1px solid #eceff2; vertical-align: top; }
        table.list tr:nth-child(even) td { background: #fbfcfd; }
        .c { text-align: center; }

        .rate { font-weight: bold; }
        .rate-high { color: #1e8449; }
        .rate-mid  { color: #b9770e; }
        .rate-low  { color: #c0392b; }
        .missed { color: #c0392b; font-weight: bold; }

        .footer { position: fixed; bottom: -8px; left: 0; right: 0; text-align: center; font-size: 8px; color: #999; }
    </style>
</head>
<body>

@php
    $rateClass = function ($r) {
        if ($r === null) return '';
        if ($r >= 80) return 'rate-high';
        if ($r >= 50) return 'rate-mid';
        return 'rate-low';
    };
    $statusText  = ['done' => 'TÜMÜNE GİRDİ', 'partial' => 'KISMEN GİRDİ', 'none' => 'GİRMEDİ'][$status];
    $statusClass = ['done' => 'st-done', 'partial' => 'st-partial', 'none' => 'st-none'][$status];
@endphp

<div class="header">
    <h1>Kendi Sınavım — Günlük Rapor</h1>
    <div class="student">
        {{ $student->name }}
        <span class="status {{ $statusClass }}">{{ $statusText }}</span>
    </div>
    <div class="meta">
        Tarih: <strong>{{ $date->locale('tr')->isoFormat('D MMMM YYYY, dddd') }}</strong>
        @if($teacher) &nbsp;|&nbsp; Raporu alan: <strong>{{ $teacher->name }}</strong> @endif
    </div>
</div>

<table class="summary">
    <tr>
        <td class="s-total"><span class="num">{{ $total }}</span><span class="lbl">Oluşturulan sınav</span></td>
        <td class="s-done"><span class="num">{{ $taken }}</span><span class="lbl">Girilen</span></td>
        <td class="s-none"><span class="num">{{ $notTaken }}</span><span class="lbl">Girilmeyen</span></td>
        <td class="s-cw"><span class="num">{{ $correct }} / {{ $wrong }}</span><span class="lbl">Doğru / Yanlış</span></td>
        <td class="s-avg"><span class="num">{{ $avgSuccess !== null ? '%' . $avgSuccess : '-' }}</span><span class="lbl">Ortalama başarı</span></td>
    </tr>
</table>

<table class="list">
    <thead>
        <tr>
            <th style="width:5%" class="c">#</th>
            <th style="width:9%" class="c">Saat</th>
            <th style="width:40%">Kelime Seti</th>
            <th style="width:10%" class="c">Soru Süresi</th>
            <th style="width:9%" class="c">Doğru</th>
            <th style="width:9%" class="c">Yanlış</th>
            <th style="width:18%" class="c">Başarı</th>
        </tr>
    </thead>
    <tbody>
    @foreach($rows as $i => $r)
        <tr>
            <td class="c">{{ $i + 1 }}</td>
            <td class="c">{{ $r['time'] }}</td>
            <td>{{ $r['sets'] ?: '-' }}</td>
            <td class="c">{{ $r['time_per_question'] }} sn</td>
            @if($r['done'])
                <td class="c">{{ $r['correct'] }}</td>
                <td class="c">{{ $r['wrong'] }}</td>
                <td class="c"><span class="rate {{ $rateClass($r['success_rate']) }}">%{{ $r['success_rate'] }}</span></td>
            @else
                <td class="c">-</td>
                <td class="c">-</td>
                <td class="c"><span class="missed">Girmedi</span></td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    Rise English • Oluşturulma: {{ now()->format('d.m.Y H:i') }}
</div>

</body>
</html>