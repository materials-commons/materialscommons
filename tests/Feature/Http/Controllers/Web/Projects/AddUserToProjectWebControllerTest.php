<?php

namespace Tests\Feature\Http\Controllers\Web\Projects;

use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AddUserToProjectWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function owner_of_project_can_add_users_to_project()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $userToAdd = factory(User::class)->create();

        $this->actingAs($user);
        $this->get(route('projects.users.add', [$project, $userToAdd]))
             ->assertStatus(302);
        $this->assertDatabaseHas('team2member', ['team_id' => $project->team->id, 'user_id' => $userToAdd->id]);
    }

    /** @test */
    public function members_of_project_cannot_add_users_to_project()
    {
//        $this->withoutExceptionHandling();
        $users = factory(User::class, 3)->create();
        $owner = $users[0];
        $member = $users[1];
        $userToAdd = $users[2];

        $project = ProjectFactory::ownedBy($owner)->create();
        ProjectFactory::addMemberToProject($member, $project);

        $this->actingAs($member);

        $this->get(route('projects.users.add', [$project, $userToAdd]))
             ->assertStatus(403);
        $this->assertDatabaseMissing('team2member', ['team_id' => $project->team->id, 'user_id' => $userToAdd->id]);
    }

    /** @test */
    public function users_are_only_added_once_to_a_project()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $userToAdd = factory(User::class)->create();

        $this->actingAs($user);
        $this->get(route('projects.users.add', [$project, $userToAdd]))
             ->assertStatus(302);
        $this->get(route('projects.users.add', [$project, $userToAdd]))
             ->assertStatus(302);
        $count = DB::table('team2member')->where('user_id', $userToAdd->id)
                   ->where('team_id', $project->team->id)->count();
        $this->assertEquals(1, $count);
    }
}
