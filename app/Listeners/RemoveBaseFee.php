<?php

namespace App\Listeners;

use App\Enums\TransactionType;
use App\Events\MatchdayDeleted;
use App\Events\TransactionDeleted;
use App\Models\Transaction;

class RemoveBaseFee
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
    public function handle(MatchdayDeleted $event): void
    {
        $matchday = $event->matchday;
        $transactions = Transaction::where('matchday_id', $matchday->id)
            ->where('type', TransactionType::BASE_FEE)
            ->get();

        foreach ($transactions as $transaction) {
            $transaction->delete();
            TransactionDeleted::dispatch($transaction);
        }
    }
}
