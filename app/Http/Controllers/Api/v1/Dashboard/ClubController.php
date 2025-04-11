<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\BalanceListResource;
use App\Http\Resources\ClubResource;
use App\Models\Club;
use App\Models\Transaction;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ClubController extends Controller
{
    /**
     * Get a balance list over the last months
     */
    public function balance(Club $club): AnonymousResourceCollection
    {
        if (! Gate::allows('view', $club)) {
            abort(403);
        }

        $balanceList = Transaction::selectRaw('
            DATE(date) as date,
            (SUM(SUM(amount)) OVER (ORDER BY DATE(date)) * 1.0) / 100 + ? as balance
            ', [$club->initial_balance])
            ->where('club_id', $club->id)
            ->whereIn('type', [TransactionType::PAYMENT, TransactionType::TIP, TransactionType::EXPENSE])
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date')
            ->paginate();

        return BalanceListResource::collection($balanceList);
    }

    /**
     * Get the current club info
     */
    public function show(Club $club): ClubResource
    {
        if (! Gate::allows('view', $club)) {
            abort(403);
        }

        return new ClubResource($club);
    }
}
