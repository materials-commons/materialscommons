<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\SearchAcrossProjectsAction;
use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Scout\EngineManager;
use Mockery;
use Tests\TestCase;

class SearchAcrossProjectsActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Configure Scout to use the database engine for testing
        config(['scout.driver' => 'database']);
    }

    /** @test */
    public function search_should_find_matching_entity_across_projects()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $project1 = Project::factory()->create(['owner_id' => $user->id]);
        $entity1 = Entity::factory()->create([
            'owner_id'    => $user->id,
            'project_id'  => $project1->id,
            'name'        => 'Heat Treatment at 400c',
            'description' => 'Conduct HT at 400c for 2 hours',
        ]);

        $project2 = Project::factory()->create(['owner_id' => $user->id]);
        $entity2 = Entity::factory()->create([
            'owner_id'    => $user->id,
            'project_id'  => $project2->id,
            'name'        => 'Heat Treatment at 500c',
            'description' => 'Conduct HT at 500c for 3 hours',
        ]);

        // Mock the Scout engine to return both entities
        $engineMock = Mockery::mock('Laravel\Scout\Engines\Engine');

        // For Project model search
        $engineMock->shouldReceive('search')->andReturn(['results' => []]);
        $engineMock->shouldReceive('map')->andReturn(collect([]));
        $engineMock->shouldReceive('lazyMap')->andReturn(collect([]));
        $engineMock->shouldReceive('getTotalCount')->andReturn(0);

        // For Entity model search
        $engineMock->shouldReceive('search')->andReturn(['results' => [$entity1->toSearchableArray(), $entity2->toSearchableArray()]]);
        $engineMock->shouldReceive('map')->andReturn(collect([$entity1, $entity2]));
        $engineMock->shouldReceive('lazyMap')->andReturn(collect([$entity1, $entity2]));
        $engineMock->shouldReceive('getTotalCount')->andReturn(2);

        $this->app->instance(EngineManager::class, Mockery::mock(EngineManager::class, function ($mock) use ($engineMock) {
            $mock->shouldReceive('engine')->andReturn($engineMock);
        }));

        $searchAcrossProjectsAction = new SearchAcrossProjectsAction();
        $results = $searchAcrossProjectsAction('Heat Treatment', $user);

        // Should find both entities
        $this->assertEquals(2, $results->count());

        // Check that both entities are in the results
        $foundEntity1 = false;
        $foundEntity2 = false;

        foreach ($results as $result) {
            if ($result->title === $entity1->name) {
                $foundEntity1 = true;
                $this->assertEquals(route('projects.entities.show', [$project1, $entity1]), $result->url);
            }

            if ($result->title === $entity2->name) {
                $foundEntity2 = true;
                $this->assertEquals(route('projects.entities.show', [$project2, $entity2]), $result->url);
            }
        }

        $this->assertTrue($foundEntity1, 'Entity 1 not found in search results');
        $this->assertTrue($foundEntity2, 'Entity 2 not found in search results');
    }

    /** @test */
    public function search_should_not_find_entities_in_other_users_projects()
    {
        $this->withoutExceptionHandling();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $project1 = Project::factory()->create(['owner_id' => $user1->id]);
        $entity1 = Entity::factory()->create([
            'owner_id'    => $user1->id,
            'project_id'  => $project1->id,
            'name'        => 'Heat Treatment at 400c',
            'description' => 'Conduct HT at 400c for 2 hours',
        ]);

        $project2 = Project::factory()->create(['owner_id' => $user2->id]);
        $entity2 = Entity::factory()->create([
            'owner_id'    => $user2->id,
            'project_id'  => $project2->id,
            'name'        => 'Heat Treatment at 500c',
            'description' => 'Conduct HT at 500c for 3 hours',
        ]);

        // Mock the Scout engine to return only entity1 for user1
        $engineMock = Mockery::mock('Laravel\Scout\Engines\Engine');

        // For Project model search
        $engineMock->shouldReceive('search')->andReturn(['results' => []]);
        $engineMock->shouldReceive('map')->andReturn(collect([]));
        $engineMock->shouldReceive('lazyMap')->andReturn(collect([]));
        $engineMock->shouldReceive('getTotalCount')->andReturn(0);

        // For Entity model search
        $engineMock->shouldReceive('search')->andReturn(['results' => [$entity1->toSearchableArray()]]);
        $engineMock->shouldReceive('map')->andReturn(collect([$entity1]));
        $engineMock->shouldReceive('lazyMap')->andReturn(collect([$entity1]));
        $engineMock->shouldReceive('getTotalCount')->andReturn(1);

        $this->app->instance(EngineManager::class, Mockery::mock(EngineManager::class, function ($mock) use ($engineMock) {
            $mock->shouldReceive('engine')->andReturn($engineMock);
        }));

        $searchAcrossProjectsAction = new SearchAcrossProjectsAction();
        $results = $searchAcrossProjectsAction('Heat Treatment', $user1);

        // Should only find entities in user1's projects
        $this->assertEquals(1, $results->count());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
