<?php

namespace Database\Factories;

use App\Models\Lab;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabFactory extends Factory
{
    protected $model = Lab::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->sentence(6, true),
            'description' => $this->faker->sentence,
            'default_lab' => false,
            'owner_id'    => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}