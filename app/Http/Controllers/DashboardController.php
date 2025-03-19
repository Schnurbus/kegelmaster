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
        $club = session('currentClub');
        $user = User::find(Auth::user()->id)->firstOrFail();
        $player = Player::where('user_id', $user->id)->firstOrFail();

        $competitionType = $club->competitionTypes->first();

        $layout = DashboardLayout::where('user_id', $user->id)
            ->where('club_id', $club->id)
            ->first();

        $baseLayout = [
            [
                'x' => 0,
                'y' => 1,
                'w' => 1,
                'h' => 1,
                'id' => '1',
                'component' => 'Balance',
                'props' => ['player_id' => $player->id],
            ],
            [
                'x' => 0,
                'y' => 0,
                'w' => 1,
                'h' => 1,
                'id' => '2',
                'component' => 'Balance',
                'props' => ['club_id' => $club->id],
            ],
            [
                'x' => 1,
                'y' => 0,
                'w' => 2,
                'h' => 2,
                'id' => '3',
                'component' => 'ClubBalanceApex',
                'props' => ['club_id' => $club->id],
            ],
            [
                'x' => 3,
                'y' => 0,
                'w' => 2,
                'h' => 2,
                'id' => '4',
                'component' => 'LastCompetition',
                'props' => ['competition_type_id' => $club->competitionTypes()->first()->id],
            ],
        ];

        return Inertia::render('Dashboard', [
            'club' => $club,
            'layout' => $layout ? json_decode($layout->layout) : $baseLayout,
            'competitionType' => $competitionType ?? null,
        ]);
    }
}
