<?php

namespace Tests\Feature\Livewire\Communities;

use App\Livewire\Communities\DatasetSelection;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DatasetSelectionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_community_datasets(): void
    {
        $user = User::factory()->create();

        $community = Community::factory()->create([
            'owner_id' => $user->id,
            'name' => 'Community A',
        ]);

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Dataset A',
            'summary' => 'Dataset summary',
            'published_at' => now(),
        ]);

        $this->actingAs($user);

        Livewire::test(DatasetSelection::class, [
            'community' => $community,
            'datasets' => collect([$dataset]),
        ])
                ->assertSee('Community: Community A')
                ->assertSee('Dataset A')
                ->assertSee('Dataset summary')
                ->assertSeeHtml('id="' . $dataset->uuid . '"')
                ->assertSeeHtml('type="checkbox"')
                ->assertSee('Done');
    }

    #[Test]
    public function it_toggles_dataset_in_community(): void
    {
        $user = User::factory()->create();

        $community = Community::factory()->create([
            'owner_id' => $user->id,
        ]);

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'published_at' => now(),
        ]);

        $this->actingAs($user);

        Livewire::test(DatasetSelection::class, [
            'community' => $community,
            'datasets' => collect([$dataset]),
        ])
                ->call('toggleDataset', $dataset->id);

        $this->assertDatabaseHas('dataset2community', [
            'community_id' => $community->id,
            'dataset_id' => $dataset->id,
        ]);

        Livewire::test(DatasetSelection::class, [
            'community' => $community->fresh(),
            'datasets' => collect([$dataset]),
        ])
                ->call('toggleDataset', $dataset->id);

        $this->assertDatabaseMissing('dataset2community', [
            'community_id' => $community->id,
            'dataset_id' => $dataset->id,
        ]);
    }

    #[Test]
    public function it_does_not_render_api_token(): void
    {
        $user = User::factory()->create([
            'api_token' => 'plain-text-token-that-should-not-render',
        ]);

        $community = Community::factory()->create([
            'owner_id' => $user->id,
            'name' => 'Community A',
        ]);

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Dataset A',
            'published_at' => now(),
        ]);

        $this->actingAs($user);

        Livewire::test(DatasetSelection::class, [
            'community' => $community,
            'datasets' => collect([$dataset]),
        ])
                ->assertDontSee('plain-text-token-that-should-not-render')
                ->assertDontSee('api_token');
    }
}
