<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\FeeType;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Role;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected array $entityMap = [
        'player' => Player::class,
        'role' => Role::class,
        'matchday' => Matchday::class,
        'feeType' => FeeType::class,
        'transaction' => Transaction::class,
    ];

    protected $permissions = [
        'Gast' => [
            'player' => [
                'list' => true,
                'show' => false,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'role' => [
                'list' => true,
                'show' => false,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'matchday' => [
                'list' => true,
                'show' => false,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'feeType' => [
                'list' => true,
                'show' => false,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'transaction' => [
                'list' => true,
                'show' => false,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
        ],
        'Mitglied' => [
            'player' => [
                'list' => true,
                'show' => true,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'role' => [
                'list' => true,
                'show' => true,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'matchday' => [
                'list' => true,
                'show' => true,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'feeType' => [
                'list' => true,
                'show' => true,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'transaction' => [
                'list' => true,
                'show' => true,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
        ],
        'Kassenwart' => [
            'player' => [
                'list' => true,
                'show' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
            'role' => [
                'list' => true,
                'show' => true,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'matchday' => [
                'list' => true,
                'show' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
            'feeType' => [
                'list' => true,
                'show' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
            'transaction' => [
                'list' => true,
                'show' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'club_id' => Club::factory(),
        ];
    }
}
