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
    public $paymentAccount; // Stores JSON or string depending on method
    
    // Dynamic Payment Fields
    public $paypalEmail;
    public $iban;
    public $accountHolderName;
    public $bankName;
    
    public $themePreference;
    public $allowAnalytics;
    public $allowPersonalizedAds;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->paymentMethod = $user->payment_method;
        
        // Parse payment account details
        if ($this->paymentMethod === 'paypal') {
            $this->paypalEmail = $user->payment_account;
        } elseif ($this->paymentMethod === 'bank_transfer') {
            $details = json_decode($user->payment_account, true);
            $this->iban = $details['iban'] ?? '';
            $this->accountHolderName = $details['account_holder_name'] ?? '';
            $this->bankName = $details['bank_name'] ?? '';
        }

        $this->themePreference = $user->theme_preference ?? 'light';
        $this->allowAnalytics = (bool) $user->allow_analytics;
        $this->allowPersonalizedAds = (bool) $user->allow_personalized_ads;
    }

    public function getSessionsProperty()
    {
        if (config('session.driver') === 'database') {
            return \Illuminate\Support\Facades\DB::table('sessions')
                ->where('user_id', Auth::id())
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function ($session) {
                    $agent = $this->createAgent($session);
                    return (object) [
                        'agent_platform' => $agent->platform(),
                        'agent_browser' => $agent->browser(),
                        'is_desktop' => $agent->isDesktop(),
                        'is_tablet' => $agent->isTablet(),
                        'ip_address' => $session->ip_address,
                        'is_current_device' => $session->id === request()->session()->getId(),
                        'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    ];
                });
        }
        return [];
    }

    protected function createAgent($session)
    {
        return tap(new \Jenssegers\Agent\Agent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
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
        $rules = [
            'paymentMethod' => ['required', 'string', 'max:255'],
        ];

        if ($this->paymentMethod === 'paypal') {
            $rules['paypalEmail'] = ['required', 'email'];
        } elseif ($this->paymentMethod === 'bank_transfer') {
            $rules['iban'] = ['required', 'string'];
            $rules['accountHolderName'] = ['required', 'string'];
            $rules['bankName'] = ['required', 'string'];
        }

        $this->validate($rules);

        $paymentAccountValue = null;

        if ($this->paymentMethod === 'paypal') {
            $paymentAccountValue = $this->paypalEmail;
        } elseif ($this->paymentMethod === 'bank_transfer') {
            $paymentAccountValue = json_encode([
                'iban' => $this->iban,
                'account_holder_name' => $this->accountHolderName,
                'bank_name' => $this->bankName,
            ]);
        }

        Auth::user()->update([
            'payment_method' => $this->paymentMethod,
            'payment_account' => $paymentAccountValue,
        ]);

        $this->dispatch('settings-updated', message: 'Payment settings updated successfully.');
    }

    public function updateThemePreference($theme)
    {
        if (in_array($theme, ['light', 'dark'])) {
            $this->themePreference = $theme;
            Auth::user()->update(['theme_preference' => $theme]);
            $this->dispatch('theme-changed', theme: $theme);
        }
    }

    public function updatePrivacySettings()
    {
        Auth::user()->update([
            'allow_analytics' => $this->allowAnalytics,
            'allow_personalized_ads' => $this->allowPersonalizedAds,
        ]);

        $this->dispatch('settings-updated', message: 'Privacy settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.user.settings');
    }
}