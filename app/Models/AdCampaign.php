<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\AdStep;

class AdCampaign extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'campaign_type',
        'is_active',
        'targeting_rules',
        'total_impressions',
        'total_clicks',
        'campaign_template_id',
        'start_date',
        'end_date',
        'daily_click_limit',
        'frequency_cap',
        'frequency_cap_unit',
        'estimated_traffic',
        'available_traffic',
        'budget',
        'run_until_budget_depleted',
        'campaign_schedule', // Add this field
    ];

    protected $casts = [
        'targeting_rules' => 'json',
        'is_active' => 'boolean',
        'campaign_type' => \App\Enums\CampaignType::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'daily_click_limit' => 'integer',
        'frequency_cap' => 'integer',
        'frequency_cap_unit' => 'string', // Will create an Enum for this later if needed
        'estimated_traffic' => 'integer',
        'available_traffic' => 'integer',
        'budget' => 'decimal:2',
        'run_until_budget_depleted' => 'boolean',
        'campaign_schedule' => 'json', // Cast as JSON
    ];

    /**
     * Kampanyayı oluşturan kullanıcıyı getirir.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kampanyaya ait reklam adımlarını getirir.
     */
    public function adSteps(): HasMany
    {
        return $this->hasMany(AdStep::class)->orderBy('step_number');
    }

    /**
     * Kampanyanın türetildiği şablonu getirir.
     */
    public function campaignTemplate(): BelongsTo
    {
        return $this->belongsTo(CampaignTemplate::class);
    }
}
