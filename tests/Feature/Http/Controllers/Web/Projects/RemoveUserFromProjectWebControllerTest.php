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
        $this->post(route('projects.users.remove', [$project, $userToRemove]))
             ->assertStatus(200);
        $this->assertDatabaseHas('project2user', ['project_id' => $project->id, 'user_id' => $users[0]->id]);
    }

    /** @test */
    public function project_members_cannot_delete_users()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function project_owner_cannot_remove_themself()
    {
        $this->markTestIncomplete('Not implemented');
    }
}
