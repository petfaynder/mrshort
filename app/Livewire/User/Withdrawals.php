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
    public $crypto_wallet;  // Crypto wallet address
    public $papara_number; // Papara account number

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
        // Check if withdrawals are enabled
        if (!setting('withdrawals_enabled', true)) {
            session()->flash('error', 'Withdrawals are currently disabled.');
            return;
        }
        
        $user = Auth::user();
        
        // Get minimum withdrawal amount from settings
        $minWithdrawal = setting('min_withdrawal_amount', 5);

        $this->validate([
            'withdrawal_amount'  => 'required|numeric|min:' . $minWithdrawal . '|max:' . $this->available_balance,
            'payment_method'     => 'required|in:PayPal,Bank Transfer,Crypto,Papara',
            'paypal_email'       => 'required_if:payment_method,PayPal|nullable|email',
            'iban'               => 'required_if:payment_method,Bank Transfer|nullable|string',
            'account_holder_name'=> 'required_if:payment_method,Bank Transfer|nullable|string',
            'bank_name'          => 'required_if:payment_method,Bank Transfer|nullable|string',
            'crypto_wallet'      => 'required_if:payment_method,Crypto|nullable|string',
            'papara_number'      => 'required_if:payment_method,Papara|nullable|string',
        ]);

        // Build payment details based on method
        $paymentDetails = null;
        if ($this->payment_method === 'PayPal') {
            $paymentDetails = ['email' => $this->paypal_email];
        } elseif ($this->payment_method === 'Bank Transfer') {
            $paymentDetails = [
                'iban'           => $this->iban,
                'account_holder' => $this->account_holder_name,
                'bank_name'      => $this->bank_name,
                'swift_bic'      => $this->swift_bic,
            ];
        } elseif ($this->payment_method === 'Crypto') {
            $paymentDetails = ['wallet_address' => $this->crypto_wallet];
        } elseif ($this->payment_method === 'Papara') {
            $paymentDetails = ['papara_number' => $this->papara_number];
        }

        // Wrap the entire operation in a transaction for true atomicity
        $success = \Illuminate\Support\Facades\DB::transaction(function () use ($user, $paymentDetails) {
            // 1. Atomically check & decrement balance first
            $affected = \App\Models\User::where('id', $user->id)
                ->where('earnings', '>=', $this->withdrawal_amount)
                ->decrement('earnings', $this->withdrawal_amount);

            if (!$affected) {
                return false; // Insufficient balance — transaction rolls back
            }

            // 2. Only create the record if balance was successfully deducted
            WithdrawalRequest::create([
                'user_id'         => $user->id,
                'amount'          => $this->withdrawal_amount,
                'payment_method'  => $this->payment_method,
                'payment_details' => $paymentDetails ? json_encode($paymentDetails) : null,
                'status'          => 'pending',
            ]);

            return true;
        });

        if (!$success) {
            $this->addError('withdrawal_amount', 'Insufficient balance. Please refresh and try again.');
            return;
        }

        session()->flash('success', 'Withdrawal request submitted successfully.');

        return redirect()->route('user.withdrawals');
    }

    public function cancelWithdrawal($id)
    {
        $withdrawal = WithdrawalRequest::where('id', $id)->where('user_id', Auth::id())->where('status', 'pending')->first();

        if ($withdrawal) {
            // Atomically refund the balance
            \App\Models\User::where('id', Auth::id())->increment('earnings', $withdrawal->amount);

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
                $query->where('payment_method', $this->method); // 'method' property maps to 'payment_method' column
            })
            ->latest()
            ->paginate(10);

        return view('livewire.user.withdrawals', [
            'withdrawals' => $withdrawals,
        ]);
    }
}
