<?php

namespace Tests\Feature\Http\Controllers\Api\Etl;

use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetFileByPathInProjectApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_find_files_with_project_rather_than_slash_in_path()
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
        $user->projects()->sync($project);
        $file = factory(File::class)->create([
            'project_id'   => $project->id,
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
            'name'         => 'test.txt',
        ]);

        $this->actingAs($user, 'api');
        $this->json('post', '/api/etl/getFileByPathInProject', [
            'project_id' => $project->id,
            'path'       => 'p1/test.txt',
        ])
             ->assertStatus(200)
             ->assertJsonFragment(['name' => 'test.txt', 'id' => $file->id]);
    }

    /** @test */
    public function it_should_given_error_when_file_does_not_exist()
    {
//        $this->withoutExceptionHandling();
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
        $user->projects()->sync($project);
        $file = factory(File::class)->create([
            'project_id'   => $project->id,
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
            'name'         => 'test.txt',
        ]);

        $this->actingAs($user, 'api');
        $this->json('post', '/api/etl/getFileByPathInProject', [
            'project_id' => $project->id,
            'path'       => 'p1/test-does-not-exist.txt',
        ])
             ->assertStatus(404);
    }
}
