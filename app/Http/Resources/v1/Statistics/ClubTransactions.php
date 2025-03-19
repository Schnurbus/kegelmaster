<?php

namespace App\Http\Resources\v1\Statistics;

use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Silber\Bouncer\BouncerFacade;

class ClubTransactions extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $club = $this->resource;
        BouncerFacade::authorize('view', $club);

        return Transaction::selectRaw('
        DATE(date) as date,
        (SUM(SUM(amount)) OVER (ORDER BY DATE(date)) * 1.0) / 100 + ? as balance
        ', [$club->initial_balance])
            ->where('club_id', $club->id)
            ->whereIn('type', [TransactionType::PAYMENT, TransactionType::TIP, TransactionType::EXPENSE])
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date')
            ->get()
            ->toArray();
    }
}
