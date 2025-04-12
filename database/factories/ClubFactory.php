<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Silber\Bouncer\BouncerFacade;

/**
 * @extends Factory<Club>
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
        return [
            'name' => $this->faker->name(),
            'initial_balance' => 0,
            'balance' => 0,
            'base_fee' => $this->faker->randomNumber(1) + 1,
            'user_id' => User::factory(),
        ];
    }

    public function withRole(): self
    {
        return $this->afterCreating(function (Club $club) {
            BouncerFacade::scope()->to($club->id);
            Role::factory()->create(['club_id' => $club->id]);
        });
    }
}
