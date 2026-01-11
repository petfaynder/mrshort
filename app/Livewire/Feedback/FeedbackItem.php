<?php

namespace App\Livewire\Feedback;

use Livewire\Component;
use App\Models\FeedbackPost;

class FeedbackItem extends Component
{
    public FeedbackPost $post;

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

    public function render()
    {
        return view('livewire.feedback.feedback-item');
    }
}
