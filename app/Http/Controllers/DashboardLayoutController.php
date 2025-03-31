<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardLayoutStoreRequest;
use App\Models\DashboardLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardLayoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DashboardLayoutStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $layout = DashboardLayout::where('user_id', $validated['user_id'])
            ->where('club_id', $validated['club_id'])
            ->first();

        if ($layout) {
            $r = $layout->update(['layout' => json_encode($validated['layout'])]);
            Log::debug('Layout updated', ['r' => $r]);
        } else {
            DashboardLayout::create(
                [
                    'user_id' => $validated['user_id'],
                    'club_id' => $validated['club_id'],
                    'layout' => json_encode($validated['layout']),
                ],
            );
        }

        return back();
    }
}
