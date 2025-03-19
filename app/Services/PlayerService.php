<?php

namespace App\Services;

use App\Enums\PermissionType;
use App\Enums\TransactionType;
use App\Models\CompetitionEntry;
use App\Models\FeeEntry;
use App\Models\Player;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\BouncerFacade;

class PlayerService
{
    /**
     * Create player
     *
     * @param  array {name: string, club_id: number, sex: number, active: bool, initial_balance: number, role_id: number }  $playerData
     */
    public function createPlayer(array $playerData): ?Player
    {
        try {
            $player = new Player($playerData);
            $player->initial_balance = (float) $playerData['initial_balance'];
            $player->balance = (float) $playerData['initial_balance'];
            $player->save();
            Log::info("Player created", ['user_id' => Auth::user()->id, 'player' => $player]);

            return $player;
        } catch (Exception $exception) {
            Log::error('Could not create player', ['error' => $exception->getMessage()]);

            return null;
        }
    }

    /**
     * Create player
     *
     * @param array {?name: string, ?sex: number, ?active: boolean, ?role_id: number } $playerData
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
                Log::info("Player updated", ['user_id' => Auth::user()->id, 'player' => $player]);
                $this->recalculateBalance($player);
            }

            if ($player->user_id) {
                $role = $player->role;
                BouncerFacade::sync($player->user)->roles([$role]);
            }

            return $player;
        } catch (Exception $exception) {
            Log::error('Could not update player', ['error' => $exception->getMessage()]);

            return null;
        }
    }

    public function getPlayersWithPermissions(User $user, int $clubId)
    {
        BouncerFacade::scope()->to($clubId);

        $players = Player::where('club_id', $clubId)
            ->with('role', function ($query) {
                $query->select('id', 'title');
            })
            ->orderBy('name')
            ->get();

        return $players->map(function ($player) use ($user) {
            $permissions = [
                'view' => $user->can('view', $player) || $player->user_id === $user->id,
                'update' => $user->can('update', $player),
                'delete' => $user->can('delete', $player),
            ];
            if (! $user->can('view', $player)) {
                $player->makeHidden('balance');
            }
            $playerArray = $player->toArray();
            $playerArray['can'] = $permissions;

            return $playerArray;
        });
    }

    public function getPlayerStatistics(Player $player)
    {
        $feeStatistics = FeeEntry::join('fee_type_versions', 'fee_entries.fee_type_version_id', '=', 'fee_type_versions.id')
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

        $competitionStatistics = CompetitionEntry::join('competition_types', 'competition_entries.competition_type_id', '=', 'competition_types.id')
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

        $transactions = Transaction::where('player_id', $player->id)
            ->with([
                'matchday' => function ($query) {
                    $query->select('id', 'date');
                },
                'feeEntry.feeTypeVersion' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->orderByDesc('date')
            ->get();

        return ['fees' => $feeStatistics, 'competitions' => $competitionStatistics, 'transactions' => $transactions];
    }

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
