<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Club>
 */
class ClubFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $user = User::factory()->create();
        $user = User::find(1);

        $balance = $this->faker->randomFloat(2, 100, 1000);

        return [
            'name' => $this->faker->name(),
            'initial_balance' => $balance,
            'balance' => $balance,
            'base_fee' => $this->faker->randomNumber() + 1,
            'user_id' => $user->id,
        ];
    }
}
