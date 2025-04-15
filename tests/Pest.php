<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Models\Club;
use App\Models\Player;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * Club mit definierten Rollen und Berechtigungen einrichten
 */
function createClubWithRolesAndPermissions(array $permissions = []): Club
{
    $club = Club::factory()->create();

    // Player-Rolle mit Berechtigungen erstellen
    $playerRole = Role::create(['name' => 'player', 'guard_name' => 'web', 'club_id' => $club->id]);

    // Berechtigungen erstellen und zuweisen
    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'web', 'club_id' => $club->id])
            ->assignRole($playerRole);
    }

    return $club;
}

/**
 * User mit Player in einem Club erstellen
 */
function createUserWithPlayerInClub(Club $club, string $roleName = 'player'): array
{
    $user = User::factory()->create();
    $role = Role::where('name', $roleName)->first();
    $player = Player::factory()->create([
        'user_id' => $user->id,
        'club_id' => $club->id,
        'role_id' => $role->id,
    ]);

    return [
        'user' => $user,
        'player' => $player,
    ];
}
