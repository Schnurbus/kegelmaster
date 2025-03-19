<?php

namespace App\Providers;

use App\Models\Club;
use App\Models\CompetitionEntry;
use App\Models\CompetitionType;
use App\Models\FeeEntry;
use App\Models\FeeType;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Role;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Silber\Bouncer\BouncerFacade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || $this->app->environment('local')) {
            URL::forceScheme('https');
        }

        // locale
        Carbon::setLocale(app()->getLocale());

        // Bouncer role model
        BouncerFacade::useRoleModel(\App\Models\Role::class);

        BouncerFacade::allowEveryone()->toOwnEverything();

        BouncerFacade::ownedVia(Club::class, function ($club, $user) {
            return $club->user_id == $user->id;
        });
        BouncerFacade::ownedVia(Role::class, function ($role, $user) {
            return $role->club->user_id == $user->id;
        });
        BouncerFacade::ownedVia(Player::class, function ($player, $user) {
            return $player->club->user_id == $user->id;
        });
        BouncerFacade::ownedVia(FeeType::class, function ($feeType, $user) {
            return $feeType->club->user_id == $user->id;
        });
        BouncerFacade::ownedVia(FeeEntry::class, function ($feeEntry, $user) {
            return $feeEntry->matchday->club->user_id == $user->id;
        });
        BouncerFacade::ownedVia(CompetitionType::class, function ($competitionType, $user) {
            return $competitionType->club->user_id == $user->id;
        });
        BouncerFacade::ownedVia(CompetitionEntry::class, function ($competitionEntry, $user) {
            return $competitionEntry->matchday->club->user_id == $user->id;
        });
        BouncerFacade::ownedVia(Matchday::class, function ($matchday, $user) {
            return $matchday->club->user_id == $user->id;
        });
        BouncerFacade::ownedVia(Transaction::class, function ($transaction, $user) {
            return $transaction->club->user_id == $user->id;
        });
    }
}
