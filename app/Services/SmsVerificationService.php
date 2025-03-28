<?php

namespace App\Services;

use App\Models\SmsVerification;
use App\Models\User;
use App\Netgsm\Otp\otp;
use Carbon\Carbon;

class SmsVerificationService
{
    protected $otpService;

    public function __construct()
    {
        $this->otpService = new otp();
    }

    public function generateCode(): string
    {
        return rand(100000, 999999);
    }

    public function sendVerificationCode(User $user, string $phone)
    {
        // Eski kodları temizle
        SmsVerification::where('user_id', $user->id)
            ->where('verified', false)
            ->delete();

        // Yeni kod oluştur
        $code = $this->generateCode();
        $expires_at = Carbon::now()->addMinutes(15);

        // Veritabanına kaydet
        $verification = SmsVerification::create([
            'user_id' => $user->id,
            'phone' => $phone,
            'code' => $code,
            'expires_at' => $expires_at,
        ]);

        // SMS gönder
        $message = "Doğrulama kodunuz: {$code}";
        $response = $this->otpService->otp([
            'message' => $message,
            'no' => $phone,
            'header' => '3326062804' // Başlık bilginizi buraya yazın
        ]);

        return $verification;
    }

    public function verify(User $user, string $code): bool
    {
        $verification = SmsVerification::where('user_id', $user->id)
            ->where('code', $code)
            ->where('verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verification) {
            return false;
        }

        // Doğrulama işlemleri
        $verification->verified = true;
        $verification->save();

        // User modelini güncelle
        $user->phone = $verification->phone;
        $user->phone_verified = true;
        $user->save();

        return true;
    }
}