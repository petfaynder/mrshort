<?php

namespace App\Services;

use App\Models\CpmCampaign;
use App\Models\CpmRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CpmCampaignService
{
    /**
     * Start a new CPM campaign
     *
     * @param string $name
     * @param float $multiplier
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return CpmCampaign
     * @throws \Exception
     */
    public function startCampaign(string $name, float $multiplier, Carbon $startDate, Carbon $endDate): CpmCampaign
    {
        // Check for expired campaigns first and clear them
        $this->checkExpiredCampaigns();

        // Check if there's already an active campaign
        $activeCampaign = CpmCampaign::active()->first();
        if ($activeCampaign) {
            throw new \Exception('There is already an active or scheduled campaign. Please stop it before starting a new one.');
        }

        // Validate dates
        if ($endDate <= $startDate) {
            throw new \Exception('End date must be after start date.');
        }

        if ($endDate <= now()) {
            throw new \Exception('End date must be in the future.');
        }

        return DB::transaction(function () use ($name, $multiplier, $startDate, $endDate) {
            // Backup all current CPM rates
            $allRates = CpmRate::all()->map(function ($rate) {
                return [
                    'id' => $rate->id,
                    'country_id' => $rate->country_id,
                    'publisher_rate' => $rate->publisher_rate,
                    'advertiser_rate' => $rate->advertiser_rate,
                ];
            })->toArray();

            // Create campaign record
            $campaign = CpmCampaign::create([
                'name' => $name,
                'multiplier' => $multiplier,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'original_rates_backup' => $allRates,
            ]);

            // Apply multiplier to publisher rates only (advertiser rates stay the same)
            CpmRate::query()->update([
                'publisher_rate' => DB::raw("publisher_rate * {$multiplier}"),
            ]);

            Log::info("CPM Campaign '{$name}' started with multiplier {$multiplier}x", [
                'campaign_id' => $campaign->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'rates_count' => count($allRates),
            ]);

            return $campaign;
        });
    }

    /**
     * Stop an active campaign and revert rates
     *
     * @param int|null $campaignId If null, stops the currently active campaign
     * @return bool
     * @throws \Exception
     */
    public function stopCampaign(?int $campaignId = null): bool
    {
        $campaign = $campaignId 
            ? CpmCampaign::findOrFail($campaignId)
            : CpmCampaign::active()->first();

        if (!$campaign) {
            throw new \Exception('No active campaign found to stop.');
        }

        if ($campaign->status !== 'active') {
            throw new \Exception('Campaign is not active and cannot be stopped.');
        }

        return DB::transaction(function () use ($campaign) {
            // Restore original rates from backup
            $this->restoreRatesFromBackup($campaign->original_rates_backup);

            // Mark campaign as cancelled
            $campaign->markAsCancelled();

            Log::info("CPM Campaign '{$campaign->name}' stopped manually", [
                'campaign_id' => $campaign->id,
            ]);

            return true;
        });
    }

    /**
     * Check for expired campaigns and auto-revert them
     *
     * @return int Number of campaigns expired
     */
    public function checkExpiredCampaigns(): int
    {
        $expiredCampaigns = CpmCampaign::active()
            ->where('end_date', '<=', now())
            ->get();

        $count = 0;

        foreach ($expiredCampaigns as $campaign) {
            DB::transaction(function () use ($campaign) {
                // Restore original rates
                $this->restoreRatesFromBackup($campaign->original_rates_backup);

                // Mark as expired
                $campaign->markAsExpired();

                Log::info("CPM Campaign '{$campaign->name}' expired and rates reverted", [
                    'campaign_id' => $campaign->id,
                    'end_date' => $campaign->end_date,
                ]);
            });

            $count++;
        }

        return $count;
    }

    /**
     * Get the currently active campaign, if any
     *
     * @return CpmCampaign|null
     */
    public function getActiveCampaign(): ?CpmCampaign
    {
        $this->checkExpiredCampaigns();

        return CpmCampaign::active()->first();
    }

    /**
     * Restore CPM rates from backup
     *
     * @param array $backup
     * @return void
     */
    protected function restoreRatesFromBackup(array $backup): void
    {
        foreach ($backup as $rateData) {
            // Only restore publisher rates (advertiser rates were never changed)
            CpmRate::where('id', $rateData['id'])->update([
                'publisher_rate' => $rateData['publisher_rate'],
            ]);
        }

        Log::debug('CPM rates restored from backup', [
            'rates_count' => count($backup),
        ]);
    }
}
