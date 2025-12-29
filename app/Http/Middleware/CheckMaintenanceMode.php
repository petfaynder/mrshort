<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Paths that should be excluded from maintenance mode
     */
    protected array $except = [
        'admin',
        'admin/*',
        'livewire/*',
        'filament/*',
        'login',
        'logout',
        'register',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip maintenance check for excluded paths FIRST
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }
        
        // Check if maintenance mode is enabled
        $setting = SiteSetting::where('key', 'maintenance_mode')->first();
        $maintenanceMode = $setting ? filter_var($setting->value, FILTER_VALIDATE_BOOLEAN) : false;
        
        if ($maintenanceMode) {
            // Allow authenticated admins
            if (auth()->check() && auth()->user()->is_admin) {
                return $next($request);
            }
            
            // Get maintenance message
            $message = SiteSetting::get('maintenance_message', 'We are currently performing maintenance. Please check back soon.');
            
            // Return maintenance view
            return response()->view('errors.maintenance', [
                'message' => $message,
                'siteName' => SiteSetting::get('site_name', config('app.name')),
            ], 503);
        }
        
        return $next($request);
    }
}
