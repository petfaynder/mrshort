<?php
// Run me with: php artisan tinker < clear.php
$campaigns = App\Models\CpmCampaign::where('status', 'active')->get();
foreach ($campaigns as $campaign) {
    echo "Found campaign: ID={$campaign->id}, Name={$campaign->name}, Start={$campaign->start_date}, End={$campaign->end_date}\n";
    $campaign->markAsCancelled();
    echo "Marked as cancelled.\n";
}
echo "Done.\n";
