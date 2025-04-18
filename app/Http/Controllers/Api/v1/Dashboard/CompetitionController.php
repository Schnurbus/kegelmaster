<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CompetitionEntry;
use App\Models\CompetitionType;
use App\Models\Matchday;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Response;

class CompetitionController extends Controller
{
    /**
     * Get the competition results for the last matchday
     */
    public function last(CompetitionType $competitionType): JsonResponse
    {
        if (! Gate::allows('view', Matchday::class)) {
            abort(403);
        }

        $lastEntry = CompetitionEntry::join('matchdays', 'competition_entries.matchday_id', '=', 'matchdays.id')
            ->where('competition_entries.competition_type_id', $competitionType->id)
            ->orderByDesc('matchdays.date')
            ->select('competition_entries.matchday_id')
            ->first();

        if (! $lastEntry) {
            return Response::json([
                'message' => 'Keine Einträge für diesen Wettbewerb gefunden.',
            ]);
        }

        $lastMatchdayId = $lastEntry->matchday_id;

        $entries = CompetitionEntry::with('player')
            ->where('competition_type_id', $competitionType->id)
            ->where('matchday_id', $lastMatchdayId)
            ->where('amount', '>', 0)
            ->get();

        $grouped = $competitionType->is_sex_specific ? $entries->groupBy(function ($entry) {
            return $entry->player->sex ?? 'unknown';
        }) : ['all' => $entries];

        $results = [];
        foreach ($grouped as $groupKey => $groupEntries) {
            $extreme = [
                'highest' => null,
                'lowest' => null,
            ];

            foreach ($groupEntries as $entry) {
                if (! isset($entry->player)) {
                    continue;
                }

                $amount = $entry->amount;
                if (in_array($competitionType->type, [1, 2])) {
                    if ($extreme['highest'] === null || $amount > $extreme['highest']['amount']) {
                        $extreme['highest'] = [
                            'amount' => $amount,
                            'player' => $entry->player,
                        ];
                    }
                }
                if (in_array($competitionType->type, [1, 3])) {
                    if ($extreme['lowest'] === null || $amount < $extreme['lowest']['amount']) {
                        $extreme['lowest'] = [
                            'amount' => $amount,
                            'player' => $entry->player,
                        ];
                    }
                }
            }

            $resultData = [];
            if (in_array($competitionType->type, [1, 2])) {
                $resultData['highest'] = $extreme['highest'];
            }
            if (in_array($competitionType->type, [1, 3])) {
                $resultData['lowest'] = $extreme['lowest'];
            }

            $results[$groupKey] = $resultData;
        }

        return Response::json([
            'matchday_id' => $lastMatchdayId,
            'competition_type' => $competitionType,
            'results' => $results,
        ]);
    }
}
