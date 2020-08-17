<?php

namespace Tests\Feature\Http\Controllers\Web\Projects;

use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
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
        $project = ProjectFactory::ownedBy($users[0])->create();
        ProjectFactory::addMemberToProject($users[1], $project);

        $userToRemove = $users[1];

        $this->actingAs($users[0]);
        $this->get(route('projects.users.remove', [$project, $userToRemove]))
             ->assertStatus(302);
        $this->assertDatabaseHas('team2admin', ['team_id' => $project->team->id, 'user_id' => $users[0]->id]);
        $this->assertDatabaseMissing('team2member', ['team_id' => $project->team->id, 'user_id' => $users[1]->id]);
    }

    /** @test */
    public function project_owner_cannot_remove_themself()
    {
        $user = factory(User::class)->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $this->actingAs($user);
        $this->get(route('projects.users.remove', [$project, $user]))
             ->assertStatus(400);
        $this->assertDatabaseHas('team2admin', ['team_id' => $project->team->id, 'user_id' => $user->id]);
    }

    /** @test */
    public function project_members_cannot_delete_users()
    {
        $users = factory(User::class, 3)->create();
        $owner = $users[0];
        $member = $users[1];
        $memberToRemove = $users[2];
        $project = ProjectFactory::ownedBy($owner)->create();
        ProjectFactory::addMemberToProject($users[1], $project);
        ProjectFactory::addMemberToProject($users[2], $project);

        $this->actingAs($member);
        $this->get(route('projects.users.remove', [$project, $memberToRemove]))
             ->assertStatus(403);
        $this->assertDatabaseHas('team2member', ['team_id' => $project->team->id, 'user_id' => $memberToRemove->id]);
    }
}
