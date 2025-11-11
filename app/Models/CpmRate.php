<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpmRate extends Model
{
    use HasFactory;

    protected $fillable = ['cpm_tier_id', 'country_id', 'rate', 'advertiser_rate'];

    public function cpmTier()
    {
        return $this->belongsTo(CpmTier::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
