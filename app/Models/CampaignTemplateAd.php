<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignTemplateAd extends Model
{
    protected $fillable = [
        'campaign_template_step_id',
        'ad_type',
        'ad_data',
    ];

    protected $casts = [
        'ad_data' => 'array',
        'ad_type' => \App\Enums\AdType::class,
    ];

    public function campaignTemplateStep()
    {
        return $this->belongsTo(CampaignTemplateStep::class);
    }
}
