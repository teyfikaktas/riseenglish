<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * İletişim mesajlarını listele
     */
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * İletişim mesajı detayını göster
     */
    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * İletişim mesajını okundu olarak işaretle
     */
    public function markAsRead(Contact $contact)
    {
        $contact->is_read = true;
        $contact->save();

        return redirect()->back()->with('success', 'Mesaj okundu olarak işaretlendi.');
    }

    /**
     * İletişim mesajını okunmadı olarak işaretle
     */
    public function markAsUnread(Contact $contact)
    {
        $contact->is_read = false;
        $contact->save();

        return redirect()->back()->with('success', 'Mesaj okunmadı olarak işaretlendi.');
    }

    /**
     * İletişim mesajını sil
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')->with('success', 'Mesaj başarıyla silindi.');
    }
}