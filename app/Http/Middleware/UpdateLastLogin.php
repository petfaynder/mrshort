<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\StreakService;

class UpdateLastLogin
{
    /**
     * Handle an incoming request.
     * Updates the user's last_login_at timestamp and streak on each request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            // Only update if last_login_at is null or more than 1 hour ago
            // This prevents excessive database writes on every request
            $user = $request->user();
            $lastLogin = $user->last_login_at;
            
            if ($lastLogin === null || $lastLogin->diffInHours(now()) >= 1) {
                $user->last_login_at = now();
                $user->save();

                // Update streak
                try {
                    $streakService = new StreakService();
                    $result = $streakService->updateStreak($user);

                    // If milestones were claimed, store them in session for display
                    if (!empty($result['milestones'])) {
                        session()->put('claimed_milestones', $result['milestones']);
                    }
                } catch (\Exception $e) {
                    // Log error but don't break the request
                    \Log::error('Streak update failed: ' . $e->getMessage());
                }
            }
        }
        
        return $next($request);
    }
}

