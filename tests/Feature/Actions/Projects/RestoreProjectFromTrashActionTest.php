<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\CreateProjectAction;
use App\Actions\Projects\DeleteProjectAction;
use App\Actions\Projects\RestoreProjectFromTrashAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RestoreProjectFromTrashActionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_clears_the_deleted_at_timestamp_when_restoring_a_project()
    {
        $user = User::factory()->create();

        $project = (new CreateProjectAction())
                       ->execute(['name' => 'Project To Restore'], $user->id)['project'];

        (new DeleteProjectAction())($project);

        $project->refresh();

        $this->assertNotNull($project->deleted_at);

        $restoredProject = (new RestoreProjectFromTrashAction())->execute($project);

        $restoredProject->refresh();

        $this->assertNull($restoredProject->deleted_at);

        $this->assertDatabaseHas('projects', [
            'id'         => $project->id,
            'deleted_at' => null,
        ]);
    }

    #[Test]
    public function it_restores_a_deleted_project_with_a_unique_name_when_the_original_name_is_taken()
    {
        $user = User::factory()->create();

        $deletedProject = (new CreateProjectAction())
                              ->execute(['name' => 'Duplicate Project'], $user->id)['project'];

        (new DeleteProjectAction())($deletedProject);

        $deletedProject->refresh();

        $activeProject = (new CreateProjectAction())
                             ->execute(['name' => 'Duplicate Project'], $user->id)['project'];

        $this->assertNotEquals($deletedProject->id, $activeProject->id);
        $this->assertEquals('Duplicate Project', $activeProject->name);
        $this->assertNotNull($deletedProject->deleted_at);

        $restoredProject = (new RestoreProjectFromTrashAction())->execute($deletedProject);

        $restoredProject->refresh();

        $this->assertNull($restoredProject->deleted_at);
        $this->assertEquals('Duplicate Project-1', $restoredProject->name);
        $this->assertStringEndsWith('-1', $restoredProject->slug);
        $this->assertEquals(
            'Team for Duplicate Project-1',
            $restoredProject->team->fresh()->name
        );

        $this->assertDatabaseHas('projects', [
            'id'         => $restoredProject->id,
            'name'       => 'Duplicate Project-1',
            'owner_id'   => $user->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('teams', [
            'id'   => $restoredProject->team_id,
            'name' => 'Team for Duplicate Project-1',
        ]);

        $this->assertDatabaseHas('projects', [
            'id'         => $activeProject->id,
            'name'       => 'Duplicate Project',
            'owner_id'   => $user->id,
            'deleted_at' => null,
        ]);
    }

    #[Test]
    public function it_tries_incrementing_suffixes_until_the_restored_project_name_is_unique()
    {
        $user = User::factory()->create();

        $deletedProject = (new CreateProjectAction())
                              ->execute(['name' => 'Restored Project'], $user->id)['project'];

        (new DeleteProjectAction())($deletedProject);

        $deletedProject->refresh();

        (new CreateProjectAction())->execute(['name' => 'Restored Project'], $user->id);
        (new CreateProjectAction())->execute(['name' => 'Restored Project-1'], $user->id);
        (new CreateProjectAction())->execute(['name' => 'Restored Project-1-2'], $user->id);

        $restoredProject = (new RestoreProjectFromTrashAction())->execute($deletedProject);

        $restoredProject->refresh();

        $this->assertNull($restoredProject->deleted_at);
        $this->assertEquals('Restored Project-1-2-3', $restoredProject->name);
        $this->assertEquals('restored-projec-1', $restoredProject->slug);
        $this->assertEquals(
            'Team for Restored Project-1-2-3',
            $restoredProject->team->fresh()->name
        );

        $this->assertDatabaseHas('projects', [
            'id'         => $restoredProject->id,
            'name'       => 'Restored Project-1-2-3',
            'slug'       => 'restored-projec-1',
            'owner_id'   => $user->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('teams', [
            'id'   => $restoredProject->team_id,
            'name' => 'Team for Restored Project-1-2-3',
        ]);
    }
}
