<?php

namespace App\Http\Controllers\Auth;

use App\Services\SmsVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Netgsm\Otp\otp;
class SmsVerificationController extends Controller
{
    protected $smsService;

    public function __construct(SmsVerificationService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function show()
    {
        return view('auth.verify-phone');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^05[0-9]{9}$/',
        ]);

        $user = Auth::user();
        $verification = $this->smsService->sendVerificationCode($user, $request->phone);

        return redirect()->route('verification.phone.notice')
            ->with('status', 'verification-code-sent');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        $verified = $this->smsService->verify($user, $request->code);

        if (!$verified) {
            return back()->withErrors(['code' => 'Geçersiz veya süresi dolmuş doğrulama kodu.']);
        }

        return redirect()->route('dashboard')
            ->with('status', 'phone-verified');
    }
}