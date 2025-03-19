<?php

namespace Database\Factories;

use App\Models\FeeType;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Role;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Silber\Bouncer\BouncerFacade;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
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

    private $permissions = [
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
            //
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Role $role) {
            BouncerFacade::scope()->to(1);
            foreach ($this->permissions[$role->title] as $entity => $actions) {
                if (! isset($this->entityMap[$entity])) {
                    continue;
                }

                $modelClass = $this->entityMap[$entity];

                foreach ($actions as $action => $allowed) {
                    if ($allowed) {
                        BouncerFacade::allow($role)->to($action, $modelClass);
                    }
                }
            }
        });
    }
}
