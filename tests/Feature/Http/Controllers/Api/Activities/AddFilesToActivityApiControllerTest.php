<?php

namespace Tests\Feature\Http\Controllers\Api\Activities;

use App\Models\Activity;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddFilesToActivityApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function files_can_be_added_to_an_activity()
    {
        $this->withoutExceptionHandling();
        $user    = factory(User::class)->create();
        $project = factory(Project::class)->create([
            'owner_id' => $user->id,
        ]);
        $rootDir = factory(File::class)->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);
        $file    = factory(File::class)->create([
            'project_id'   => $project->id,
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
        ]);

        $activity = factory(Activity::class)->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $user->projects()->sync($project);

        $this->actingAs($user, 'api');

        $this->json('post', "/api/activities/{$activity->id}/add-files", [
            'files' => [['id' => $file->id, 'direction' => 'in']],
        ])->assertStatus(200);

        $this->assertDatabaseHas('activity2file',
            ['activity_id' => $activity->id, 'file_id' => $file->id, 'direction' => 'in']);
    }
}
