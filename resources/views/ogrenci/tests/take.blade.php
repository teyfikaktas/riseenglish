{{-- resources/views/ogrenci/tests/take.blade.php --}}

@extends('layouts.app')

@section('content')
    <livewire:test-taking :test-slug="$test->slug" />
@endsection

@push('scripts')
<script>
    console.log('🎯 Ana sayfa script yüklendi');
    
    // Sayfadan çıkarken uyarı
    let testInProgress = false;
    let securitySystemActive = false;
    
    document.addEventListener('livewire:navigated', () => {
        console.log('📡 Livewire navigated event');
        
        window.addEventListener('test-started', () => {
            console.log('🚀 Test started event alındı');
            testInProgress = true;
            securitySystemActive = true;
        });
        
        window.addEventListener('test-completed', () => {
            console.log('✅ Test completed event alındı');
            testInProgress = false;
            securitySystemActive = false;
        });
        
        window.addEventListener('time-up', () => {
            console.log('⏰ Time up event alındı');
            testInProgress = false;
            securitySystemActive = false;
            alert('Süre doldu! Test otomatik olarak tamamlandı.');
        });
    });
    
    // Global güvenlik sistemi
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔧 Global güvenlik sistemi başlatılıyor...');
        
        let violationCount = 0;
        let maxViolations = 2;
        let hasWarningModalShown = false;
        
        // Global değişkenler
        window.globalSecuritySystem = {
            violationCount: 0,
            maxViolations: 2,
            isActive: false,
            handleViolation: function(reason) {
                console.log('🚨 Global güvenlik ihlali:', reason);
                
                if (!this.isActive) {
                    console.log('❌ Güvenlik sistemi aktif değil');
                    return;
                }
                
                this.violationCount++;
                
                // Livewire event gönder
                if (window.Livewire) {
                    console.log('📤 Livewire event gönderiliyor (global):', reason);
                    try {
                        window.Livewire.dispatch('handleSecurityViolation', { reason: reason });
                        console.log('✅ Event başarıyla gönderildi (global)');
                    } catch (error) {
                        console.error('❌ Event gönderme hatası (global):', error);
                    }
                } else {
                    console.error('❌ Livewire bulunamadı (global)!');
                }
                
                if (this.violationCount >= this.maxViolations) {
                    console.log('🔴 Maximum ihlal, test sonlandırılıyor (global)');
                    this.endExam(reason);
                }
            },
            endExam: function(reason) {
                this.isActive = false;
                alert(`Sınav sonlandırıldı: ${reason}`);
                
                if (window.Livewire) {
                    window.Livewire.dispatch('forceCompleteTest', { reason: reason });
                }
            },
            activate: function() {
                console.log('🔒 Global güvenlik sistemi aktifleştirildi');
                this.isActive = true;
                this.violationCount = 0;
            },
            deactivate: function() {
                console.log('🔓 Global güvenlik sistemi deaktifleştirildi');
                this.isActive = false;
            }
        };
        
        // Test için 5 saniye sonra bir ihlal oluştur
        setTimeout(() => {
            console.log('🧪 Test ihlali tetikleniyor (global)...');
            window.globalSecuritySystem.handleViolation('Test İhlali - Global System');
        }, 5000);
        
        // Visibility change - Sekme değiştirme
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && window.globalSecuritySystem.isActive) {
                window.globalSecuritySystem.handleViolation('Sekme değiştirme/Tarayıcı minimize (Global)');
            }
        });
        
        // Focus kaybı
        window.addEventListener('blur', () => {
            if (window.globalSecuritySystem.isActive) {
                window.globalSecuritySystem.handleViolation('Pencere fokus kaybı (Global)');
            }
        });
        
        // Kısayol tuşları
        document.addEventListener('keydown', (e) => {
            if (!window.globalSecuritySystem.isActive) return;
            
            // F12
            if (e.key === 'F12') {
                e.preventDefault();
                window.globalSecuritySystem.handleViolation('F12 Developer Tools (Global)');
                return false;
            }
            
            // Ctrl+Shift+I
            if (e.ctrlKey && e.shiftKey && e.key === 'I') {
                e.preventDefault();
                window.globalSecuritySystem.handleViolation('Ctrl+Shift+I Developer Tools (Global)');
                return false;
            }
            
            // Alt+Tab
            if (e.altKey && e.key === 'Tab') {
                e.preventDefault();
                window.globalSecuritySystem.handleViolation('Alt+Tab (Global)');
                return false;
            }
        });
        
        console.log('✅ Global güvenlik sistemi hazır');
    });
    
    // Livewire init
    document.addEventListener('livewire:init', () => {
        console.log('🔗 Livewire init (ana sayfa)');
        
        Livewire.on('test-started', (event) => {
            console.log('🚀 Test başlatıldı (ana sayfa event)', event);
            window.globalSecuritySystem.activate();
        });

        Livewire.on('security-violation-logged', (event) => {
            console.log('📝 Güvenlik ihlali kaydedildi (ana sayfa):', event);
        });

        Livewire.on('test-terminated-security', (event) => {
            console.log('🔴 Test güvenlik nedeniyle sonlandırıldı (ana sayfa):', event);
            window.globalSecuritySystem.deactivate();
        });
        
        Livewire.on('test-completed', (event) => {
            console.log('✅ Test tamamlandı (ana sayfa):', event);
            window.globalSecuritySystem.deactivate();
        });
    });
    
    window.addEventListener('beforeunload', function (e) {
        if (testInProgress) {
            e.preventDefault();
            e.returnValue = 'Test devam ediyor. Sayfadan çıkmak istediğinizden emin misiniz?';
        }
    });
    
    console.log('✅ Ana sayfa script tamamlandı');
</script>
@endpush