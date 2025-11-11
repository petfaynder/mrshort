<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class LevelConfiguration extends Model
{
    protected $fillable = [
        'level',
        'required_experience',
        'rewards_description',
        'name', // Add name field for level
    ];

    public function rewards(): HasMany
    {
        return $this->hasMany(GamificationReward::class, 'level_id'); // level_id foreign key olarak eklenecek
    }
}
