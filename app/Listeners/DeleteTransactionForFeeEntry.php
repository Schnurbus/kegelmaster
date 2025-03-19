<?php

namespace App\Listeners;

use App\Contracts\TransactionServiceContract;
use Illuminate\Support\Facades\Log;

class DeleteTransactionForFeeEntry
{
    protected $transactionService;

    /**
     * Create the event listener.
     */
    public function __construct(TransactionServiceContract $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Log::debug('DeleteTransactionForFeeEntry listener fired', ['feeEntry' => $event->feeEntry]);
        $this->transactionService->deleteForFeeEntry($event->feeEntry);
    }
}
