<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackPost extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'status',
        'vote_count',
        'comment_count',
    ];

    protected $casts = [
        'vote_count' => 'integer',
        'comment_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(FeedbackVote::class);
    }

    public function comments()
    {
        return $this->hasMany(FeedbackComment::class);
    }

    public function isVotedBy(?User $user)
    {
        if (!$user) return false;
        return $this->votes()->where('user_id', $user->id)->exists();
    }
}
