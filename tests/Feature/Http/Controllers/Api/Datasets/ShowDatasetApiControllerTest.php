<?php

namespace Tests\Feature\Http\Controllers\Api\Datasets;

use Facades\Tests\Factories\DatasetFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowDatasetApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_retrieve_a_project_dataset()
    {
        $this->withoutExceptionHandling();
        $ds = DatasetFactory::create();
        $ds2 = DatasetFactory::inProject($ds->project)->create();
        $this->actingAs($ds->owner, 'api');
        $response = $this->json('get', route('api.projects.datasets.show', [$ds->project_id, $ds2->id]));
        $response->assertJsonFragment(['id' => $ds2->id]);
        $response->assertStatus(200);
    }

    /** @test */
    public function dataset_contains_counts()
    {
        $this->withoutExceptionHandling();
        $ds = DatasetFactory::create();
        $this->actingAs($ds->owner, 'api')
             ->json('get', route('api.projects.datasets.show', [$ds->project_id, $ds->id]))
             ->assertJsonFragment([
                 'files_count'       => 0,
                 'activities_count'  => 0,
                 'entities_count'    => 0,
                 'experiments_count' => 0,
                 'comments_count'    => 0,
                 'workflows_count'   => 0,
             ]);
    }
}
