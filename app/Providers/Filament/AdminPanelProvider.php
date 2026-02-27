<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\Pages\Auth\Login;
use Filament\PanelProvider;
use App\Filament\Resources\ReportsResource;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Navigation\NavigationGroup;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->brandName('MrShort Admin')
            ->favicon(asset('favicon.ico'))
            ->darkMode(true, true) // Force dark mode
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->spa()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => [
                    50 => '#f0f9ff',
                    100 => '#e0f2fe',
                    200 => '#bae6fd',
                    300 => '#7dd3fc',
                    400 => '#38bdf8',
                    500 => '#0ea5e9',
                    600 => '#0284c7',
                    700 => '#0369a1',
                    800 => '#075985',
                    900 => '#0c4a6e',
                    950 => '#082f49',
                ],
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'info' => Color::Sky,
                'gray' => [
                    50 => '#f8fafc',
                    100 => '#f1f5f9',
                    200 => '#e2e8f0',
                    300 => '#cbd5e1',
                    400 => '#94a3b8',
                    500 => '#64748b',
                    600 => '#475569',
                    700 => '#334155',
                    800 => '#1e293b',
                    900 => '#0f172a',
                    950 => '#020617',
                ],
            ])
            ->font('Inter')
            ->maxContentWidth('full')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('User Management')
                    ->icon('heroicon-o-users')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Links & Analytics')
                    ->icon('heroicon-o-link')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Gamification')
                    ->icon('heroicon-o-trophy')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Features')
                    ->icon('heroicon-o-sparkles')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Competitions')
                    ->icon('heroicon-o-flag')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Advertising')
                    ->icon('heroicon-o-megaphone')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Finance')
                    ->icon('heroicon-o-banknotes')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Content')
                    ->icon('heroicon-o-document-text')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Support')
                    ->icon('heroicon-o-lifebuoy')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Raporlar')
                    ->icon('heroicon-o-chart-bar')
                    ->collapsed(false),
            ])
            ->resources([
                // User Management
                \App\Filament\Resources\UserResource::class,
                \App\Filament\Resources\ReportsResource::class,
                
                // Links & Analytics
                \App\Filament\Resources\LinkResource::class,
                \App\Filament\Resources\LinkClickResource::class,
                
                // Gamification
                \App\Filament\Resources\GamificationGoalResource::class,
                \App\Filament\Resources\GamificationRewardResource::class,
                \App\Filament\Resources\GamificationSettingResource::class,
                \App\Filament\Resources\UserAchievementResource::class,
                \App\Filament\Resources\UserRewardResource::class,
                \App\Filament\Resources\LevelConfigurationResource::class,
                \App\Filament\Resources\VipLevelResource::class,
                \App\Filament\Resources\UserInventoryResource::class,
                
                // Gamification Features
                \App\Filament\Resources\DailySpinPrizeResource::class,
                \App\Filament\Resources\DailyChallengePoolResource::class,
                \App\Filament\Resources\MysteryBoxResource::class,
                \App\Filament\Resources\StreakMilestoneResource::class,
                
                // Competitions
                \App\Filament\Resources\CompetitionResource::class,
                \App\Filament\Resources\SeasonResource::class,
                \App\Filament\Resources\TeamResource::class,
                
                // Support
                \App\Filament\Resources\TicketResource::class,
                \App\Filament\Resources\DmcaComplaintResource::class,
                
                // Advertising System
                \App\Filament\Resources\CampaignTemplateResource::class,
                \App\Filament\Resources\UserAdCampaignResource::class,
                \App\Filament\Resources\CpmTierResource::class,
                
                // Finance
                \App\Filament\Resources\WithdrawalRequestResource::class,
                
                // Content Management
                \App\Filament\Resources\PageResource::class,
                \App\Filament\Resources\AnnouncementResource::class,
                
                // Blog
                \App\Filament\Resources\BlogCategoryResource::class,
                \App\Filament\Resources\BlogPostResource::class,
                
                // Settings
                \App\Filament\Resources\CountryResource::class,
                \App\Filament\Resources\DomainResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Resources\DashboardResource\Pages\Dashboard::class,
                \App\Filament\Pages\ManageCountryCpmRates::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Widgets\CampaignPerformanceWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
