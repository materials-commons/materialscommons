<?php

namespace Database\Factories;

use App\Models\GlobusDrop;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GlobusDropFactory extends Factory
{
    protected $model = GlobusDrop::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid'               => $this->faker->uuid(),
            'name'               => 'Drop '.$this->faker->word(),
            'message'            => $this->faker->sentence(),
            'globus_accounts'    => [$this->faker->safeEmail()],
            'globus_endpoint_id' => 'test-endpoint-id',
            'globus_path'        => '/__globus_uploads/drops/test/drop/',
            'globus_url'         => 'https://app.globus.org/file-manager?destination_id=test-endpoint-id&destination_path=/__globus_uploads/drops/test/drop/',
            'status'             => 'available',
            'project_id'         => Project::factory(),
            'owner_id'           => User::factory(),
            'directory_id'       => null,
        ];
    }
}
