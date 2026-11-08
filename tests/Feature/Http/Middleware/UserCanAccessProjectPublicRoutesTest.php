<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\UserCanAccessProject;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserCanAccessProjectPublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function published_api_route_with_dataset_parameter_does_not_require_private_project_access(): void
    {
        Route::get('/api/published/datasets/{dataset}/middleware-test', function () {
            return response()->json(['ok' => true]);
        })->middleware(UserCanAccessProject::class);

        $owner = User::factory()->create();

        $project = Project::factory()->create([
            'owner_id' => $owner->id,
            'is_public' => false,
        ]);

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $owner->id,
            'published_at' => now(),
        ]);

        $response = $this->getJson("/api/published/datasets/{$dataset->id}/middleware-test");

        $response->assertOk();
        $response->assertJson(['ok' => true]);
    }

    #[Test]
    public function public_web_route_with_dataset_parameter_does_not_require_private_project_access(): void
    {
        Route::get('/public/datasets/{dataset}/middleware-test', function () {
            return response()->json(['ok' => true]);
        })->middleware(UserCanAccessProject::class);

        $owner = User::factory()->create();

        $project = Project::factory()->create([
            'owner_id' => $owner->id,
            'is_public' => false,
        ]);

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $owner->id,
            'published_at' => now(),
        ]);

        $response = $this->getJson("/public/datasets/{$dataset->id}/middleware-test");

        $response->assertOk();
        $response->assertJson(['ok' => true]);
    }

    #[Test]
    public function private_by_id_route_still_requires_project_access(): void
    {
        Route::get('/api/datasets/{dataset}/middleware-test', function () {
            return response()->json(['ok' => true]);
        })->middleware(UserCanAccessProject::class);

        $owner = User::factory()->create();
        $unauthorizedUser = User::factory()->create();

        $project = Project::factory()->create([
            'owner_id' => $owner->id,
            'is_public' => false,
        ]);

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $owner->id,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($unauthorizedUser, 'api')
                         ->getJson("/api/datasets/{$dataset->id}/middleware-test");

        $response->assertNotFound();
    }
}
