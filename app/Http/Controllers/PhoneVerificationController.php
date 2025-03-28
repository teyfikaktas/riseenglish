<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Netgsm\Otp\otp;

class PhoneVerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify-phone');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        if ($request->user()->verification_code == $request->code) {
            $request->user()->phone_verified_at = now();
            $request->user()->save();

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'code' => 'Doğrulama kodu hatalı.',
        ]);
    }

    public function sendVerificationCode(Request $request)
    {
        $user = $request->user();
        
        // 6 haneli rastgele doğrulama kodu oluştur
        $verificationCode = rand(100000, 999999);
        $user->verification_code = $verificationCode;
        $user->save();

        // SMS gönder
        $otpService = new otp();
        $otpService->otp([
            'message' => 'Doğrulama kodunuz: ' . $verificationCode,
            'no' => $user->phone,
            'header' => '3326062804' // Sizin başlık ID'niz
        ]);

        return back()->with('status', 'Doğrulama kodu telefonunuza gönderildi.');
    }
}