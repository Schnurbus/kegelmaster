<?php

namespace App\Policies;

use App\Models\Club;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClubPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Club $club): bool
    {
        if (setClubContext($user, $club->id)) {
            return true;
        }

        $hasPlayer = $user->players()
            ->where('club_id', $club->id)
            ->exists();
        $hasPermission = $user->can('view.Club');

        return $hasPlayer && $hasPermission;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Club $club): bool
    {
        if (setClubContext($user, $club->id)) {
            return true;
        }

        $hasPlayer = $user->players()
            ->where('club_id', $club->id)
            ->exists();
        $hasPermission = $user->can('update.Club');

        return $hasPlayer && $hasPermission;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Club $club): bool
    {
        if ($club->user_id === $user->id) {
            return true;
        }

        return false;
    }
}
