<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PaymentSummary extends Component
{
    public function render()
    {
        $user = Auth::user();
        $balance = $user->earnings ?? 0;
        $threshold = 50.00; // Example threshold
        $progress = min(($balance / $threshold) * 100, 100);
        
        $lastPayment = $user->withdrawalRequests()
            ->where('status', 'paid') // Assuming 'paid' status exists
            ->latest('updated_at')
            ->first();

        return view('livewire.user.payment-summary', [
            'balance' => $balance,
            'threshold' => $threshold,
            'progress' => $progress,
            'lastPaymentDate' => $lastPayment ? $lastPayment->updated_at->format('F d, Y') : 'N/A',
            'nextPaymentDate' => 'Upon Request', // Logic depends on payout schedule
        ]);
    }
}
