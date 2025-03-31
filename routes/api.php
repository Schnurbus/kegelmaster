<?php

use App\Http\Controllers\Api\v1\Dashboard\ClubController;
use App\Http\Controllers\Api\v1\Dashboard\CompetitionController;
use App\Http\Controllers\Api\v1\Dashboard\CompetitionTypeController;
use App\Http\Controllers\Api\v1\Dashboard\PlayerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('club/{club}', [ClubController::class, 'show'])
            ->name('api.v1.club.show');
        Route::get('club/{club}/balance', [ClubController::class, 'balance'])
            ->name('api.v1.club.balance');
        Route::get('club/{club}/competition-types', [CompetitionTypeController::class, 'index'])
            ->name('api.v1.competition-types.index');
        Route::get('player/{player}', [PlayerController::class, 'show'])
            ->name('api.v1.player.show');
        Route::get('competitions/{competitionType}/last', [CompetitionController::class, 'last'])
            ->name('api.v1.competitions.last');
    });
});
