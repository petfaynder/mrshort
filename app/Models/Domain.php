<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'protocol',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active domain
     */
    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Set this domain as active (and deactivate others)
     */
    public function setAsActive(): void
    {
        // Deactivate all other domains
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this domain
        $this->update(['is_active' => true]);
    }

    /**
     * Get the full base URL for this domain
     */
    public function getBaseUrl(): string
    {
        return $this->protocol . '://' . $this->domain;
    }

    /**
     * Get the full short URL for a given code
     */
    public function getShortUrl(string $code): string
    {
        return $this->getBaseUrl() . '/' . $code;
    }

    /**
     * Links relationship (optional - for reference only)
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    /**
     * Scope for active domains
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
