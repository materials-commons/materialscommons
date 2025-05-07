<?php

namespace Database\Factories;

use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntityFactory extends Factory
{
    protected $model = Entity::class;

    public function definition()
    {
        return [
            'name'        => "Sample {$this->faker->randomNumber()}",
            'description' => "Sample description",
            'summary'     => "Sample summary",
            'uuid'        => $this->faker->uuid,
            'category' => 'experimental',
            'owner_id'    => function () {
                return User::factory()->create()->id;
            },
            'project_id'  => function () {
                return Project::factory()->create()->id;
            },
        ];
    }
}
