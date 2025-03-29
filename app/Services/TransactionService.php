<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\FeeEntry;
use App\Models\Player;
use App\Models\Transaction;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function __construct(
        private ClubService $clubService,
        private PlayerService $playerService
    ) {}

    /**
     * Create a transaction for a fee entry
     *
     * @throws Exception
     */
    public function createForFeeEntry(FeeEntry $feeEntry): ?Transaction
    {
        if ((int) $feeEntry->amount === 0) {
            return null;
        }

        // $matchday = $feeEntry->matchday;
        // $feeTypeVersion = $feeEntry->feeTypeVersion;

        try {
            $transaction = Transaction::create([
                'club_id' => $feeEntry->matchday->club->id,
                'player_id' => $feeEntry->player_id,
                'matchday_id' => $feeEntry->matchday_id,
                'fee_entry_id' => $feeEntry->id,
                'amount' => $feeEntry->amount * $feeEntry->feeTypeVersion->amount * -1,
                'type' => TransactionType::FEE,
                'date' => $feeEntry->matchday->date,
            ]);

            Log::info('Transaction created', [
                'transaction' => $transaction->toArray(),
            ]);
        } catch (Exception $exception) {
            Log::error('Error creating transaction', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }

        return $transaction;
    }

    public function updateForFeeEntry(FeeEntry $feeEntry): bool
    {
        $oldTransaction = Transaction::where('fee_entry_id', $feeEntry->id)
            ->where('player_id', $feeEntry->player_id)
            ->where('matchday_id', $feeEntry->matchday_id)
            ->first();

        if ($oldTransaction) {
            $oldTransaction->delete();
        }

        $this->createForFeeEntry($feeEntry);

        return true;
    }

    public function deleteForFeeEntry(FeeEntry $feeEntry): bool
    {
        Log::debug('deleteForFeeEntry called');

        $transaction = Transaction::where('fee_entry_id', $feeEntry->id)->first();
        Log::debug('deleteForFeeEntry: transaction found', ['transaction' => $transaction]);

        if ($transaction) {

            $transaction->delete();
            Log::debug('deleteForFeeEntry: transaction deleted');
        }

        Log::debug('deleteForFeeEntry finished');

        return true;
    }

    /**
     * Create a transaction
     *
     * @param  array  $transactionData  { club_id: number, ?player_id: number, ?matchday_id: number, ?fee_entry_id: number, amount: number, type: TransactionType, date: string, auto_tip: boolean, ?notes: string }
     *
     * @throws Exception
     */
    public function createTransaction(array $transactionData): ?Transaction
    {
        try {
            $transaction = null;
            if ($transactionData['type'] === TransactionType::PAYMENT) {
                $player = Player::find($transactionData['player_id']);
                if (! $player) {
                    throw new Exception('Player not found');
                }
                if ($transactionData['amount'] + $player->balance > 0 && $transactionData['auto_tip']) {
                    $paymentData = [...$transactionData, 'amount' => abs($player->balance)];
                    $tipData = [...$transactionData, 'type' => TransactionType::TIP, 'amount' => $player->balance + $transactionData['amount']];
                    $transaction = DB::transaction(function () use ($paymentData, $tipData) {
                        $transaction = Transaction::create($paymentData);
                        Transaction::create($tipData);
                        Log::info('Transaction created', ['user_id' => Auth::user()->id, 'transaction' => $transaction]);

                        return $transaction;
                    });
                } else {
                    $transaction = Transaction::create($transactionData);
                    Log::info('Transaction created', ['user_id' => Auth::user()->id, 'transaction' => $transaction]);
                }
            } else {
                $transaction = Transaction::create($transactionData);
                Log::info('Transaction created', ['user_id' => Auth::user()->id, 'transaction' => $transaction]);
            }
            if ($transaction->player) {
                $this->playerService->recalculateBalance($transaction->player);
            }
            if (in_array($transaction->type, [TransactionType::PAYMENT, TransactionType::TIP, TransactionType::EXPENSE])) {
                $this->clubService->recalculateBalance($transaction->club);
            }

            return $transaction;
        } catch (Exception $exception) {
            Log::error('Error creating transaction', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Update a transaction
     *
     * @param  array  $transactionData  { ?club_id: number, ?player_id: number, ?matchday_id: number, ?fee_entry_id: number, ?amount: number, ?type: number, ?date: string, ?notes: string }
     *
     * @throws Exception
     */
    public function updateTransaction(Transaction $transaction, array $transactionData): ?Transaction
    {
        Log::debug('updateTransaction called');
        try {
            $transaction->update($transactionData);
            Log::info('Transaction updated', ['user_id' => Auth::user()->id, 'transaction' => $transaction]);
            if ($transaction->player) {
                $this->playerService->recalculateBalance($transaction->player);
            }

            if (in_array($transaction->type, [TransactionType::PAYMENT, TransactionType::TIP, TransactionType::EXPENSE])) {
                $this->clubService->recalculateBalance($transaction->club);
            }

            return $transaction;
        } catch (Exception $exception) {
            Log::error('Error creating transaction', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Delete a transaction
     *
     * @throws Exception
     */
    public function deleteTransaction(Transaction $transaction): bool
    {
        Log::debug('deleteTransaction called');
        try {
            $transaction->delete();
            Log::info('Transaction deleted', ['user_id' => Auth::user()->id, 'transaction' => $transaction]);
            if ($transaction->player) {
                $this->playerService->recalculateBalance($transaction->player);
            }

            if (in_array($transaction->type, [TransactionType::PAYMENT, TransactionType::TIP, TransactionType::EXPENSE])) {
                $this->clubService->recalculateBalance($transaction->club);
            }

            return true;
        } catch (Exception $exception) {
            Log::error('Error deleting transaction', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Get transactions by club id
     */
    public function getByClubId(int $clubId): Collection
    {
        return Transaction::where('club_id', $clubId)
            ->get();
    }
}
