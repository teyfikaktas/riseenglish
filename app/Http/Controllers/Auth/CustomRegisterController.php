<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Log;

class CustomRegisterController extends Controller
{
    public function create()
    {
        // Eğer kullanıcı zaten giriş yapmışsa, ana sayfaya yönlendir
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^5[0-9]{9}$/|unique:users', // Başında 5 olan, sonrası 9 rakam
            'password' => 'required|string|confirmed|min:8',
            'terms' => 'required',
        ], [
            'phone.regex' => 'Telefon numarası 5xx xxx xx xx formatında olmalıdır.',
            'phone.unique' => 'Bu telefon numarası zaten kullanılmaktadır.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'phone_verified' => false, // Telefon doğrulanmadı olarak ayarla
        ]);

        // Kullanıcıya öğrenci rolünü ver
        $user->assignRole('ogrenci');

        event(new Registered($user));

        Auth::login($user);

        // Otomatik OTP gönderme işlemi
        try {
            // OtpController'ı kullanarak OTP gönder
            $otpRequest = new Request();
            $otpRequest->merge([
                'message' => 'Doğrulama kodunuz: [otomatik oluşturulacak]',
                'no' => '0' . $user->phone
            ]);
            
            app(OtpController::class)->sendOtp($otpRequest);
            
            Log::info('OTP gönderildi: ' . $user->phone);
        } catch (\Exception $e) {
            Log::error('OTP gönderimi sırasında hata: ' . $e->getMessage());
        }

        // Telefon doğrulama sayfasına yönlendir
        return redirect()->route('verification.phone.notice')
            ->with('success', 'Kayıt başarılı! Telefon numaranızı doğrulamanız gerekiyor.');
    }
}