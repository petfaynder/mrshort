<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Settings extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $name;
    public $email;
    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirmation;
    public $paymentMethod;
    public $paymentDetails;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->paymentMethod = $user->payment_method;
        $this->paymentDetails = $user->payment_details;
    }

    public function updateProfileInformation()
    {
        $user = Auth::user();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->forceFill([
            'name' => $this->name,
            'email' => $this->email,
        ])->save();

        session()->flash('success', 'Profil bilgileri başarıyla güncellendi.');
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => ['required', 'string', 'current_password'],
            'newPassword' => ['required', 'string', 'min:8', 'confirmed', 'different:currentPassword'],
        ]);

        Auth::user()->update([
            'password' => bcrypt($this->newPassword),
        ]);

        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
        session()->flash('success', 'Şifre başarıyla güncellendi.');
    }

    public function updatePaymentSettings()
    {
        $this->validate([
            'paymentMethod' => ['nullable', 'string', 'max:255'],
            'paymentDetails' => ['nullable', 'string', 'max:255'],
        ]);

        Auth::user()->update([
            'payment_method' => $this->paymentMethod,
            'payment_details' => $this->paymentDetails,
        ]);

        session()->flash('success', 'Ödeme ayarları başarıyla güncellendi.');
    }

    public function render()
    {
        return view('livewire.user.settings');
    }
}