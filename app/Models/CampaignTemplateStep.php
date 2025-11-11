<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignTemplateStep extends Model
{
    protected $fillable = [
        'campaign_template_id',
        'step_number',
        'step_type',
        'wait_time',
        'show_popup',
    ];

    protected $casts = [
        'show_popup' => 'boolean',
        'step_type' => \App\Enums\StepType::class,
    ];

    public function campaignTemplate()
    {
        return $this->belongsTo(CampaignTemplate::class);
    }

    public function campaignTemplateAds()
    {
        return $this->hasMany(CampaignTemplateAd::class);
    }
}
