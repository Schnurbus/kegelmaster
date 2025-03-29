<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\CompetitionEntry;
use App\Models\FeeEntry;
use App\Models\FeeType;
use App\Models\FeeTypeVersion;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\BouncerFacade;

class MatchdayService
{
    public function __construct(
        private readonly TransactionService $transactionService
    ) {}

    public function createMatchday($validated): Matchday
    {
        /** @var Matchday $matchday */
        $matchday = Matchday::create($validated);
        Log::info('Matchday created', ['user_id' => Auth::user()->id, 'matchday' => $matchday]);

        $latestFeeTypeVersions = FeeTypeVersion::select('fee_type_versions.*')
            ->joinSub(
                FeeTypeVersion::selectRaw('MAX(id) as latest_id, fee_type_id')
                    ->groupBy('fee_type_id'),
                'latest_versions',
                'fee_type_versions.id',
                'latest_versions.latest_id'
            )
            ->whereIn('fee_type_versions.fee_type_id', FeeType::where('club_id', $matchday->club_id)->pluck('id'))
            ->get();

        $matchday->feeTypeVersions()->attach($latestFeeTypeVersions);

        if ($matchday->club->base_fee > 0) {
            $activePlayersWithBaseFee = Player::where('club_id', $matchday->club_id)
                ->where('active', true)
                ->whereHas(
                    'role',
                    fn ($query) => $query->where('is_base_fee_active', true)
                )
                ->get();

            foreach ($activePlayersWithBaseFee as $player) {
                $this->transactionService->createTransaction([
                    'club_id' => $matchday->club_id,
                    'player_id' => $player->id,
                    'matchday_id' => $matchday->id,
                    'date' => $matchday->date,
                    'amount' => abs($matchday->club->base_fee) * -1,
                    'type' => TransactionType::BASE_FEE,
                ]);
            }
        }

        return $matchday;
    }

    public function deleteMatchday(Matchday $matchday): bool
    {
        try {
            DB::transaction(function () use ($matchday) {
                $transactions = Transaction::where('matchday_id', $matchday->id)->get();
                foreach ($transactions as $transaction) {
                    $this->transactionService->deleteTransaction($transaction);
                }

                FeeEntry::where('matchday_id', $matchday->id)->delete();
                CompetitionEntry::where('matchday_id', $matchday->id)->delete();

                $matchday->delete();
            });

            Log::info('Matchday deleted', ['user_id' => Auth::user()->id, 'matchday' => $matchday]);

            return true;
        } catch (\Exception $exeption) {
            Log::error('Error deleting matchday', ['exception' => $exeption->getMessage()]);
        }

        return false;
    }

    public function getByClubId(int $clubId): Collection
    {
        return Matchday::where('club_id', $clubId)
            ->withCount('players')
            ->orderByDesc('date')
            ->get();
    }

    public function getMatchdaysWithPermissions(User $user, int $clubId)
    {
        BouncerFacade::scope()->to($clubId);

        $matchdays = Matchday::where('club_id', $clubId)
            ->withCount('players')
            ->orderByDesc('date')
            ->get();

        return $matchdays->map(function ($matchday) use ($user) {
            $permissions = [
                'view' => $user->can('view', $matchday),
                'update' => $user->can('update', $matchday),
                'delete' => $user->can('delete', $matchday),
            ];

            $matchdayArray = $matchday->toArray();
            $matchdayArray['can'] = $permissions;

            return $matchdayArray;
        });
    }

    public function addPlayerToMatchday(Matchday $matchday, int $playerId): bool
    {
        $player = Player::find($playerId);
        if (! $player) {
            return false;
        }

        try {
            $matchday->players()->attach($player);
            $matchday->loadMissing('feeTypeVersions', 'club.competitionTypes');

            return true;
        } catch (Exception $exception) {
            Log::error('Error adding player', ['error' => $exception->getMessage()]);

            return false;
        }
    }

    public function removePlayerFromMatchday(Matchday $matchday, int $playerId): void
    {
        $player = Player::findOrFail($playerId);
        $matchday->players()->detach($player);

        $feeEntries = FeeEntry::where('matchday_id', $matchday->id)
            ->where('player_id', $player->id)->get();
        foreach ($feeEntries as $feeEntry) {
            $feeEntry->delete();
        }

        CompetitionEntry::where('matchday_id', $matchday->id)
            ->where('player_id', $player->id)
            ->delete();
    }
}
