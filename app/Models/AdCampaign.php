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
        'approval_status',
        'rejection_reason',
        'approved_at',
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
        'campaign_schedule',
        'is_telegram_promotion',
    ];

    protected $casts = [
        'targeting_rules' => 'json',
        'is_active' => 'boolean',
        'campaign_type' => \App\Enums\CampaignType::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'approved_at' => 'datetime',
        'daily_click_limit' => 'integer',
        'frequency_cap' => 'integer',
        'frequency_cap_unit' => 'string',
        'estimated_traffic' => 'integer',
        'available_traffic' => 'integer',
        'budget' => 'decimal:2',
        'run_until_budget_depleted' => 'boolean',
        'campaign_schedule' => 'json',
        'is_telegram_promotion' => 'boolean',
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
