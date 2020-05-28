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
        $this->actingAs($ds->owner, 'api');
        $response = $this->json('get', route('api.projects.datasets.show', [$ds->project_id, $ds->id]));
        $response->assertStatus(200);
    }
}
