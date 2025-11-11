<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampaignTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'is_active',
        'sort_order',
        'targeting_countries',
        'targeting_devices',
        'targeting_os',
        'targeting_ages',
        'start_date',
        'end_date',
        'daily_click_limit',
        'frequency_cap',
        'frequency_cap_unit',
        'campaign_schedule',
        'estimated_traffic',
        'available_traffic',
        'default_budget', // Moved here for consistency
        'estimated_ctr',
        'estimated_cpc',
        'estimated_reach',
        'estimated_conversions',
        'estimated_performance', // Keep if still used for a general JSON blob
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'targeting_countries' => 'array',
        'targeting_devices' => 'array',
        'targeting_os' => 'array',
        'targeting_ages' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'daily_click_limit' => 'integer',
        'frequency_cap' => 'integer',
        'campaign_schedule' => 'json',
        'estimated_traffic' => 'integer',
        'available_traffic' => 'integer',
        'default_budget' => 'decimal:2',
        'estimated_ctr' => 'decimal:2',
        'estimated_cpc' => 'decimal:2',
        'estimated_reach' => 'integer',
        'estimated_conversions' => 'integer',
        'estimated_performance' => 'json', // Keep if still used for a general JSON blob
    ];

    public function campaignTemplateSteps()
    {
        return $this->hasMany(CampaignTemplateStep::class);
    }

    /**
     * Aktif şablonları getir
     */
    public static function getActiveTemplates()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Tahmini performans metrikleri
     */
    public function getEstimatedPerformance(): array
    {
        return $this->estimated_performance ?? $this->getDefaultPerformanceForCategory();
    }

    /**
     * Kategoriye göre varsayılan performans metrikleri
     */
    protected function getDefaultPerformanceForCategory(): array
    {
        return match($this->category) {
            'quick_start' => [
                'estimated_ctr' => 2.1,
                'estimated_cpc' => 0.85,
                'estimated_reach' => 150000,
                'estimated_conversions' => 3150,
            ],
            'brand_awareness' => [
                'estimated_ctr' => 1.8,
                'estimated_cpc' => 1.20,
                'estimated_reach' => 300000,
                'estimated_conversions' => 5400,
            ],
            'lead_generation' => [
                'estimated_ctr' => 3.2,
                'estimated_cpc' => 2.50,
                'estimated_reach' => 80000,
                'estimated_conversions' => 2560,
            ],
            'traffic_drive' => [
                'estimated_ctr' => 2.8,
                'estimated_cpc' => 0.65,
                'estimated_reach' => 200000,
                'estimated_conversions' => 5600,
            ],
            default => [
                'estimated_ctr' => 2.0,
                'estimated_cpc' => 1.00,
                'estimated_reach' => 100000,
                'estimated_conversions' => 2000,
            ]
        };
    }

    /**
     * Şablon istatistiklerini güncelle
     */
    public function updatePerformanceStats(array $actualPerformance): void
    {
        $currentStats = $this->estimated_performance ?? [];

        // Üstel düzleştirme ile gerçek performansı harmanla
        $alpha = 0.3; // Düzleştirme faktörü

        foreach ($actualPerformance as $metric => $value) {
            if (isset($currentStats[$metric])) {
                $currentStats[$metric] = ($alpha * $value) + ((1 - $alpha) * $currentStats[$metric]);
            } else {
                $currentStats[$metric] = $value;
            }
        }

        $this->update(['estimated_performance' => $currentStats]);
    }
}
