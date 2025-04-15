<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\CompetitionEntry;
use App\Models\FeeEntry;
use App\Models\Player;
use App\Models\Role;
use App\Models\Transaction;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PlayerService
{
    /**
     * Create player
     *
     * @param  array  $playerData  {name: string, club_id: number, sex: number, active: bool, initial_balance: number, role_id: number }
     */
    public function createPlayer(array $playerData): ?Player
    {
        try {
            $player = new Player($playerData);
            $player->initial_balance = (float) $playerData['initial_balance'];
            $player->balance = (float) $playerData['initial_balance'];
            $player->save();
            Log::info('Player created', ['user_id' => Auth::user()->id, 'player' => $player]);

            return $player;
        } catch (Exception $exception) {
            Log::error('Could not create player', ['error' => $exception->getMessage()]);

            return null;
        }
    }

    /**
     * Update player
     *
     * @param  array  $playerData  {?name: string, ?sex: number, ?active: boolean, ?role_id: number }
     */
    public function updatePlayer(Player $player, array $playerData): ?Player
    {
        try {
            Log::debug('Updating player', ['player' => $player, 'data' => $playerData]);
            $player->fill($playerData);
            if (isset($playerData['initial_balance'])) {
                $player->initial_balance = $playerData['initial_balance'];
            }

            if ($player->isDirty()) {
                $player->save();
                Log::info('Player updated', ['user_id' => Auth::user()->id, 'player' => $player]);
                $this->recalculateBalance($player);
            }

            $role = Role::find($playerData['role_id']);

            $player->syncRoles($role);

            //            if ($player->user_id) {
            //                $role = $player->role;
            //                $player->user->syncRoles($role);
            //            }

            return $player;
        } catch (Exception $exception) {
            Log::error('Could not update player', ['error' => $exception->getMessage()]);

            return null;
        }
    }

    /**
     * Get players by club id
     */
    public function getByClubId(int $clubId): Collection
    {
        return Player::where('club_id', $clubId)
            ->with('role')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get fee statistic for given player
     */
    public function getFeeStatistics(Player $player): Collection
    {
        return FeeEntry::join('fee_type_versions', 'fee_entries.fee_type_version_id', '=', 'fee_type_versions.id')
            ->join('fee_types', 'fee_types.id', '=', 'fee_type_versions.fee_type_id')
            ->where('fee_entries.player_id', $player->id)
            ->selectRaw('
                fee_type_versions.fee_type_id as fee_type_id,
                fee_types.name as name,
                MIN(fee_entries.amount) as min_value,
                MAX(fee_entries.amount) as max_value,
                AVG(fee_entries.amount) as avg_value,
                COUNT(*) as total_entries
            ')
            ->groupBy('fee_type_versions.fee_type_id', 'fee_types.name', 'fee_types.position')
            ->orderBy('fee_types.position', 'asc')
            ->get();
    }

    /**
     * Get competition stattistic for given player
     */
    public function getCompetitionStatistics(Player $player): Collection
    {
        return CompetitionEntry::join('competition_types', 'competition_entries.competition_type_id', '=', 'competition_types.id')
            ->where('competition_entries.player_id', $player->id)
            ->selectRaw('
                competition_types.id as competition_type_id,
                competition_types.name as name,
                MIN(competition_entries.amount) as min_value,
                MAX(competition_entries.amount) as max_value,
                AVG(competition_entries.amount) as avg_value,
                COUNT(*) as total_entries
            ')
            ->groupBy('competition_entries.competition_type_id', 'competition_types.name', 'competition_types.position', 'competition_types.id')
            ->orderBy('competition_types.position', 'asc')
            ->get();
    }

    /**
     * Get transactions for given player
     */
    public function getTransactionStatistics(Player $player): Collection
    {
        return Transaction::where('player_id', $player->id)
            ->with([
                'matchday' => function ($query) {
                    $query->select('id', 'date');
                },
                'feeEntry.feeTypeVersion' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->orderByDesc('date')
            ->get();
    }

    /**
     * Recalculate the balance of the given player
     */
    public function recalculateBalance(Player $player): void
    {
        Log::debug('Recalculating player balance', ['player' => $player]);
        $sumForPlayer = Transaction::where('player_id', $player->id)
            ->whereIn('type', [TransactionType::PAYMENT, TransactionType::BASE_FEE, TransactionType::FEE])
            ->sum('amount');

        $player->balance = (float) ($sumForPlayer / 100) + $player->initial_balance;
        $player->save();
        Log::debug('Balance recaluclated', ['sum' => $sumForPlayer, 'balance' => $player->balance]);
    }
}
