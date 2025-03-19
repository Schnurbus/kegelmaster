<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClubSelectController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'club_id' => ['required', 'exists:clubs,id'],
        ]);

        $club = \App\Models\Club::find($validated['club_id']);

        session(['currentClub' => $club]);

        Log::info('Changing current club for user {user}', ['user' => Auth::user(), 'club_id' => $club->id]);

        return redirect()->back()->withCookie(cookie('currentClubId', $club->id, 1440));
    }
}
