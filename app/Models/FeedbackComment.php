<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackComment extends Model
{
    protected $fillable = ['user_id', 'feedback_post_id', 'body', 'is_official_response'];

    protected $casts = [
        'is_official_response' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(FeedbackPost::class, 'feedback_post_id');
    }
}
