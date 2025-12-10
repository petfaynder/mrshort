<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;

class MilestoneModal extends Component
{
    public $show = false;
    public $milestoneName = '';
    public $milestoneDescription = '';
    public $milestoneIcon = '🏆';
    public $pointsEarned = 0;
    public $badgeEarned = null;

    #[On('milestone-achieved')]
    public function showMilestone($data)
    {
        $this->milestoneName = $data['name'] ?? 'Tebrikler!';
        $this->milestoneDescription = $data['description'] ?? 'Yeni bir başarı kazandınız!';
        $this->milestoneIcon = $data['icon'] ?? '🏆';
        $this->pointsEarned = $data['points'] ?? 0;
        $this->badgeEarned = $data['badge'] ?? null;
        $this->show = true;

        // Dispatch confetti event to JS
        $this->dispatch('trigger-confetti');
    }

    public function close()
    {
        $this->show = false;
        $this->reset(['milestoneName', 'milestoneDescription', 'milestoneIcon', 'pointsEarned', 'badgeEarned']);
    }

    public function shareOnTwitter()
    {
        $text = "🎉 {$this->milestoneName} başarısını kazandım! " . config('app.name');
        $url = urlencode(config('app.url'));
        $this->dispatch('open-share-window', url: "https://twitter.com/intent/tweet?text=" . urlencode($text) . "&url={$url}");
    }

    public function shareOnFacebook()
    {
        $url = urlencode(config('app.url'));
        $this->dispatch('open-share-window', url: "https://www.facebook.com/sharer/sharer.php?u={$url}");
    }

    public function render()
    {
        return view('livewire.user.milestone-modal');
    }
}
