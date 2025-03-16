<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

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
            'password' => 'required|string|confirmed|min:8',
            'terms' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Başarılı bir şekilde kayıt olduktan sonra ana sayfaya yönlendirme
        return redirect('/')->with('success', 'Kayıt olundu!');
    }
}