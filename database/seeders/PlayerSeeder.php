<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Player;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BouncerFacade::scope()->to(1);
        $gastRole = Role::where('title', 'Gast')->firstOrFail();
        $mitgliedRole = Role::where('title', 'Mitglied')->firstOrFail();
        $kassenwartRole = Role::where('title', 'Kassenwart')->firstOrFail();
        $club = Club::where('name', 'Krumme Kugel')->firstOrFail();

        $players = [
            [
                'name' => 'Pascal',
                'initial_balance' => 0,
                'balance' => 0,
                'sex' => 1,
                'active' => 1,
                'role_id' => $mitgliedRole->id,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Nicole',
                'initial_balance' => 0,
                'balance' => 0,
                'sex' => 2,
                'active' => 1,
                'role_id' => $kassenwartRole->id,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Sven',
                'initial_balance' => 0,
                'balance' => 0,
                'sex' => 1,
                'active' => 1,
                'role_id' => $mitgliedRole->id,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Bine',
                'initial_balance' => 0,
                'balance' => 0,
                'sex' => 2,
                'active' => 1,
                'role_id' => $mitgliedRole->id,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Daniel',
                'initial_balance' => 0,
                'balance' => 0,
                'sex' => 1,
                'active' => 0,
                'role_id' => $mitgliedRole->id,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Monique',
                'initial_balance' => 0,
                'balance' => 0,
                'sex' => 2,
                'active' => 0,
                'role_id' => $gastRole->id,
                'club_id' => $club->id,
            ],
        ];

        Player::factory()
            ->count(count($players))
            ->sequence(
                ...$players
            )
            ->create();
    }
}
