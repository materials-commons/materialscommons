<?php

namespace Tests\Feature\Livewire\Datasets;

use App\Livewire\Datasets\EntitySelection;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EntitySelectionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_entities(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
        ]);

        $entity = Entity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Sample 001',
        ]);

        $this->actingAs($user);

        Livewire::test(EntitySelection::class, [
            'project' => $project,
            'dataset' => $dataset,
            'entitiesCountInDataset' => 0,
        ])
                ->assertSee('Sample 001')
                ->assertSeeHtml('id="' . $entity->uuid . '"')
                ->assertSeeHtml('type="checkbox"')
                ->assertSee('Select All Samples')
                ->assertSee('Unselect All Samples');
    }

    #[Test]
    public function it_toggles_entity_selection(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
        ]);

        $entity = Entity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
        ]);

        $this->actingAs($user);

        Livewire::test(EntitySelection::class, [
            'project' => $project,
            'dataset' => $dataset,
            'entitiesCountInDataset' => 0,
        ])
                ->call('toggleEntity', $entity->id);

        $this->assertDatabaseHas('item2dataset', [
            'dataset_id' => $dataset->id,
            'item_id' => $entity->id,
            'item_type' => Entity::class,
        ]);

        Livewire::test(EntitySelection::class, [
            'project' => $project,
            'dataset' => $dataset->fresh(),
            'entitiesCountInDataset' => 1,
        ])
                ->call('toggleEntity', $entity->id);

        $this->assertDatabaseMissing('item2dataset', [
            'dataset_id' => $dataset->id,
            'item_id' => $entity->id,
            'item_type' => Entity::class,
        ]);
    }

    #[Test]
    public function it_selects_and_unselects_all_entities(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
        ]);

        $entity1 = Entity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
        ]);

        $entity2 = Entity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
        ]);

        $this->actingAs($user);

        Livewire::test(EntitySelection::class, [
            'project' => $project,
            'dataset' => $dataset,
            'entitiesCountInDataset' => 0,
        ])
                ->call('selectAllEntities');

        $this->assertDatabaseHas('item2dataset', [
            'dataset_id' => $dataset->id,
            'item_id' => $entity1->id,
            'item_type' => Entity::class,
        ]);

        $this->assertDatabaseHas('item2dataset', [
            'dataset_id' => $dataset->id,
            'item_id' => $entity2->id,
            'item_type' => Entity::class,
        ]);

        Livewire::test(EntitySelection::class, [
            'project' => $project,
            'dataset' => $dataset->fresh(),
            'entitiesCountInDataset' => 2,
        ])
                ->call('unselectAllEntities');

        $this->assertDatabaseMissing('item2dataset', [
            'dataset_id' => $dataset->id,
            'item_id' => $entity1->id,
            'item_type' => Entity::class,
        ]);

        $this->assertDatabaseMissing('item2dataset', [
            'dataset_id' => $dataset->id,
            'item_id' => $entity2->id,
            'item_type' => Entity::class,
        ]);
    }

    #[Test]
    public function it_does_not_render_api_token(): void
    {
        $user = User::factory()->create([
            'api_token' => 'plain-text-token-that-should-not-render',
        ]);

        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
        ]);

        Entity::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Sample A',
        ]);

        $this->actingAs($user);

        Livewire::test(EntitySelection::class, [
            'project' => $project,
            'dataset' => $dataset,
            'entitiesCountInDataset' => 0,
        ])
                ->assertDontSee('plain-text-token-that-should-not-render')
                ->assertDontSee('api_token');
    }
}
