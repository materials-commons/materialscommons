<?php

namespace Database\Factories;

use App\Models\Entity;
use App\Models\EntityState;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntityStateFactory extends Factory
{
    protected $model = EntityState::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->sentence(6, true),
            'description' => $this->faker->sentence,
            'uuid'        => $this->faker->uuid,
            'owner_id'    => function () {
                return User::factory()->create()->id;
            },
            'entity_id'   => function () {
                return Entity::factory()->create()->id;
            },
        ];
    }
}