<?php

namespace Tests\Feature\Livewire\Mql;

use App\Livewire\Mql\ActivitySelector;
use App\Models\Activity;
use App\Models\Project;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ActivitySelectorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_activity_selector(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $activity = Activity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Sintering',
        ]);

        $this->actingAs($user);

        Livewire::test(ActivitySelector::class, [
            'project' => $project,
            'category' => 'experimental',
            'activities' => collect([$activity]),
        ])
                ->assertSee('In Process:')
                ->assertSee('Sintering')
                ->assertSeeHtml('<select')
                ->assertSeeHtml('wire:model.live="selectedActivity"');
    }

    #[Test]
    public function it_renders_computational_label(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $activity = Activity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Simulation',
        ]);

        $this->actingAs($user);

        Livewire::test(ActivitySelector::class, [
            'project' => $project,
            'category' => 'computational',
            'activities' => collect([$activity]),
        ])
                ->assertSee('In Activity:')
                ->assertSee('Simulation');
    }

    #[Test]
    public function it_dispatches_matching_entities_event_when_activity_selection_is_cleared(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $activity = Activity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Sintering',
        ]);

        $this->actingAs($user);

        Livewire::test(ActivitySelector::class, [
            'project' => $project,
            'category' => 'experimental',
            'activities' => collect([$activity]),
        ])
                ->set('selectedActivity', '')
                ->assertDispatched('mql-activity-entities-matched');
    }

    #[Test]
    public function it_does_not_render_api_token(): void
    {
        $user = User::factory()->create([
            'api_token' => 'plain-text-token-that-should-not-render',
        ]);

        $project = ProjectFactory::ownedBy($user)->create();

        $activity = Activity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Sintering',
        ]);

        $this->actingAs($user);

        Livewire::test(ActivitySelector::class, [
            'project' => $project,
            'category' => 'experimental',
            'activities' => collect([$activity]),
        ])
                ->assertDontSee('plain-text-token-that-should-not-render')
                ->assertDontSee('api_token');
    }
}
