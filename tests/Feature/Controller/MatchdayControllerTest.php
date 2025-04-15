<?php

use App\Enums\PermissionsEnum;
use App\Models\Club;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\actingAs;

test('user can/cannot view matchday', function () {
    $club = Club::factory()->create();
    setPermissionsTeamId($club->id);

    $roleWithPermissions = Role::factory()
        ->for($club, 'club')
        ->create(['name' => 'View', 'guard_name' => 'player']);
    $permission = Permission::findOrCreate(PermissionsEnum::VIEW_MATCHDAY->value, 'player');
    $roleWithPermissions->givePermissionTo($permission);

    $matchday = Matchday::factory()
        ->for($club, 'club')
        ->create();

    Role::factory()
        ->for($club, 'club')
        ->create(['name' => 'No View', 'guard_name' => 'player']);

    $userWithPlayerAndRole = User::factory()->create();
    $userWithPlayer = User::factory()->create();
    $userWithOtherClub = User::factory()->create();
    $userWithoutPlayer = User::factory()->create();

    Player::factory()
        ->for($userWithPlayerAndRole, 'user')
        ->for($club, 'club')
        ->withRole('View')
        ->create();

    Player::factory()
        ->for($userWithPlayer, 'user')
        ->for($club, 'club')
        ->withRole('No View')
        ->create();

    $otherClub = Club::factory()->create();
    Player::factory()
        ->for($userWithOtherClub, 'user')
        ->for($otherClub, 'club')
        ->create();

    actingAs($userWithPlayerAndRole)
        ->withCookie('currentClubId', strval($club->id))
        ->get('/matchdays/'.$matchday->id)
        ->assertStatus(200);
    actingAs($userWithPlayer)
        ->withCookie('currentClubId', strval($club->id))
        ->get('/matchdays/'.$matchday->id)
        ->assertStatus(403);
    actingAs($userWithOtherClub)
        ->withCookie('currentClubId', strval($club->id))
        ->get('/matchdays/'.$matchday->id)
        ->assertStatus(403);
    actingAs($userWithoutPlayer)
        ->withCookie('currentClubId', strval($club->id))
        ->get('/matchdays/'.$matchday->id)
        ->assertStatus(403);
});
