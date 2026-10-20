<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\CreateProjectAction;
use App\Actions\Projects\UpdateProjectAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateProjectActionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_the_updated_project()
    {
        $user = User::factory()->create();

        $createdProject = (new CreateProjectAction())
                              ->execute(['name' => 'Original Project'], $user->id)['project'];

        $updatedProject = (new UpdateProjectAction())(
            ['name' => 'Updated Project'],
            $createdProject
        );

        $this->assertEquals($createdProject->id, $updatedProject->id);
        $this->assertEquals('Updated Project', $updatedProject->name);

        $this->assertDatabaseHas('projects', [
            'id'   => $createdProject->id,
            'name' => 'Updated Project',
        ]);
    }

    #[Test]
    public function when_the_project_name_is_changed_the_project_slug_and_team_name_are_renamed()
    {
        $user = User::factory()->create();

        $project = (new CreateProjectAction())
                       ->execute(['name' => 'Original Project'], $user->id)['project'];

        $originalSlug = $project->slug;
        $originalTeamName = $project->team->name;

        $updatedProject = (new UpdateProjectAction())(
            ['name' => 'Renamed Project'],
            $project
        );

        $updatedProject->refresh();

        $this->assertEquals('Renamed Project', $updatedProject->name);
        $this->assertEquals('renamed-project', $updatedProject->slug);
        $this->assertEquals("Team for Renamed Project", $updatedProject->team->fresh()->name);

        $this->assertNotEquals($originalSlug, $updatedProject->slug);
        $this->assertNotEquals($originalTeamName, $updatedProject->team->name);

        $this->assertDatabaseHas('projects', [
            'id'   => $project->id,
            'name' => 'Renamed Project',
            'slug' => 'renamed-project',
        ]);

        $this->assertDatabaseHas('teams', [
            'id'   => $project->team_id,
            'name' => 'Team for Renamed Project',
        ]);
    }
}
