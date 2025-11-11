<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PaymentSettings extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $paymentMethod = '';
    public $paymentAccount = '';

    protected $rules = [
        'paymentMethod' => 'required|string|max:255',
        'paymentAccount' => 'required|string',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function render()
    {
        return view('livewire.user.payment-settings');
    }

    private function loadSettings()
    {
        $user = Auth::user();
        $this->paymentMethod = $user->payment_method ?? '';
        $this->paymentAccount = $user->payment_account ?? '';
    }

    public function saveSettings()
    {
        $this->validate();

        $user = Auth::user();
        $user->payment_method = $this->paymentMethod;
        $user->payment_account = $this->paymentAccount;
        $user->save();

        session()->flash('message', 'Ödeme ayarları başarıyla kaydedildi.');
    }
}
