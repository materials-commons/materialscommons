<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowFactory extends Factory
{
    protected $model = Workflow::class;

    public function definition()
    {
        $workflow = <<<WORKFLOW
Sand Sample(right)->Heat Treat?(yes, right)->HT 400c/2h(right)->HT 400c/2h(right)->HT 600c/1h(right)->HT 200c/3h(right)->sem
Heat Treat?(no)->sem
WORKFLOW;

        return [
            'name'        => $this->faker->sentence(6, true),
            'description' => 'Workflow description',
            'summary'     => 'Workflow summary',
            'uuid'        => $this->faker->uuid,
            'owner_id'    => User::factory(),
            'project_id'  => Project::factory(),
            'workflow'    => $workflow,
        ];
    }
}