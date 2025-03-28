<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class StudentSettingsController extends Controller
{
    /**
     * Show the settings page
     */
    public function index()
    {
        $user = Auth::user();
        return view('student.settings.index', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        // Telefon ve veli telefon numarasını değiştirmeye izin verme - mevcut değerleri koru
        $validated['phone'] = $user->phone;
        $validated['parent_phone_number'] = $user->parent_phone_number;
        
        $user->update($validated);
        
        return redirect()->route('ogrenci.settings.index')->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
    }
    
    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mevcut şifreniz doğru değil.']);
        }
        
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('ogrenci.settings.index')->with('success', 'Şifreniz başarıyla güncellendi.');
    }
}