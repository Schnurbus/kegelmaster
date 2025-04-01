<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\BalanceListResource;
use App\Http\Resources\ClubResource;
use App\Models\Club;
use App\Models\Transaction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Silber\Bouncer\BouncerFacade;

class ClubController extends Controller
{
    /**
     * Get a balance list over the last months
     *
     * @throws AuthorizationException
     */
    public function balance(Club $club): AnonymousResourceCollection
    {
        BouncerFacade::authorize('view', $club);

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
     *
     * @return ClubResource
     *
     * @throws AuthorizationException
     */
    public function show(Club $club)
    {
        BouncerFacade::authorize('view', $club);

        return new ClubResource($club);
    }
}
