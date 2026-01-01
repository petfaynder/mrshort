<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class CpmCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'multiplier',
        'start_date',
        'end_date',
        'status',
        'original_rates_backup',
    ];

    protected $casts = [
        'multiplier' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'original_rates_backup' => 'array',
    ];

    /**
     * Scope to get only active campaigns
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if campaign is currently active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->start_date <= now() 
            && $this->end_date > now();
    }

    /**
     * Check if campaign has expired
     */
    public function hasExpired(): bool
    {
        return $this->end_date <= now() && $this->status === 'active';
    }

    /**
     * Mark campaign as expired
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Mark campaign as cancelled
     */
    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
