<?php

namespace Tests\Feature\Actions\Etl;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateFromJsonActionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
