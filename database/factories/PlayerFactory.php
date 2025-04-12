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
}
