<?php

namespace Tests\Feature\Http\Controllers\Web\Projects;

use App\Models\Project;
use App\Models\User;
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
        $project = factory(Project::class)->create([
            'owner_id' => $user->id,
        ]);

        $user->projects()->attach($project);
        $userToAdd = factory(User::class)->create();

        $this->actingAs($user);
        $this->get(route('projects.users.add', [$project, $userToAdd]))
             ->assertStatus(302);
        $this->assertDatabaseHas('project2user', ['project_id' => $project->id, 'user_id' => $userToAdd->id]);
    }

    /** @test */
    public function members_of_project_cannot_add_users_to_project()
    {
//        $this->withoutExceptionHandling();
        $users = factory(User::class, 3)->create();
        $owner = $users[0];
        $member = $users[1];
        $userToAdd = $users[2];

        $project = factory(Project::class)->create([
            'owner_id' => $owner->id,
        ]);

        $project->users()->attach($owner);
        $project->users()->attach($member);

        $this->actingAs($member);

        $this->get(route('projects.users.add', [$project, $userToAdd]))
             ->assertStatus(403);
        $this->assertDatabaseMissing('project2user', ['project_id' => $project->id, 'user_id' => $userToAdd->id]);
    }

    /** @test */
    public function users_are_only_added_once_to_a_project()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create([
            'owner_id' => $user->id,
        ]);

        $user->projects()->attach($project);
        $userToAdd = factory(User::class)->create();

        $this->actingAs($user);
        $this->get(route('projects.users.add', [$project, $userToAdd]))
             ->assertStatus(302);
        $this->get(route('projects.users.add', [$project, $userToAdd]))
             ->assertStatus(302);
        $count = DB::table('project2user')->where('user_id', $userToAdd->id)
                   ->where('project_id', $project->id)->count();
        $this->assertEquals(1, $count);
    }
}
