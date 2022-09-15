<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Services\AuthService;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_give_access_to_user_with_is_admin_set_even_when_not_in_project()
    {
        $adminUser = User::factory()->create();
        $adminUser->update(['is_admin' => true]);

        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        // Sanity check, make sure user who owns project has access.
        $this->assertTrue(AuthService::userCanAccessProject($user, $project));

        // Now test that an admin user who is not in project can access it.
        $this->assertTrue(AuthService::userCanAccessProject($adminUser, $project));
    }

    /** @test */
    public function it_should_give_access_to_project_member()
    {
        $project = ProjectFactory::create();
        $member = User::factory()->create();
        ProjectFactory::addMemberToProject($member, $project);

        // Check if member we added to project has access.
        $this->assertTrue(AuthService::userCanAccessProject($member, $project));
    }

    /** @test */
    public function it_should_give_access_to_project_admin()
    {
        $project = ProjectFactory::create();
        $admin = User::factory()->create();
        ProjectFactory::addAdminToProject($admin, $project);

        // Check if admin we added to project has access.
        $this->assertTrue(AuthService::userCanAccessProject($admin, $project));
    }

    /** @test */
    public function it_should_deny_access_to_users_not_in_project()
    {
        $project = ProjectFactory::create();
        $randomUser = User::factory()->create();

        // Users who do not have is_admin flag set, and not are in project should not have access.
        $this->assertFalse(AuthService::userCanAccessProject($randomUser, $project));
    }
}
