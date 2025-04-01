<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\Player;
use App\Models\Transaction;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class TransactionService
{
    public function __construct(
        private ClubService $clubService,
        private PlayerService $playerService
    ) {}

    /**
     * Create one or multiple transactions
     *
     * @param  array  $transactionData  { club_id: number, ?player_id: number|array, ?matchday_id: number,
     *                                  ?fee_entry_id: number, amount: number, type: TransactionType,
     *                                  date: string, ?auto_tip: boolean, ?notes: string }
     * @return array|Transaction|null Returns an array of Transactions when multiple transactions are created,
     *                                a single Transaction for single transactions,
     *                                or null if an error occurs
     *
     * @throws Throwable
     */
    public function createTransaction(array $transactionData): array|Transaction|null
    {
        try {
            $type = $transactionData['type'];

            // Case 1: Transaction type EXPENSE (5) doesn't require player_id
            if ($type === TransactionType::EXPENSE) {
                // Ensure player_id is null for EXPENSE transactions
                $transactionData['player_id'] = null;
                /** @var Transaction $transaction */
                $transaction = Transaction::create($transactionData);

                Log::info('EXPENSE transaction created', [
                    'transaction_id' => $transaction->id,
                    'amount' => $transactionData['amount'],
                ]);

                return $transaction;
            }

            // Case 2: No player_id provided but required
            if (! isset($transactionData['player_id'])) {
                throw new Exception('player_id is required for transaction type '.$type);
            }

            // Case 3: Payment transaction (type 3) with potential multiple players
            if ($type === TransactionType::PAYMENT) {
                // Convert single player_id to array for uniform processing
                $playerIds = is_array($transactionData['player_id'])
                    ? $transactionData['player_id']
                    : [$transactionData['player_id']];

                $autoTip = $transactionData['auto_tip'] ?? false;

                // Process the payment transaction for one or more players
                return $this->processPaymentTransaction($playerIds, $transactionData['amount'], $autoTip, $transactionData);
            }

            // Case 4: Other transaction types (always single player)
            // Validate that player_id is not an array for non-payment transactions
            if (is_array($transactionData['player_id'])) {
                throw new Exception('Multiple player_ids are only supported for payment transactions');
            }

            /** @var Transaction $transaction */
            $transaction = Transaction::create($transactionData);

            Log::info('Transaction created', [
                'transaction_id' => $transaction->id,
                'player_id' => $transactionData['player_id'],
                'type' => $type,
                'amount' => $transactionData['amount'],
            ]);

            return $transaction;
        } catch (Exception $e) {
            Log::error('Error creating transaction', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $transactionData,
            ]);
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Process a payment transaction for one or more players
     *
     * @param  array  $playerIds  Array of player IDs
     * @param  float  $amount  Total payment amount
     * @param  bool  $autoTip  Whether to create tip transaction for remaining amount
     * @param  array  $baseTransactionData  Base transaction data
     * @return array|Transaction Array of transactions or single transaction
     *
     * @throws Throwable
     */
    protected function processPaymentTransaction(array $playerIds, float $amount, bool $autoTip, array $baseTransactionData): array|Transaction
    {
        // If only one player, simplify processing
        if (count($playerIds) === 1) {
            return $this->processSinglePlayerPayment($playerIds[0], $amount, $autoTip, $baseTransactionData);
        }

        // For multiple players, retrieve them all at once
        $players = Player::whereIn('id', $playerIds)->get();

        if ($players->isEmpty()) {
            throw new Exception('No valid players found');
        }

        // Calculate total balance of all players
        $totalBalance = $players->sum('balance');
        $totalAbsBalance = abs($totalBalance);

        // If amount is less than or equal to total balance, distribute evenly
        if ($amount <= $totalAbsBalance) {
            return $this->distributePaymentEvenly($players, $amount, $baseTransactionData);
        }

        // If amount is greater than total balance, handle according to auto_tip setting
        return DB::transaction(function () use ($players, $amount, $autoTip, $totalAbsBalance, $baseTransactionData) {
            $transactions = [];

            // First, create transactions to zero all balances
            foreach ($players as $player) {
                if ($player->balance >= 0) {
                    continue;
                } // Skip players with non-negative balance

                $playerTransactionData = array_merge($baseTransactionData, [
                    'player_id' => $player->id,
                    'amount' => abs($player->balance),
                ]);

                /** @var Transaction $transaction */
                $transaction = Transaction::create($playerTransactionData);
                $this->playerService->recalculateBalance($player);
                $transactions[] = $transaction;

                Log::info('Multi-player payment: Balance zeroed', [
                    'transaction_id' => $transaction->id,
                    'player_id' => $player->id,
                    'amount' => abs($player->balance),
                ]);
            }

            // Handle remaining amount
            $remainingAmount = $amount - $totalAbsBalance;

            if ($remainingAmount > 0) {
                if ($autoTip) {
                    // Create tip for the first player
                    $tipData = array_merge($baseTransactionData, [
                        'player_id' => $players->first()->id,
                        'amount' => $remainingAmount,
                        'type' => TransactionType::TIP,
                        'notes' => 'Auto Tip',
                    ]);

                    /** @var Transaction $tipTransaction */
                    $tipTransaction = Transaction::create($tipData);
                    $transactions[] = $tipTransaction;

                    Log::info('Multi-player payment: Tip created for remaining amount', [
                        'transaction_id' => $tipTransaction->id,
                        'player_id' => $players->first()->id,
                        'amount' => $remainingAmount,
                    ]);
                } else {
                    // Distribute remaining amount proportionally
                    $additionalTransactions = $this->distributeAmountProportionally(
                        $players,
                        $remainingAmount,
                        $baseTransactionData
                    );

                    $transactions = array_merge($transactions, $additionalTransactions);
                }
            }

            return $transactions;
        });
    }

    /**
     * Process a payment transaction for a single player
     *
     * @throws Throwable
     */
    protected function processSinglePlayerPayment(int $playerId, float $amount, bool $autoTip, array $transactionData): Transaction
    {
        $player = Player::findOrFail($playerId);

        // If amount would exceed the player's debt and auto_tip is enabled
        if ($autoTip && $amount + $player->balance > 0) {
            return DB::transaction(function () use ($player, $amount, $transactionData) {
                // Create payment transaction for the exact balance amount
                $paymentData = array_merge($transactionData, [
                    'player_id' => $player->id,
                    'amount' => abs($player->balance),
                ]);

                // Create tip transaction for the remaining amount
                $tipData = array_merge($transactionData, [
                    'player_id' => $player->id,
                    'type' => TransactionType::TIP,
                    'amount' => $amount + $player->balance,
                ]);

                /** @var Transaction $paymentTransaction */
                $paymentTransaction = Transaction::create($paymentData);
                /** @var Transaction $tipTransaction */
                $tipTransaction = Transaction::create($tipData);
                $this->playerService->recalculateBalance($player);

                Log::info('Payment with auto-tip created', [
                    'payment_id' => $paymentTransaction->id,
                    'tip_id' => $tipTransaction->id,
                    'player_id' => $player->id,
                    'payment_amount' => abs($player->balance),
                    'tip_amount' => $amount + $player->balance,
                ]);

                return $paymentTransaction; // Return the payment transaction
            });
        }

        // Regular payment transaction
        $transactionData['player_id'] = $player->id;
        /** @var Transaction $transaction */
        $transaction = Transaction::create($transactionData);
        $this->playerService->recalculateBalance($player);

        Log::info('Single player payment created', [
            'transaction_id' => $transaction->id,
            'player_id' => $player->id,
            'amount' => $amount,
        ]);

        return $transaction;
    }

    /**
     * Distribute a payment amount evenly among multiple players
     *
     * @throws Throwable
     */
    protected function distributePaymentEvenly(Collection $players, float $amount, array $baseTransactionData): array
    {
        // Only consider players with negative balance
        /** @var Collection $playersWithDebt */
        $playersWithDebt = $players->filter(function ($player) {
            /** @var Player $player */
            return $player->balance < 0;
        });

        if ($playersWithDebt->isEmpty()) {
            throw new Exception('No players with debt found to distribute payment');
        }

        $playerCount = $playersWithDebt->count();
        $baseAmountPerPlayer = floor($amount * 100 / $playerCount) / 100;
        $totalDistributed = $baseAmountPerPlayer * $playerCount;
        $remainderCents = round(($amount - $totalDistributed) * 100);

        return DB::transaction(function () use ($playersWithDebt, $baseAmountPerPlayer, $remainderCents, $baseTransactionData) {
            $transactions = [];

            /**
             * @var int $index
             * @var Player $player
             */
            foreach ($playersWithDebt as $index => $player) {
                $playerAmount = $baseAmountPerPlayer;

                // Add 1 cent to the first $remainderCents players
                if ($index < $remainderCents) {
                    $playerAmount += 0.01;
                }

                $playerTransactionData = array_merge($baseTransactionData, [
                    'player_id' => $player->id,
                    'amount' => $playerAmount,
                ]);

                /** @var Transaction $transaction */
                $transaction = Transaction::create($playerTransactionData);
                $transactions[] = $transaction;

                Log::info('Payment distributed evenly', [
                    'transaction_id' => $transaction->id,
                    'player_id' => $player->id,
                    'amount' => $playerAmount,
                    'extra_cent' => $index < $remainderCents,
                ]);
            }

            return $transactions;
        });
    }

    /**
     * Distribute an amount proportionally among multiple players
     *
     * @throws Throwable
     */
    protected function distributeAmountProportionally(Collection $players, float $amount, array $baseTransactionData): array
    {
        $playerCount = $players->count();
        $baseAmount = floor($amount * 100 / $playerCount) / 100;
        $totalDistributed = $baseAmount * $playerCount;
        $remainderCents = round(($amount - $totalDistributed) * 100);

        return DB::transaction(function () use ($players, $baseAmount, $remainderCents, $baseTransactionData) {
            $transactions = [];

            /**
             * @var int $index
             * @var Player $player
             */
            foreach ($players as $index => $player) {
                $playerAmount = $baseAmount;

                // Add 1 cent to the first $remainderCents players
                if ($index < $remainderCents) {
                    $playerAmount += 0.01;
                }

                $playerTransactionData = array_merge($baseTransactionData, [
                    'player_id' => $player->id,
                    'amount' => $playerAmount,
                ]);

                /** @var Transaction $transaction */
                $transaction = Transaction::create($playerTransactionData);
                $transactions[] = $transaction;

                Log::info('Remaining amount distributed proportionally', [
                    'transaction_id' => $transaction->id,
                    'player_id' => $player->id,
                    'amount' => $playerAmount,
                    'extra_cent' => $index < $remainderCents,
                ]);
            }

            return $transactions;
        });
    }

    /**
     * Update a transaction
     *
     * @param  array  $transactionData  { ?club_id: number, ?player_id: number, ?matchday_id: number, ?fee_entry_id: number, ?amount: number, ?type: number, ?date: string, ?notes: string }
     *
     * @throws Throwable
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
     * @throws Throwable
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
            ->orderByDesc('date')
            ->get();
    }
}
