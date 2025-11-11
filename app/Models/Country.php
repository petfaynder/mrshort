<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'iso_code', 'cpm_tier_id'];

    public function cpmTier()
    {
        return $this->belongsTo(CpmTier::class);
    }

    public function cpmRates()
    {
        return $this->hasMany(CpmRate::class);
    }
}