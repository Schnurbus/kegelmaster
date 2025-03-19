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
        // CompetitionType laden
        $competitionType = CompetitionType::findOrFail($this->id);
        $isSexSpecific = (bool) $competitionType->is_sex_specific;
        $compType = (int) $competitionType->type; // 1: highest & lowest, 2: highest only, 3: lowest only

        // Ermitteln des letzten Matchdays für diesen Wettbewerb
        $lastEntry = CompetitionEntry::join('matchdays', 'competition_entries.matchday_id', '=', 'matchdays.id')
            ->where('competition_entries.competition_type_id', $this->id)
            ->orderByDesc('matchdays.date')
            ->select('competition_entries.matchday_id')
            ->first();

        if (!$lastEntry) {
            return ['message' => 'Keine Einträge für diesen Wettbewerb gefunden.'];
        }

        $lastMatchdayId = $lastEntry->matchday_id;

        // Alle Einträge des letzten Matchdays holen (ignore amount == 0)
        $entries = CompetitionEntry::with('player')
            ->where('competition_type_id', $this->id)
            ->where('matchday_id', $lastMatchdayId)
            ->where('amount', '>', 0)
            ->get();

        // Gruppieren nach "all" oder per Geschlecht falls is_sex_specific true.
        $grouped = $isSexSpecific ? $entries->groupBy(function ($entry) {
            return $entry->player && isset($entry->player->sex) ? $entry->player->sex : 'unknown';
        }) : ['all' => $entries];

        $results = [];

        foreach ($grouped as $groupKey => $groupEntries) {
            $extreme = [
                'highest' => null, // enthält ['amount' => ..., 'player' => ...]
                'lowest' => null,
            ];

            foreach ($groupEntries as $entry) {
                // Sicherstellen, dass ein Player referenziert ist
                if (!$entry->player) {
                    continue;
                }
                $amount = $entry->amount;
                // Ermitteln des höchsten Werts
                if (($compType === 1 || $compType === 2)) {
                    if ($extreme['highest'] === null || $amount > $extreme['highest']['amount']) {
                        $extreme['highest'] = [
                            'amount' => $amount,
                            'player' => $entry->player,
                        ];
                    }
                }
                // Ermitteln des niedrigsten Werts
                if (($compType === 1 || $compType === 3)) {
                    if ($extreme['lowest'] === null || $amount < $extreme['lowest']['amount']) {
                        $extreme['lowest'] = [
                            'amount' => $amount,
                            'player' => $entry->player,
                        ];
                    }
                }
            }

            // Je nach Wettbewerbstyp (compType) definieren, was zurückgegeben werden soll.
            $resultData = [];
            if ($compType === 1) {
                $resultData = [
                    'highest' => $extreme['highest'],
                    'lowest' => $extreme['lowest']
                ];
            } elseif ($compType === 2) {
                $resultData = [
                    'highest' => $extreme['highest']
                ];
            } elseif ($compType === 3) {
                $resultData = [
                    'lowest' => $extreme['lowest']
                ];
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
