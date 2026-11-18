<?php

namespace Tests\Feature\Livewire\Datasets;

use App\Livewire\Datasets\WorkflowSelection;
use App\Models\Dataset;
use App\Models\User;
use App\Models\Workflow;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WorkflowSelectionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_workflows(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $workflow = Workflow::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 'Heat Treatment Workflow',
            'summary'    => 'Workflow summary',
        ]);

        $this->actingAs($user);

        Livewire::test(WorkflowSelection::class, [
            'project'   => $project,
            'dataset'   => $dataset,
            'workflows' => collect([$workflow]),
        ])
                ->assertSee('Heat Treatment Workflow')
                ->assertSee('Workflow summary')
                ->assertSeeHtml('id="'.$workflow->uuid.'"')
                ->assertSeeHtml('type="checkbox"');
    }

    #[Test]
    public function it_toggles_workflow_selection(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $workflow = Workflow::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user);

        Livewire::test(WorkflowSelection::class, [
            'project'   => $project,
            'dataset'   => $dataset,
            'workflows' => collect([$workflow]),
        ])
                ->call('toggleWorkflow', $workflow->id);

        $this->assertDatabaseHas('item2workflow', [
            'item_type'   => Dataset::class,
            'item_id'     => $dataset->id,
            'workflow_id' => $workflow->id,
        ]);

        Livewire::test(WorkflowSelection::class, [
            'project'   => $project,
            'dataset'   => $dataset->fresh(),
            'workflows' => collect([$workflow]),
        ])
                ->call('toggleWorkflow', $workflow->id);

        $this->assertDatabaseMissing('item2workflow', [
            'item_type'   => Dataset::class,
            'item_id'     => $dataset->id,
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

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $workflow = Workflow::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 'Workflow A',
        ]);

        $this->actingAs($user);

        Livewire::test(WorkflowSelection::class, [
            'project'   => $project,
            'dataset'   => $dataset,
            'workflows' => collect([$workflow]),
        ])
                ->assertDontSee('plain-text-token-that-should-not-render')
                ->assertDontSee('api_token');
    }
}
