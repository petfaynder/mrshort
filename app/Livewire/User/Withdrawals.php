<?php

namespace App\Livewire\User;

use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Withdrawals extends Component
{
    use WithPagination;

    public $available_balance;
    public $total_withdrawn;
    public $withdrawal_amount;
    public $payment_method;
    public $paypal_email;
    public $iban;
    public $account_holder_name;
    public $bank_name;
    public $swift_bic;

    // Statistics
    public $stats_total_withdrawn;
    public $stats_pending_requests;
    public $stats_pending_amount;
    public $stats_completed_requests;
    public $stats_cancelled_requests;
    public $stats_average_amount;

    public $search = '';
    public $status = '';
    public $method = '';
    public $date_range = '';

    public function mount()
    {
        $user = Auth::user();
        $this->available_balance = $user->earnings;
        $this->total_withdrawn = WithdrawalRequest::where('user_id', $user->id)->where('status', 'completed')->sum('amount');

        // Pre-fill payment details from user settings if available
        if ($user->payment_method) {
            // Map saved payment method to select options (assuming values match or are similar)
            // Options in view: 'PayPal', 'Bank Transfer'
            // Saved values might be lowercase or snake_case
            $methodMap = [
                'paypal' => 'PayPal',
                'bank_transfer' => 'Bank Transfer',
                'crypto' => 'Crypto', // Assuming you might add this later
                'papara' => 'Papara', // Assuming you might add this later
            ];

            $this->payment_method = $methodMap[$user->payment_method] ?? $user->payment_method;

            if ($this->payment_method === 'PayPal') {
                $this->paypal_email = $user->payment_account;
            } elseif ($this->payment_method === 'Bank Transfer') {
                // If payment_account stores IBAN, or JSON
                // Assuming simple string for now based on previous context, but user might have put full details in string.
                // For better UX, we might need to parse or just put it in IBAN for now if it looks like one.
                $this->iban = $user->payment_account;
            }
        }

        $this->calculateStatistics();
    }

    public function calculateStatistics()
    {
        $user = Auth::user();
        $query = WithdrawalRequest::where('user_id', $user->id)->where('created_at', '>=', now()->subDays(30));

        $this->stats_total_withdrawn = (clone $query)->where('status', 'completed')->sum('amount');
        $this->stats_pending_requests = (clone $query)->where('status', 'pending')->count();
        $this->stats_pending_amount = (clone $query)->where('status', 'pending')->sum('amount');
        $this->stats_completed_requests = (clone $query)->where('status', 'completed')->count();
        $this->stats_cancelled_requests = (clone $query)->where('status', 'cancelled')->count();
        $this->stats_average_amount = (clone $query)->whereIn('status', ['completed', 'pending'])->avg('amount') ?? 0;
    }

    public function submit()
    {
        $user = Auth::user();

        $this->validate([
            'withdrawal_amount' => 'required|numeric|min:5|max:' . $this->available_balance,
            'payment_method' => 'required|in:PayPal,Bank Transfer',
            'paypal_email' => 'required_if:payment_method,PayPal|email',
            'iban' => 'required_if:payment_method,Bank Transfer',
            'account_holder_name' => 'required_if:payment_method,Bank Transfer',
            'bank_name' => 'required_if:payment_method,Bank Transfer',
        ]);

        WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => $this->withdrawal_amount,
            'method' => $this->payment_method,
            'status' => 'pending',
            'details' => $this->payment_method === 'PayPal' ? json_encode(['email' => $this->paypal_email]) : json_encode([
                'iban' => $this->iban,
                'account_holder_name' => $this->account_holder_name,
                'bank_name' => $this->bank_name,
                'swift_bic' => $this->swift_bic,
            ]),
        ]);

        $user->earnings -= $this->withdrawal_amount;
        $user->save();

        session()->flash('success', 'Withdrawal request submitted successfully.');

        return redirect()->route('user.withdrawals');
    }

    public function cancelWithdrawal($id)
    {
        $withdrawal = WithdrawalRequest::where('id', $id)->where('user_id', Auth::id())->where('status', 'pending')->first();

        if ($withdrawal) {
            $user = Auth::user();
            $user->earnings += $withdrawal->amount;
            $user->save();

            $withdrawal->status = 'cancelled';
            $withdrawal->save();
        }
    }

    public function render()
    {
        $withdrawals = WithdrawalRequest::where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->method, function ($query) {
                $query->where('method', $this->method);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.user.withdrawals', [
            'withdrawals' => $withdrawals,
        ]);
    }
}
