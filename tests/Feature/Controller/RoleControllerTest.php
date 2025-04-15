<?php

use App\Enums\PermissionsEnum;
use App\Models\Club;
use App\Models\Player;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function () {
    // parent::setUp();
    $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    $this->user = User::factory()->create();

});

test('owner can render roles page', function () {
    $user = User::factory()->create();
    $club = Club::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    $response = $this->actingAs($user)->get('/role');

    $response->assertStatus(200);
});

test('owner can create role', function () {
    $user = User::factory()->create();
    $club = Club::factory()->create(['user_id' => $user->id]);

    $noPermissions = [
        'club' => [],
        'player' => [],
        'role' => [],
        'matchday' => [],
        'feeType' => [],
        'competitionType' => [],
        'transaction' => [],
    ];

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    $roleData = [
        'club_id' => $club->id,
        'name' => 'Test Role',
        'is_base_fee_active' => false,
        'permissions' => $noPermissions,
    ];

    // Act
    $response = $this->post('/role', $roleData);

    // Assert
    $response->assertRedirect('/role');
    $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
});

test('unauthorized user cancot create role', function () {
    $user = User::factory()->create();
    $club = Club::factory()->create(['user_id' => $user->id]);

    $noPermissions = [
        'club' => [],
        'player' => [],
        'role' => [],
        'matchday' => [],
        'feeType' => [],
        'competitionType' => [],
        'transaction' => [],
    ];

    $roleData = [
        'club_id' => $club->id,
        'name' => 'Test Role',
        'is_base_fee_active' => false,
        'permissions' => $noPermissions,
    ];

    // Act
    $response = $this->post('/role', $roleData);

    // Assert
    $response->assertRedirect('/login');
});

test('owner can delete role', function () {
    $user = User::factory()->create();
    $club = Club::factory()->create(['user_id' => $user->id]);
    $role = Role::factory()->create(['club_id' => $club->id, 'name' => 'Test Role']);

    assertDatabaseHas('roles', ['name' => 'Test Role']);

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    assertAuthenticated();

    // Act
    $response = delete('/role/'.$role->id);

    // Assert
    $response->assertRedirect('/role');
    assertDatabaseMissing('roles', ['name' => 'Test Role']);
});

test('owner can update role', function () {
    $user = User::factory()->create();
    $club = Club::factory()->create(['user_id' => $user->id]);
    $role = Role::factory()->create(['club_id' => $club->id, 'name' => 'Test Role']);

    assertDatabaseHas('roles', ['name' => 'Test Role']);

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    assertAuthenticated();

    $updatedData = [
        // 'id' => $role->id,
        'name' => 'Test Role 2',
        'is_base_fee_active' => false,
        'permissions' => [
            'club' => [
                'view' => false,
                'update' => false,
            ],
        ],
    ];

    $response = patch('/role/'.$role->id, $updatedData);

    $response->assertRedirect('/role');
    assertDatabaseMissing('roles', ['name' => 'Test Role']);
    assertDatabaseHas('roles', ['name' => 'Test Role 2']);
});

test('user list roles', function () {
    $club = Club::factory()->create();

    $user = User::factory()->create();

    $userWithoutPlayer = User::factory()->create();

    actingAs($userWithoutPlayer)
        ->withCookie('currentClubId', strval($club->id))
        ->get('/role')
        ->assertStatus(403);
});

test('user can/cannot view role', function () {
    $club = Club::factory()->create();
    setPermissionsTeamId($club->id);

    $roleWithPermissions = Role::factory()
        ->for($club, 'club')
        ->create(['name' => 'View Role', 'guard_name' => 'player']);
    $permission = Permission::findOrCreate(PermissionsEnum::VIEW_ROLE->value, 'player');
    $roleWithPermissions->givePermissionTo($permission);

    Role::factory()
        ->for($club, 'club')
        ->create(['name' => 'No View Role', 'guard_name' => 'player']);

    $userWithPlayerAndRole = User::factory()->create();
    $userWithPlayer = User::factory()->create();
    $userWithOtherClub = User::factory()->create();
    $userWithoutPlayer = User::factory()->create();

    $playerWithRole = Player::factory()
        ->for($userWithPlayerAndRole, 'user')
        ->for($club, 'club')
        ->withRole('View Role')
        ->create();

    $playerWithoutRole = Player::factory()
        ->for($userWithPlayer, 'user')
        ->for($club, 'club')
        ->withRole('No List Role')
        ->create();

    $otherClub = Club::factory()->create();
    $playerWithOtherClub = Player::factory()
        ->for($userWithOtherClub, 'user')
        ->for($otherClub, 'club')
        ->create();

    actingAs($userWithPlayerAndRole)
        ->withCookie('currentClubId', strval($club->id))
        ->get('/role/'.$roleWithPermissions->id)
        ->assertStatus(200);
    actingAs($userWithPlayer)
        ->withCookie('currentClubId', strval($club->id))
        ->get('/role/'.$roleWithPermissions->id)
        ->assertStatus(403);
    actingAs($userWithOtherClub)
        ->withCookie('currentClubId', strval($club->id))
        ->get('/role/'.$roleWithPermissions->id)
        ->assertStatus(403);
    actingAs($userWithoutPlayer)
        ->withCookie('currentClubId', strval($club->id))
        ->get('/role/'.$roleWithPermissions->id)
        ->assertStatus(403);
});
