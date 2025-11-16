<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Filament\Http\Livewire\Auth\Login;

Route::post('/admin/login', Login::class)->name('filament.admin.auth.login');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('user.dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');

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

    Route::get('/user/withdrawals', function () {
        return view('user.withdrawals.index'); // Withdrawals view
    })->name('user.withdrawals');

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

    Route::get('/user/settings', function () {
        return view('user.settings.index'); // Settings view (placeholder)
    })->name('user.settings');

    // Gamification Routes
    Route::get('/user/inventory', \App\Livewire\User\Inventory::class)->name('user.inventory');
    Route::get('/user/leaderboard', \App\Livewire\User\Leaderboard::class)->name('user.leaderboard');
    Route::get('/user/inventory', \App\Livewire\User\Inventory::class)->name('user.inventory');
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

use App\Http\Controllers\LinkController;
use App\Http\Controllers\UserController; // Add this line

Route::middleware('auth')->group(function () {
    // ... (existing auth routes)

    // Full Page Script Route
    Route::get('/user/tools/full-page-script', [UserController::class, 'fullPageScript'])->name('user.tools.full-page-script');

    // Quick Link / Bookmarklet Script Route
    Route::get('/user/tools/bookmarklet-script', [UserController::class, 'bookmarkletScript'])->name('user.tools.bookmarklet-script');
});


require __DIR__.'/auth.php';

Route::post('/links', [LinkController::class, 'store'])->name('links.store');

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


Route::get('/debug-ip', [LinkController::class, 'debugIp'])->name('debug.ip');

Route::get('/test-ip', function () {
    phpinfo();
});
