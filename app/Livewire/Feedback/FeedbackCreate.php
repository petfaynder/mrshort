<?php

namespace App\Livewire\Feedback;

use Livewire\Component;
use App\Models\FeedbackPost;
use Illuminate\Support\Str;

class FeedbackCreate extends Component
{
    public $title = '';
    public $description = '';

    protected $rules = [
        'title' => 'required|min:5|max:100',
        'description' => 'required|min:10',
    ];

    public function save()
    {
        $this->validate();

        $slug = Str::slug($this->title);
        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (FeedbackPost::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $post = FeedbackPost::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'slug' => $slug,
            'description' => $this->description,
            'status' => 'review',
        ]);

        // Auto-vote for own post
        $post->votes()->create(['user_id' => auth()->id()]);
        $post->increment('vote_count');

        session()->flash('message', 'Feedback submitted successfully!');
        
        return redirect()->route('feedback.show', $post->slug);
    }

    public function render()
    {
        return view('livewire.feedback.feedback-create');
    }
}
