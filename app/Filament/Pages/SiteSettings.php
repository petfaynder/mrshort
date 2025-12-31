<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.site-settings';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $title = 'Site Settings';
    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public function mount(): void
    {
        $this->loadSettings();
    }

    protected function loadSettings(): void
    {
        // Set default values for settings that may not exist in database
        $defaults = [
            'popup_admin_weight' => 70,
            'popup_user_campaigns_enabled' => true,
        ];
        
        // Initialize with defaults
        foreach ($defaults as $key => $value) {
            $this->data[$key] = $value;
        }
        
        // Override with actual database values
        $settings = SiteSetting::all();
        foreach ($settings as $setting) {
            $dbValue = SiteSetting::get($setting->key);
            // Convert '0' and '1' strings back to boolean for toggles
            if (in_array($setting->key, ['popup_user_campaigns_enabled']) && !is_bool($dbValue)) {
                $dbValue = $dbValue === '1' || $dbValue === 1 || $dbValue === true;
            }
            $this->data[$setting->key] = $dbValue;
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        $this->getGeneralTab(),
                        $this->getSeoTab(),
                        $this->getCurrencyTab(),
                        $this->getLinksTab(),
                        $this->getEarningsTab(),
                        $this->getUsersTab(),
                        $this->getSecurityTab(),
                        $this->getCaptchaTab(),
                        $this->getBlogTab(),
                        $this->getSocialTab(),
                        $this->getEmailTab(),
                        $this->getCronTab(),
                        $this->getWithdrawTab(),
                        $this->getIntegrationTab(),
                        $this->getAdvertisingTab(),
                        $this->getWordPressTab(),
                        $this->getPerformanceTab(),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getGeneralTab(): Tab
    {
        return Tab::make('General')
            ->icon('heroicon-o-home')
            ->schema([
                Section::make('Site Information')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Site Name')
                            ->placeholder('MRShort'),
                        Select::make('timezone')
                            ->label('Timezone')
                            ->options([
                                'UTC' => 'UTC',
                                'America/New_York' => 'New York (UTC-5)',
                                'Europe/London' => 'London (UTC+0)',
                                'Europe/Istanbul' => 'Istanbul (UTC+3)',
                                'Asia/Tokyo' => 'Tokyo (UTC+9)',
                            ])
                            ->default('UTC'),
                    ])->columns(2),
                Section::make('Maintenance Mode')
                    ->schema([
                        Toggle::make('maintenance_mode')
                            ->label('Enable Maintenance Mode')
                            ->helperText('When enabled, visitors will see a maintenance page'),
                        Textarea::make('maintenance_message')
                            ->label('Maintenance Message')
                            ->placeholder('We are currently performing maintenance. Please check back soon.')
                            ->rows(3),
                    ])->columns(2),
                Section::make('Homepage Counters')
                    ->description('Add base values to the counters displayed on homepage')
                    ->schema([
                        Toggle::make('display_homepage_stats')
                            ->label('Show Counters')
                            ->default(true),
                        TextInput::make('fake_users_base')
                            ->label('Users Counter Addition')
                            ->numeric()
                            ->default(0)
                            ->helperText('Added to real count'),
                        TextInput::make('fake_links_base')
                            ->label('Links Counter Addition')
                            ->numeric()
                            ->default(0),
                        TextInput::make('fake_clicks_base')
                            ->label('Clicks Counter Addition')
                            ->numeric()
                            ->default(0),
                    ])->columns(4),
                Section::make('Cookie Notice')
                    ->schema([
                        Toggle::make('display_cookie_notification')
                            ->label('Show Cookie Notice')
                            ->helperText('GDPR compliant cookie warning')
                            ->default(true),
                    ]),
            ]);
    }

    protected function getSeoTab(): Tab
    {
        return Tab::make('SEO')
            ->icon('heroicon-o-magnifying-glass')
            ->schema([
                Section::make('Meta Tags')
                    ->schema([
                        TextInput::make('seo_meta_title')
                            ->label('Meta Title')
                            ->placeholder('MRShort - #1 Link Shortener')
                            ->maxLength(70)
                            ->helperText('Title shown in Google search results (max 70 chars)'),
                        Textarea::make('seo_keywords')
                            ->label('Keywords')
                            ->placeholder('link shortener, url shortener, earn money')
                            ->rows(2)
                            ->helperText('Separate with commas'),
                        Textarea::make('seo_description')
                            ->label('Meta Description')
                            ->placeholder('Shorten your links and earn money with every click.')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Description shown in Google search results (max 160 chars)'),
                    ]),
            ]);
    }

    protected function getCurrencyTab(): Tab
    {
        return Tab::make('Currency')
            ->icon('heroicon-o-currency-dollar')
            ->schema([
                Section::make('Currency Settings')
                    ->description('Currency format displayed across the site')
                    ->schema([
                        TextInput::make('currency_code')
                            ->label('Currency Code')
                            ->placeholder('USD')
                            ->maxLength(5)
                            ->default('USD'),
                        TextInput::make('currency_symbol')
                            ->label('Currency Symbol')
                            ->placeholder('$')
                            ->maxLength(5)
                            ->default('$'),
                        Select::make('currency_position')
                            ->label('Symbol Position')
                            ->options([
                                'before' => 'Before ($50)',
                                'after' => 'After (50$)',
                            ])
                            ->default('before'),
                        TextInput::make('price_decimals')
                            ->label('Decimal Places')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(4)
                            ->default(2)
                            ->helperText('Number of decimals in prices'),
                    ])->columns(4),
            ]);
    }

    protected function getLinksTab(): Tab
    {
        return Tab::make('Links')
            ->icon('heroicon-o-link')
            ->schema([
                Section::make('Link Creation')
                    ->schema([
                        TextInput::make('link_code_length')
                            ->label('Code Length')
                            ->numeric()
                            ->minValue(4)
                            ->maxValue(12)
                            ->default(6)
                            ->helperText('Number of characters in short link'),
                        TextInput::make('alias_min_length')
                            ->label('Min Alias Length')
                            ->numeric()
                            ->default(4),
                        TextInput::make('alias_max_length')
                            ->label('Max Alias Length')
                            ->numeric()
                            ->default(8),
                        TextInput::make('mass_shrinker_limit')
                            ->label('Mass Shrinker Limit')
                            ->numeric()
                            ->default(20)
                            ->helperText('Max URLs at once'),
                    ])->columns(4),
                Section::make('Restrictions')
                    ->schema([
                        Textarea::make('banned_words')
                            ->label('Banned Words')
                            ->placeholder('porn,sex,warez,hack')
                            ->helperText('Comma separated. URLs containing these will be blocked')
                            ->rows(2),
                        Textarea::make('disallowed_domains')
                            ->label('Disallowed Domains')
                            ->placeholder('bit.ly,adf.ly,goo.gl')
                            ->helperText('Comma separated. Links from these domains cannot be shortened')
                            ->rows(2),
                        Textarea::make('reserved_aliases')
                            ->label('Reserved Aliases')
                            ->placeholder('admin,api,login,register')
                            ->helperText('These aliases cannot be used')
                            ->rows(2),
                    ])->columns(3),
            ]);
    }

    protected function getEarningsTab(): Tab
    {
        return Tab::make('Earnings')
            ->icon('heroicon-o-banknotes')
            ->schema([
                Section::make('CPM Settings')
                    ->schema([
                        TextInput::make('default_cpm_rate')
                            ->label('Default CPM')
                            ->numeric()
                            ->step(0.0001)
                            ->default(0.001)
                            ->helperText('Rate per 1000 views for unknown countries'),
                    ]),
                Section::make('Referral System')
                    ->schema([
                        Toggle::make('enable_referrals')
                            ->label('Enable Referral System')
                            ->default(true),
                        TextInput::make('referral_commission_rate')
                            ->label('Referral Commission (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(15)
                            ->helperText('Percentage of referred user earnings'),
                    ])->columns(2),
                Section::make('Click Limits')
                    ->schema([
                        TextInput::make('paid_views_per_day')
                            ->label('Paid Views Per Day (per IP)')
                            ->numeric()
                            ->default(1)
                            ->helperText('Max paid clicks from same IP per day'),
                        Textarea::make('block_referrer_domains')
                            ->label('Blocked Referrer Domains')
                            ->placeholder('facebook.com,twitter.com')
                            ->helperText('Clicks from these sites are not paid')
                            ->rows(2),
                    ])->columns(2),
            ]);
    }

    protected function getUsersTab(): Tab
    {
        return Tab::make('Users')
            ->icon('heroicon-o-users')
            ->schema([
                Section::make('Registration Settings')
                    ->schema([
                        Toggle::make('close_registration')
                            ->label('Close Registration')
                            ->helperText('When enabled, new registrations are not allowed'),
                        Toggle::make('email_account_activation')
                            ->label('Require Email Verification')
                            ->default(true),
                        TextInput::make('signup_bonus')
                            ->label('Signup Bonus')
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->helperText('Starting balance for new users'),
                    ])->columns(3),
                Section::make('Restrictions')
                    ->schema([
                        Textarea::make('reserved_usernames')
                            ->label('Reserved Usernames')
                            ->placeholder('admin,root,support,help')
                            ->helperText('These names cannot be used during registration')
                            ->rows(2),
                    ]),
            ]);
    }

    protected function getSecurityTab(): Tab
    {
        return Tab::make('Security')
            ->icon('heroicon-o-shield-check')
            ->schema([
                Section::make('HTTPS')
                    ->schema([
                        Toggle::make('enable_https_short_links')
                            ->label('HTTPS Short Links')
                            ->default(true)
                            ->helperText('Generated links start with https://'),
                    ]),
                Section::make('URL Safety Check')
                    ->description('API integrations to detect malicious URLs')
                    ->schema([
                        Toggle::make('url_safety_enabled')
                            ->label('Enable Safety Check')
                            ->helperText('Check URLs for malware when creating links'),
                        TextInput::make('google_safe_browsing_key')
                            ->label('Google Safe Browsing API Key')
                            ->password()
                            ->revealable()
                            ->helperText('For malware and phishing detection'),
                        TextInput::make('phishtank_api_key')
                            ->label('PhishTank API Key')
                            ->password()
                            ->revealable()
                            ->helperText('For phishing URL detection'),
                    ])->columns(3),
            ]);
    }

    protected function getCaptchaTab(): Tab
    {
        return Tab::make('Captcha')
            ->icon('heroicon-o-puzzle-piece')
            ->schema([
                Section::make('Captcha Settings')
                    ->schema([
                        Toggle::make('captcha_enabled')
                            ->label('Enable Captcha')
                            ->reactive(),
                        Select::make('captcha_provider')
                            ->label('Provider')
                            ->options([
                                'turnstile' => 'Cloudflare Turnstile',
                                'recaptcha_v2' => 'Google reCAPTCHA v2 (Checkbox)',
                                'recaptcha_v2_invisible' => 'Google reCAPTCHA v2 (Invisible)',
                                'recaptcha_v3' => 'Google reCAPTCHA v3 (Score-based)',
                                'hcaptcha' => 'hCaptcha',
                            ])
                            ->default('turnstile'),
                        TextInput::make('captcha_site_key')
                            ->label('Site Key'),
                        TextInput::make('captcha_secret_key')
                            ->label('Secret Key')
                            ->password()
                            ->revealable(),
                        TextInput::make('captcha_v3_min_score')
                            ->label('v3 Min Score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(1)
                            ->step(0.1)
                            ->default(0.5)
                            ->helperText('Minimum success score for reCAPTCHA v3'),
                    ])->columns(3),
                Section::make('Form Integrations')
                    ->description('Which forms should show captcha')
                    ->schema([
                        Toggle::make('captcha_on_login')->label('Login Form')->default(false),
                        Toggle::make('captcha_on_register')->label('Register Form')->default(true),
                        Toggle::make('captcha_on_forgot_password')->label('Forgot Password')->default(true),
                        Toggle::make('captcha_on_contact')->label('Contact Form')->default(true),
                        Toggle::make('captcha_on_shortlink')->label('Link Transition Page (All Ad Steps)')->default(false),
                        Toggle::make('captcha_on_home_shortener')->label('Homepage Shortener')->default(true),
                    ])->columns(3),
            ]);
    }

    protected function getBlogTab(): Tab
    {
        return Tab::make('Blog')
            ->icon('heroicon-o-document-text')
            ->schema([
                Section::make('Blog Settings')
                    ->schema([
                        Toggle::make('blog_enabled')
                            ->label('Enable Blog')
                            ->reactive(),
                        TextInput::make('blog_posts_per_page')
                            ->label('Posts Per Page')
                            ->numeric()
                            ->default(10),
                        Toggle::make('blog_on_shortlink_page')
                            ->label('Blog on Interstitial Page')
                            ->helperText('Show random blog post on link interstitial page'),
                    ])->columns(3),
                Section::make('Comments')
                    ->schema([
                        Toggle::make('blog_comments_enabled')
                            ->label('Enable Comments'),
                        TextInput::make('disqus_shortname')
                            ->label('Disqus Shortname')
                            ->placeholder('your-site-name')
                            ->helperText('Get from your Disqus account'),
                    ])->columns(2),
            ]);
    }

    protected function getSocialTab(): Tab
    {
        return Tab::make('Social')
            ->icon('heroicon-o-share')
            ->schema([
                Section::make('Social Media Links')
                    ->description('Social media links to display in footer and header')
                    ->schema([
                        TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url()
                            ->placeholder('https://facebook.com/yourpage'),
                        TextInput::make('twitter_url')
                            ->label('Twitter/X')
                            ->url()
                            ->placeholder('https://twitter.com/yourprofile'),
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->placeholder('https://instagram.com/yourprofile'),
                        TextInput::make('youtube_url')
                            ->label('YouTube')
                            ->url()
                            ->placeholder('https://youtube.com/yourchannel'),
                        TextInput::make('linkedin_url')
                            ->label('LinkedIn')
                            ->url()
                            ->placeholder('https://linkedin.com/company/yourcompany'),
                    ])->columns(3),
            ]);
    }

    protected function getEmailTab(): Tab
    {
        return Tab::make('Email')
            ->icon('heroicon-o-envelope')
            ->schema([
                Section::make('Sender Information')
                    ->schema([
                        TextInput::make('mail_from_address')
                            ->label('From Email')
                            ->email()
                            ->placeholder('noreply@yoursite.com'),
                        TextInput::make('mail_from_name')
                            ->label('From Name')
                            ->placeholder('MRShort'),
                    ])->columns(2),
                Section::make('Admin Notifications')
                    ->description('Email notifications sent to admin')
                    ->schema([
                        Toggle::make('notify_admin_new_user')
                            ->label('New User Registration'),
                        Toggle::make('notify_admin_new_withdrawal')
                            ->label('New Withdrawal Request')
                            ->default(true),
                    ])->columns(2),
                Section::make('User Notifications')
                    ->description('Email notifications sent to users')
                    ->schema([
                        Toggle::make('notify_user_withdrawal_approved')
                            ->label('Withdrawal Approved')
                            ->default(true),
                        Toggle::make('notify_user_withdrawal_completed')
                            ->label('Withdrawal Completed')
                            ->default(true),
                        Toggle::make('notify_user_withdrawal_cancelled')
                            ->label('Withdrawal Cancelled')
                            ->default(true),
                    ])->columns(3),
            ]);
    }

    protected function getCronTab(): Tab
    {
        return Tab::make('Cron')
            ->icon('heroicon-o-clock')
            ->schema([
                Section::make('Automatic Cleanup')
                    ->description('Scheduled cleanup tasks (0 = disabled)')
                    ->schema([
                        TextInput::make('delete_inactive_links_months')
                            ->label('Delete Inactive Links (months)')
                            ->numeric()
                            ->default(0)
                            ->helperText('Links with no clicks for X months are deleted (0 = disabled)'),
                        TextInput::make('delete_unverified_users_months')
                            ->label('Delete Unverified Users (months)')
                            ->numeric()
                            ->default(0)
                            ->helperText('Users not verified within X months are deleted'),
                    ])->columns(2),
            ]);
    }

    protected function getWithdrawTab(): Tab
    {
        return Tab::make('Withdrawals')
            ->icon('heroicon-o-credit-card')
            ->schema([
                Section::make('Withdrawal Settings')
                    ->schema([
                        Toggle::make('withdrawals_enabled')
                            ->label('Enable Withdrawals')
                            ->default(true),
                        TextInput::make('min_withdrawal_amount')
                            ->label('Minimum Withdrawal')
                            ->numeric()
                            ->default(5)
                            ->prefix('$'),
                        TextInput::make('withdraw_business_days')
                            ->label('Processing Time (business days)')
                            ->numeric()
                            ->default(4)
                            ->helperText('Estimated time shown to users'),
                    ])->columns(3),
            ]);
    }

    protected function getIntegrationTab(): Tab
    {
        return Tab::make('Integration')
            ->icon('heroicon-o-code-bracket')
            ->schema([
                Section::make('Custom Code')
                    ->description('Custom JavaScript/CSS code to inject into pages')
                    ->schema([
                        Textarea::make('front_head_code')
                            ->label('Public Pages - Head')
                            ->placeholder('<script>...</script>')
                            ->helperText('Homepage, login, register etc.')
                            ->rows(4),
                        Textarea::make('member_head_code')
                            ->label('Member Area - Head')
                            ->placeholder('<script>...</script>')
                            ->helperText('Dashboard, links, withdrawals etc.')
                            ->rows(4),
                        Textarea::make('footer_code')
                            ->label('All Pages - Footer')
                            ->placeholder('<script>...</script>')
                            ->helperText('Before </body> on all pages')
                            ->rows(4),
                    ])->columns(3),
            ]);
    }

    protected function getAdvertisingTab(): Tab
    {
        return Tab::make('Advertising')
            ->icon('heroicon-o-megaphone')
            ->schema([
                Section::make('Pop-under Priority Settings')
                    ->description('Configure priority between admin and user pop-under URLs when both exist')
                    ->schema([
                        TextInput::make('popup_admin_weight')
                            ->label('Admin Pop-under Weight (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(70)
                            ->helperText('Percentage chance to show admin pop-under (user weight = 100 - admin)'),
                    ])->columns(1),
                Section::make('Pop-under Behavior')
                    ->schema([
                        Toggle::make('popup_user_campaigns_enabled')
                            ->label('Enable User Pop-under Campaigns')
                            ->default(true)
                            ->helperText('When disabled, only admin pop-unders will be shown'),
                    ]),
            ]);
    }

    protected function getWordPressTab(): Tab
    {
        return Tab::make('WordPress')
            ->icon('heroicon-o-globe-alt')
            ->schema([
                Section::make('WordPress Integration')
                    ->description('Connect your WordPress blog for AdSense monetization')
                    ->schema([
                        Toggle::make('wordpress_enabled')
                            ->label('Enable WordPress Integration')
                            ->reactive()
                            ->helperText('Allow links to be served through your WordPress blog'),
                        TextInput::make('wordpress_domain')
                            ->label('WordPress Blog URL')
                            ->url()
                            ->placeholder('https://blog.yoursite.com')
                            ->helperText('Your WordPress blog address'),
                        TextInput::make('wordpress_api_token')
                            ->label('API Token')
                            ->password()
                            ->revealable()
                            ->helperText('Copy this token to your WordPress plugin settings')
                            ->suffixAction(
                                \Filament\Forms\Components\Actions\Action::make('generateToken')
                                    ->icon('heroicon-o-arrow-path')
                                    ->tooltip('Generate new token')
                                    ->action(function ($set) {
                                        $set('wordpress_api_token', bin2hex(random_bytes(32)));
                                    })
                            ),
                    ])->columns(1),
                Section::make('Redirect Page Settings')
                    ->description('Configure how visitors experience your WordPress redirect pages')
                    ->schema([
                        TextInput::make('wordpress_pages_count')
                            ->label('Number of Pages')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->default(2)
                            ->helperText('How many AdSense pages to show before redirecting to main site'),
                        TextInput::make('wordpress_wait_time')
                            ->label('Wait Time (seconds)')
                            ->numeric()
                            ->minValue(3)
                            ->maxValue(30)
                            ->default(5)
                            ->helperText('How long visitors must wait on each page'),
                    ])->columns(2),
            ]);
    }


    public function save(): void
    {
        $data = $this->data;
        
        foreach ($data as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            
            // Convert boolean to string '1' or '0' for proper storage
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }
            
            if ($setting) {
                if ($setting->is_encrypted && $value) {
                    $value = \Illuminate\Support\Facades\Crypt::encryptString($value);
                }
                $setting->update(['value' => is_array($value) ? json_encode($value) : $value]);
            } else {
                SiteSetting::create([
                    'group' => $this->guessGroup($key),
                    'key' => $key,
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'type' => $this->guessType($value),
                ]);
            }
        }
        
        SiteSetting::clearCache();
        
        Notification::make()
            ->title('Settings saved successfully.')
            ->success()
            ->send();
    }

    protected function guessGroup(string $key): string
    {
        $prefixes = [
            'site_' => 'general', 'seo_' => 'seo', 'currency_' => 'currency',
            'link_' => 'links', 'alias_' => 'links', 'banned_' => 'links',
            'disallowed_' => 'links', 'reserved_' => 'links', 'mass_' => 'links',
            'referral_' => 'earnings', 'default_cpm_' => 'earnings', 'paid_' => 'earnings',
            'block_' => 'earnings', 'enable_referrals' => 'earnings',
            'close_' => 'users', 'email_account_' => 'users', 'signup_' => 'users',
            'captcha_' => 'captcha', 'url_safety_' => 'security', 'google_safe_' => 'security',
            'phishtank_' => 'security', 'enable_https_' => 'security',
            'blog_' => 'blog', 'disqus_' => 'blog',
            'facebook_' => 'social', 'twitter_' => 'social', 'instagram_' => 'social',
            'youtube_' => 'social', 'linkedin_' => 'social',
            'mail_' => 'email', 'notify_' => 'email', 'delete_' => 'cron',
            'withdrawals_' => 'withdraw', 'min_withdrawal_' => 'withdraw', 'withdraw_' => 'withdraw',
            'front_' => 'integration', 'member_' => 'integration', 'footer_' => 'integration',
            'maintenance_' => 'general', 'display_' => 'general', 'fake_' => 'general',
            'timezone' => 'general', 'price_' => 'currency',
            'popup_' => 'advertising',
            'wordpress_' => 'wordpress',
            'cache_' => 'performance', 'queue_' => 'performance',
        ];
        
        foreach ($prefixes as $prefix => $group) {
            if (str_starts_with($key, $prefix)) {
                return $group;
            }
        }
        return 'general';
    }

    protected function guessType(mixed $value): string
    {
        if (is_bool($value)) return 'boolean';
        if (is_numeric($value)) return 'number';
        if (is_array($value)) return 'json';
        return 'string';
    }

    protected function getPerformanceTab(): Tab
    {
        return Tab::make('Performance')
            ->icon('heroicon-o-bolt')
            ->schema([
                Section::make('System Status')
                    ->description('Current performance system status')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('cache_driver')
                            ->label('Cache Driver')
                            ->content(fn () => strtoupper(config('cache.default'))),
                        \Filament\Forms\Components\Placeholder::make('session_driver')
                            ->label('Session Driver')
                            ->content(fn () => strtoupper(config('session.driver'))),
                        \Filament\Forms\Components\Placeholder::make('queue_driver')
                            ->label('Queue Driver')
                            ->content(fn () => strtoupper(config('queue.default'))),
                        \Filament\Forms\Components\Placeholder::make('redis_status')
                            ->label('Redis Status')
                            ->content(function () {
                                try {
                                    if (config('cache.default') === 'redis') {
                                        \Illuminate\Support\Facades\Redis::ping();
                                        return new \Illuminate\Support\HtmlString('<span class="text-green-500 font-semibold">✓ Connected</span>');
                                    }
                                    return new \Illuminate\Support\HtmlString('<span class="text-gray-500">Not configured</span>');
                                } catch (\Exception $e) {
                                    return new \Illuminate\Support\HtmlString('<span class="text-red-500 font-semibold">✗ Disconnected</span>');
                                }
                            }),
                        \Filament\Forms\Components\Placeholder::make('opcache_status')
                            ->label('OPcache Status')
                            ->content(function () {
                                if (function_exists('opcache_get_status')) {
                                    $status = @opcache_get_status(false);
                                    if ($status && $status['opcache_enabled']) {
                                        $memory = round($status['memory_usage']['used_memory'] / 1024 / 1024, 1);
                                        return new \Illuminate\Support\HtmlString("<span class='text-green-500 font-semibold'>✓ Enabled ({$memory}MB used)</span>");
                                    }
                                }
                                return new \Illuminate\Support\HtmlString('<span class="text-yellow-500">Disabled</span>');
                            }),
                        \Filament\Forms\Components\Placeholder::make('php_version')
                            ->label('PHP Version')
                            ->content(fn () => PHP_VERSION),
                    ])->columns(3),
                Section::make('Cache Settings')
                    ->description('Configure caching behavior')
                    ->schema([
                        TextInput::make('cache_ttl_default')
                            ->label('Default Cache TTL (seconds)')
                            ->numeric()
                            ->default(3600)
                            ->helperText('How long to cache data by default (3600 = 1 hour)'),
                        TextInput::make('cache_ttl_leaderboard')
                            ->label('Leaderboard Cache TTL (seconds)')
                            ->numeric()
                            ->default(3600)
                            ->helperText('Leaderboard refresh interval'),
                        TextInput::make('cache_ttl_settings')
                            ->label('Settings Cache TTL (seconds)')
                            ->numeric()
                            ->default(3600)
                            ->helperText('How long site settings are cached'),
                    ])->columns(3),
                Section::make('Queue Settings')
                    ->description('Background job processing settings')
                    ->schema([
                        Toggle::make('queue_emails')
                            ->label('Queue Email Sending')
                            ->default(true)
                            ->helperText('Send emails in background instead of blocking'),
                        Toggle::make('queue_analytics')
                            ->label('Queue Analytics Processing')
                            ->default(true)
                            ->helperText('Process click analytics in background'),
                        Toggle::make('queue_webhooks')
                            ->label('Queue Webhook Calls')
                            ->default(true)
                            ->helperText('Send webhook notifications in background'),
                    ])->columns(3),
                Section::make('Cache Management')
                    ->description('Clear various caches')
                    ->schema([
                        \Filament\Forms\Components\Actions::make([
                            \Filament\Forms\Components\Actions\Action::make('clear_app_cache')
                                ->label('Clear Application Cache')
                                ->icon('heroicon-o-trash')
                                ->color('danger')
                                ->requiresConfirmation()
                                ->action(function () {
                                    \Illuminate\Support\Facades\Artisan::call('cache:clear');
                                    Notification::make()
                                        ->title('Application cache cleared')
                                        ->success()
                                        ->send();
                                }),
                            \Filament\Forms\Components\Actions\Action::make('clear_view_cache')
                                ->label('Clear View Cache')
                                ->icon('heroicon-o-eye-slash')
                                ->color('warning')
                                ->requiresConfirmation()
                                ->action(function () {
                                    \Illuminate\Support\Facades\Artisan::call('view:clear');
                                    Notification::make()
                                        ->title('View cache cleared')
                                        ->success()
                                        ->send();
                                }),
                            \Filament\Forms\Components\Actions\Action::make('clear_config_cache')
                                ->label('Clear Config Cache')
                                ->icon('heroicon-o-cog-6-tooth')
                                ->color('warning')
                                ->requiresConfirmation()
                                ->action(function () {
                                    \Illuminate\Support\Facades\Artisan::call('config:clear');
                                    Notification::make()
                                        ->title('Config cache cleared')
                                        ->success()
                                        ->send();
                                }),
                            \Filament\Forms\Components\Actions\Action::make('optimize')
                                ->label('Optimize Application')
                                ->icon('heroicon-o-rocket-launch')
                                ->color('success')
                                ->requiresConfirmation()
                                ->modalDescription('This will cache config, routes, and views for better performance.')
                                ->action(function () {
                                    \Illuminate\Support\Facades\Artisan::call('optimize');
                                    Notification::make()
                                        ->title('Application optimized')
                                        ->success()
                                        ->send();
                                }),
                        ])->fullWidth(),
                    ]),
            ]);
    }
}
