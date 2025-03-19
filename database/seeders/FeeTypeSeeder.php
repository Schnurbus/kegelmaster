<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\FeeType;
use Illuminate\Database\Seeder;

class FeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $club = Club::where('name', 'Krumme Kugel')->firstOrFail();

        $feeTypes = [
            [
                'name' => 'Verlorenes Spiel',
                'amount' => 0.20,
                'position' => 1,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Gosse',
                'amount' => 0.20,
                'position' => 2,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Klingel',
                'amount' => 0.20,
                'position' => 3,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Lustwurf',
                'amount' => 0.20,
                'position' => 4,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Kugel',
                'amount' => 1,
                'position' => 5,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Fluchen',
                'description' => 'Schimpfwort auf der Bahn',
                'amount' => 1,
                'position' => 6,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Puppe',
                'description' => 'Sieger- oder Verlierer-Puppe vergessen',
                'amount' => 5,
                'position' => 7,
                'club_id' => $club->id,
            ],
            [
                'name' => 'Sonstiges',
                'description' => 'Geldspiele etc.',
                'amount' => 1,
                'position' => 8,
                'club_id' => $club->id,
            ],
        ];

        FeeType::factory()
            ->count(count($feeTypes))
            ->sequence(
                ...$feeTypes
            )
            ->create();
    }
}
