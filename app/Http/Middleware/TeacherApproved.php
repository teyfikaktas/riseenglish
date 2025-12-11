<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherApproved
{
    public function handle(Request $request, Closure $next)
    {
        // Yönetici ve öğretmenler kontrolden muaf
        if (Auth::user()->hasRole('yonetici') || Auth::user()->hasRole('ogretmen')) {
            return $next($request);
        }
        
        // Öğrenci onaysızsa
        if (!Auth::user()->teacher_approved) {
            return redirect()->route('waiting-approval')
                ->with('warning', 'Hesabınız öğretmen onayı bekliyor.');
        }
        
        return $next($request);
    }
}