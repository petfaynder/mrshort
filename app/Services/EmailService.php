<?php

namespace App\Services;

use App\Mail\WelcomeMail;
use App\Mail\WithdrawalStatusMail;
use App\Mail\AdminNotificationMail;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send welcome email to new user
     */
    public static function sendWelcomeEmail(User $user): void
    {
        if (!self::shouldSendEmail('notify_user_welcome', true)) {
            return;
        }
        
        try {
            $mail = new WelcomeMail($user);
            
            if (SiteSetting::shouldQueueEmails()) {
                Mail::to($user->email)->queue($mail);
            } else {
                Mail::to($user->email)->send($mail);
            }
            
            Log::info('Welcome email sent to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }
    
    /**
     * Send withdrawal status update email
     */
    public static function sendWithdrawalStatusEmail(WithdrawalRequest $withdrawal, string $status, ?string $reason = null): void
    {
        $settingKey = "notify_user_withdrawal_{$status}";
        
        if (!self::shouldSendEmail($settingKey, true)) {
            return;
        }
        
        $user = $withdrawal->user;
        if (!$user || !$user->email) {
            return;
        }
        
        try {
            $mail = new WithdrawalStatusMail($withdrawal, $status, $reason);
            
            if (SiteSetting::shouldQueueEmails()) {
                Mail::to($user->email)->queue($mail);
            } else {
                Mail::to($user->email)->send($mail);
            }
            
            Log::info("Withdrawal {$status} email sent to: " . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal status email: ' . $e->getMessage());
        }
    }
    
    /**
     * Send admin notification email
     */
    public static function sendAdminNotification(string $type, array $data): void
    {
        $settingKey = "notify_admin_{$type}";
        
        if (!self::shouldSendEmail($settingKey, false)) {
            return;
        }
        
        $adminEmail = SiteSetting::get('admin_email');
        if (!$adminEmail) {
            // Try to find first admin user
            $admin = User::where('is_admin', true)->first();
            $adminEmail = $admin?->email;
        }
        
        if (!$adminEmail) {
            Log::warning('No admin email configured for notification: ' . $type);
            return;
        }
        
        try {
            $mail = new AdminNotificationMail($type, $data);
            
            if (SiteSetting::shouldQueueEmails()) {
                Mail::to($adminEmail)->queue($mail);
            } else {
                Mail::to($adminEmail)->send($mail);
            }
            
            Log::info("Admin notification ({$type}) sent to: " . $adminEmail);
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Check if email should be sent based on site settings
     */
    protected static function shouldSendEmail(string $settingKey, bool $default = true): bool
    {
        return (bool) SiteSetting::get($settingKey, $default);
    }
}
