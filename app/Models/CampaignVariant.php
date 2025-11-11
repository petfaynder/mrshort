<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampaignVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'name',
        'variant_key',
        'traffic_split',
        'is_control',
        'settings',
        'status',
        'performance_data',
    ];

    protected $casts = [
        'settings' => 'json',
        'performance_data' => 'json',
        'traffic_split' => 'integer',
        'is_control' => 'boolean',
    ];

    /**
     * Kampanya ilişkisi
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(AdCampaign::class);
    }

    /**
     * Varyasyon performans metrikleri
     */
    public function getPerformanceMetrics(): array
    {
        return $this->performance_data ?? [
            'impressions' => 0,
            'clicks' => 0,
            'conversions' => 0,
            'ctr' => 0.0,
            'conversion_rate' => 0.0,
            'cost_per_click' => 0.0,
            'cost_per_conversion' => 0.0,
        ];
    }

    /**
     * CTR hesapla
     */
    public function getCTR(): float
    {
        $metrics = $this->getPerformanceMetrics();

        if ($metrics['impressions'] > 0) {
            return ($metrics['clicks'] / $metrics['impressions']) * 100;
        }

        return 0.0;
    }

    /**
     * Dönüşüm oranını hesapla
     */
    public function getConversionRate(): float
    {
        $metrics = $this->getPerformanceMetrics();

        if ($metrics['clicks'] > 0) {
            return ($metrics['conversions'] / $metrics['clicks']) * 100;
        }

        return 0.0;
    }

    /**
     * Tıklama başına maliyeti hesapla
     */
    public function getCostPerClick(): float
    {
        $metrics = $this->getPerformanceMetrics();

        if ($metrics['clicks'] > 0) {
            return $metrics['cost_per_click'];
        }

        return 0.0;
    }

    /**
     * Performansı güncelle
     */
    public function updatePerformance(array $newData): void
    {
        $current = $this->performance_data ?? [];

        foreach ($newData as $key => $value) {
            if (isset($current[$key])) {
                // Üstel düzleştirme uygula
                $alpha = 0.3;
                $current[$key] = ($alpha * $value) + ((1 - $alpha) * $current[$key]);
            } else {
                $current[$key] = $value;
            }
        }

        $this->update(['performance_data' => $current]);
    }

    /**
     * Kontrol grubuna göre performans karşılaştırması
     */
    public function compareWithControl(): array
    {
        $controlVariant = $this->campaign->variants()->where('is_control', true)->first();

        if (!$controlVariant) {
            return [];
        }

        $variantMetrics = $this->getPerformanceMetrics();
        $controlMetrics = $controlVariant->getPerformanceMetrics();

        return [
            'ctr_improvement' => $this->calculateImprovement($variantMetrics['ctr'] ?? 0, $controlMetrics['ctr'] ?? 0),
            'conversion_improvement' => $this->calculateImprovement(
                $variantMetrics['conversion_rate'] ?? 0,
                $controlMetrics['conversion_rate'] ?? 0
            ),
            'cpc_improvement' => $this->calculateImprovement(
                $controlMetrics['cost_per_click'] ?? 0,
                $variantMetrics['cost_per_click'] ?? 0
            ),
        ];
    }

    /**
     * İyileştirme yüzdesini hesapla
     */
    protected function calculateImprovement(float $variantValue, float $controlValue): float
    {
        if ($controlValue == 0) {
            return $variantValue > 0 ? 100.0 : 0.0;
        }

        return (($variantValue - $controlValue) / $controlValue) * 100;
    }

    /**
     * Varyasyonun istatistiksel anlamlılığını kontrol et
     */
    public function isStatisticallySignificant(): bool
    {
        $metrics = $this->getPerformanceMetrics();

        // Minimum örneklem büyüklüğü kontrolü
        if ($metrics['impressions'] < 1000) {
            return false;
        }

        // Güven aralığı hesaplaması için basit kontrol
        $ctr = $this->getCTR();
        $standardError = sqrt(($ctr * (100 - $ctr)) / $metrics['impressions']);

        // %95 güven aralığında minimum fark kontrolü
        $confidenceInterval = 1.96 * $standardError;

        return $confidenceInterval < abs($ctr * 0.1); // En az %10'luk fark
    }

    /**
     * Otomatik kazanan varyasyon seçimi
     */
    public static function selectWinningVariant(int $campaignId): ?self
    {
        $variants = static::where('campaign_id', $campaignId)
            ->where('status', 'active')
            ->get();

        if ($variants->count() < 2) {
            return null;
        }

        $controlVariant = $variants->where('is_control', true)->first();

        if (!$controlVariant) {
            return null;
        }

        $winningVariant = $controlVariant;
        $bestImprovement = 0;

        foreach ($variants->where('is_control', false) as $variant) {
            $comparison = $variant->compareWithControl();

            if (isset($comparison['ctr_improvement']) &&
                $comparison['ctr_improvement'] > $bestImprovement &&
                $variant->isStatisticallySignificant()) {

                $winningVariant = $variant;
                $bestImprovement = $comparison['ctr_improvement'];
            }
        }

        return $bestImprovement > 5 ? $winningVariant : null; // En az %5 iyileştirme
    }
}