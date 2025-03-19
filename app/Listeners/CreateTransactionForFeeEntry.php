<?php

namespace App\Listeners;

use App\Contracts\TransactionServiceContract;
use App\Events\FeeEntryCreated;
use Illuminate\Support\Facades\Log;

class CreateTransactionForFeeEntry
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
    public function handle(FeeEntryCreated $event): void
    {
        Log::debug('CreateTransactionForFeeEntry listener fired', ['feeEntry' => $event->feeEntry]);
        $this->transactionService->updateForFeeEntry($event->feeEntry);
    }
}
