<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Pascal',
                'email' => 'pascal@schnurbus.de',
                'password' => '$2y$12$NNNgBCOMll9XCwAvdHkjeuo4i7QV8AQBxOXmx3lRIl3NO7aDEv8vy',
            ],
            [
                'name' => 'Nicole',
                'email' => 'nicole@schnurbus.de',
                'password' => Hash::make('Test1234'),
            ],
            [
                'name' => 'Thilo',
                'email' => 'thilo@example.com',
                'password' => Hash::make('Test1234'),
            ],
            [
                'name' => 'Micha',
                'email' => 'm.ludwig182@icloud.com',
                'password' => '$2y$12$XmuanFJOCRTMNVVqnD6zSeQlWEPtcT/caW3Ba1/vzrgvaA7r1E9pO',
            ],
            [
                'name' => 'Bine',
                'email' => 'bine@schnurbus.de',
                'password' => Hash::make('Test1234'),
            ],
            [
                'name' => 'Mathias',
                'email' => 'mathias@schnurbus.de',
                'password' => Hash::make('Test1234'),
            ],
        ];

        User::factory()
            ->count(count($users))
            ->sequence(
                ...$users
            )
            ->create();
    }
}
