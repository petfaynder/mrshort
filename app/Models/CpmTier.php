<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpmTier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'default_publisher_cpm_rate', 'default_advertiser_cpm_rate'];

    protected $casts = [
        'default_publisher_cpm_rate' => 'decimal:4',
        'default_advertiser_cpm_rate' => 'decimal:4',
    ];

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    public function cpmRates()
    {
        return $this->hasMany(CpmRate::class);
    }
}
