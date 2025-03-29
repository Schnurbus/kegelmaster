<?php

namespace App\Http\Controllers;

use App\Models\DashboardLayout;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $club = session('currentClub');
        $user = User::find(Auth::user()->id)->firstOrFail();

        if (! $club) {
            return Inertia::render('Dashboard', [
                'club' => null,
                'layout' => null,
                'competitionType' => null,
            ]);
        }

        $player = Player::where('user_id', $user->id)
            ->where('club_id', $club->id)
            ->first();

        $competitionType = $club->competitionTypes->first();

        $layout = DashboardLayout::where('user_id', $user->id)
            ->where('club_id', $club->id)
            ->first();

        $baseLayout = [
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
        ];
        if ($player) {
            $baseLayout[] = [
                'x' => 0,
                'y' => 1,
                'w' => 1,
                'h' => 1,
                'id' => Str::uuid7()->toString(),
                'component' => 'Balance',
                'props' => ['player_id' => $player->id],
            ];
        }
        $firstCompetition = $club->competitionTypes()->first();
        if ($firstCompetition) {
            $baseLayout[] = [
                'x' => 3,
                'y' => 0,
                'w' => 2,
                'h' => 2,
                'id' => '4',
                'component' => 'LastCompetition',
                'props' => ['competition_type_id' => $club->competitionTypes()->first()->id],
            ];
        }

        return Inertia::render('Dashboard', [
            'club' => $club,
            'layout' => isset($layout) ? json_decode($layout->layout) : $baseLayout,
            'competitionType' => $competitionType ?? null,
        ]);
    }
}
