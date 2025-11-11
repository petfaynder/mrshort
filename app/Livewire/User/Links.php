<?php

namespace App\Livewire\User;

use App\Models\Link;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\GamificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Links extends Component
{
    protected $layout = 'components.user-dashboard-layout';

    public $links;
    public $original_url;
    public $editingLink = null;
    public $newOriginalUrl;
    public $newShortLink;
    public $newTitle;
    public $newExpiresAt;
    public $showingStats = null;
    public $statsData = [];
    public $performanceData = [];

    protected $rules = [
        'original_url' => 'required|url',
        'newOriginalUrl' => 'required|url',
        'newTitle' => 'nullable|string|max:255',
        'newExpiresAt' => 'nullable|date',
    ];

    public function mount()
    {
        $this->loadLinks();
    }

    public function render()
    {
        return view('livewire.user.links');
    }

    public function shortenLink()
    {
        $this->validate(['original_url' => 'required|url']);

        $code = Str::random(6); // Generate a random short code

        Auth::user()->links()->create([
            'original_url' => $this->original_url,
            'code' => $code,
        ]);

        $this->original_url = ''; // Clear the input field
        $this->loadLinks(); // Refresh the links list

        session()->flash('message', 'Bağlantı başarıyla kısaltıldı.');
    }

    public function deleteLink($linkId)
    {
        $link = Auth::user()->links()->find($linkId);

        if ($link) {
            $link->delete();
            $this->loadLinks();
            session()->flash('message', 'Bağlantı başarıyla silindi.');
        }
    }

    public function editLink($linkId)
    {
        $this->editingLink = Auth::user()->links()->find($linkId);
        if ($this->editingLink) {
            $this->newOriginalUrl = $this->editingLink->original_url;
            $this->newShortLink = $this->editingLink->code;
            $this->newTitle = $this->editingLink->title;
            $this->newExpiresAt = $this->editingLink->expires_at ? $this->editingLink->expires_at->format('Y-m-d\TH:i') : null;
        }
    }

    public function updateLink()
    {
        $this->validate([
            'newOriginalUrl' => 'required|url',
            'newTitle' => 'nullable|string|max:255',
            'newExpiresAt' => 'nullable|date',
        ]);

        if ($this->editingLink) {
            $this->editingLink->update([
                'original_url' => $this->newOriginalUrl,
                'code' => $this->newShortLink,
                'title' => $this->newTitle,
                'expires_at' => $this->newExpiresAt,
            ]);

            $this->cancelEdit();
            $this->loadLinks();
            session()->flash('message', 'Bağlantı başarıyla güncellendi.');
        }
    }

    public function cancelEdit()
    {
        $this->editingLink = null;
        $this->newOriginalUrl = '';
        $this->newShortLink = '';
        $this->newTitle = '';
        $this->newExpiresAt = null;
    }

    public function toggleHiddenStatus($linkId)
    {
        $link = Auth::user()->links()->find($linkId);

        if ($link) {
            $link->is_hidden = !$link->is_hidden;
            $link->save();
            $this->loadLinks();
            session()->flash('message', 'Bağlantı gizlilik durumu güncellendi.');
        }
    }

    public function toggleStats($linkId)
    {
        if ($this->showingStats === $linkId) {
            $this->showingStats = null;
            $this->statsData = [];
        } else {
            $this->showingStats = $linkId;
            $this->statsData = []; // Clear previous stats
            $this->loadStats($linkId); // Load stats when showing
        }
    }

    public function loadStats($linkId)
    {
        $link = Auth::user()->links()->find($linkId);

        if ($link) {
            Log::info('Loading stats for link ID: ' . $linkId);
            $this->statsData = $link->clicks()
                ->selectRaw('DATE(created_at) as click_date, count(*) as total_clicks')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('click_date')
                ->orderBy('click_date')
                ->get();
            Log::info('Stats data loaded: ' . json_encode($this->statsData));
        } else {
            Log::warning('Link not found for stats: ' . $linkId);
        }
    }

    public function shareLink($linkId)
    {
        $link = Auth::user()->links()->find($linkId);

        if ($link) {
            $gamificationService = app(GamificationService::class);
            $gamificationService->updateGoalProgress($link->user, 'shares', 1);
            session()->flash('message', 'Bağlantı paylaşım hedefi güncellendi.');
        }
    }

    protected function loadLinks()
    {
        $this->links = Auth::user()->links()->where('is_hidden', false)->get();
        $this->loadPerformanceData();
    }

    protected function loadPerformanceData()
    {
        $linkIds = $this->links->pluck('id');
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $clicks = DB::table('link_clicks')
            ->whereIn('link_id', $linkIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                'link_id',
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as clicks')
            )
            ->groupBy('link_id', 'date')
            ->get()
            ->groupBy('link_id');

        $this->performanceData = [];
        foreach ($this->links as $link) {
            $linkClicks = $clicks->get($link->id, collect())->keyBy('date');
            $dailyClicks = [];
            for ($i = 0; $i < 7; $i++) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $dailyClicks[$date] = $linkClicks->get($date)->clicks ?? 0;
            }
            $this->performanceData[$link->id] = array_reverse(array_values($dailyClicks));
        }
    }
}
