<?php

namespace App\Listeners;

use App\Enums\TransactionType;
use App\Events\MatchdayCreated;
use App\Events\TransactionCreated;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddBaseFee
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
    public function handle(MatchdayCreated $event): void
    {
        Log::info('AddBaseFee called');
        if (! $event->matchday->id) {
            Log::info('Event has no matchday id', ['event' => $event]);

            return;
        }

        $matchday = Matchday::with('club')->find($event->matchday->id);

        if (! $matchday) {
            Log::info('No matchday found', ['event' => $event]);

            return;
        }

        $activePlayersWithBaseFee = Player::where('club_id', $matchday->club_id)
            ->where('active', true)
            ->whereHas(
                'role',
                fn ($query) => $query->where('is_base_fee_active', true)
            )
            ->get();
        $baseFee = $matchday->club->base_fee;

        if ($baseFee) {
            DB::transaction(function () use ($matchday, $activePlayersWithBaseFee, $baseFee) {
                foreach ($activePlayersWithBaseFee as $player) {
                    $transaction = Transaction::create([
                        'club_id' => $matchday->club_id,
                        'player_id' => $player->id,
                        'matchday_id' => $matchday->id,
                        'date' => $matchday->date,
                        'amount' => abs($baseFee) * -1,
                        'type' => TransactionType::BASE_FEE,
                    ]);

                    TransactionCreated::dispatch($transaction);
                }
            });
        } else {
            Log::info('No base fee for club', ['club' => $matchday->club]);
        }
    }
}
