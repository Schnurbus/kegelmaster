<?php

namespace App\Listeners;

use App\Enums\TransactionType;
use App\Events\TransactionCreated;
use App\Events\TransactionDeleted;
use App\Events\TransactionUpdated;
use App\Models\Club;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class UpdateClubBalance
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
        Log::info('UpdateClubBalance::handle', ['event' => $event]);
        $club = Club::find($event->transaction->club_id);

        if ($club) {
            $sumForClub = Transaction::where('club_id', $club->id)
                ->whereIn('type', [TransactionType::PAYMENT, TransactionType::TIP, TransactionType::EXPENSE])
                ->sum('amount');

            $club->balance = $sumForClub + $club->initial_balance;
            $club->save();
        }
    }
}
