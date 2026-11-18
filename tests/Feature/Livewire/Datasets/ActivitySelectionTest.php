<?php

namespace Tests\Feature\Livewire\Datasets;

use App\Livewire\Datasets\ActivitySelection;
use App\Models\Activity;
use App\Models\Dataset;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ActivitySelectionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_dataset_activities(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
        ]);

        $activity = Activity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Polishing',
        ]);

        $this->actingAs($user);

        Livewire::test(ActivitySelection::class, [
            'project' => $project,
            'dataset' => $dataset,
        ])
                ->assertSee('Polishing')
                ->assertSeeHtml('id="' . $activity->uuid . '"')
                ->assertSeeHtml('type="checkbox"');
    }

    #[Test]
    public function it_toggles_activity_selection(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
        ]);

        $activity = Activity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
        ]);

        $this->actingAs($user);

        Livewire::test(ActivitySelection::class, [
            'project' => $project,
            'dataset' => $dataset,
        ])
                ->call('toggleActivity', $activity->id);

        $this->assertDatabaseHas('dataset2activity', [
            'activity_id' => $activity->id,
            'dataset_id' => $dataset->id,
        ]);

        Livewire::test(ActivitySelection::class, [
            'project' => $project,
            'dataset' => $dataset->fresh(),
        ])
                ->call('toggleActivity', $activity->id);

        $this->assertDatabaseMissing('dataset2activity', [
            'activity_id' => $activity->id,
            'dataset_id' => $dataset->id,
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
            'owner_id' => $user->id,
        ]);

        Activity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Activity A',
        ]);

        $this->actingAs($user);

        Livewire::test(ActivitySelection::class, [
            'project' => $project,
            'dataset' => $dataset,
        ])
                ->assertDontSee('plain-text-token-that-should-not-render')
                ->assertDontSee('api_token');
    }
}
