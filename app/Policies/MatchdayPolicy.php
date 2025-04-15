<?php

namespace App\Policies;

use App\Models\Matchday;
use App\Models\User;

class MatchdayPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function list(User $user, int $clubId): bool
    {
        if (setClubContext($user, $clubId)) {
            return true;
        }

        return $user->players()
            ->where('club_id', $clubId)
            ->exists() && $user->can('list.Matchday');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ?Matchday $matchday = null): bool
    {
        $clubId = $matchday->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->currentPlayer();

        return $player && $player->can('view.Matchday');

        //        $hasPlayer = $user->players()
        //            ->where('club_id', $clubId)
        //            ->exists();
        //        $hasPermission = $user->can('view.Matchday');
        //
        //        return $hasPlayer && $hasPermission;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, int $clubId): bool
    {
        if (setClubContext($user, $clubId)) {
            return true;
        }

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('create.Matchday');

        return $hasPlayer && $hasPermission;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ?Matchday $matchday = null): bool
    {
        $clubId = $matchday->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('update.Matchday');

        return $hasPlayer && $hasPermission;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ?Matchday $matchday = null): bool
    {
        $clubId = $matchday->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('delete.Matchsday');

        return $hasPlayer && $hasPermission;
    }
}
