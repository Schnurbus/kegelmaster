<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\ClubSelectController;
use App\Http\Controllers\CompetitionEntryController;
use App\Http\Controllers\CompetitionTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardLayoutController;
use App\Http\Controllers\FeeEntryController;
use App\Http\Controllers\FeeTypeController;
use App\Http\Controllers\MatchdayController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerInvitationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\LoadCurrentClubSetting;
use App\Http\Middleware\ScopeBouncer;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landingpage');
})->name('home');

// Route::get('language/{language}', function ($language) {
//     Session()->put('locale', $language);

//     return redirect()->back();
// })->name('language');

Route::middleware(['auth', 'verified', LoadCurrentClubSetting::class, ScopeBouncer::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // UI Helper routes
    Route::post('/set-current-club', ClubSelectController::class)->name('set-club');
    Route::post('/save-dashboard', DashboardLayoutController::class)->name('save-dashboard');

    // Resource routes
    Route::resource('/club', ClubController::class);
    Route::resource('/role', RoleController::class);
    Route::post('/players/{player}/invite', [PlayerInvitationController::class, 'store'])->name('players.invite.store');
    Route::resource('players', PlayerController::class);
    Route::post('matchdays/${matchday}/add-player', [MatchdayController::class, 'addPlayer'])->name('matchdays.add-player');
    Route::post('matchdays/${matchday}/remove-player', [MatchdayController::class, 'removePlayer'])->name('matchdays.remove-player');
    Route::resource('matchdays', MatchdayController::class);

    Route::put('fee-entries/bulk-update/${matchday}', FeeEntryController::class)->name('fee-entries.bulk-update');
    Route::resource('/fee-type', FeeTypeController::class);

    Route::put('competition-entries/bulk-update/${matchday}', CompetitionEntryController::class)->name('competition-entries.bulk-update');
    Route::resource('/competition-type', CompetitionTypeController::class);

    Route::resource('/transactions', TransactionController::class);
});

// Einladung akzeptieren
Route::get('/invitation/accept/{token}', [PlayerInvitationController::class, 'accept'])->middleware(['auth', 'verified'])->name('players.invitation.accept');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
