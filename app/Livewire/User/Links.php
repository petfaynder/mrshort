<?php

namespace App\Livewire\User;

use App\Models\Link;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\GamificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Links extends Component
{
    use WithPagination;

    protected $layout = 'components.user-dashboard-layout';

    // Filters & Sorting
    public $search = '';
    public $filterDate = '';
    public $sortStr = 'newest';
    
    // Bulk Actions
    public $selectedLinks = [];
    public $selectAll = false;

    // Creating/Editing
    public $original_url;
    public $editingLink = null;
    public $newOriginalUrl;
    public $newShortLink;
    public $newTitle;
    public $newExpiresAt;
    
    // Stats
    public $showingStats = null;
    public $statsData = [];
    public $performanceData = [];

    protected $rules = [
        'original_url' => 'required|url',
        'newOriginalUrl' => 'required|url',
        'newTitle' => 'nullable|string|max:255',
        'newExpiresAt' => 'nullable|date',
    ];

    // Reset pagination when filters change
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterDate() { $this->resetPage(); }
    public function updatedSortStr() { $this->resetPage(); }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedLinks = $this->getLinksQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedLinks = [];
        }
    }

    public function updatedSelectedLinks()
    {
        $this->selectAll = false;
    }

    public function render()
    {
        $links = $this->getLinksQuery()->paginate(10);
        $this->loadPerformanceData($links->items());

        return view('livewire.user.links', [
            'links' => $links
        ]);
    }

    protected function getLinksQuery()
    {
        $query = Auth::user()->links()->where('is_hidden', false);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('original_url', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('title', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterDate) {
            $query->whereDate('created_at', $this->filterDate);
        }

        switch ($this->sortStr) {
            case 'oldest':
                $query->oldest();
                break;
            case 'clicks_high':
                $query->orderByDesc('clicks');
                break;
            case 'clicks_low':
                $query->orderBy('clicks');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        return $query;
    }

    public function shortenLink()
    {
        $this->validate(['original_url' => 'required|url']);
        
        // Validate URL against banned words and domains
        $validator = app(\App\Services\LinkValidationService::class);
        $errors = $validator->validate($this->original_url);
        
        if (!empty($errors)) {
            $this->addError('original_url', $errors[0]);
            return;
        }

        $codeLength = setting('link_code_length', 6);
        $code = Str::random($codeLength);

        Auth::user()->links()->create([
            'original_url' => $this->original_url,
            'code' => $code,
        ]);

        $this->original_url = '';
        $this->resetPage();
        
        session()->flash('message', 'Link successfully shortened.');
    }

    public function deleteLink($linkId)
    {
        $link = Auth::user()->links()->find($linkId);

        if ($link) {
            $link->delete();
            session()->flash('message', 'Link successfully deleted.');
        }
    }

    public function hideLink($linkId)
    {
        $link = Auth::user()->links()->find($linkId);

        if ($link) {
            $link->is_hidden = true;
            $link->save();
            session()->flash('message', 'Link successfully hidden.');
        }
    }

    public function deleteSelected()
    {
        if (empty($this->selectedLinks)) return;

        Auth::user()->links()->whereIn('id', $this->selectedLinks)->delete();
        
        $this->selectedLinks = [];
        $this->selectAll = false;
        
        session()->flash('message', 'Selected links successfully deleted.');
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
            session()->flash('message', 'Link successfully updated.');
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
            $this->statsData = $link->clicks()
                ->selectRaw('DATE(created_at) as click_date, count(*) as total_clicks')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('click_date')
                ->orderBy('click_date')
                ->get();
        }
    }

    // Simplified performance data loading for current page links
    protected function loadPerformanceData($currentLinks)
    {
        $currentLinksCollection = collect($currentLinks);
        if ($currentLinksCollection->isEmpty()) {
            $this->performanceData = [];
            return;
        }

        $linkIds = $currentLinksCollection->pluck('id');
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
        foreach ($currentLinks as $link) {
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
