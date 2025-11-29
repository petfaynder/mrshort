<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // For referrals

class RecentActivity extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        // Fetch recent activities
        
        // 1. Link Creations
        $links = $user->links()->latest()->take(5)->get()->map(function ($link) {
            return [
                'type' => 'link',
                'description' => "New link created: " . $link->code,
                'created_at' => $link->created_at,
                'icon' => 'add_link',
                'color_class' => 'bg-purple-100 dark:bg-purple-900/50 text-purple-500'
            ];
        });

        // 2. Withdrawals
        $withdrawals = $user->withdrawalRequests()->latest()->take(5)->get()->map(function ($withdrawal) {
            return [
                'type' => 'withdrawal',
                'description' => "Withdrawal request ({$withdrawal->amount}) status: {$withdrawal->status}",
                'created_at' => $withdrawal->updated_at, // Use updated_at for status changes
                'icon' => 'payments',
                'color_class' => 'bg-green-100 dark:bg-green-900/50 text-green-500'
            ];
        });

        // 3. Referrals
        $referrals = User::where('referred_by_user_id', $user->id)->latest()->take(5)->get()->map(function ($referral) {
            return [
                'type' => 'referral',
                'description' => "New referral signup: " . $referral->name,
                'created_at' => $referral->created_at,
                'icon' => 'person_add',
                'color_class' => 'bg-blue-100 dark:bg-blue-900/50 text-primary'
            ];
        });

        // Merge and sort
        $activities = $links->concat($withdrawals)->concat($referrals)
            ->sortByDesc('created_at')
            ->take(5);

        return view('livewire.user.recent-activity', [
            'activities' => $activities
        ]);
    }
}
