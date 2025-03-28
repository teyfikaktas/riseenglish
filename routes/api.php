<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;

// API route for OTP SMS without middleware
Route::post('/send-otp', [OtpController::class, 'sendOtp']);