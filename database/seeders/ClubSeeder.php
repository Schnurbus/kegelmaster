<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clubs = [
            [
                'name' => 'Krumme Kugel',
                'base_fee' => 10,
                'initial_balance' => 0,
                'balance' => 0,
                'user_id' => 1,
            ],
            [
                'name' => 'Die Legionäre',
                'base_fee' => 10,
                'initial_balance' => 0,
                'balance' => 0,
                'user_id' => 1,
            ],
            [
                'name' => 'Hateful 8',
                'base_fee' => 10,
                'initial_balance' => 0,
                'balance' => 0,
                'user_id' => 4,
            ],
        ];

        Club::factory()
            ->count(count($clubs))
            ->sequence(
                ...$clubs
            )
            ->create();
    }
}
