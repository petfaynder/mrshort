<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Carbon\Carbon;

class Leaderboard extends Component
{
    use WithPagination;

    protected $layout = 'components.user-dashboard-layout';

    public $topThree;
    public $filter = 'all_time'; // 'all_time', 'monthly', 'weekly'

    protected $queryString = ['filter'];

    public function mount()
    {
        $this->loadLeaderboard();
    }

    public function updatedFilter()
    {
        $this->resetPage();
        $this->loadLeaderboard();
    }

    public function loadLeaderboard()
    {
        $query = User::query();

        // Not: Bu sorgular şu anki yapıya göre sadece toplam puana göre sıralama yapmaktadır.
        // Gerçek haftalık/aylık puanlar için puan kazanım geçmişinin (örn: user_achievements tablosu)
        // tarihe göre sorgulanması ve birleştirilmesi gerekmektedir.
        switch ($this->filter) {
            case 'monthly':
                // Örnek: Son 30 günde en çok puan kazananları getirecek mantık buraya eklenmeli.
                // $query->whereHas('achievements', function ($q) {
                //     $q->where('created_at', '>=', Carbon::now()->subDays(30));
                // })->withSum('achievements', 'points')->orderByDesc('achievements_sum_points');
                $query->orderByDesc('gamification_points');
                break;
            case 'weekly':
                // Örnek: Son 7 günde en çok puan kazananları getirecek mantık buraya eklenmeli.
                // $query->whereHas('achievements', function ($q) {
                //     $q->where('created_at', '>=', Carbon::now()->subWeek());
                // })->withSum('achievements', 'points')->orderByDesc('achievements_sum_points');
                $query->orderByDesc('gamification_points');
                break;
            case 'all_time':
            default:
                $query->orderByDesc('gamification_points');
                break;
        }

        // İlk 3 kullanıcıyı podyum için ayrı alıyoruz.
        $this->topThree = (clone $query)->limit(3)->get();

        // Podyumdaki kullanıcıların sayısını alıyoruz.
        $topThreeCount = $this->topThree->count();

        // Geri kalan kullanıcıları, podyumdakiler hariç, sayfalama ile alıyoruz.
        $topThreeIds = $this->topThree->pluck('id')->toArray();
        return $query->whereNotIn('id', $topThreeIds)->paginate(100);
    }

    public function render()
    {
        return view('livewire.user.leaderboard', [
            'leaderboard' => $this->loadLeaderboard()
        ]);
    }
}