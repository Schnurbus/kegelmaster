<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\Club;
use App\Models\CompetitionType;
use App\Models\FeeType;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\BouncerFacade;

class ClubService
{
    public function createClub(User $user, $validated)
    {
        try {
            $club = new Club($validated);

            $club->balance = $validated['initial_balance'];
            $club->initial_balance = $validated['initial_balance'];
            $club->user_id = $user->id;
            $club->save();

            // BouncerFacade::scope()->to($club->id);
            // $owner = BouncerFacade::role()->create([
            //     'name' => 'owner',
            // ]);
            // BouncerFacade::allow($owner)->everything();
            // $user->assign($owner);

            return $club;
        } catch (\Exception $exception) {
            Log::error(
                'Club Service: ' . $exception->getMessage(),
                ['user' => $user, 'validated' => $validated]
            );
            throw new Exception('Fehler beim Erstellen des Clubs: ' . $exception->getMessage(), 500, $exception);
        }
    }

    public function deleteClub(User $user, Club $club)
    {
        try {
            if (! $user->can('delete', $club)) {
                abort(403);
            }

            Transaction::where('club_id', $club->id)->delete();
            FeeType::where('club_id', $club->id)->delete();
            CompetitionType::where('club_id', $club->id)->delete();
            Matchday::where('club_id', $club->id)->delete();
            Player::where('club_id', $club->id)->delete();
            $club->delete();

            return true;
        } catch (Exception $exception) {
            Log::error(
                'Club Service: ' . $exception->getMessage(),
                ['user' => $user, 'club' => $club]
            );
            throw new Exception('Fehler beim Löschen des Clubs: ' . $exception->getMessage(), 500, $exception);
        }
    }

    public function getClubInfo(Club $club, $action = 'view')
    {
        BouncerFacade::scope()->to($club->id);
        if (! BouncerFacade::can($action, $club)) {
            abort(403);
        }

        $club
            ->load('owner:id,name')
            ->loadCount('players');

        return $club;
    }

    public function getClubsWithPermissions(User $user)
    {
        $clubs = Club::select('id', 'name', 'balance', 'user_id')
            ->withCount('players')
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();

        return $clubs->map(function ($club) use ($user) {
            BouncerFacade::scope()->to($club->id);
            $permissions = [
                'view' => $user->can('view', $club),
                'update' => $user->can('update', $club),
                'delete' => $user->can('delete', $club),
            ];

            if (! $user->can('view', $club)) {
                $club->makeHidden('balance');
            }

            $clubArray = $club->toArray();
            $clubArray['can'] = $permissions;

            return $clubArray;
        });
    }

    public function getUserClubs(User $user)
    {
        return $user->clubs->merge($user->players->pluck('club')->filter());
    }

    public function recalculateBalance(Club $club): void
    {
        Log::debug('Recalculating club balance', ['club' => $club]);
        $sumForClub = Transaction::where('club_id', $club->id)
            ->whereIn('type', [TransactionType::PAYMENT, TransactionType::TIP, TransactionType::EXPENSE])
            ->sum('amount');

        $club->balance = (float) ($sumForClub / 100) + $club->initial_balance;
        $club->save();
        Log::debug('Balance recaluclated', ['sum' => $sumForClub, 'balance' => $club->balance]);
    }
}
