<?php

namespace Database\Factories;

use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatasetFactory extends Factory
{
    protected $model = Dataset::class;

    public function definition()
    {
        return [
            'name'           => "Dataset {$this->faker->randomNumber()}",
            'description'    => "Dataset description",
            'summary'        => "Dataset summary",
            'owner_id'       => function () {
                return User::factory()->create()->id;
            },
            'uuid'           => $this->faker->uuid,
            'project_id'     => function () {
                return Project::factory()->create()->id;
            },
            'license'        => 'Public Domain Dedication and License (PDDL)',
            'license_link'   => 'http://opendatacommons.org/licenses/pddl/summary/',
            'doi'            => $this->faker->url,
            'authors'        => $this->faker->name,
            'published_at'   => $this->faker->dateTime,
            'file_selection' => [
                'include_files' => [],
                'exclude_files' => [],
                'include_dirs'  => [],
                'exclude_dirs'  => [],
            ],
        ];
    }
}