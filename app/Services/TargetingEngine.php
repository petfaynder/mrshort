<?php

namespace App\Services;

use App\Models\Country;
use App\Models\CpmRate;
use Illuminate\Support\Facades\Cache;

class TargetingEngine
{
    /**
     * Hedefleme kurallarını oluştur
     */
    public function buildTargetingRules(array $formData): array
    {
        $rules = [];

        // Coğrafi hedefleme
        if (!empty($formData['targeting_countries'])) {
            $rules['geography'] = [
                'type' => 'countries',
                'countries' => $formData['targeting_countries'],
                'cities' => $formData['targeting_cities'] ?? [],
                'location_type' => $formData['location_targeting_type'] ?? 'presence',
            ];
        }

        // Demografik hedefleme
        if (!empty($formData['age_ranges']) || !empty($formData['genders'])) {
            $rules['demographics'] = [
                'age_ranges' => $formData['age_ranges'] ?? [],
                'genders' => $formData['genders'] ?? [],
                'languages' => $formData['languages'] ?? [],
            ];
        }

        // Davranışsal hedefleme
        if (!empty($formData['interests']) || !empty($formData['behavioral_categories'])) {
            $rules['behavioral'] = [
                'interests' => $formData['interests'] ?? [],
                'categories' => $formData['behavioral_categories'] ?? [],
            ];
        }

        // Teknik hedefleme
        if (!empty($formData['device_types']) || !empty($formData['operating_systems'])) {
            $rules['technical'] = [
                'device_types' => $formData['device_types'] ?? [],
                'operating_systems' => $formData['operating_systems'] ?? [],
                'browsers' => $formData['browsers'] ?? [],
                'connection_types' => $formData['connection_types'] ?? [],
            ];
        }

        return $rules;
    }

    /**
     * Tahmini erişim hesapla
     */
    public function calculateEstimatedReach(array $targetingRules): int
    {
        $cacheKey = 'reach_estimate_' . md5(serialize($targetingRules));

        return Cache::remember($cacheKey, 3600, function() use ($targetingRules) {
            $baseReach = 1000000; // 1M temel erişim

            // Coğrafi faktörler
            if (isset($targetingRules['geography'])) {
                $countryMultiplier = $this->getCountryMultiplier($targetingRules['geography']['countries']);
                $baseReach *= $countryMultiplier;
            }

            // Demografik faktörler
            if (isset($targetingRules['demographics'])) {
                $demoMultiplier = $this->getDemographicMultiplier($targetingRules['demographics']);
                $baseReach *= $demoMultiplier;
            }

            // Davranışsal faktörler
            if (isset($targetingRules['behavioral'])) {
                $behavioralMultiplier = $this->getBehavioralMultiplier($targetingRules['behavioral']);
                $baseReach *= $behavioralMultiplier;
            }

            return (int) $baseReach;
        });
    }

    /**
     * Tahmini gösterim hesapla
     */
    public function calculateEstimatedImpressions(array $targetingRules): int
    {
        $reach = $this->calculateEstimatedReach($targetingRules);

        // Trafik ve reklam görünürlük faktörleri
        $trafficMultiplier = 0.15; // %15'lik trafik dilimi
        $visibilityFactor = 0.8; // %80 görünürlük oranı

        return (int) ($reach * $trafficMultiplier * $visibilityFactor);
    }

    /**
     * Tahmini CTR hesapla
     */
    public function calculateEstimatedCTR(array $targetingRules): float
    {
        $baseCTR = 0.02; // %2 temel CTR

        // Kalite faktörleri
        if (isset($targetingRules['geography'])) {
            $geoCTR = $this->getGeographicCTR($targetingRules['geography']);
            $baseCTR *= $geoCTR;
        }

        if (isset($targetingRules['demographics'])) {
            $demoCTR = $this->getDemographicCTR($targetingRules['demographics']);
            $baseCTR *= $demoCTR;
        }

        return $baseCTR * 100; // Yüzde olarak döndür
    }

