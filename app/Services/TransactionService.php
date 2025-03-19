<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Events\TransactionCreated;
use App\Events\TransactionDeleted;
use App\Models\FeeEntry;
use App\Models\Player;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\BouncerFacade;

class TransactionService
{
    public function __construct(
        private ClubService $clubService,
        private PlayerService $playerService
    ) {}

    public function createForFeeEntry(FeeEntry $feeEntry): ?Transaction
    {
        if ((int) $feeEntry->amount === 0) {
            return null;
        }

        Log::debug('Creating transaction for fee entry', [
            'feeEntry' => $feeEntry->toArray(),
        ]);

        $transaction = Transaction::create([
            'club_id' => $feeEntry->matchday->club_id,
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

        TransactionCreated::dispatch($transaction);

        return $transaction;
    }

    public function updateForFeeEntry(FeeEntry $feeEntry): bool
    {
        $oldTransaction = Transaction::where('fee_entry_id', $feeEntry->id)
            ->where('player_id', $feeEntry->player_id)
            ->where('matchday_id', $feeEntry->matchday_id)
            ->first();

        if ($oldTransaction) {
            TransactionDeleted::dispatch($oldTransaction);
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
            $response = TransactionDeleted::dispatch($transaction);
            Log::debug('deleteForFeeEntry: event dispatched', ['response' => $response]);

            $transaction->delete();
            Log::debug('deleteForFeeEntry: transaction deleted');
        }

        Log::debug('deleteForFeeEntry finished');

        return true;
    }

    /**
     * Create a transaction
     *
     * @param array { club_id: number, ?player_id: number, ?matchday_id: number, ?fee_entry_id: number, amount: number, type: TransactionType, date: string, auto_tip: boolean, ?notes: string } $transactionData
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

            return null;
        }
    }

    /**
     * Update a transaction
     *
     * @param array { ?club_id: number, ?player_id: number, ?matchday_id: number, ?fee_entry_id: number, ?amount: number, ?type: number, ?date: string, ?notes: string } $transactionData
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

            return null;
        }
    }

    /**
     * Delete a transaction
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

            return false;
        }
    }

    public function getTransactionsWithPermissions(User $user, int $clubId)
    {
        BouncerFacade::scope()->to($clubId);

        $transactions = Transaction::where('club_id', $clubId)
            ->with([
                'player' => function ($query) {
                    $query->select('id', 'name');
                },
                'matchday' => function ($query) {
                    $query->select('id', 'date');
                },
                'feeEntry.feeTypeVersion' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return $transactions->map(function ($transaction) use ($user) {
            $permissions = [
                'view' => $user->can('view', $transaction),
                'update' => $user->can('update', $transaction),
                'delete' => $user->can('delete', $transaction),
            ];

            if (! $user->can('view', $transaction)) {
                $transaction->makeHidden('amount');
            }

            $transactionArray = $transaction->toArray();
            $transactionArray['can'] = $permissions;

            return $transactionArray;
        });
    }
}
