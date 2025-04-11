<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class SmsPasswordResetController extends Controller
{
    /**
     * Şifremi unuttum formunu göster
     */
    public function showForgotForm()
    {
        return view('auth.passwords.sms-email');
    }

    /**
     * Şifre sıfırlama linki gönder
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
        ]);

        // Kullanıcı bilgilerini al
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return back()
                ->withErrors(['phone' => 'Bu telefon numarasına kayıtlı kullanıcı bulunamadı.']);
        }

        // Eski token varsa sil
        DB::table('password_reset_tokens')->where('phone', $request->phone)->delete();

        // Yeni token oluştur
        $token = Str::random(30);
        
        // Token'ı veritabanına kaydet
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email ?? 'no-email', // Email alanı için varsayılan değer
            'phone' => $request->phone,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Reset URL'ini oluştur (telefon numarası olmadan)
        $resetUrl = url('reset-password-sms/' . $token);
        
        // Mesajı kısalt ve SMS gönder
        $smsContent = "Rise English - Sifrenizi sifirlamak icin: " . $resetUrl;
        
        // SMS içeriğini debug için logla
        Log::info("SMS içeriği boyutu: " . strlen($smsContent) . " karakter");
        Log::info("SMS içeriği: " . $smsContent);
        
        // SMS gönder
        $smsResult = SmsService::sendSms($request->phone, $smsContent);
        
        Log::info("Şifre sıfırlama SMS sonucu - Telefon: {$request->phone}, Sonuç: " . json_encode($smsResult));
        
        // Sonucu kontrol et
        if (isset($smsResult['success']) && $smsResult['success']) {
            return back()->with('status', 'Şifre sıfırlama linki telefonunuza gönderildi.');
        } else {
            // Debug için SMS sonucunu loglama
            Log::error("SMS gönderimi başarısız: " . json_encode($smsResult));
            
            // Hatayı göster
            return back()->with('error', 'SMS gönderilirken bir sorun oluştu: ' . ($smsResult['message'] ?? 'Bilinmeyen hata'));
        }
    }

    /**
     * Şifre sıfırlama formunu göster
     */
    public function showResetForm(Request $request, $token)
    {
        // Token'ı veritabanında kontrol et
        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();
        
        if (!$tokenData) {
            return redirect()->route('password.sms.request')
                ->withErrors(['email' => 'Geçersiz şifre sıfırlama linki.']);
        }
        
        return view('auth.passwords.sms-reset', [
            'token' => $token,
            'phone' => $tokenData->phone // Telefon numarasını token kaydından al
        ]);
    }

    /**
     * Şifreyi sıfırla
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        // Token doğrulama ve telefonu al
        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->first();

        if (!$tokenData) {
            return back()->withErrors(['token' => ['Geçersiz token!']]);
        }
        
        $phone = $tokenData->phone;

        // Token süresi kontrolü (1 saat)
        if (Carbon::parse($tokenData->created_at)->addHour()->isPast()) {
            DB::table('password_reset_tokens')->where('token', $request->token)->delete();
            return back()->withErrors(['token' => ['Şifre sıfırlama linkinin süresi dolmuş!']]);
        }

        // Kullanıcıyı güncelle
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return back()->withErrors(['token' => ['Kullanıcı bulunamadı!']]);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();

        // Token'ı sil
        DB::table('password_reset_tokens')->where('token', $request->token)->delete();

        // Şifre değiştirildi SMS'i gönder
// Kullanıcı bilgilerini al
        $user = User::where('phone', $phone)->first();

        // Şifre değiştirildi SMS'i gönder (kullanıcı adıyla)
        $smsContent = "Sayın {$user->name}, Risenglish - Sifreniz basariyla degistirildi. Bu işlem size ait değil ise hemen bizimle iletişime geçin.";
        $smsResult = SmsService::sendSms($phone, $smsContent);
        
        Log::info("Şifre değişikliği bilgilendirme SMS'i - Telefon: {$phone}, Sonuç: " . json_encode($smsResult));

        return redirect()->route('login')->with('status', 'Şifreniz başarıyla değiştirildi! Yeni şifrenizle giriş yapabilirsiniz.');
    }
}