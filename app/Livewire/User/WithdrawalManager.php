<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\WithdrawalRequest;
use App\Enums\WithdrawalStatus;

class WithdrawalManager extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $withdrawalRequests;
    public $amount;
    public $paymentMethod;
    public $paymentDetails;

    protected $rules = [
        'amount' => 'required|numeric|min:1|max:10000', // Örnek limitler
        'paymentMethod' => 'required|string|max:255',
        'paymentDetails' => 'required|string|max:500',
    ];

    public function mount()
    {
        $this->loadWithdrawalRequests();
    }

    public function loadWithdrawalRequests()
    {
        $this->withdrawalRequests = Auth::user()->withdrawalRequests()->latest()->get();
    }

    public function createWithdrawalRequest()
    {
        $this->validate();

        $user = Auth::user();

        if ($user->earnings < $this->amount) {
            session()->flash('error', 'Yetersiz bakiye.');
            return;
        }

        $user->withdrawalRequests()->create([
            'amount' => $this->amount,
            'payment_method' => $this->paymentMethod,
            'payment_details' => $this->paymentDetails,
            'status' => WithdrawalStatus::Pending,
        ]);

        // Kullanıcının bakiyesinden düş
        $user->earnings -= $this->amount;
        $user->save();

        $this->reset(['amount', 'paymentMethod', 'paymentDetails']);
        $this->loadWithdrawalRequests();
        session()->flash('success', 'Çekim talebiniz başarıyla oluşturuldu.');
    }

    public function render()
    {
        return view('livewire.user.withdrawal-manager');
    }
}
