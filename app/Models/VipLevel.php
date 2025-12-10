<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VipLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'color',
        'min_earnings',
        'max_earnings',
        'cpm_bonus_percent',
        'spin_extra',
        'benefits',
        'order',
        'is_active',
    ];

    protected $casts = [
        'min_earnings' => 'decimal:2',
        'max_earnings' => 'decimal:2',
        'cpm_bonus_percent' => 'integer',
        'spin_extra' => 'integer',
        'benefits' => 'array',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'vip_level_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(UserVipHistory::class);
    }

    /**
     * Get VIP level by earnings
     */
    public static function getByEarnings(float $earnings): ?self
    {
        return self::where('is_active', true)
            ->where('min_earnings', '<=', $earnings)
            ->where(function ($query) use ($earnings) {
                $query->whereNull('max_earnings')
                    ->orWhere('max_earnings', '>=', $earnings);
            })
            ->orderBy('order', 'desc')
            ->first();
    }

    /**
     * Get all active levels ordered
     */
    public static function getAllActive()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get display name with icon
     */
    public function getDisplayNameAttribute(): string
    {
        return ($this->icon ? $this->icon . ' ' : '') . $this->name;
    }

    /**
     * Get earning range text
     */
    public function getEarningRangeAttribute(): string
    {
        if ($this->max_earnings === null) {
            return '$' . number_format($this->min_earnings) . '+';
        }
        return '$' . number_format($this->min_earnings) . ' - $' . number_format($this->max_earnings);
    }

    /**
     * Get benefits list
     */
    public function getBenefitsListAttribute(): array
    {
        $list = [];
        
        if ($this->cpm_bonus_percent > 0) {
            $list[] = '+' . $this->cpm_bonus_percent . '% CPM Bonus';
        }
        
        if ($this->spin_extra > 0) {
            $list[] = '+' . $this->spin_extra . ' Günlük Spin';
        }
        
        if ($this->benefits) {
            foreach ($this->benefits as $benefit) {
                $list[] = $benefit;
            }
        }
        
        return $list;
    }
}
