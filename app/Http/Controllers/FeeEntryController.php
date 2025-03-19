<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeeEntryRequest;
use App\Models\FeeEntry;
use App\Models\Matchday;
use App\Services\FeeEntryService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeeEntryController extends Controller
{
    public function __construct(private FeeEntryService $feeEntryService) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreFeeEntryRequest $request, Matchday $matchday)
    {
        try {
            $validated = $request->validated();
            Log::debug('Updating fee entries', ['data' => $validated]);
            foreach ($validated['entries'] as $entry) {
                $feeEntry = FeeEntry::find($entry['id']);
                if (! $feeEntry) {
                    abort(404);
                }
                $this->feeEntryService->updateFeeEntry($feeEntry, $entry);
            }
            toast_success('Entries updated successfully');
        } catch (Exception $exception) {
            Log::error('Error updating fee entries', ['error' => $exception->getMessage()]);
            toast_error('Could not update entries');
        }

        return redirect()->back();
    }
}
