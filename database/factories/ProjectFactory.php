<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'name'            => "Project {$this->faker->randomNumber()}",
            'description'     => 'Project description',
            'summary'         => 'Project summary',
            'owner_id'        => User::factory(),
            'uuid'            => $this->faker->uuid,
            'default_project' => false,
            'is_active'       => true,
            'file_types'      => [],
            'size'            => 0,
            'file_count'      => 0,
            'directory_count' => 0,
        ];
    }
}