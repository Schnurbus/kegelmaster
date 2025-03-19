<?php

namespace App\Contracts;

interface ClubServiceContract
{
    public function createClub(\App\Models\User $user, $validated);

    public function deleteClub(\App\Models\User $user, \App\Models\Club $club);

    public function getClubInfo(\App\Models\Club $club, $action = 'view');

    public function getClubsWithPermissions(\App\Models\User $user);

    public function getUserClubs(\App\Models\User $user);
}
