<?php

namespace Tests\Feature\Security;

use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Factories\ProjectFactory;
use Tests\TestCase;

class ProjectAuthorizationByIdRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_show_experiment_from_project_they_cannot_access_by_id_route(): void
    {
        $attacker = User::factory()->create();
        $victim = User::factory()->create();

        $victimProject = Project::factory()->create([
            'owner_id' => $victim->id,
            'is_public' => false,
        ]);

        $experiment = Experiment::factory()->create([
            'project_id' => $victimProject->id,
            'owner_id' => $victim->id,
        ]);

        $response = $this->actingAs($attacker, 'api')
                         ->getJson("/api/experiments/{$experiment->id}");

        $response->assertNotFound();
    }

    public function test_user_cannot_update_experiment_from_project_they_cannot_access_by_id_route(): void
    {
        $attacker = User::factory()->create();
        $victim = User::factory()->create();

        $victimProject = Project::factory()->create([
            'owner_id' => $victim->id,
            'is_public' => false,
        ]);

        $experiment = Experiment::factory()->create([
            'project_id' => $victimProject->id,
            'owner_id' => $victim->id,
            'name' => 'Original Experiment Name',
        ]);

        $response = $this->actingAs($attacker, 'api')
                         ->putJson("/api/experiments/{$experiment->id}", [
                             'name' => 'Modified By Attacker',
                         ]);

        $response->assertNotFound();

        $this->assertDatabaseHas('experiments', [
            'id' => $experiment->id,
            'name' => 'Original Experiment Name',
        ]);
    }

    public function test_user_cannot_update_file_from_project_they_cannot_access_by_id_route(): void
    {
        $attacker = User::factory()->create();
        $victim = User::factory()->create();

        $victimProject = Project::factory()->create([
            'owner_id' => $victim->id,
            'is_public' => false,
        ]);

        $file = File::factory()->create([
            'project_id' => $victimProject->id,
            'owner_id' => $victim->id,
            'name' => 'victim-file.txt',
        ]);

        $response = $this->actingAs($attacker, 'api')
                         ->putJson("/api/files/{$file->id}", [
                             'name' => 'attacker-renamed-file.txt',
                         ]);

        $response->assertNotFound();

        $this->assertDatabaseHas('files', [
            'id' => $file->id,
            'name' => 'victim-file.txt',
        ]);
    }

    public function test_user_cannot_publish_dataset_from_project_they_cannot_access_by_id_route(): void
    {
        $attacker = User::factory()->create();
        $victim = User::factory()->create();

        $victimProject = Project::factory()->create([
            'owner_id' => $victim->id,
            'is_public' => false,
        ]);

        $dataset = Dataset::factory()->create([
            'project_id' => $victimProject->id,
            'owner_id' => $victim->id,
            'published_at' => null,
        ]);

        $response = $this->actingAs($attacker, 'api')
                         ->putJson("/api/datasets/{$dataset->id}/publish");

        $response->assertNotFound();

        $this->assertDatabaseHas('datasets', [
            'id' => $dataset->id,
            'published_at' => null,
        ]);
    }

    public function test_project_member_can_access_resource_by_id_route(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = ProjectFactory::ownedBy($owner)->create();

//        $project = Project::factory()->create([
//            'owner_id' => $owner->id,
//            'is_public' => false,
//        ]);

        $experiment = Experiment::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $owner->id,
        ]);

        $this->addUserToProjectAsMember($member, $project);

        $response = $this->actingAs($member, 'api')
                         ->getJson("/api/experiments/{$experiment->id}");

        $response->assertSuccessful();
    }

    private function addUserToProjectAsMember(User $user, Project $project): void
    {
        \DB::table('team2member')->insert([
            'team_id' => $project->team_id,
            'user_id' => $user->id,
        ]);
    }
}
