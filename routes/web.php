<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Filament\Http\Livewire\Auth\Login;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\TutorialController;


Route::post('/admin/login', Login::class)->name('filament.admin.auth.login');

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payout-rates', function () {
    return view('payout-rates');
})->name('payout.rates');

// Legal Pages
Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy.policy');

Route::get('/terms-of-service', function () {
    return view('pages.terms-of-service');
})->name('terms.of.service');

Route::get('/cookie-policy', function () {
    return view('pages.cookie-policy');
})->name('cookie.policy');

// API Documentation
Route::get('/api-documentation', function () {
    return view('pages.api-documentation');
})->name('api.documentation');

Route::get('/dashboard', function () {
    return view('user.dashboard.index');
})->middleware(['auth', 'verified', \App\Http\Middleware\UpdateLastLogin::class])->name('dashboard');

// Tutorial completion route
Route::post('/tutorial/complete', [TutorialController::class, 'complete'])
    ->middleware(['auth'])
    ->name('tutorial.complete');

Route::middleware('auth')->group(function () {
    // Profile routes (already exist)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Dashboard Routes

    Route::get('/user/links', function () {
        return view('user.links.index'); // Links management view
    })->name('user.links.index');

    Route::get('/user/hidden-links', function () {
        return view('user.hidden-links.index'); // Hidden Links management view
    })->name('user.hidden-links.index');

    Route::get('/user/withdrawals', \App\Livewire\User\Withdrawals::class)->name('user.withdrawals');

    Route::get('/user/tools', function () {
        return view('user.tools.index'); // Tools view (placeholder)
    })->name('user.tools');

    Route::get('/user/referrals', function () {
        return view('user.referrals.index'); // Referrals view (placeholder)
    })->name('user.referrals');

    Route::get('/user/contact', \App\Livewire\User\TicketManager::class)->name('user.contact');

    Route::get('/user/reports', function () {
        return view('user.reports.index'); // Reports view (placeholder)
    })->name('user.reports');

    Route::get('/user/settings', \App\Livewire\User\Settings::class)->name('user.settings');

    // Gamification Routes
    Route::get('/user/inventory', \App\Livewire\User\Inventory::class)->name('user.inventory');
    Route::get('/user/leaderboard', \App\Livewire\User\Leaderboard::class)->name('user.leaderboard');
    Route::get('/user/daily-spin', \App\Livewire\User\DailySpin::class)->name('user.daily-spin');
    Route::get('/user/mystery-boxes', \App\Livewire\User\MysteryBoxes::class)->name('user.mystery-boxes');
    Route::get('/user/competition', \App\Livewire\User\WeeklyCompetition::class)->name('user.competition');
    
    // New Gamification Routes
    Route::get('/user/battle-pass', \App\Livewire\User\BattlePass::class)->name('user.battle-pass');
    Route::get('/user/teams', \App\Livewire\User\TeamManager::class)->name('user.teams');
    Route::get('/user/vip', \App\Livewire\User\VipProgress::class)->name('user.vip');
});

Route::middleware('auth')->group(function () {
    Route::get('/user/achievements', \App\Livewire\User\Achievements::class)->name('user.achievements');
});

// User Ad Management Routes
Route::middleware('auth')->group(function () {
    Route::get('/user/ads', \App\Livewire\User\AdCampaigns::class)->name('user.ads.index');
    Route::get('/user/ads/create', \App\Livewire\User\CreateAdCampaign::class)->name('user.ads.create');
    Route::get('/user/ads/{adCampaign}/edit', \App\Livewire\User\EditAdCampaign::class)->name('user.ads.edit');
});

Route::post('/payment/cryptomus/callback', [PaymentController::class, 'cryptomusCallback'])->name('payment.cryptomus.callback');

Route::middleware('auth')->group(function () {
    // ... (existing auth routes)

    // Full Page Script Route
    Route::get('/user/tools/full-page-script', [UserController::class, 'fullPageScript'])->name('user.tools.full-page-script');

    // Quick Link / Bookmarklet Script Route
    Route::get('/user/tools/bookmarklet-script', [UserController::class, 'bookmarkletScript'])->name('user.tools.bookmarklet-script');
});


require __DIR__.'/auth.php';

Route::post('/links', [LinkController::class, 'store'])->name('links.store');
// Guest Shorten Route (Public)
Route::post('/guest/shorten', [LinkController::class, 'apiStore'])->name('guest.shorten');

Route::get('/{code}', [LinkController::class, 'redirect'])->name('shortlink.redirect');

// Reklam Adımı Gösterim Route
Route::get('/link/{link:code}/step/{stepNumber}', [LinkController::class, 'showAdStep'])
    ->name('link.ad_step')
    ->whereNumber('stepNumber'); // stepNumber'ın sayı olmasını sağla

// Reklam Tıklama Takip Route (Yeni)
Route::post('/ads/track-click/{adType}/{adId}', [LinkController::class, 'trackAdClick'])->name('ads.track-click');

// Yönetilebilir Sayfalar Route
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Link istatistikleri route'u
Route::get('/stats/{code}', [LinkController::class, 'showStats'])->name('stats');

// Admin tarafından kullanıcı olarak giriş yapma route'u
Route::middleware('auth', 'can:admin')->group(function () { // Sadece adminlerin erişebilmesi için middleware ekledim
    Route::get('/admin/users/{user}/login-as', function (App\Models\User $user) {
        Auth::loginUsingId($user->id);
        return redirect()->route('user.dashboard.index'); // Kullanıcı dashboard anasayfasına yönlendir
    })->name('admin.users.login-as');
});


Route::get('/test-ip', function () {
    phpinfo();
});
