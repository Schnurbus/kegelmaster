<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClubSelectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'club_id' => ['required', 'exists:clubs,id'],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $clubId = $validated['club_id'];

        setPermissionsTeamId($clubId);

        $isClubOwner = $user->clubs()->where('id', $clubId)->exists();
        $player = $user->players()
            ->where('club_id', $clubId)
            ->first();

        if (! $isClubOwner && ! isset($player)) {
            abort(403);
        }

        //        if ($player) {
        //            Auth::guard('player')->login($player);
        //        }
        //        if (isset($player)) {
        //            /** @var Player $player */
        //            $user->syncRoles($player->role);
        //        }

        Log::info('Changing current club for user {user}', ['user' => Auth::user(), 'club_id' => $clubId]);

        return redirect()->back()->withCookie(cookie('currentClubId', strval($clubId), 1440));
    }
}
