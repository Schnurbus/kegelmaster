<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\FeeEntry;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FeeEntryService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private TransactionService $transactionService
    ) {}

    /**
     * Create a fee entry
     *
     * @param  $feeEntryData  array {matchday_id: number, fee_type_version_id: number, player_id: number, amount: number}
     *
     * @throws Exception
     */
    public function createFeeEntry(array $feeEntryData): ?FeeEntry
    {
        Log::debug('createFeeEntry called');
        try {
            $feeEntry = FeeEntry::create($feeEntryData);
            Log::info('Fee Entry created', ['user_id' => Auth::user()->id, 'feeEntry' => $feeEntry]);

            if ($feeEntry->amount > 0 && $feeEntry->feeTypeVersion->amount != 0) {
                $this->transactionService->createTransaction([
                    'club_id' => $feeEntry->matchday->club_id,
                    'player_id' => $feeEntry->player_id,
                    'matchday_id' => $feeEntry->matchday_id,
                    'fee_entry_id' => $feeEntry->id,
                    'amount' => $feeEntry->amount * $feeEntry->feeTypeVersion->amount,
                    'type' => TransactionType::FEE,
                    'date' => $feeEntry->matchday->date,
                ]);
            }

            return $feeEntry;
        } catch (Exception $exception) {
            Log::error('Error creating fee enty', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Update fee entry
     *
     * @param  array  $feeEntryData  {matchday_id: number, fee_type_version_id: number, player_id: number, amount: number}
     *
     * @throws Exception
     */
    public function updateFeeEntry(FeeEntry $feeEntry, array $feeEntryData): ?FeeEntry
    {
        // Log::debug('updateFeeEntry called');
        try {
            $feeEntry->fill($feeEntryData);

            if (! $feeEntry->isDirty()) {
                return $feeEntry;
            }

            $feeEntry->save();
            Log::info('Fee Entry updated', ['user_id' => Auth::user()->id, 'feeEntry' => $feeEntry]);

            if ($feeEntry->transaction) {
                if ($feeEntry->amount == 0) {
                    $this->transactionService->deleteTransaction($feeEntry->transaction);
                } else {
                    $this->transactionService->updateTransaction(
                        $feeEntry->transaction,
                        [
                            'club_id' => $feeEntry->matchday->club_id,
                            'player_id' => $feeEntry->player_id,
                            'matchday_id' => $feeEntry->matchday_id,
                            'fee_entry_id' => $feeEntry->id,
                            'amount' => $feeEntry->amount * $feeEntry->feeTypeVersion->amount * -1,
                            'type' => TransactionType::FEE,
                            'date' => $feeEntry->matchday->date,
                        ]
                    );
                }
            } elseif ($feeEntry->amount != 0 && $feeEntry->feeTypeVersion->amount != 0) {
                Log::debug('Creating new transaction for existing fee entry', ['feeEntry' => $feeEntry]);
                $this->transactionService->createTransaction([
                    'club_id' => $feeEntry->matchday->club_id,
                    'player_id' => $feeEntry->player_id,
                    'matchday_id' => $feeEntry->matchday_id,
                    'fee_entry_id' => $feeEntry->id,
                    'amount' => $feeEntry->amount * $feeEntry->feeTypeVersion->amount * -1,
                    'type' => TransactionType::FEE,
                    'date' => $feeEntry->matchday->date,
                ]);
            }

            return $feeEntry;
        } catch (Exception $exception) {
            Log::error('Error updating fee entry', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Delete fee entry
     *
     * @throws Exception
     */
    public function deleteFeeEntry(FeeEntry $feeEntry): bool
    {
        try {
            $feeEntry->transaction?->delete();
            $feeEntry->delete();
            Log::info('Fee Entry deleted', ['user_id' => Auth::user()->id, 'feeEntry' => $feeEntry]);

            return true;
        } catch (Exception $exception) {
            Log::error('Error deleting fee enty', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }
}
