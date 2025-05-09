<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Player;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'club_id' => Club::factory(),
            'role_id' => Role::factory(),
            'name' => $this->faker->unique()->firstName(),
            'sex' => $this->faker->randomElement([1, 2]),
            'balance' => 0,
            'initial_balance' => 0,
        ];
    }

    public function withRole(string $roleName): static
    {
        return $this->afterCreating(function (Player $player) use ($roleName) {
            $role = Role::where('name', $roleName)
                ->where('guard_name', $player->guard_name ?? 'player')
                ->first();

            // Falls die Rolle nicht existiert, erstelle sie
            if (! $role) {
                $role = Role::create([
                    'name' => $roleName,
                    'guard_name' => $player->guard_name ?? 'player',
                ]);
            }

            // Weise die Rolle dem Player zu
            $player->assignRole($role);
        });
    }
}
