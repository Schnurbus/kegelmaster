<?php

namespace App\Listeners;

use App\Enums\TransactionType;
use App\Events\TransactionCreated;
use App\Events\TransactionDeleted;
use App\Events\TransactionUpdated;
use App\Models\Player;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class UpdatePlayerBalance
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCreated|TransactionUpdated|TransactionDeleted $event): void
    {
        Log::debug('UpdatePlayerBalance::handle', ['event' => $event]);
        if (! $event->transaction->player_id) {
            return;
        }

        $player = Player::find($event->transaction->player_id);

        if ($player) {
            $sumForPlayer = Transaction::where('player_id', $player->id)
                ->whereIn('type', [TransactionType::PAYMENT, TransactionType::BASE_FEE, TransactionType::FEE])
                ->sum('amount');

            $player->balance = ($sumForPlayer / 100) + $player->initial_balance;
            $player->save();
        }
    }
}
