<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google's OAuth page
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists with this Google ID or email
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
                
                // If user has no avatar, update with Google avatar
                if (!$user->avatar && $googleUser->getAvatar()) {
                    $user->avatar = $googleUser->getAvatar();
                    $user->save();
                }

                Auth::login($user, true); // Remember the user
                
                return redirect()->route('dashboard');
            }

            // Create new user
            $user = new User();
            $user->name = $googleUser->getName();
            $user->email = $googleUser->getEmail();
            $user->google_id = $googleUser->getId();
            $user->avatar = $googleUser->getAvatar();
            $user->password = Hash::make(Str::random(24));
            $user->email_verified_at = now();
            $user->save();

            Auth::login($user, true); // Remember the user

            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            // Log the error for debugging (detailed info stays in logs only)
            Log::error('Google OAuth Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Show generic message to user - don't expose internal error details
            return redirect()->route('login')
                ->with('error', 'Google authentication failed. Please try again or contact support.');
        }
    }
}
