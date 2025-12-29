<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifyEmailIfEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if email verification is enabled in settings
        if (!setting('email_account_activation', true)) {
            return $next($request);
        }
        
        // If email verification is enabled, check if user has verified email
        if ($request->user() &&
            $request->user() instanceof MustVerifyEmail &&
            !$request->user()->hasVerifiedEmail()) {
            return $request->expectsJson()
                    ? abort(403, 'Your email address is not verified.')
                    : redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