    /**
     * Ülkelere göre çarpan hesapla
     */
    protected function getCountryMultiplier(array $countries): float
    {
        $multiplier = 1.0;

        foreach ($countries as $countryCode) {
            $country = Country::where('iso_code', $countryCode)->first();
            if ($country) {
                $cpmRate = CpmRate::where('country_id', $country->id)->first();
                if ($cpmRate) {
                    // CPM oranı yüksekse erişim potansiyeli de yüksek
                    $multiplier *= (1 + ($cpmRate->rate / 10));
                }
            }
        }

        return $multiplier;
    }

    /**
     * Demografik çarpan hesapla
     */
    protected function getDemographicMultiplier(array $demographics): float
    {
        $multiplier = 1.0;

        // Yaş aralıklarına göre çarpan
        $ageRanges = $demographics['age_ranges'] ?? [];
        if (in_array('18-24', $ageRanges) || in_array('25-34', $ageRanges)) {
            $multiplier *= 1.2; // Genç kullanıcılar daha aktif
        }

        // Cinsiyet çeşitliliği
        $genders = $demographics['genders'] ?? [];
        if (count($genders) > 1) {
            $multiplier *= 1.1; // Çoklu cinsiyet hedefleme
        }

        return $multiplier;
    }

    /**
     * Davranışsal çarpan hesapla
     */
    protected function getBehavioralMultiplier(array $behavioral): float
    {
        $multiplier = 1.0;

        // İlgi alanları sayısı
        $interests = $behavioral['interests'] ?? [];
        if (count($interests) > 3) {
            $multiplier *= 1.15; // Çoklu ilgi alanı hedefleme
        }

        // Davranış kategorileri
        $categories = $behavioral['categories'] ?? [];
        if (in_array('online_shopper', $categories)) {
            $multiplier *= 1.25; // Online alışveriş yapanlar daha aktif
        }

        return $multiplier;
    }

    /**
     * Coğrafi CTR çarpanı
     */
    protected function getGeographicCTR(array $geography): float
    {
        $ctr = 1.0;

        // Türkiye için özel çarpan
        if (in_array('TR', $geography['countries'])) {
            $ctr *= 1.3; // Türkiye pazarında daha yüksek CTR
        }

        return $ctr;
    }

    /**
     * Demografik CTR çarpanı
     */
    protected function getDemographicCTR(array $demographics): float
    {
        $ctr = 1.0;

        // 18-34 yaş arası için daha yüksek CTR
        $ageRanges = $demographics['age_ranges'] ?? [];
        if (array_intersect(['18-24', '25-34'], $ageRanges)) {
            $ctr *= 1.4;
        }

        return $ctr;
    }

    /**
     * Şehir listesini ülkelere göre getir
     */
    public function getCitiesForCountries(array $countryCodes): array
    {
        $cacheKey = 'cities_for_countries_' . implode('_', $countryCodes);

        return Cache::remember($cacheKey, 3600, function() use ($countryCodes) {
            return Country::whereIn('iso_code', $countryCodes)
                ->with('cities')
                ->get()
                ->pluck('cities')
                ->flatten()
                ->pluck('name', 'id')
                ->toArray();
        });
    }

    /**
     * Hedefleme kurallarını doğrula
     */
    public function validateTargetingRules(array $rules): array
    {
        $errors = [];

        // Çakışan hedefleme kurallarını kontrol et
        if ($this->hasConflictingRules($rules)) {
            $errors[] = 'Hedefleme kurallarında çakışma tespit edildi.';
        }

        // Erişim çok düşükse uyar
        $estimatedReach = $this->calculateEstimatedReach($rules);
        if ($estimatedReach < 1000) {
            $errors[] = 'Hedefleme çok dar. Tahmini erişim: ' . number_format($estimatedReach);
        }

        return $errors;
    }

    /**
     * Çakışan kuralları kontrol et
     */
    protected function hasConflictingRules(array $rules): bool
    {
        // Örneğin: Aynı anda hem çok genç hem çok yaşlı hedefleme
        if (isset($rules['demographics']['age_ranges'])) {
            $ages = $rules['demographics']['age_ranges'];
            $young = array_intersect(['13-17', '18-24'], $ages);
            $old = array_intersect(['55-64', '65+'], $ages);

            if (count($young) > 0 && count($old) > 0) {
                return true;
            }
        }

        return false;
    }
}