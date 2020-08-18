<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\DeleteProjectAction;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteProjectActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_deleting_a_project_the_team_is_also_deleted()
    {
        $project = ProjectFactory::create();
        $deleteAction = new DeleteProjectAction();
        $team = $project->team;
        $deleteAction($project);
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }

    /** @test */
    public function when_deleting_a_project_the_files_are_also_deleted()
    {
        $project = ProjectFactory::create();
        $rootDir = $project->rootDir;
        $deleteAction = new DeleteProjectAction();
        $deleteAction($project);
        $this->assertDatabaseMissing('files', ['id' => $rootDir->id]);
    }
}
