<?php

namespace Tests\Feature\Http\Controllers\Api\Datasets;

use Facades\Tests\Factories\DatasetFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexDatasetsApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_retrieve_all_project_datasets()
    {
        $this->withoutExceptionHandling();
        $ds = DatasetFactory::create();
        $this->actingAs($ds->owner, 'api');
        $response = $this->json('get', route('api.projects.datasets.index', [$ds->project_id]));
        $response->assertStatus(200);
    }

    /** @test */
    public function datasets_contain_counts()
    {
        $this->withoutExceptionHandling();
        $ds = DatasetFactory::create();
        $this->actingAs($ds->owner, 'api')
             ->json('get', route('api.projects.datasets.index', [$ds->project_id]))
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
