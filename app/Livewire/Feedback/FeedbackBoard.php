<?php

namespace App\Livewire\Feedback;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FeedbackPost;

class FeedbackBoard extends Component
{
    use WithPagination;

    public $status = 'all';
    public $sort = 'popular';
    public $search = '';

    protected $queryString = [
        'status' => ['except' => 'all'],
        'sort' => ['except' => 'popular'],
        'search' => ['except' => ''],
    ];

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = FeedbackPost::query()
            ->with('user')
            ->withCount(['votes', 'comments']);

        // Filter by Status
        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // Search
        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        // Sorting
        if ($this->sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } else {
            // Popular (default)
            $query->orderBy('vote_count', 'desc')
                  ->orderBy('created_at', 'desc');
        }

        $posts = $query->paginate(10);

        return view('livewire.feedback.feedback-board', [
            'posts' => $posts
        ])->layout('layouts.feedback');
    }
}
