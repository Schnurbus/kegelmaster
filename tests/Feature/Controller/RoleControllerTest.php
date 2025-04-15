<?php

use App\Enums\PermissionsEnum\PermissionsEnum;
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
    parent::setUp();
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

test('user cannot list roles', function () {
    $club = Club::factory()->create();
    $role = Role::factory()->create(['club_id' => $club->id, 'name' => 'Test Role']);
    $user = User::factory()->create();
    $player = Player::factory()->create([
        'club_id' => $club->id,
        'user_id' => $user->id,
        'role_id' => $role->id,
    ]);

    $response = actingAs($user)->post('/set-current-club', ['club_id' => $club->id]);
    $response->assertStatus(302);

    actingAs($user)->get('/role')->assertStatus(403);
});

test('user can list roles', function () {
    $club = Club::factory()->create();
    $role = Role::factory()->create(['club_id' => $club->id, 'name' => 'Test Role']);

    Permission::create(['name' => PermissionsEnum::LISTROLES->value]);
    $role->givePermissionTo(PermissionsEnum::LISTROLES->value);
    $player = Player::factory()->create([
        'club_id' => $club->id,
        'user_id' => $this->user->id,
        'role_id' => $role->id,
    ]);

    $response = actingAs($this->user)->post('/set-current-club', ['club_id' => $club->id]);
    $response->assertStatus(302);

    expect($this->user->getAllPermissions()->count())->toBe(1);

    actingAs($this->user)->get('/role')->assertStatus(200);
});

test('user with active player having list.Player permission can list players', function () {
    $club = createClubWithRolesAndPermissions(['list.Player']);
    $result = createUserWithPlayerInClub($club);
    $user = $result['user'];

    setPermissionsTeamId($club->id);

    $response = $this->actingAs($user)
        ->withSession(['current_club_id' => $club->id])
        ->get('/players');

    $response->assertStatus(200);

    // Zweiter Club ohne diese Berechtigung
    $clubWithoutPerm = createClubWithRolesAndPermissions([]);
    $resultWithoutPerm = createUserWithPlayerInClub($clubWithoutPerm);
    $playerWithoutPerm = $resultWithoutPerm['player'];

    // Derselbe User, aber anderer aktiver Player ohne Berechtigung
    $response = $this->actingAs($user)
        ->withSession(['current_club_id' => $club->id])
        ->get('/players');

    $response->assertStatus(403); // Zugriff verweigert
});
