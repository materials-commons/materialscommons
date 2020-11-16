<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\CreateProjectAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProjectActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function created_project_is_setup_correctly()
    {
        $user = User::factory()->create();
        $createAction = new CreateProjectAction();
        $rv = $createAction->execute(['name' => 'proj1'], $user->id);
        $project = $rv['project'];
        $this->assertTrue($rv['created']);
        $project->refresh();
        $this->assertEquals(0, $project->size);
        $this->assertEquals(0, $project->file_count);
        $this->assertEquals(1, $project->directory_count);
        $this->assertIsArray($project->file_types);
        $this->assertEquals(0, sizeof($project->file_types));
    }

    /** @test */
    public function project_create_should_create_a_root_directory()
    {
        $user = User::factory()->create();
        $createAction = new CreateProjectAction();
        $rv = $createAction->execute(['name' => 'proj1'], $user->id);
        $project = $rv['project'];
        $this->assertTrue($rv['created']);
        $this->assertDatabaseHas('files', [
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
        ]);
    }

    /** @test */
    public function project_create_should_create_a_team()
    {
        $user = User::factory()->create();
        $createAction = new CreateProjectAction();
        $rv = $createAction->execute(['name' => 'proj1'], $user->id);
        $project = $rv['project'];
        $this->assertTrue($rv['created']);
        $this->assertNotNull($project->team_id);
        $this->assertDatabaseHas('teams', ['id' => $project->team_id]);
        $this->assertDatabaseHas('team2admin', [
            'team_id' => $project->team_id,
            'user_id' => $user->id,
        ]);
    }
}
