<?php

namespace App\Listeners;

use App\Contracts\PlayerServiceContract;
use App\Events\TransactionDeleted;
use Illuminate\Support\Facades\Log;

class RemoveTransactionFromPlayer
{
    protected $playerService;

    /**
     * Create the event listener.
     */
    public function __construct(PlayerServiceContract $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionDeleted $event): void
    {
        Log::debug('RemoveTransactionFromPlayer listener fired', ['transaction' => $event->transaction]);
        $this->playerService->removeTransaction($event->transaction);
    }
}
