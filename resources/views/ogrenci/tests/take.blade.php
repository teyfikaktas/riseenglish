{{-- resources/views/ogrenci/tests/take.blade.php --}}

@extends('layouts.app')

@section('content')
    <livewire:test-taking :test-slug="$test->slug" />
@endsection

@push('scripts')
<script>
    // Sayfadan çıkarken uyarı
    let testInProgress = false;
    
    document.addEventListener('livewire:navigated', () => {
        window.addEventListener('test-started', () => {
            testInProgress = true;
        });
        
        window.addEventListener('test-completed', () => {
            testInProgress = false;
        });
        
        window.addEventListener('time-up', () => {
            testInProgress = false;
            alert('Süre doldu! Test otomatik olarak tamamlandı.');
        });
    });
    
    window.addEventListener('beforeunload', function (e) {
        if (testInProgress) {
            e.preventDefault();
            e.returnValue = 'Test devam ediyor. Sayfadan çıkmak istediğinizden emin misiniz?';
        }
    });
</script>
@endpush