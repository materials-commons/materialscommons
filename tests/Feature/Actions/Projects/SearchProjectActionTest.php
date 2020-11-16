<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\SearchProjectAction;
use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchProjectActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function search_should_find_matching_entity_by_name()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $project = Project::factory()->create(['owner_id' => $user->id]);
        $entity = Entity::factory()->create([
            'owner_id'    => $user->id,
            'project_id'  => $project->id,
            'name'        => 'Heat Treatment at 400c',
            'description' => 'Conduct HT at 400c for 2 hours',
        ]);

        $searchProjectAction = new SearchProjectAction();
        $results = $searchProjectAction('Heat Treatment', $project->id);
        $this->assertEquals(1, $results->count());
        $e = $results->get(0);
        $this->assertEquals($entity->name, $e->title);
        $this->assertEquals(route('projects.entities.show', [$project, $entity]), $e->url);
    }

    /** @test */
    public function search_should_not_find_matching_entity_in_different_project()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $project = Project::factory()->create(['owner_id' => $user->id]);
        Entity::factory()->create([
            'owner_id'    => $user->id,
            'project_id'  => $project->id,
            'name'        => 'Heat Treatment at 400c',
            'description' => 'Conduct HT at 400c for 2 hours',
        ]);
        $project2 = Project::factory()->create(['owner_id' => $user->id]);
        $searchProjectAction = new SearchProjectAction();
        $results = $searchProjectAction('Heat Treatment', $project2->id);
        $this->assertEquals(0, $results->count());
    }
}
