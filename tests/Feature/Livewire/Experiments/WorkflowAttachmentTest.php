<?php

namespace Tests\Feature\Livewire\Experiments;

use App\Livewire\Experiments\WorkflowAttachment;
use App\Models\Experiment;
use App\Models\User;
use App\Models\Workflow;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WorkflowAttachmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_workflows_for_experiment_attachment(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $experiment = Experiment::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 'Study A',
        ]);

        $workflow = Workflow::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 'Workflow A',
        ]);

        $this->actingAs($user);

        Livewire::test(WorkflowAttachment::class, [
            'project'    => $project,
            'experiment' => $experiment,
        ])
                ->assertSee('Attach Workflows To Study Study A')
                ->assertSee('Workflow A')
                ->assertSeeHtml('id="'.$workflow->uuid.'"')
                ->assertSeeHtml('type="checkbox"')
                ->assertSee('Done');
    }

    #[Test]
    public function it_toggles_workflow_attachment(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $experiment = Experiment::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $workflow = Workflow::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user);

        Livewire::test(WorkflowAttachment::class, [
            'project'    => $project,
            'experiment' => $experiment,
        ])
                ->call('toggleWorkflow', $workflow->id);

        $this->assertDatabaseHas('item2workflow', [
            'item_type'   => Experiment::class,
            'item_id'     => $experiment->id,
            'workflow_id' => $workflow->id,
        ]);

        Livewire::test(WorkflowAttachment::class, [
            'project'    => $project,
            'experiment' => $experiment->fresh(),
        ])
                ->call('toggleWorkflow', $workflow->id);

        $this->assertDatabaseMissing('item2workflow', [
            'item_type'   => Experiment::class,
            'item_id'     => $experiment->id,
            'workflow_id' => $workflow->id,
        ]);
    }

    #[Test]
    public function it_does_not_render_api_token(): void
    {
        $user = User::factory()->create([
            'api_token' => 'plain-text-token-that-should-not-render',
        ]);

        $project = ProjectFactory::ownedBy($user)->create();

        $experiment = Experiment::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 'Study A',
        ]);

        Workflow::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 'Workflow A',
        ]);

        $this->actingAs($user);

        Livewire::test(WorkflowAttachment::class, [
            'project'    => $project,
            'experiment' => $experiment,
        ])
                ->assertDontSee('plain-text-token-that-should-not-render')
                ->assertDontSee('api_token');
    }
}
