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
}
