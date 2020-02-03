<?php

namespace Tests\Feature\Actions\Datasets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateDatasetInGlobusActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function published_datasets_creates_files()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
