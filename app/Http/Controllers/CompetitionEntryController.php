<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompetitionEntryRequest;
use App\Models\CompetitionEntry;
use App\Models\Matchday;
use App\Services\CompetitionEntryService;
use Exception;
use Illuminate\Support\Facades\Log;

class CompetitionEntryController extends Controller
{
    private CompetitionEntryService $competitionEntryService;

    public function __construct(CompetitionEntryService $competitionEntryService)
    {
        $this->competitionEntryService = $competitionEntryService;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreCompetitionEntryRequest $request, Matchday $matchday): \Illuminate\Http\RedirectResponse
    {
        try {
            $validated = $request->validated();
            Log::debug('Updating competition entries', ['data' => $validated]);
            foreach ($validated['entries'] as $entry) {
                if (isset($entry['id'])) {
                    $competitionEntry = CompetitionEntry::find($entry['id']);
                    if (! $competitionEntry) {
                        abort(404);
                    }
                    $this->competitionEntryService->updateCompetitionEntry($competitionEntry, $entry);
                } else {
                    $this->competitionEntryService->createCompetitionEntry($entry);
                }
            }
            toast_success('Entries updated successfully');
        } catch (Exception $exception) {
            Log::error('Error updating competition entries', ['error' => $exception->getMessage()]);
            toast_error('Could not update entries');
        }

        return redirect()->back();
    }
}
