<?php

namespace Database\Factories;

use App\Enums\ExperimentStatus;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperimentFactory extends Factory
{
    protected $model = Experiment::class;

    public function definition()
    {
        return [
            'name'        => "Experiment {$this->faker->randomNumber()}",
            'description' => "Experiment description",
            'summary'     => "Experiment summary",
            'loading'     => false,
            'status'      => ExperimentStatus::InProgress,
            'owner_id'    => function () {
                return User::factory()->create()->id;
            },
            'uuid'        => $this->faker->uuid,
            'project_id'  => function () {
                return Project::factory()->create()->id;
            },
        ];
    }
}