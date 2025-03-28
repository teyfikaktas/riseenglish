<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * İletişim sayfasını göster
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * İletişim formunu işle
     */
    public function send(Request $request)
    {
        // Form verilerini doğrula
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Veritabanına kayıt işlemi
        Contact::create($validated);
        
        // Başarılı mesajı dön
        return back()->with('success', 'Mesajınız başarıyla gönderildi. En kısa sürede sizinle iletişime geçeceğiz.');
    }
}