<?php

namespace Tests\Feature\Api;

use App\Models\TourState;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourStateControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_empty_state_for_new_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->getJson('/api/tour-states');

        $response->assertStatus(200)
            ->assertJson([
                'completedSteps' => [],
                'completedTours' => []
            ]);
    }

    /** @test */
    public function it_returns_tour_state_for_user()
    {
        $user = User::factory()->create();
        $tourState = TourState::create([
            'user_id' => $user->id,
            'tour_name' => 'dashboard',
            'completed_steps' => ['step1', 'step2'],
            'is_completed' => false
        ]);

        $this->actingAs($user, 'api');

        $response = $this->getJson('/api/tour-states');

        $response->assertStatus(200)
            ->assertJson([
                'completedSteps' => [
                    'dashboard' => ['step1', 'step2']
                ],
                'completedTours' => [
                    'dashboard' => false
                ]
            ]);
    }

    /** @test */
    public function it_returns_specific_tour_state()
    {
        $user = User::factory()->create();
        $tourState = TourState::create([
            'user_id' => $user->id,
            'tour_name' => 'dashboard',
            'completed_steps' => ['step1', 'step2'],
            'is_completed' => false
        ]);

        $this->actingAs($user, 'api');

        $response = $this->getJson('/api/tour-states/dashboard');

        $response->assertStatus(200)
            ->assertJson([
                'completedSteps' => ['step1', 'step2'],
                'isCompleted' => false
            ]);
    }

    /** @test */
    public function it_creates_tour_state()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/tour-states', [
            'tourName' => 'dashboard',
            'completedSteps' => ['step1', 'step2'],
            'isCompleted' => false
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tour_states', [
            'user_id' => $user->id,
            'tour_name' => 'dashboard',
            'is_completed' => false
        ]);
    }

    /** @test */
    public function it_completes_step()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/tour-states/complete-step', [
            'tourName' => 'dashboard',
            'stepId' => 'step1'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tour_states', [
            'user_id' => $user->id,
            'tour_name' => 'dashboard'
        ]);

        $tourState = TourState::where('user_id', $user->id)
            ->where('tour_name', 'dashboard')
            ->first();

        $this->assertTrue(in_array('step1', $tourState->completed_steps));
    }

    /** @test */
    public function it_completes_tour()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/tour-states/complete-tour', [
            'tourName' => 'dashboard'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tour_states', [
            'user_id' => $user->id,
            'tour_name' => 'dashboard',
            'is_completed' => true
        ]);
    }

    /** @test */
    public function it_resets_tour()
    {
        $user = User::factory()->create();
        $tourState = TourState::create([
            'user_id' => $user->id,
            'tour_name' => 'dashboard',
            'completed_steps' => ['step1', 'step2'],
            'is_completed' => true
        ]);

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/tour-states/reset', [
            'tourName' => 'dashboard'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseMissing('tour_states', [
            'id' => $tourState->id
        ]);
    }

    /** @test */
    public function it_resets_all_tours()
    {
        $user = User::factory()->create();
        $tourState1 = TourState::create([
            'user_id' => $user->id,
            'tour_name' => 'dashboard',
            'completed_steps' => ['step1', 'step2'],
            'is_completed' => true
        ]);
        $tourState2 = TourState::create([
            'user_id' => $user->id,
            'tour_name' => 'project',
            'completed_steps' => ['step1'],
            'is_completed' => false
        ]);

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/tour-states/reset-all');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseMissing('tour_states', [
            'id' => $tourState1->id
        ]);
        $this->assertDatabaseMissing('tour_states', [
            'id' => $tourState2->id
        ]);
    }
}
