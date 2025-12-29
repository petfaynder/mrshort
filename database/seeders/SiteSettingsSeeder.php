<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            ['group' => 'general', 'key' => 'site_name', 'value' => 'MRShort', 'type' => 'string', 'label' => 'Site Name', 'order' => 1],
            ['group' => 'general', 'key' => 'timezone', 'value' => 'UTC', 'type' => 'string', 'label' => 'Timezone', 'order' => 2],
            ['group' => 'general', 'key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'label' => 'Maintenance Mode', 'order' => 3],
            ['group' => 'general', 'key' => 'maintenance_message', 'value' => 'We are currently performing maintenance. Please check back soon.', 'type' => 'textarea', 'label' => 'Maintenance Message', 'order' => 4],
            ['group' => 'general', 'key' => 'display_homepage_stats', 'value' => '1', 'type' => 'boolean', 'label' => 'Homepage Stats', 'order' => 5],
            ['group' => 'general', 'key' => 'fake_users_base', 'value' => '0', 'type' => 'number', 'label' => 'Fake Users', 'order' => 6],
            ['group' => 'general', 'key' => 'fake_links_base', 'value' => '0', 'type' => 'number', 'label' => 'Fake Links', 'order' => 7],
            ['group' => 'general', 'key' => 'fake_clicks_base', 'value' => '0', 'type' => 'number', 'label' => 'Fake Clicks', 'order' => 8],
            ['group' => 'general', 'key' => 'display_cookie_notification', 'value' => '1', 'type' => 'boolean', 'label' => 'Cookie Notification', 'order' => 9],
            
            // SEO Settings
            ['group' => 'seo', 'key' => 'seo_meta_title', 'value' => 'MRShort - #1 Link Shortener', 'type' => 'string', 'label' => 'Meta Title', 'order' => 1],
            ['group' => 'seo', 'key' => 'seo_keywords', 'value' => 'link shortener, url shortener, earn money, shorten links', 'type' => 'textarea', 'label' => 'Keywords', 'order' => 2],
            ['group' => 'seo', 'key' => 'seo_description', 'value' => 'Shorten your links and earn money with every click. Best CPM rates worldwide.', 'type' => 'textarea', 'label' => 'Meta Description', 'order' => 3],
            
            // Currency Settings
            ['group' => 'currency', 'key' => 'currency_code', 'value' => 'USD', 'type' => 'string', 'label' => 'Currency Code', 'order' => 1],
            ['group' => 'currency', 'key' => 'currency_symbol', 'value' => '$', 'type' => 'string', 'label' => 'Currency Symbol', 'order' => 2],
            ['group' => 'currency', 'key' => 'currency_position', 'value' => 'before', 'type' => 'select', 'label' => 'Symbol Position', 'options' => '{"before":"Before ($50)","after":"After (50$)"}', 'order' => 3],
            ['group' => 'currency', 'key' => 'price_decimals', 'value' => '2', 'type' => 'number', 'label' => 'Decimal Places', 'order' => 4],
            
            // Link Settings
            ['group' => 'links', 'key' => 'link_code_length', 'value' => '6', 'type' => 'number', 'label' => 'Code Length', 'order' => 1],
            ['group' => 'links', 'key' => 'alias_min_length', 'value' => '4', 'type' => 'number', 'label' => 'Min Alias Length', 'order' => 2],
            ['group' => 'links', 'key' => 'alias_max_length', 'value' => '8', 'type' => 'number', 'label' => 'Max Alias Length', 'order' => 3],
            ['group' => 'links', 'key' => 'mass_shrinker_limit', 'value' => '20', 'type' => 'number', 'label' => 'Mass Shrinker Limit', 'order' => 4],
            ['group' => 'links', 'key' => 'banned_words', 'value' => '', 'type' => 'textarea', 'label' => 'Banned Words', 'order' => 5],
            ['group' => 'links', 'key' => 'disallowed_domains', 'value' => '', 'type' => 'textarea', 'label' => 'Disallowed Domains', 'order' => 6],
            ['group' => 'links', 'key' => 'reserved_aliases', 'value' => 'admin,api,login,register,dashboard', 'type' => 'textarea', 'label' => 'Reserved Aliases', 'order' => 7],
            
            // Earnings Settings
            ['group' => 'earnings', 'key' => 'default_cpm_rate', 'value' => '0.001', 'type' => 'number', 'label' => 'Default CPM', 'order' => 1],
            ['group' => 'earnings', 'key' => 'enable_referrals', 'value' => '1', 'type' => 'boolean', 'label' => 'Referral System', 'order' => 2],
            ['group' => 'earnings', 'key' => 'referral_commission_rate', 'value' => '15', 'type' => 'number', 'label' => 'Referral Commission (%)', 'order' => 3],
            ['group' => 'earnings', 'key' => 'paid_views_per_day', 'value' => '1', 'type' => 'number', 'label' => 'Paid Views Per Day', 'order' => 4],
            ['group' => 'earnings', 'key' => 'block_referrer_domains', 'value' => '', 'type' => 'textarea', 'label' => 'Blocked Referrer Domains', 'order' => 5],
            
            // User Settings
            ['group' => 'users', 'key' => 'close_registration', 'value' => '0', 'type' => 'boolean', 'label' => 'Close Registration', 'order' => 1],
            ['group' => 'users', 'key' => 'email_account_activation', 'value' => '1', 'type' => 'boolean', 'label' => 'Email Verification', 'order' => 2],
            ['group' => 'users', 'key' => 'signup_bonus', 'value' => '0', 'type' => 'number', 'label' => 'Signup Bonus', 'order' => 3],
            ['group' => 'users', 'key' => 'reserved_usernames', 'value' => 'admin,root,support,help,administrator', 'type' => 'textarea', 'label' => 'Reserved Usernames', 'order' => 4],
            
            // Security Settings
            ['group' => 'security', 'key' => 'enable_https_short_links', 'value' => '1', 'type' => 'boolean', 'label' => 'HTTPS Links', 'order' => 1],
            ['group' => 'security', 'key' => 'url_safety_enabled', 'value' => '0', 'type' => 'boolean', 'label' => 'URL Safety Check', 'order' => 2],
            ['group' => 'security', 'key' => 'google_safe_browsing_key', 'value' => '', 'type' => 'password', 'label' => 'Google Safe Browsing Key', 'is_encrypted' => true, 'order' => 3],
            ['group' => 'security', 'key' => 'phishtank_api_key', 'value' => '', 'type' => 'password', 'label' => 'PhishTank API Key', 'is_encrypted' => true, 'order' => 4],
            
            // Captcha Settings
            ['group' => 'captcha', 'key' => 'captcha_enabled', 'value' => '0', 'type' => 'boolean', 'label' => 'Captcha Enabled', 'order' => 1],
            ['group' => 'captcha', 'key' => 'captcha_provider', 'value' => 'turnstile', 'type' => 'select', 'label' => 'Provider', 'options' => '{"turnstile":"Cloudflare Turnstile","recaptcha_v2":"reCAPTCHA v2","recaptcha_v2_invisible":"reCAPTCHA v2 Invisible","recaptcha_v3":"reCAPTCHA v3","hcaptcha":"hCaptcha"}', 'order' => 2],
            ['group' => 'captcha', 'key' => 'captcha_site_key', 'value' => '', 'type' => 'string', 'label' => 'Site Key', 'order' => 3],
            ['group' => 'captcha', 'key' => 'captcha_secret_key', 'value' => '', 'type' => 'password', 'label' => 'Secret Key', 'is_encrypted' => true, 'order' => 4],
            ['group' => 'captcha', 'key' => 'captcha_v3_min_score', 'value' => '0.5', 'type' => 'number', 'label' => 'v3 Min Score', 'order' => 5],
            ['group' => 'captcha', 'key' => 'captcha_on_login', 'value' => '0', 'type' => 'boolean', 'label' => 'Login Captcha', 'order' => 6],
            ['group' => 'captcha', 'key' => 'captcha_on_register', 'value' => '1', 'type' => 'boolean', 'label' => 'Register Captcha', 'order' => 7],
            ['group' => 'captcha', 'key' => 'captcha_on_forgot_password', 'value' => '1', 'type' => 'boolean', 'label' => 'Forgot Password Captcha', 'order' => 8],
            ['group' => 'captcha', 'key' => 'captcha_on_contact', 'value' => '1', 'type' => 'boolean', 'label' => 'Contact Captcha', 'order' => 9],
            ['group' => 'captcha', 'key' => 'captcha_on_shortlink', 'value' => '0', 'type' => 'boolean', 'label' => 'Shortlink Captcha', 'order' => 10],
            ['group' => 'captcha', 'key' => 'captcha_on_home_shortener', 'value' => '1', 'type' => 'boolean', 'label' => 'Home Shortener Captcha', 'order' => 11],
            
            // Blog Settings
            ['group' => 'blog', 'key' => 'blog_enabled', 'value' => '0', 'type' => 'boolean', 'label' => 'Blog Enabled', 'order' => 1],
            ['group' => 'blog', 'key' => 'blog_posts_per_page', 'value' => '10', 'type' => 'number', 'label' => 'Posts Per Page', 'order' => 2],
            ['group' => 'blog', 'key' => 'blog_on_shortlink_page', 'value' => '0', 'type' => 'boolean', 'label' => 'Blog on Shortlink Page', 'order' => 3],
            ['group' => 'blog', 'key' => 'blog_comments_enabled', 'value' => '0', 'type' => 'boolean', 'label' => 'Comments Enabled', 'order' => 4],
            ['group' => 'blog', 'key' => 'disqus_shortname', 'value' => '', 'type' => 'string', 'label' => 'Disqus Shortname', 'order' => 5],
            
            // Social Settings
            ['group' => 'social', 'key' => 'facebook_url', 'value' => '', 'type' => 'string', 'label' => 'Facebook URL', 'order' => 1],
            ['group' => 'social', 'key' => 'twitter_url', 'value' => '', 'type' => 'string', 'label' => 'Twitter URL', 'order' => 2],
            ['group' => 'social', 'key' => 'instagram_url', 'value' => '', 'type' => 'string', 'label' => 'Instagram URL', 'order' => 3],
            ['group' => 'social', 'key' => 'youtube_url', 'value' => '', 'type' => 'string', 'label' => 'YouTube URL', 'order' => 4],
            ['group' => 'social', 'key' => 'linkedin_url', 'value' => '', 'type' => 'string', 'label' => 'LinkedIn URL', 'order' => 5],
            
            // Email Settings
            ['group' => 'email', 'key' => 'mail_from_address', 'value' => '', 'type' => 'string', 'label' => 'From Email', 'order' => 1],
            ['group' => 'email', 'key' => 'mail_from_name', 'value' => '', 'type' => 'string', 'label' => 'From Name', 'order' => 2],
            ['group' => 'email', 'key' => 'notify_admin_new_user', 'value' => '0', 'type' => 'boolean', 'label' => 'Admin: New User', 'order' => 3],
            ['group' => 'email', 'key' => 'notify_admin_new_withdrawal', 'value' => '1', 'type' => 'boolean', 'label' => 'Admin: New Withdrawal', 'order' => 4],
            ['group' => 'email', 'key' => 'notify_user_withdrawal_approved', 'value' => '1', 'type' => 'boolean', 'label' => 'User: Withdrawal Approved', 'order' => 5],
            ['group' => 'email', 'key' => 'notify_user_withdrawal_completed', 'value' => '1', 'type' => 'boolean', 'label' => 'User: Withdrawal Completed', 'order' => 6],
            ['group' => 'email', 'key' => 'notify_user_withdrawal_cancelled', 'value' => '1', 'type' => 'boolean', 'label' => 'User: Withdrawal Cancelled', 'order' => 7],
            
            // Cron Settings
            ['group' => 'cron', 'key' => 'delete_inactive_links_months', 'value' => '0', 'type' => 'number', 'label' => 'Delete Inactive Links (months)', 'order' => 1],
            ['group' => 'cron', 'key' => 'delete_unverified_users_months', 'value' => '0', 'type' => 'number', 'label' => 'Delete Unverified Users (months)', 'order' => 2],
            
            // Withdraw Settings
            ['group' => 'withdraw', 'key' => 'withdrawals_enabled', 'value' => '1', 'type' => 'boolean', 'label' => 'Withdrawals Enabled', 'order' => 1],
            ['group' => 'withdraw', 'key' => 'min_withdrawal_amount', 'value' => '5', 'type' => 'number', 'label' => 'Minimum Withdrawal', 'order' => 2],
            ['group' => 'withdraw', 'key' => 'withdraw_business_days', 'value' => '4', 'type' => 'number', 'label' => 'Processing Time (days)', 'order' => 3],
            
            // Integration Settings
            ['group' => 'integration', 'key' => 'front_head_code', 'value' => '', 'type' => 'textarea', 'label' => 'Public Head Code', 'order' => 1],
            ['group' => 'integration', 'key' => 'member_head_code', 'value' => '', 'type' => 'textarea', 'label' => 'Member Head Code', 'order' => 2],
            ['group' => 'integration', 'key' => 'footer_code', 'value' => '', 'type' => 'textarea', 'label' => 'Footer Code', 'order' => 3],
            
            // Design Settings
            ['group' => 'design', 'key' => 'logo_url', 'value' => '', 'type' => 'string', 'label' => 'Logo URL', 'order' => 1],
            ['group' => 'design', 'key' => 'logo_url_alt', 'value' => '', 'type' => 'string', 'label' => 'Alternative Logo URL', 'order' => 2],
            ['group' => 'design', 'key' => 'favicon_url', 'value' => '', 'type' => 'string', 'label' => 'Favicon URL', 'order' => 3],
        ];
        
        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
        
        $this->command->info('Site settings seeded successfully!');
    }
}
