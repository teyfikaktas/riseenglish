<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Netgsm\Otp\otp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    /**
     * OTP gönderme işlemi
     */
    public function sendOtp(Request $request)
    {
        // Giriş yapmış kullanıcıyı al
        $user = Auth::user();
        
        // Kullanıcı yoksa veya telefon numarası yoksa hata döndür
        if (!$user || !$user->phone) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kullanıcı bulunamadı veya telefon numarası eksik.'
                ], 400);
            }
            
            return redirect()->back()->with('error', 'Kullanıcı bulunamadı veya telefon numarası eksik.');
        }
        
        // 6 haneli rastgele OTP kodu oluştur
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // OTP'yi önbelleğe kaydet (1 saatlik süre ile)
        Cache::put('otp_' . $user->id, $otp, 60 * 60);
        
        try {
            // SMS gönderme işlemi - Netgsm OTP entegrasyonu
            $otpService = new otp();
            $phoneNumber = '0' . $user->phone; // Telefon numarasının başına 0 ekle (5xx... -> 05xx...)
            
            $response = $otpService->otp([
                'message' => 'Doğrulama kodunuz: ' . $otp,
                'no' => $phoneNumber,
                'header' => '3326062804'  // SMS başlık numarası
            ]);
            
            // Geliştirme aşamasında OTP'yi loglayalım
            Log::info('User ' . $user->name . ' için OTP: ' . $otp . ', Netgsm yanıtı: ' . json_encode($response));
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Doğrulama kodu başarıyla gönderildi.',
                    'response' => $response
                ]);
            }
            
            return redirect()->back()->with('success', 'Doğrulama kodu başarıyla gönderildi.');
        } catch (\Exception $e) {
            Log::error('OTP gönderimi sırasında hata: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Doğrulama kodu gönderilirken bir hata oluştu.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Doğrulama kodu gönderilirken bir hata oluştu.');
        }
    }
    
    /**
     * OTP doğrulama işlemi
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);
        
        $user = Auth::user();
        
        // Önbellekten OTP'yi al
        $cachedOtp = Cache::get('otp_' . $user->id);
        
        // OTP eşleşiyorsa kullanıcıyı doğrula
        if ($cachedOtp && $cachedOtp === $request->otp) {
            // Kullanıcının telefon doğrulama durumunu güncelle
            $user->phone_verified = true;
            $user->phone_verified_at = now();
            $user->save();
            
            // Önbellekten OTP'yi temizle
            Cache::forget('otp_' . $user->id);
            
            return redirect()->route('home')->with('success', 'Telefon numaranız başarıyla doğrulandı!');
        }
        
        // OTP eşleşmiyorsa hata döndür
        return back()->with('error', 'Geçersiz doğrulama kodu. Lütfen tekrar deneyin.');
    }
}