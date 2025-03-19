<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Silber\Bouncer\BouncerFacade;

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
        return [
            //
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Club $club) {
            BouncerFacade::scope()->to($club->id);

            $user = User::findOrFail($club->user_id);
            $owner = BouncerFacade::role()->firstOrCreate([
                'name' => 'owner',
            ]);

            BouncerFacade::allow($owner)->everything();
            $user->assign($owner);
        });
    }
}
