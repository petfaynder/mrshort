<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if registration is closed
        if (setting('close_registration', false)) {
            return redirect()->route('login')
                ->with('error', 'Registration is currently closed.');
        }
        
        // Verify captcha if enabled for register form
        if (setting('captcha_enabled', false) && setting('captcha_on_register', true)) {
            $captchaService = app(\App\Services\CaptchaService::class);
            $tokenField = $captchaService->getTokenFieldName();
            $token = $request->input($tokenField);
            
            if (!$captchaService->verify($token)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['captcha' => 'Captcha verification failed. Please try again.']);
            }
        }
        
        // Get reserved usernames from settings
        $reservedUsernames = array_filter(array_map('trim', explode(',', setting('reserved_usernames', ''))));
        
        $request->validate([
            'first_name' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) use ($reservedUsernames) {
                if (in_array(strtolower($value), array_map('strtolower', $reservedUsernames))) {
                    $fail('This name is not allowed.');
                }
            }],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::min(8)],
            'referral_code' => ['nullable', 'string', 'exists:users,referral_code'],
            'ref' => ['nullable', 'string', 'exists:users,referral_code'],
        ]);

        // Find the referrer user if a referral code is provided
        $referrer = null;
        $incomingReferralCode = $request->filled('referral_code') ? $request->referral_code : ($request->filled('ref') ? $request->ref : null);
        
        if ($incomingReferralCode) {
            $referrer = User::where('referral_code', $incomingReferralCode)->first();
        }

        // Generate a unique referral code for the new user
        $newUserReferralCode = Str::random(8);
        while (User::where('referral_code', $newUserReferralCode)->exists()) {
            $newUserReferralCode = Str::random(8);
        }
        
        // Get signup bonus from settings
        $signupBonus = (float) setting('signup_bonus', 0);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referred_by_user_id' => $referrer ? $referrer->id : null,
            'referral_code' => $newUserReferralCode,
            'earnings' => $signupBonus, // Signup bonus from settings
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))->with('status', 'Successfully registered! Welcome to our platform.');
    }
}
