<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubsPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! empty(auth()->user())) {
            $currentClubId = session('current_club_id');
            if (! empty($currentClubId)) {
                setPermissionsTeamId($currentClubId);
            }
        }

        return $next($request);
    }
}
