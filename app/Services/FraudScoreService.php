<?php

namespace App\Services;

use App\Models\WithdrawalRequest;
use Illuminate\Support\Collection;

class FraudScoreService
{
    /**
     * Calculate fraud score (0-100)
     */
    public function calculate(WithdrawalRequest $withdrawal): array
    {
        $clicks = $withdrawal->clicks()->with('link')->get();
        $total = $clicks->count();
        
        if ($total === 0) {
            return [
                'score' => 0,
                'is_flagged' => false,
                'flag_reason' => null,
                'metrics' => []
            ];
        }
        
        $metrics = [];
        $flagReasons = [];
        
        // 1. Bot Ratio (30%)
        $botCount = $clicks->where('is_bot', true)->count();
        $botRatio = $botCount / $total;
        $botScore = min($botRatio * 100, 30);
        $metrics['bot_ratio'] = [
            'value' => round($botRatio * 100, 1),
            'count' => $botCount,
            'score' => round($botScore, 1),
            'max' => 30
        ];
        
        // Auto-flag: Bot > 50%
        if ($botRatio > 0.5) {
            $flagReasons[] = "Bot ratio too high: " . round($botRatio * 100, 1) . "%";
        }
        
        // 2. IP Duplication (25%)
        $uniqueIps = $clicks->unique('ip_address')->count();
        $ipDuplicateRatio = 1 - ($uniqueIps / $total);
        $ipScore = $ipDuplicateRatio * 25;
        $metrics['ip_duplicate'] = [
            'value' => round($ipDuplicateRatio * 100, 1),
            'unique_ips' => $uniqueIps,
            'score' => round($ipScore, 1),
            'max' => 25
        ];
        
        // Auto-flag: Unique IP < 10%
        if ($uniqueIps / $total < 0.1 && $total > 100) {
            $flagReasons[] = "Unique IP ratio too low: " . round($uniqueIps / $total * 100, 1) . "%";
        }
        
        // 3. Country Diversity (15%)
        // Few unique countries with high volume = suspicious (bot farm in one region)
        // Many diverse countries = organic traffic (low fraud risk)
        $countryCounts = $clicks->groupBy('country_id');
        $uniqueCountries = $countryCounts->count();
        $topCountryRatio = $countryCounts->max(fn($g) => $g->count()) / $total;
        
        $countryScore = match(true) {
            $uniqueCountries < 3 && $total > 100  => 15,  // Very few countries with lots of clicks = suspicious
            $uniqueCountries < 5 && $total > 200  => 10,  // Still low diversity
            $uniqueCountries > 50                 => 0,   // Very diverse traffic = organic
            default                               => 0
        };
        $metrics['country_diversity'] = [
            'unique_countries'  => $uniqueCountries,
            'top_country_ratio' => round($topCountryRatio * 100, 1),
            'score'             => $countryScore,
            'max'               => 15
        ];
        
        // Auto-flag: Single country > 95% with high volume = concentrated bot traffic
        if ($topCountryRatio > 0.95 && $total > 100) {
            $flagReasons[] = "Too much traffic from single country: " . round($topCountryRatio * 100, 1) . "%";
        }
        
        // 4. Referrer Quality (15%)
        $directAccess = $clicks->filter(fn($c) => 
            $c->referrer === 'Direct Access' || $c->referrer === 'Doğrudan Erişim' || empty($c->referrer)
        )->count();
        $directRatio = $directAccess / $total;
        $referrerScore = match(true) {
            $directRatio > 0.9 => 15,
            $directRatio > 0.7 => 10,
            $directRatio > 0.5 => 5,
            default => 0
        };
        $metrics['referrer_quality'] = [
            'direct_ratio' => round($directRatio * 100, 1),
            'direct_count' => $directAccess,
            'score' => $referrerScore,
            'max' => 15
        ];
        
        // 5. Time Distribution (10%)
        $nightClicks = $clicks->filter(fn($c) => 
            in_array($c->created_at->hour, [2, 3, 4, 5])
        )->count();
        $nightRatio = $nightClicks / $total;
        $timeScore = match(true) {
            $nightRatio > 0.3 => 10,
            $nightRatio > 0.15 => 5,
            default => 0
        };
        $metrics['time_distribution'] = [
            'night_ratio' => round($nightRatio * 100, 1),
            'night_count' => $nightClicks,
            'score' => $timeScore,
            'max' => 10
        ];
        
        // 6. Device Diversity (5%)
        $deviceTypes = $clicks->unique('device_type')->count();
        $deviceScore = ($deviceTypes === 1 && $total > 50) ? 5 : 0;
        $metrics['device_diversity'] = [
            'unique_devices' => $deviceTypes,
            'score' => $deviceScore,
            'max' => 5
        ];
        
        // Total score
        $totalScore = (int) round($botScore + $ipScore + $countryScore + 
                                  $referrerScore + $timeScore + $deviceScore);
        
        // Auto-flag: Score > 60
        if ($totalScore > 60 && empty($flagReasons)) {
            $flagReasons[] = "Fraud score at critical level: $totalScore";
        }
        
        // Auto-flag: Too many clicks in 24 hours
        $last24hClicks = $clicks->filter(fn($c) => $c->created_at->gt(now()->subDay()))->count();
        if ($last24hClicks > 10000) {
            $flagReasons[] = "Abnormal traffic spike in last 24h: " . number_format($last24hClicks);
        }
        
        return [
            'score' => $totalScore,
            'is_flagged' => !empty($flagReasons),
            'flag_reason' => !empty($flagReasons) ? implode('; ', $flagReasons) : null,
            'metrics' => $metrics,
            'summary' => [
                'total_clicks' => $total,
                'bot_clicks' => $botCount,
                'unique_ips' => $uniqueIps,
                'unique_countries' => $uniqueCountries,
            ]
        ];
    }
    
    /**
     * Get risk level
     */
    public function getRiskLevel(int $score): array
    {
        return match(true) {
            $score <= 20 => ['level' => 'low', 'label' => 'Low', 'color' => 'success'],
            $score <= 40 => ['level' => 'medium', 'label' => 'Medium', 'color' => 'warning'],
            $score <= 60 => ['level' => 'high', 'label' => 'High', 'color' => 'danger'],
            default => ['level' => 'critical', 'label' => 'Critical', 'color' => 'danger'],
        };
    }
}
