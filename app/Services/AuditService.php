<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log an action.
     */
    public static function log(
        string $action,
        ?string $description = null,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
        ]);
    }

    /**
     * Log user login.
     */
    public static function logLogin(int $userId, ?string $email = null): AuditLog
    {
        return self::log(
            action: 'login',
            description: "User logged in" . ($email ? " ({$email})" : ""),
            modelType: \App\Models\User::class,
            modelId: $userId
        );
    }

    /**
     * Log failed login attempt.
     */
    public static function logFailedLogin(string $email): AuditLog
    {
        return AuditLog::create([
            'user_id' => null,
            'action' => 'login_failed',
            'description' => "Failed login attempt for email: {$email}",
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
        ]);
    }

    /**
     * Log user logout.
     */
    public static function logLogout(): AuditLog
    {
        return self::log(
            action: 'logout',
            description: "User logged out"
        );
    }

    /**
     * Log withdrawal request.
     */
    public static function logWithdrawalRequest(int $withdrawalId, float $amount, string $method): AuditLog
    {
        return self::log(
            action: 'withdrawal_request',
            description: "Withdrawal request for \${$amount} via {$method}",
            modelType: \App\Models\WithdrawalRequest::class,
            modelId: $withdrawalId,
            newValues: ['amount' => $amount, 'method' => $method]
        );
    }

    /**
     * Log withdrawal status change.
     */
    public static function logWithdrawalStatusChange(
        int $withdrawalId,
        string $oldStatus,
        string $newStatus,
        ?string $reason = null
    ): AuditLog {
        $action = match($newStatus) {
            'approved', 'completed' => 'withdrawal_approved',
            'rejected' => 'withdrawal_rejected',
            default => 'withdrawal_updated'
        };

        return self::log(
            action: $action,
            description: "Withdrawal status changed from {$oldStatus} to {$newStatus}" . ($reason ? ": {$reason}" : ""),
            modelType: \App\Models\WithdrawalRequest::class,
            modelId: $withdrawalId,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => $newStatus, 'reason' => $reason]
        );
    }

    /**
     * Log admin impersonation.
     */
    public static function logImpersonation(int $targetUserId, string $targetEmail): AuditLog
    {
        return self::log(
            action: 'admin_impersonate',
            description: "Admin started impersonating user: {$targetEmail}",
            modelType: \App\Models\User::class,
            modelId: $targetUserId
        );
    }

    /**
     * Log user deactivation.
     */
    public static function logUserDeactivation(int $userId, string $reason): AuditLog
    {
        return self::log(
            action: 'user_deactivated',
            description: "User deactivated: {$reason}",
            modelType: \App\Models\User::class,
            modelId: $userId,
            newValues: ['reason' => $reason]
        );
    }

    /**
     * Log user reactivation.
     */
    public static function logUserReactivation(int $userId): AuditLog
    {
        return self::log(
            action: 'user_reactivated',
            description: "User account reactivated",
            modelType: \App\Models\User::class,
            modelId: $userId
        );
    }

    /**
     * Log settings change.
     */
    public static function logSettingsChange(string $key, $oldValue, $newValue): AuditLog
    {
        return self::log(
            action: 'settings_changed',
            description: "Setting '{$key}' was changed",
            oldValues: [$key => $oldValue],
            newValues: [$key => $newValue]
        );
    }

    /**
     * Generic action logger.
     */
    public static function logAction(string $action, string $description, $model = null): AuditLog
    {
        return self::log(
            action: $action,
            description: $description,
            modelType: $model ? get_class($model) : null,
            modelId: $model?->id
        );
    }
}
