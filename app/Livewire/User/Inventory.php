<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\UserInventory;
use App\Models\GamificationReward;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class Inventory extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $inventoryItems;
    public $filterType = 'all'; // 'all', 'active', 'inactive', 'expired'
    public $pointsToConvert = null;
    public $conversionRate = 0.01;

    public $search = '';
    public $sortBy = 'name_asc';

    public $selectedItem = null;
    public $showItemModal = false;
    public $showConvertModal = false;

    protected $queryString = ['filterType', 'search', 'sortBy'];

    public function mount()
    {
        $this->loadInventory();
        $this->loadConversionRate();
    }

    public function updatedFilterType()
    {
        $this->loadInventory();
    }

    public function updatedSearch()
    {
        $this->loadInventory();
    }

    public function updatedSortBy()
    {
        $this->loadInventory();
    }

    public function loadConversionRate()
    {
        // GamificationSetting modelinden çevrim kurunu çek
        $setting = \App\Models\GamificationSetting::where('setting_key', 'points_to_currency_rate')->first();
        if ($setting) {
            $this->conversionRate = (float) $setting->setting_value;
        }
    }

    public function convertPoints()
    {
        $this->validate([
            'pointsToConvert' => 'required|numeric|min:1|max:' . Auth::user()->gamification_points,
        ]);

        $this->showConvertModal = true;
    }

    public function cancelConversion()
    {
        $this->showConvertModal = false;
        $this->pointsToConvert = null;
    }

    public function confirmConversion()
    {
        $user = Auth::user();
        $points = (int) $this->pointsToConvert;

        if ($points <= 0 || $user->gamification_points < $points) {
            Notification::make()
                ->title('Geçersiz veya yetersiz puan miktarı.')
                ->danger()
                ->send();
            $this->cancelConversion();
            return;
        }

        $convertedAmount = $points * $this->conversionRate;

        $user->gamification_points -= $points;
        $user->earnings += $convertedAmount;
        $user->save();

        Notification::make()
            ->title($points . ' puan başarıyla ' . number_format($convertedAmount, 2) . ' birim paraya çevrildi!')
            ->success()
            ->send();

        $this->cancelConversion();
        $this->dispatch('refresh-user-dashboard');
    }

    public function loadInventory()
    {
        $query = Auth::user()->inventory()->with('reward');

        if ($this->search) {
            $query->whereHas('reward', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        switch ($this->filterType) {
            case 'active':
                $query->where('user_inventory.is_active', true)->where(function ($q) {
                    $q->whereNull('user_inventory.expires_at')->orWhere('user_inventory.expires_at', '>', now());
                });
                break;
            case 'inactive':
                $query->where('user_inventory.is_active', false)->where(function ($q) {
                    $q->whereNull('user_inventory.expires_at')->orWhere('user_inventory.expires_at', '>', now());
                });
                break;
            case 'expired':
                $query->whereNotNull('user_inventory.expires_at')->where('user_inventory.expires_at', '<=', now());
                break;
        }

        if (in_array($this->sortBy, ['name_asc', 'name_desc', 'rarity'])) {
            $query->join('gamification_rewards', 'user_inventory.reward_id', '=', 'gamification_rewards.id')
                  ->select('user_inventory.*', 'gamification_rewards.name as reward_name', 'gamification_rewards.rarity as reward_rarity');

            if ($this->sortBy === 'name_asc') {
                $query->orderBy('reward_name', 'asc');
            } elseif ($this->sortBy === 'name_desc') {
                $query->orderBy('reward_name', 'desc');
            } elseif ($this->sortBy === 'rarity') {
                $query->orderBy('reward_rarity', 'desc');
            }
        } else {
            if ($this->sortBy === 'date_acquired') {
                $query->orderBy('user_inventory.created_at', 'desc');
            } elseif ($this->sortBy === 'time_remaining') {
                $query->orderByRaw('user_inventory.expires_at IS NULL, user_inventory.expires_at ASC');
            }
        }

        $this->inventoryItems = $query->get();
    }

    public function selectItem($itemId)
    {
        $this->selectedItem = UserInventory::with('reward')->find($itemId);
        $this->showItemModal = true;
    }

    public function closeModal()
    {
        $this->selectedItem = null;
        $this->showItemModal = false;
    }

    public function useReward($itemId)
    {
        $inventoryItem = UserInventory::find($itemId);

        if (!$inventoryItem) {
            Notification::make()->title('Öğe bulunamadı!')->danger()->send();
            return;
        }

        if ($inventoryItem->is_active) {
            Notification::make()->title('Bu öğe zaten aktif!')->warning()->send();
            return;
        }
        
        if ($inventoryItem->expires_at && $inventoryItem->expires_at <= now()) {
            Notification::make()->title('Bu öğenin süresi dolmuş!')->danger()->send();
            return;
        }

        $inventoryItem->is_active = true;
        if (isset($inventoryItem->reward->reward_config['duration_days'])) {
            $inventoryItem->expires_at = now()->addDays($inventoryItem->reward->reward_config['duration_days']);
        } elseif (isset($inventoryItem->reward->reward_config['duration_hours'])) {
            $inventoryItem->expires_at = now()->addHours($inventoryItem->reward->reward_config['duration_hours']);
        }
        $inventoryItem->save();

        Notification::make()->title('"' . $inventoryItem->reward->name . '" başarıyla etkinleştirildi!')->success()->send();

        $this->loadInventory();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.user.inventory');
    }
}
