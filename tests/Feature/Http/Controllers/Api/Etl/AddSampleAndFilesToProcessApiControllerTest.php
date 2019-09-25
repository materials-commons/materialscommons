<?php

namespace Tests\Feature\Http\Controllers\Api\Etl;

use App\Models\Activity;
use App\Models\Entity;
use App\Models\EntityState;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddSampleAndFilesToProcessApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testExample()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\Models\User')->create();
        $project = factory('App\Models\Project')->create([
            'name'     => 'p1',
            'owner_id' => $user->id,
        ]);
        $rootDir = factory(File::class)->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $file = factory(File::class)->create([
            'project_id'   => $project->id,
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
            'name'         => 'test.txt',
        ]);

        $e = factory(Entity::class)->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 's1',
        ]);

        $entityState = factory(EntityState::class)->create([
            'entity_id' => $e->id,
            'owner_id'  => $user->id,
        ]);

        $activity = factory(Activity::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $this->actingAs($user, 'api');

        $entity = $this->json('post', '/api/etl/addSampleAndFilesToProcess', [
            'project_id'      => $project->id,
            'experiment_id'   => 1,
            'process_id'      => $activity->id,
            'sample_id'       => $e->id,
            'property_set_id' => $entityState->id,
            'transform'       => true,
            'files_by_name'   => [['path' => 'p1/test.txt', 'direction' => 'in']],
        ])
                       ->assertStatus(200)
                       ->assertJsonFragment(['name' => 's1'])
                       ->decodeResponseJson();

        $this->assertDatabaseHas('activity2entity', ['activity_id' => $activity->id, 'entity_id' => $e->id]);
        $this->assertDatabaseHas('activity2file', ['activity_id' => $activity->id, 'file_id' => $file->id]);
    }
}
