<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompetitionEntryRequest;
use App\Models\CompetitionEntry;
use App\Models\Matchday;
use Exception;
use Illuminate\Support\Facades\Log;

class CompetitionEntryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreCompetitionEntryRequest $request, Matchday $matchday)
    {
        try {
            $validated = $request->validated();
            Log::debug('Updating competition entries', ['data' => $validated]);
            foreach ($validated['entries'] as $competitionEntry) {
                CompetitionEntry::where('id', $competitionEntry['id'])
                    ->where('matchday_id', $matchday->id)
                    ->update(['amount' => $competitionEntry['amount']]);
            }
            toast_success('Entries updated successfully');
        } catch (Exception $exception) {
            Log::error("Error updating competition entries", ['error' => $exception->getMessage()]);
            toast_error('Could not update entries');
        }

        return redirect()->back();
    }
}
