<?php

namespace Tests\Feature\Http\Controllers\Web\Projects;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemoveUserFromProjectWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function project_owner_can_delete_users()
    {
        $this->withoutExceptionHandling();
        $users = factory(User::class, 2)->create();
        $project = factory(Project::class)->create([
            'owner_id' => $users[0]->id,
        ]);

        $project->users()->attach($users);
        $userToRemove = $users[1];

        $this->actingAs($users[0]);
        $this->delete(route('projects.users.remove', [$project, $userToRemove]))
             ->assertStatus(200);
        $this->assertDatabaseHas('project2user', ['project_id' => $project->id, 'user_id' => $users[0]->id]);
        $this->assertDatabaseMissing('project2user', ['project_id' => $project->id, 'user_id' => $users[1]->id]);
    }

    /** @test */
    public function project_owner_cannot_remove_themself()
    {
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create([
            'owner_id' => $user->id,
        ]);

        $user->projects()->attach($project);
        $this->actingAs($user);
        $this->delete(route('projects.users.remove', [$project, $user]))
             ->assertStatus(400);
        $this->assertDatabaseHas('project2user', ['project_id' => $project->id, 'user_id' => $user->id]);
    }

    /** @test */
    public function project_members_cannot_delete_users()
    {
        $users = factory(User::class, 3)->create();
        $owner = $users[0];
        $member = $users[1];
        $memberToRemove = $users[2];
        $project = factory(Project::class)->create([
            'owner_id' => $owner->id,
        ]);

        $project->users()->attach($users);

        $this->actingAs($member);
        $this->delete(route('projects.users.remove', [$project, $memberToRemove]))
             ->assertStatus(403);
        $this->assertDatabaseHas('project2user', ['project_id' => $project->id, 'user_id' => $memberToRemove->id]);
    }
}
