<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\DeleteProjectAction;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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
        $project->update(['deleted_at' => Carbon::now()->subDays(config('trash.expires_in_days') + 1)]);
        $this->runExpireCommandsForProjects();
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }

    /** @test */
    public function when_deleting_a_project_the_files_are_also_deleted()
    {
        $project = ProjectFactory::create();
        $rootDir = $project->rootDir;
        $deleteAction = new DeleteProjectAction();
        $deleteAction($project);
        $days = Carbon::now()->subDays(config('trash.expires_in_days') + 1);
        $project->update(['deleted_at' => $days]);
        $this->runExpireCommandsForProjects();
        $this->assertDatabaseMissing('files', ['id' => $rootDir->id]);
    }

    private function runExpireCommandsForProjects()
    {
        $this->artisan("mc:delete-expired-trashcan-projects");
        $this->artisan("mc:delete-expired-trashcan-directories");
        $this->artisan("mc:delete-expired-trashcan-files");
        $this->artisan("mc:delete-expired-trashcan-projects");
        $this->artisan("mc:delete-expired-trashcan-projects");
    }
}
