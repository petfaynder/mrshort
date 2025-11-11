<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\WithdrawalRequest;
use App\Models\Link;
use App\Models\UserLevel;
use App\Models\UserAchievement;
use App\Models\UserReward;
use App\Models\UserInventory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'earnings',
        'link_earnings',
        'referral_earnings',
        'gamification_points', // Gamification puanları
        'virtual_currency',    // Sanal para birimi
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the links for the user.
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    /**
     * Get the tickets for the user.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the withdrawal requests for the user.
     */
    public function withdrawalRequests(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function userLevel(): HasOne
    {
        return $this->hasOne(UserLevel::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(UserReward::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(UserInventory::class);
    }
    public function getLevelAttribute(): int
    {
        return $this->userLevel ? $this->userLevel->level : 1;
    }

    public function getProgressToNextLevelAttribute(): float
    {
        $currentLevel = $this->userLevel ? $this->userLevel->level : 1;
        $currentLevelConfig = LevelConfiguration::where('level', $currentLevel)->first();
        $nextLevelConfig = LevelConfiguration::where('level', $currentLevel + 1)->first();

        if (!$currentLevelConfig) {
            return 0; // No level configuration found for current level
        }

        $pointsInCurrentLevel = $this->gamification_points - ($currentLevelConfig->required_experience ?? 0);

        if ($nextLevelConfig) {
            $pointsNeededForNextLevel = $nextLevelConfig->required_experience - $currentLevelConfig->required_experience;
            if ($pointsNeededForNextLevel > 0) {
                return ($pointsInCurrentLevel / $pointsNeededForNextLevel) * 100;
            }
        }
        return 0; // Max level or no next level config
    }

    public function getNextLevelRequiredPointsAttribute(): int
    {
        $currentLevel = $this->userLevel ? $this->userLevel->level : 1;
        $nextLevelConfig = LevelConfiguration::where('level', $currentLevel + 1)->first();
        return $nextLevelConfig ? $nextLevelConfig->required_experience : 0;
    }
}
