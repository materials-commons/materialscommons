<?php

namespace Tests\Feature\Http\Controllers\Api\Directories;

use App\Actions\Directories\CopyDirectoryAction;
use App\Http\Controllers\Api\Directories\CopyDirectoryApiController;
use App\Http\Requests\Directories\MoveDirectoryRequest;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CopyDirectoryApiControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function copy_directory_denies_access_when_user_cannot_access_destination_project()
    {
        $user = User::factory()->create();

        $sourceProject = ProjectFactory::ownedBy($user)->create();
        $sourceDir = ProjectFactory::createDirectory($sourceProject, $sourceProject->rootDir, 'source');

        $destinationOwner = User::factory()->create();
        $destinationProject = ProjectFactory::ownedBy($destinationOwner)->create();
        $destinationDir = ProjectFactory::createDirectory($destinationProject, $destinationProject->rootDir, 'destination');

        $this->actingAs($user);

        $request = MoveDirectoryRequest::create('/api/directories/'.$sourceDir->id.'/copy', 'POST', [
            'to_directory_id' => $destinationDir->id,
        ]);

        $copyDirectoryAction = Mockery::mock(CopyDirectoryAction::class);
        $copyDirectoryAction->shouldNotReceive('execute');

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(0);

        app(CopyDirectoryApiController::class)($request, $copyDirectoryAction, $sourceDir->id);
    }

    #[Test]
    public function copy_directory_denies_access_when_user_cannot_access_source_project()
    {
        $user = User::factory()->create();

        $sourceOwner = User::factory()->create();
        $sourceProject = ProjectFactory::ownedBy($sourceOwner)->create();
        $sourceDir = ProjectFactory::createDirectory($sourceProject, $sourceProject->rootDir, 'source');

        $destinationProject = ProjectFactory::ownedBy($user)->create();
        $destinationDir = ProjectFactory::createDirectory($destinationProject, $destinationProject->rootDir, 'destination');

        $this->actingAs($user);

        $request = MoveDirectoryRequest::create('/api/directories/'.$sourceDir->id.'/copy', 'POST', [
            'to_directory_id' => $destinationDir->id,
        ]);

        $copyDirectoryAction = Mockery::mock(CopyDirectoryAction::class);
        $copyDirectoryAction->shouldNotReceive('execute');

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(0);

        app(CopyDirectoryApiController::class)($request, $copyDirectoryAction, $sourceDir->id);
    }

    #[Test]
    public function copy_directory_allows_access_when_user_can_access_source_and_destination_projects()
    {
        $user = User::factory()->create();

        $sourceProject = ProjectFactory::ownedBy($user)->create();
        $sourceDir = ProjectFactory::createDirectory($sourceProject, $sourceProject->rootDir, 'source');

        $destinationOwner = User::factory()->create();
        $destinationProject = ProjectFactory::ownedBy($destinationOwner)->create();
        ProjectFactory::addMemberToProject($user, $destinationProject);
        $destinationDir = ProjectFactory::createDirectory($destinationProject, $destinationProject->rootDir, 'destination');

        $this->actingAs($user);

        $request = MoveDirectoryRequest::create('/api/directories/'.$sourceDir->id.'/copy', 'POST', [
            'to_directory_id' => $destinationDir->id,
        ]);

        $copyDirectoryAction = Mockery::mock(CopyDirectoryAction::class);
        $copyDirectoryAction
            ->shouldReceive('execute')
            ->once()
            ->withArgs(function ($fromDir, $toDir, $actingUser) use ($sourceDir, $destinationDir, $user) {
                return $fromDir->is($sourceDir)
                    && $toDir->is($destinationDir)
                    && $actingUser->is($user);
            })
            ->andReturnTrue();

        $response = app(CopyDirectoryApiController::class)($request, $copyDirectoryAction, $sourceDir->id);

        $this->assertSame($destinationDir->id, $response->resource->id);
    }
}
