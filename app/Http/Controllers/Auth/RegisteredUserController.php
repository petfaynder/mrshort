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
use Illuminate\Support\Str; // Add this line

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
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::min(8)], // Explicitly set min length to 8
            'referral_code' => ['nullable', 'string', 'exists:users,referral_code'], // Add validation for referral code
        ]);

        // Find the referrer user if a referral code is provided
        $referrer = null;
        if ($request->filled('referral_code')) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
        }

        // Generate a unique referral code for the new user
        $referralCode = Str::random(8); // Generate a random code
        while (User::where('referral_code', $referralCode)->exists()) {
            $referralCode = Str::random(8); // Regenerate if it already exists
        }


        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name, // Combine first and last name
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referred_by_user_id' => $referrer ? $referrer->id : null, // Set referred_by_user_id
            'referral_code' => $referralCode, // Set the generated referral code
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))->with('status', 'Successfully registered! Welcome to our platform.');
    }
}
