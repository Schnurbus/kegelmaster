<?php

namespace Database\Seeders;

use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Gast',
                'is_base_fee_active' => false,
            ],
            [
                'name' => 'Mitglied',
                'is_base_fee_active' => true,
            ],
            [
                'name' => 'Kassenwart',
                'is_base_fee_active' => true,
            ],
        ];

        BouncerFacade::scope()->to(1);
        Role::factory()
            ->count(count($roles))
            ->sequence(
                ...$roles
            )
            ->create();
    }
}
