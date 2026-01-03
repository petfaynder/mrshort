<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model associated with the log.
     */
    public function subject()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    /**
     * Scope for filtering by action type.
     */
    public function scopeOfAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get formatted action name.
     */
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'login' => '🔐 Login',
            'logout' => '🚪 Logout',
            'login_failed' => '❌ Failed Login',
            'withdrawal_request' => '💰 Withdrawal Request',
            'withdrawal_approved' => '✅ Withdrawal Approved',
            'withdrawal_rejected' => '❌ Withdrawal Rejected',
            'user_deactivated' => '🚫 User Deactivated',
            'user_reactivated' => '✅ User Reactivated',
            'user_banned' => '⛔ User Banned',
            'admin_impersonate' => '👤 Admin Impersonation',
            'settings_changed' => '⚙️ Settings Changed',
            'link_created' => '🔗 Link Created',
            'link_deleted' => '🗑️ Link Deleted',
            'ad_campaign_created' => '📢 Ad Campaign Created',
            'ad_campaign_updated' => '✏️ Ad Campaign Updated',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }
}
