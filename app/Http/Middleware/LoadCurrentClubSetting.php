<?php

namespace App\Http\Middleware;

use App\Models\Club;
use App\Models\Player;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class LoadCurrentClubSetting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = $request->user();
            $clubs = $user->clubs->merge($user->players->pluck('club')->filter());
            session(['userClubs' => $clubs]);

            $clubId = $request->cookie('currentClubId');

            // No club cookie
            if (! $clubId) {
                // User has clubs
                if (count($clubs) > 0) {
                    $currentClub = $clubs->first();
                    session(['current_club_id' => $currentClub->id]);
                    session(['currentClub' => $currentClub]);
                    Cookie::queue('currentClubId', $currentClub->id, 60 * 24 * 30);

                    return $next($request);
                }
            } else {
                // We have club cookie
                $currentClub = Club::find($clubId, 'id');

                if ($currentClub) {
                    // Found club from cookie
                    session(['current_club_id' => $currentClub->id]);
                    session(['currentClub' => $currentClub]);

                    //                    $player = Player::where('club_id', $currentClub->id)
                    //                        ->where('user_id', $user->id)
                    //                        ->first();
                    $player = $user->players->where('club_id', $currentClub->id)->first();
                    if ($player) {
                        session(['currentPlayer' => $player]);
                    }

                    return $next($request);
                } else {
                    // Club not found -> invalid cookie
                    if (count($clubs) > 0) {
                        $currentClub = $clubs->first();
                        session(['current_club_id' => $currentClub->id]);
                        session(['currentClub' => $currentClub]);
                        Cookie::queue('currentClubId', $currentClub->id, 60 * 24 * 30);

                        return $next($request);
                        // Delete cookie
                    } else {
                        Cookie::expire('currentClubId');
                    }
                }
            }
        }

        return $next($request);
    }
}
