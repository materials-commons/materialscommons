<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\CreateProjectAction;
use App\Actions\Projects\DeleteProjectAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateProjectActionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function user_can_create_a_project_with_the_same_name_as_a_project_marked_for_deletion()
    {
        $user = User::factory()->create();
        $createAction = new CreateProjectAction();
        $deleteAction = new DeleteProjectAction();

        $originalResult = $createAction->execute(['name' => 'proj1'], $user->id);
        $originalProject = $originalResult['project'];
        $originalTeam = $originalProject->team;

        $this->assertTrue($originalResult['created']);

        $deleteAction($originalProject);

        $newResult = $createAction->execute(['name' => 'proj1'], $user->id);
        $newProject = $newResult['project'];

        $this->assertTrue($newResult['created']);
        $this->assertNotNull($newProject);
        $this->assertNotEquals($originalProject->id, $newProject->id);
        $this->assertEquals('proj1', $newProject->name);
        $this->assertEquals("Team for proj1", $newProject->team->name);

        $this->assertDatabaseHas('projects', [
            'id'         => $newProject->id,
            'name'       => 'proj1',
            'owner_id'   => $user->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('teams', [
            'id'       => $newProject->team_id,
            'name'     => 'Team for proj1',
            'owner_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('teams', [
            'id'   => $originalTeam->id,
            'name' => 'Team for proj1',
        ]);
    }
}
