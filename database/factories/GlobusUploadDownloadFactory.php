<?php

namespace Database\Factories;

use App\Enums\GlobusStatus;
use App\Enums\GlobusType;
use App\Models\GlobusUploadDownload;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GlobusUploadDownloadFactory extends Factory
{
    protected $model = GlobusUploadDownload::class;

    public function definition()
    {
        return [
            'uuid'       => $this->faker->uuid,
            'name'       => "GlobusUpload {$this->faker->randomNumber()}",
            'owner_id'   => function () {
                return User::factory()->create()->id;
            },
            'project_id' => function () {
                return Project::factory()->create()->id;
            },
            'status'     => GlobusStatus::Uploading,
            'type'       => GlobusType::ProjectUpload,
        ];
    }
}