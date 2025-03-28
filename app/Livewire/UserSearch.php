<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Netgsm\Otp\otp;
use App\Services\SmsService;

class UserSearch extends Component
{
    public $search = '';
    public $searchResults = [];
    public $selectedUser = null;
    public $selectedUserId = null;
    public $message = '';
    public $charCount = 0;

    protected $rules = [
        'selectedUserId' => 'required',
        'message' => 'required|max:160',
    ];
// Telefon numarası temizleme ve formatlandırma fonksiyonu
private function formatPhoneNumber($phoneNumber) 
{
    // Önce tüm özel karakterleri, boşlukları temizle
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
    
    // Uluslararası formatı kontrol et (+90 veya 90 ile başlayanlar)
    if (substr($phoneNumber, 0, 2) === '90') {
        // Başındaki 90'ı kaldır
        $phoneNumber = substr($phoneNumber, 2);
    } else if (strlen($phoneNumber) > 10 && substr($phoneNumber, 0, 3) === '905') {
        // +905 veya 905 ile başlayan numaralar için baştan 90'ı kaldır
        $phoneNumber = substr($phoneNumber, 2);
    }
    
    // Başında 0 varsa kaldır
    if (substr($phoneNumber, 0, 1) === '0') {
        $phoneNumber = substr($phoneNumber, 1);
    }
    
    // Numaranın 10 haneli olduğunu doğrula (Türkiye için)
    if (strlen($phoneNumber) !== 10) {
        \Illuminate\Support\Facades\Log::warning('Geçersiz telefon numarası format: ' . $phoneNumber);
    }
    
    return $phoneNumber;
}
    public function updated($name, $value)
    {
        if ($name === 'search') {
            if (strlen($this->search) >= 3) {
                $this->searchResults = User::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->limit(10)
                    ->get()
                    ->toArray();
            } else {
                $this->searchResults = [];
            }
        }
        
        if ($name === 'message') {
            $this->charCount = strlen($this->message);
        }
    }

    public function selectUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->selectedUser = $user;
            $this->selectedUserId = $user->id;
            $this->search = $user->name;
            $this->searchResults = [];
        }
    }

    public function clearSelection()
    {
        $this->selectedUser = null;
        $this->selectedUserId = null;
        $this->search = '';
    }

    public function sendSms()
    {
        try {
            $this->validate();
            
            if (!$this->selectedUser || !$this->selectedUserId) {
                session()->flash('error', 'Lütfen bir kullanıcı seçin.');
                return;
            }
            
            // SmsService kullanarak SMS gönderimi
            $result = SmsService::sendSms(
                $this->selectedUser->phone, 
                $this->message
            );
            
            if ($result['success']) {
                session()->flash('message', $this->selectedUser->name . ' kullanıcısına SMS başarıyla gönderildi!');
                $this->reset(['message', 'selectedUser', 'selectedUserId', 'search', 'charCount']);
            } else {
                session()->flash('error', 'SMS gönderilirken bir hata oluştu: ' . $result['message']);
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Beklenmeyen bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.user-search');
    }
}