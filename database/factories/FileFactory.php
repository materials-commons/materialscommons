<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        $fileName = $this->faker->word.'.'.$this->faker->fileExtension;

        return [
            'name'        => $fileName,
            'description' => $this->faker->sentence,
            'summary'     => 'File summary',
            'uuid'        => $this->faker->uuid,
            'checksum'    => $this->faker->md5,
            'mime_type'   => $this->faker->mimeType,
            'owner_id'    => function () {
                return User::factory()->create()->id;
            },
            'project_id'  => function () {
                return Project::factory()->create()->id;
            },
            'size'        => $this->faker->randomNumber(),
        ];
    }
}