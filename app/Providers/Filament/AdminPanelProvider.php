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
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->resources([
                \App\Filament\Resources\UserResource::class,
                \App\Filament\Resources\ReportsResource::class,
                \App\Filament\Resources\AdminLinkClickReportResource::class,
                \App\Filament\Resources\GamificationGoalResource::class,
                \App\Filament\Resources\GamificationRewardResource::class,
                \App\Filament\Resources\GamificationSettingResource::class,
                \App\Filament\Resources\UserAchievementResource::class,
                \App\Filament\Resources\UserRewardResource::class,
                \App\Filament\Resources\LevelConfigurationResource::class,
                \App\Filament\Resources\UserInventoryResource::class,
                \App\Filament\Resources\TicketResource::class,
                // Yeni reklam sistemi resource'ları
                \App\Filament\Resources\CampaignTemplateResource::class,
                \App\Filament\Resources\AdCampaignResource::class,
                \App\Filament\Resources\CpmTierResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class, // Varsayılan Filament dashboard'ını kaldırıyoruz
                \App\Filament\Resources\DashboardResource\Pages\Dashboard::class, // Kendi dashboard sayfamızı ekliyoruz
                \App\Filament\Pages\ManageCountryCpmRates::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
