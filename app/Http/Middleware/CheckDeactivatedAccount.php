<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDeactivatedAccount
{
    /**
     * Routes that deactivated users CAN access
     */
    protected array $allowedRoutes = [
        'user.contact',
        'logout',
        'dashboard',
        'livewire.update',
        'livewire.upload-file',
        'livewire.preview-file',
        'admin.stop-impersonation', // Allow admins to return to admin panel
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return $next($request);
        }
        
        // If admin is impersonating, allow all requests
        if (session()->has('impersonating_from_admin_id')) {
            return $next($request);
        }
        
        // Check if user is deactivated
        if ($user->status === 'deactivated') {
            $currentRoute = $request->route()?->getName();
            
            // Allow access to specific routes
            if ($currentRoute && in_array($currentRoute, $this->allowedRoutes)) {
                return $next($request);
            }
            
            // Allow all Livewire requests
            if ($request->is('livewire/*')) {
                return $next($request);
            }
            
            // Redirect to dashboard (where modal will show)
            return redirect()->route('dashboard');
        }
        
        // Check if user is banned
        if ($user->status === 'banned') {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect('/')->with('error', 'Your account has been permanently banned.');
        }
        
        return $next($request);
    }
}
