<?php

namespace App\Http\Resources\v1\Statistics;

use App\Models\CompetitionEntry;
use App\Models\CompetitionType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LastCompetition extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $competitionType = $this->resource;

        $lastEntry = CompetitionEntry::join('matchdays', 'competition_entries.matchday_id', '=', 'matchdays.id')
            ->where('competition_entries.competition_type_id', $competitionType->id)
            ->orderByDesc('matchdays.date')
            ->select('competition_entries.matchday_id')
            ->first();

        if (! $lastEntry) {
            return ['message' => 'Keine Einträge für diesen Wettbewerb gefunden.'];
        }

        $lastMatchdayId = $lastEntry->matchday_id;

        $entries = CompetitionEntry::with('player')
            ->where('competition_type_id', $competitionType->id)
            ->where('matchday_id', $lastMatchdayId)
            ->where('amount', '>', 0)
            ->get();

        $grouped = $competitionType->is_sex_specific ? $entries->groupBy(function ($entry) {
            return $entry->player && isset($entry->player->sex) ? $entry->player->sex : 'unknown';
        }) : ['all' => $entries];

        $results = [];
        foreach ($grouped as $groupKey => $groupEntries) {
            $extreme = [
                'highest' => null,
                'lowest' => null,
            ];

            foreach ($groupEntries as $entry) {
                if (! $entry->player) {
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

        return [
            'matchday_id' => $lastMatchdayId,
            'competition_type' => $competitionType,
            'results' => $results,
        ];
    }
}
