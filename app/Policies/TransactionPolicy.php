<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
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
            ->exists() && $user->can('list.Transaction');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ?Transaction $transaction = null): bool
    {
        $clubId = $transaction->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('view.Transaction');

        return $hasPlayer && $hasPermission;
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
        $hasPermission = $user->can('create.Transaction');

        return $hasPlayer && $hasPermission;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ?Transaction $transaction = null): bool
    {
        $clubId = $transaction->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('update.Transaction');

        return $hasPlayer && $hasPermission;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ?Transaction $transaction = null): bool
    {
        $clubId = $transaction->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('delete.Transaction');

        return $hasPlayer && $hasPermission;
    }
}
