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
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
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
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'email_verified_at',
        'google_id',
        'avatar',
        // 'earnings' is a computed accessor (link_earnings + referral_earnings) — not mass-assignable
        'link_earnings',
        'referral_earnings',
        'gamification_points', // Gamification points
        'virtual_currency',    // Virtual currency
        'vip_level_id',        // VIP level
        'monthly_earnings',    // Monthly earnings
        'current_streak',      // Streak system
        'longest_streak',
        'last_streak_date',
        'streak_freeze_available',
        'referral_code',
        'referred_by_user_id',
        'payment_method',
        'payment_account',
        'is_admin',            // Admin access
        'theme_preference',
        'allow_analytics',
        'allow_personalized_ads',
        'tutorial_completed_at',
        'last_login_at',
        'has_admin_message',
        'admin_message_ticket_id',
        'deactivation_reason',
        'deactivated_at',
        // Telegram Traffic Bonus
        'telegram_bonus_enabled',
        'telegram_bonus_enabled_at',
        'telegram_bonus_verified_at',
        'telegram_bonus_failed_at',
        'telegram_verification_clicks',
        'telegram_referrer_match_rate',
        'telegram_bonus_decision_made',
        // Subscription & registration meta
        'plan',
        'expiration',
        'register_ip',
    ];

    /**
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
            'is_admin' => 'boolean',
            'tutorial_completed_at' => 'datetime',
            'last_login_at'          => 'datetime',
            'deactivated_at'         => 'datetime',
            // Telegram Bonus
            'telegram_bonus_enabled' => 'boolean',
            'telegram_bonus_enabled_at' => 'datetime',
            'telegram_bonus_verified_at' => 'datetime',
            'telegram_bonus_failed_at' => 'datetime',
            'telegram_bonus_decision_made' => 'boolean',
            // Subscription expiry
            'expiration' => 'datetime',
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

    /**
     * Get the ad campaigns for the user.
     */
    public function adCampaigns(): HasMany
    {
        return $this->hasMany(\App\Models\AdCampaign::class);
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

    public function vipLevel(): BelongsTo
    {
        return $this->belongsTo(VipLevel::class);
    }

    public function teamMembership(): HasOne
    {
        return $this->hasOne(TeamMember::class);
    }

    public function team()
    {
        return $this->teamMembership?->team;
    }

    public function seasonProgress(): HasMany
    {
        return $this->hasMany(UserSeasonProgress::class);
    }

    /**
     * Computed: total earnings = link earnings + referral earnings.
     * Avoids a redundant DB column that could become stale.
     * Use $user->earnings anywhere — Eloquent resolves via this accessor.
     */
    public function getEarningsAttribute(): float
    {
        return (float) ($this->link_earnings ?? 0) + (float) ($this->referral_earnings ?? 0);
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

    public function shouldShowTutorial(): bool
    {
        // Show if tutorial was never completed
        if ($this->tutorial_completed_at === null) {
            return true;
        }

        // Show if last login was more than 30 days ago
        if ($this->last_login_at && $this->last_login_at->diffInDays(now()) > 30) {
            return true;
        }

        return false;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->is_admin;
        }

        return false;
    }



    // ==========================================
    // Telegram Traffic Bonus Methods
    // ==========================================

    /**
     * Check if user has active telegram bonus
     */
    public function hasTelegramBonus(): bool
    {
        return $this->telegram_bonus_enabled && $this->canUseTelegramBonus();
    }

    /**
     * Check if user can use telegram bonus (not in cooldown)
     */
    public function canUseTelegramBonus(): bool
    {
        // Check if in cooldown period (7 days after failure)
        if ($this->telegram_bonus_failed_at) {
            $cooldownEnds = $this->telegram_bonus_failed_at->addDays(7);
            if (now()->lt($cooldownEnds)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if user can enable telegram bonus
     */
    public function canEnableTelegramBonus(): bool
    {
        // Already enabled
        if ($this->telegram_bonus_enabled) {
            return false;
        }

        return $this->canUseTelegramBonus();
    }

    /**
     * Enable telegram bonus for this user
     */
    public function enableTelegramBonus(): void
    {
        $this->update([
            'telegram_bonus_enabled' => true,
            'telegram_bonus_enabled_at' => now(),
            'telegram_bonus_decision_made' => true,
            'telegram_verification_clicks' => 0,
            'telegram_bonus_failed_at' => null, // Clear any previous failure
        ]);
    }

    /**
     * Disable telegram bonus
     */
    public function disableTelegramBonus(bool $failed = false): void
    {
        $data = [
            'telegram_bonus_enabled' => false,
            'telegram_verification_clicks' => 0,
        ];

        if ($failed) {
            $data['telegram_bonus_failed_at'] = now();
        }

        $this->update($data);
    }

    /**
     * Get cooldown end date if in cooldown
     */
    public function getTelegramCooldownEndsAt(): ?\Carbon\Carbon
    {
        if (!$this->telegram_bonus_failed_at) {
            return null;
        }

        $cooldownEnds = $this->telegram_bonus_failed_at->addDays(7);
        
        if (now()->gte($cooldownEnds)) {
            return null; // Cooldown has passed
        }

        return $cooldownEnds;
    }

    /**
     * Skip telegram bonus decision (user chose not to enable)
     */
    public function skipTelegramBonusDecision(): void
    {
        $this->update([
            'telegram_bonus_decision_made' => true,
        ]);
    }

    /**
     * Check if user needs to make telegram bonus decision (after tutorial)
     */
    public function needsTelegramBonusDecision(): bool
    {
        return $this->tutorial_completed_at 
            && !$this->telegram_bonus_decision_made;
    }
    /**
     * Feedback System Relationships
     */
    public function feedbackPosts(): HasMany
    {
        return $this->hasMany(FeedbackPost::class);
    }

    public function feedbackVotes(): HasMany
    {
        return $this->hasMany(FeedbackVote::class);
    }

    public function feedbackComments(): HasMany
    {
        return $this->hasMany(FeedbackComment::class);
    }
}
