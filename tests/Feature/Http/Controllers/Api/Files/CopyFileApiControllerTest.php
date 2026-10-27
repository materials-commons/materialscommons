<?php

namespace Tests\Feature\Http\Controllers\Api\Files;

use App\Actions\Files\CopyFileAction;
use App\Http\Controllers\Api\Files\CopyFileApiController;
use App\Http\Requests\Files\MoveFileRequest;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CopyFileApiControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function copy_file_denies_access_when_user_cannot_access_destination_project()
    {
        $user = User::factory()->create();

        $sourceProject = ProjectFactory::ownedBy($user)->create();
        $sourceFile = ProjectFactory::createFakeFile($sourceProject, $sourceProject->rootDir, 'source.txt');

        $destinationOwner = User::factory()->create();
        $destinationProject = ProjectFactory::ownedBy($destinationOwner)->create();
        $destinationDir = ProjectFactory::createDirectory($destinationProject, $destinationProject->rootDir, 'destination');

        $this->actingAs($user);

        $request = MoveFileRequest::create('/api/files/'.$sourceFile->id.'/copy', 'POST', [
            'directory_id' => $destinationDir->id,
            'project_id'   => $destinationProject->id,
        ]);

        $copyFileAction = Mockery::mock(CopyFileAction::class);
        $copyFileAction->shouldNotReceive('execute');

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(0);

        app(CopyFileApiController::class)($request, $copyFileAction, $sourceFile->id);
    }

    #[Test]
    public function copy_file_denies_access_when_user_cannot_access_source_project()
    {
        $user = User::factory()->create();

        $sourceOwner = User::factory()->create();
        $sourceProject = ProjectFactory::ownedBy($sourceOwner)->create();
        $sourceFile = ProjectFactory::createFakeFile($sourceProject, $sourceProject->rootDir, 'source.txt');

        $destinationProject = ProjectFactory::ownedBy($user)->create();
        $destinationDir = ProjectFactory::createDirectory($destinationProject, $destinationProject->rootDir, 'destination');

        $this->actingAs($user);

        $request = MoveFileRequest::create('/api/files/'.$sourceFile->id.'/copy', 'POST', [
            'directory_id' => $destinationDir->id,
            'project_id'   => $destinationProject->id,
        ]);

        $copyFileAction = Mockery::mock(CopyFileAction::class);
        $copyFileAction->shouldNotReceive('execute');

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(0);

        app(CopyFileApiController::class)($request, $copyFileAction, $sourceFile->id);
    }

    #[Test]
    public function copy_file_allows_access_when_user_can_access_source_and_destination_projects()
    {
        $user = User::factory()->create();

        $sourceProject = ProjectFactory::ownedBy($user)->create();
        $sourceFile = ProjectFactory::createFakeFile($sourceProject, $sourceProject->rootDir, 'source.txt');

        $destinationOwner = User::factory()->create();
        $destinationProject = ProjectFactory::ownedBy($destinationOwner)->create();
        ProjectFactory::addMemberToProject($user, $destinationProject);
        $destinationDir = ProjectFactory::createDirectory($destinationProject, $destinationProject->rootDir, 'destination');

        $this->actingAs($user);

        $request = MoveFileRequest::create('/api/files/'.$sourceFile->id.'/copy', 'POST', [
            'directory_id' => $destinationDir->id,
            'project_id'   => $destinationProject->id,
        ]);

        $copyFileAction = Mockery::mock(CopyFileAction::class);
        $copyFileAction
            ->shouldReceive('execute')
            ->once()
            ->withArgs(function ($file, $toDir, $actingUser) use ($sourceFile, $destinationDir, $user) {
                return $file->is($sourceFile)
                    && $toDir->is($destinationDir)
                    && $actingUser->is($user);
            })
            ->andReturnTrue();

        $response = app(CopyFileApiController::class)($request, $copyFileAction, $sourceFile->id);

        $this->assertSame($sourceFile->id, $response->resource->id);
    }
}
