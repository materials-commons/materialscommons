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
    public function project_create_should_create_a_root_directory()
    {
        $user = factory(User::class)->create();
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
        $user = factory(User::class)->create();
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