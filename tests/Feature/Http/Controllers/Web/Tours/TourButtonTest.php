<?php

namespace Tests\Feature\Http\Controllers\Web\Tours;

use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourButtonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function help_dialog_contains_start_tour_button()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard.projects.show'));

        $response->assertStatus(200);
        $response->assertSee('Start Tour');
    }

    /** @test */
    public function dashboard_page_loads_tour_service()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard.projects.show'));

        $response->assertStatus(200);
        $response->assertSee('window.tourService');
    }

    /** @test */
    public function project_page_loads_tour_service()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $this->actingAs($user);

        $response = $this->get(route('projects.show', [$project]));

        $response->assertStatus(200);
        $response->assertSee('window.tourService');
    }

    /** @test */
    public function tour_service_correctly_identifies_dashboard_tour()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard.projects.show'));

        $response->assertStatus(200);
        $response->assertSee('dashboard', false);
    }

    /** @test */
    public function tour_service_correctly_identifies_project_tour()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $this->actingAs($user);

        $response = $this->get(route('projects.show', [$project]));

        $response->assertStatus(200);
        $response->assertSee('project', false);
    }

    /** @test */
    public function start_tour_button_calls_tour_service()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard.projects.show'));

        $response->assertStatus(200);
        $response->assertSee('window.tourService.startTour', false);
    }
}
