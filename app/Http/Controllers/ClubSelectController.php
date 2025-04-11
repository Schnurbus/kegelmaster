<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        /** @var User $user */
        $user = Auth::user();
        $clubId = $validated['club_id'];

        setPermissionsTeamId($clubId);

        $isClubOwner = $user->clubs()->where('id', $clubId)->exists();
        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();

        if (! $isClubOwner && ! $hasPlayer) {
            abort(403);
        }

        Log::info('Changing current club for user {user}', ['club_id' => $clubId]);

        return redirect()->back()->withCookie(cookie('currentClubId', strval($clubId), 1440));
    }
}
