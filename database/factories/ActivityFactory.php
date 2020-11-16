<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        return [
            'name'        => "Process {$this->faker->randomNumber()}",
            'description' => "Process Description",
            'summary'     => "Process summary",
            'uuid'        => $this->faker->uuid,
            'owner_id'    => function () {
                return User::factory()->create()->id;
            },
            'project_id'  => function () {
                return Project::factory()->create()->id;
            },
        ];
    }
}
