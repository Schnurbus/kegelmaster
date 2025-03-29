<?php

namespace App\Services;

use App\Models\CompetitionEntry;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompetitionEntryService
{
    /**
     * Create a competition entry
     *
     * @param  array  $competitionEntryData  {matchday_id: number, fee_type_version_id: number, player_id: number, amount: number}
     *
     * @throws Exception
     */
    public function createCompetitionEntry(array $competitionEntryData): ?CompetitionEntry
    {
        Log::debug('createCompetitionEntry called');
        try {
            $competitionEntry = CompetitionEntry::create($competitionEntryData);
            Log::info('Competition entry created', ['user_id' => Auth::user()->id, 'competitionEntry' => $competitionEntry]);

            return $competitionEntry;
        } catch (Exception $exception) {
            Log::error('Error creating competition enty', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Update competition entry
     *
     * @param  array  $competitionEntryData  {matchday_id: number, competition_type_id: number, player_id: number, amount: number}
     */
    public function updateCompetitionEntry(CompetitionEntry $competitionEntry, array $competitionEntryData): ?CompetitionEntry
    {
        // Log::debug('updateCompetitionEntry called');
        try {
            $competitionEntry->fill($competitionEntryData);

            if (! $competitionEntry->isDirty()) {
                return $competitionEntry;
            }

            $competitionEntry->save();
            Log::info('Competition entry updated', ['user_id' => Auth::user()->id, 'competitionEntry' => $competitionEntry]);

            return $competitionEntry;
        } catch (Exception $exception) {
            Log::error('Error updating competition entry', ['error' => $exception->getMessage()]);

            return null;
        }
    }

    /**
     * Delete competition entry
     *
     * @throws Exception
     */
    public function deleteCompetitionEntry(CompetitionEntry $competitionEntry): bool
    {
        try {
            $competitionEntry->delete();
            Log::info('Competition entry deleted', ['user_id' => Auth::user()->id, 'competitionEntry' => $competitionEntry]);

            return true;
        } catch (Exception $exception) {
            Log::error('Error deleting fee enty', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }
}
