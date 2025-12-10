<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Link;
use App\Services\DailyActivityService;
use Illuminate\Support\Facades\Auth;

class ShareLink extends Component
{
    public Link $link;
    public bool $showModal = false;
    public ?string $shareResult = null;

    public function mount(Link $link)
    {
        $this->link = $link;
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->shareResult = null;
    }

    public function recordShare(string $platform)
    {
        try {
            $activityService = new DailyActivityService();
            $result = $activityService->recordShare(Auth::user(), $platform, 'link_share');

            if ($result['success']) {
                $this->shareResult = '+' . $result['points_earned'] . ' puan kazandınız!';
            } else {
                $this->shareResult = $result['message'] ?? 'Paylaşım kaydedildi';
            }

            // Also update daily challenge
            $activityService->recordActivity(Auth::user(), 'share_links', 1, ['platform' => $platform]);
        } catch (\Exception $e) {
            $this->shareResult = 'Paylaşım kaydedildi';
        }
    }

    public function getShareUrl(string $platform): string
    {
        $shortUrl = $this->link->shortLink();
        $text = 'Bunu bir inceleyin!';

        return match ($platform) {
            'twitter' => 'https://twitter.com/intent/tweet?url=' . urlencode($shortUrl) . '&text=' . urlencode($text),
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($shortUrl),
            'whatsapp' => 'https://wa.me/?text=' . urlencode($text . ' ' . $shortUrl),
            'telegram' => 'https://t.me/share/url?url=' . urlencode($shortUrl) . '&text=' . urlencode($text),
            'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($shortUrl),
            default => $shortUrl,
        };
    }

    public function render()
    {
        return view('livewire.user.share-link');
    }
}
