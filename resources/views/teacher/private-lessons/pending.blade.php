@extends('layouts.app') 
@section('content')
    <h1>Bekleyen Özel Ders Talepleri</h1>
    <table>
        <thead>
            <tr>
                <th>Ders Adı</th>
                <th>Öğrenci</th>
                <th>Talep Tarihi</th>
                <th>Başlangıç Saati</th>
                <th>Bitiş Saati</th>
                <th>Durum</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingSessions as $session)
                <tr>
                    <td>{{ $session->privateLesson->name ?? 'Ders Bulunamadı' }}</td>
                    <td>{{ $session->student ? $session->student->name : 'Öğrenci Yok' }}</td>
                    <td>{{ $session->start_date }}</td>
                    <td>{{ $session->start_time }}</td>
                    <td>{{ $session->end_time }}</td>
                    <td>{{ $session->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Bekleyen ders talebi bulunamadı.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
