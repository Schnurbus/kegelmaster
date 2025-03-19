<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Silber\Bouncer\BouncerFacade;

class BouncerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!App::runningInConsole()) {
            BouncerFacade::useRoleModel(\App\Models\Role::class);

            BouncerFacade::allowEveryone()->toOwnEverything();

            BouncerFacade::ownedVia(\App\Models\Club::class, function ($club, $user) {
                return $club->user_id == $user->id;
            });
            BouncerFacade::ownedVia(\App\Models\Role::class, function ($role, $user) {
                return $role->club->user_id == $user->id;
            });
            BouncerFacade::ownedVia(\App\Models\Player::class, function ($player, $user) {
                return $player->club->user_id == $user->id;
            });
            BouncerFacade::ownedVia(\App\Models\FeeType::class, function ($feeType, $user) {
                return $feeType->club->user_id == $user->id;
            });
            BouncerFacade::ownedVia(\App\Models\FeeEntry::class, function ($feeEntry, $user) {
                return $feeEntry->matchday->club->user_id == $user->id;
            });
            BouncerFacade::ownedVia(\App\Models\CompetitionType::class, function ($competitionType, $user) {
                return $competitionType->club->user_id == $user->id;
            });
            BouncerFacade::ownedVia(\App\Models\CompetitionEntry::class, function ($competitionEntry, $user) {
                return $competitionEntry->matchday->club->user_id == $user->id;
            });
            BouncerFacade::ownedVia(\App\Models\Matchday::class, function ($matchday, $user) {
                return $matchday->club->user_id == $user->id;
            });
            BouncerFacade::ownedVia(\App\Models\Transaction::class, function ($transaction, $user) {
                return $transaction->club->user_id == $user->id;
            });
        }
    }
}
