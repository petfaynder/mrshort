<?php

namespace App\Livewire\Feedback;

use Livewire\Component;
use App\Models\FeedbackPost;

class FeedbackDetail extends Component
{
    public $slug;
    public $post;
    public $body = '';

    protected $rules = [
        'body' => 'required|min:3|max:1000',
    ];

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->post = FeedbackPost::where('slug', $slug)
            ->with(['user', 'comments.user', 'votes'])
            ->withCount(['votes', 'comments'])
            ->firstOrFail();
    }

    public function vote()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($this->post->isVotedBy($user)) {
            $this->post->votes()->where('user_id', $user->id)->delete();
            $this->post->decrement('vote_count');
        } else {
            $this->post->votes()->create(['user_id' => $user->id]);
            $this->post->increment('vote_count');
        }

        $this->post->refresh();
    }

    public function saveComment()
    {
        $this->validate();

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->post->comments()->create([
            'user_id' => auth()->id(),
            'body' => $this->body,
            'is_official_response' => false, // Only admin can set true (logic later)
        ]);

        $this->post->increment('comment_count');
        $this->body = '';
        $this->post->refresh();
        
        session()->flash('message', 'Comment posted!');
    }

    public function render()
    {
        return view('livewire.feedback.feedback-detail')->layout('layouts.feedback');
    }
}
