<?php

namespace App\Http\Middleware;

use App\Models\Club;
use App\Models\Player;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
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
            $user = $request->user();
            $clubs = $request->user()->clubs->merge($request->user()->players->pluck('club')->filter());
            session(['userClubs' => $clubs]);

            $clubId = $request->cookie('currentClubId');

            // No club cookie
            if (! $clubId) {
                // User has clubs
                if (count($clubs) > 0) {
                    $currentClub = $clubs->first();
                    session(['currentClub' => $currentClub]);
                    Cookie::queue('currentClubId', $currentClub->id, 60 * 24 * 30);

                    return $next($request);
                }
                // We have club cookie
            } else {
                $currentClub = Club::find($clubId);
                // Found club from cookie
                if ($currentClub) {
                    session(['currentClub' => $currentClub]);

                    $player = Player::where('club_id', $currentClub->id)
                        ->where('user_id', $user->id)
                        ->first();

                    if ($player) {
                        session(['currentPlayer' => $player]);
                    }

                    return $next($request);
                    // Club not found -> invalid cookie
                } else {
                    // Change cookie to existing club
                    if (count($clubs) > 0) {
                        $currentClub = $clubs->first();
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
        Log::info('Middleware LoadCurrentClubSetting ausgeführt für Route: '.$request->path());

        return $next($request);
    }
}
