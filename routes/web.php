<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\TutorialController;

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

// DMCA Complaint Route
Route::get('/dmca/{linkCode}', \App\Livewire\DmcaComplaintForm::class)->name('dmca.complaint');


// Blog Routes
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{slug}', [\App\Http\Controllers\BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

Route::get('/dashboard', function () {
    return view('user.dashboard.index');
})->middleware(['auth', \App\Http\Middleware\VerifyEmailIfEnabled::class, \App\Http\Middleware\UpdateLastLogin::class])->name('dashboard');

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

// Feedback System Routes (Publicly Accessible)
Route::get('/feedback', \App\Livewire\Feedback\FeedbackBoard::class)->name('feedback.index');
Route::get('/feedback/roadmap', \App\Livewire\Feedback\Roadmap::class)->name('feedback.roadmap');
Route::get('/feedback/{slug}', \App\Livewire\Feedback\FeedbackDetail::class)->name('feedback.show');

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
Route::post('/payment/gumroad/callback', [PaymentController::class, 'gumroadCallback'])->name('payment.gumroad.callback');

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

// Shortlink captcha verification (POST only - captcha shown in interstitial overlay)
Route::post('/go/{code}/captcha', [LinkController::class, 'verifyCaptcha'])->name('shortlink.captcha.verify');

// Reklam Adımı Gösterim Route (MUST be before /{code} catch-all)
Route::get('/link/{link:code}/step/{stepNumber}', [LinkController::class, 'showAdStep'])
    ->name('link.ad_step')
    ->whereNumber('stepNumber'); // stepNumber'ın sayı olmasını sağla

// Click completion after all ad steps viewed
Route::get('/link/{link:code}/complete', [LinkController::class, 'recordClickAndRedirect'])
    ->name('link.complete');

// Shortlink redirect (catch-all - MUST be LAST)
Route::get('/{code}', [LinkController::class, 'redirect'])
    ->name('shortlink.redirect')
    ->where('code', '^(?!admin|api|auth|livewire|storage|sanctum|filament|user|dashboard|profile|page|blog|feedback|login|register|logout|password|verify|confirm|link).*$');

// Reklam Tıklama Takip Route (Yeni)
Route::post('/ads/track-click/{adType}/{adId}', [LinkController::class, 'trackAdClick'])->name('ads.track-click');

// Yönetilebilir Sayfalar Route
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Link istatistikleri route'u
Route::get('/stats/{code}', [LinkController::class, 'showStats'])->name('stats');
// Admin Login As User (Impersonation)
// IMPORTANT: This SWITCHES your session to the user. Use incognito or different browser for admin panel.
Route::middleware(['auth'])->group(function () {
    // Start impersonating a user
    Route::get('/admin/users/{user}/login-as', function (App\Models\User $user) {
        // Only admins can impersonate
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        
        // Store original admin ID so we can switch back
        session()->put('impersonating_from_admin_id', auth()->id());
        
        // Actually login as the target user
        \Illuminate\Support\Facades\Auth::login($user);
        
        return redirect()->route('dashboard');
    })->name('admin.users.login-as');
    
    // Stop impersonating and return to admin
    Route::get('/admin/stop-impersonation', function () {
        $adminId = session()->get('impersonating_from_admin_id');
        
        if (!$adminId) {
            return redirect()->route('dashboard');
        }
        
        // Clear impersonation session
        session()->forget('impersonating_from_admin_id');
        
        // Login back as admin
        $admin = App\Models\User::find($adminId);
        if ($admin) {
            \Illuminate\Support\Facades\Auth::login($admin);
        }
        
        return redirect()->route('filament.admin.resources.users.index');
    })->name('admin.stop-impersonation');
});

