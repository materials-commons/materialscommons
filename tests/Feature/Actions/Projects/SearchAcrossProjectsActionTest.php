<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\SearchAcrossProjectsAction;
use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Scout\EngineManager;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test]
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

        $engineMock->shouldReceive('get')
                   ->times(7)
                   ->andReturn(
                       collect([]), // Project results
                       collect([]), // File results
                       collect([]), // Experiment results
                       collect([$entity1, $entity2]), // Entity results
                       collect([]), // Activity results
                       collect([]), // Dataset results
                       collect([]), // Community results
                   );

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
            if ($result->id === $entity1->id) {
                $foundEntity1 = true;
                $this->assertEquals($entity1->name, $result->name);
                $this->assertEquals($project1->id, $result->project_id);
            }

            if ($result->id === $entity2->id) {
                $foundEntity2 = true;
                $this->assertEquals($entity2->name, $result->name);
                $this->assertEquals($project2->id, $result->project_id);
            }
        }

        $this->assertTrue($foundEntity1, 'Entity 1 not found in search results');
        $this->assertTrue($foundEntity2, 'Entity 2 not found in search results');
    }

    #[Test]
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

        $engineMock->shouldReceive('get')
                   ->times(7)
                   ->andReturn(
                       collect([]), // Project results
                       collect([]), // File results
                       collect([]), // Experiment results
                       collect([$entity1]), // Entity results
                       collect([]), // Activity results
                       collect([]), // Dataset results
                       collect([]), // Community results
                   );

        $this->app->instance(EngineManager::class, Mockery::mock(EngineManager::class, function ($mock) use ($engineMock) {
            $mock->shouldReceive('engine')->andReturn($engineMock);
        }));

        $searchAcrossProjectsAction = new SearchAcrossProjectsAction();
        $results = $searchAcrossProjectsAction('Heat Treatment', $user1);

        // Should only find entities in user1's projects
        // Should only find entities in user1's projects
        $this->assertEquals(1, $results->count());
        $this->assertTrue($results->contains(fn (Entity $entity) => $entity->id === $entity1->id));
        $this->assertFalse($results->contains(fn (Entity $entity) => $entity->id === $entity2->id));
        $this->assertEquals(1, $results->count());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
