<?php

namespace App\Contracts;

use App\Models\Matchday;

interface MatchdayServiceContract
{
    public function createMatchday($validated): Matchday;

    public function deleteMatchday(\App\Models\Matchday $matchday): bool;

    public function addPlayerToMatchday(Matchday $matchday, int $playerId): void;

    public function removePlayerFromMatchday(Matchday $matchday, int $playerId): void;
}
