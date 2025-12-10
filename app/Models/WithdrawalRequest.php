<?php

namespace App\Models;

use App\Services\FraudScoreService;
use Illuminate\Database\Eloquent\Model;
use App\Models\LinkClick;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'status',
        'fraud_score',
        'is_flagged',
        'flag_reason',
    ];

    protected $casts = [
        'is_flagged' => 'boolean',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the withdrawal request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the clicks associated with the withdrawal request.
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class, 'withdrawal_id');
    }

    /**
     * Fraud score hesapla ve kaydet
     */
    public function calculateAndSaveFraudScore(): array
    {
        $service = app(FraudScoreService::class);
        $result = $service->calculate($this);
        
        $this->fraud_score = $result['score'];
        $this->is_flagged = $result['is_flagged'];
        $this->flag_reason = $result['flag_reason'];
        $this->save();
        
        return $result;
    }

    /**
     * Risk seviyesi al
     */
    public function getRiskLevel(): array
    {
        return app(FraudScoreService::class)->getRiskLevel($this->fraud_score ?? 0);
    }

    /**
     * Trafik istatistikleri özeti
     */
    public function getTrafficStats(): array
    {
        $clicks = $this->clicks;
        $total = $clicks->count();
        
        if ($total === 0) {
            return [
                'total_clicks' => 0,
                'bot_clicks' => 0,
                'bot_percentage' => 0,
                'unique_ips' => 0,
                'unique_countries' => 0,
                'top_devices' => [],
                'top_browsers' => [],
                'top_os' => [],
                'top_countries' => [],
                'top_referrers' => [],
            ];
        }
        
        $botClicks = $clicks->where('is_bot', true)->count();
        
        return [
            'total_clicks' => $total,
            'bot_clicks' => $botClicks,
            'bot_percentage' => round($botClicks / $total * 100, 1),
            'unique_ips' => $clicks->unique('ip_address')->count(),
            'unique_countries' => $clicks->unique('country_id')->count(),
            'top_devices' => $clicks->groupBy('device_type')
                ->map(fn($g) => ['count' => $g->count(), 'percentage' => round($g->count() / $total * 100, 1)])
                ->sortByDesc('count')
                ->take(5)
                ->toArray(),
            'top_browsers' => $clicks->groupBy('browser')
                ->map(fn($g) => ['count' => $g->count(), 'percentage' => round($g->count() / $total * 100, 1)])
                ->sortByDesc('count')
                ->take(5)
                ->toArray(),
            'top_os' => $clicks->groupBy('os')
                ->map(fn($g) => ['count' => $g->count(), 'percentage' => round($g->count() / $total * 100, 1)])
                ->sortByDesc('count')
                ->take(5)
                ->toArray(),
            'top_countries' => $clicks->groupBy('country_id')
                ->map(fn($g) => ['count' => $g->count(), 'percentage' => round($g->count() / $total * 100, 1)])
                ->sortByDesc('count')
                ->take(10)
                ->toArray(),
            'top_referrers' => $clicks->groupBy('referrer')
                ->map(fn($g) => ['count' => $g->count(), 'percentage' => round($g->count() / $total * 100, 1)])
                ->sortByDesc('count')
                ->take(10)
                ->toArray(),
        ];
    }
}
