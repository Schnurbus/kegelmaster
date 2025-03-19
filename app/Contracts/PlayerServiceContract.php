<?php

namespace App\Contracts;

use App\Models\Player;
use App\Models\Transaction;

interface PlayerServiceContract
{
    // public function updateBalance(Player $player, int $balance): bool;
    // public function addTransaction(Transaction $transaction): bool;
    // public function removeTransaction(Transaction $transaction): bool;
    public function getPlayerStatistics(Player $player);

    public function recalculateBalance(Player $player);
}
