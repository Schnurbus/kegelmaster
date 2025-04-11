<?php

namespace App\Http\Controllers;

use App\Models\DashboardLayout;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $currentClubId = session('current_club_id');
        /** @var User $user */
        $user = Auth::user();

        if (isset($currentClubId)) {
            $player = Player::where('club_id', $currentClubId)
                ->where('user_id', $user->id)
                ->first();
            $dashboardLayout = DashboardLayout::where('club_id', $currentClubId)
                ->where('user_id', $user->id)
                ->first();
        }

        return Inertia::render('Dashboard', [
            'playerId' => empty($player) ? null : $player->id,
            'layout' => json_decode($dashboardLayout->layout ?? '[]', true),
        ]);
    }
}
