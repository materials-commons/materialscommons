<?php

namespace Tests\Unit\Models;

use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_url_file()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a project
        $project = Project::factory()->create([
            'owner_id' => $user->id
        ]);

        // Create a root directory
        $rootDir = File::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => '/',
            'path' => '/',
            'mime_type' => 'directory'
        ]);

        // Create a URL file
        $urlFile = File::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Example URL',
            'path' => '/Example URL',
            'mime_type' => 'url',
            'url' => 'https://example.com',
            'directory_id' => $rootDir->id
        ]);

        // Assert the URL file was created correctly
        $this->assertDatabaseHas('files', [
            'id' => $urlFile->id,
            'name' => 'Example URL',
            'mime_type' => 'url',
            'url' => 'https://example.com'
        ]);

        // Assert the URL property is accessible
        $this->assertEquals('https://example.com', $urlFile->url);
    }

    /** @test */
    public function it_can_update_a_url_file()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a project
        $project = Project::factory()->create([
            'owner_id' => $user->id
        ]);

        // Create a root directory
        $rootDir = File::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => '/',
            'path' => '/',
            'mime_type' => 'directory'
        ]);

        // Create a URL file
        $urlFile = File::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Example URL',
            'path' => '/Example URL',
            'mime_type' => 'url',
            'url' => 'https://example.com',
            'directory_id' => $rootDir->id
        ]);

        // Update the URL
        $urlFile->url = 'https://updated-example.com';
        $urlFile->save();

        // Assert the URL was updated
        $this->assertDatabaseHas('files', [
            'id' => $urlFile->id,
            'url' => 'https://updated-example.com'
        ]);

        // Refresh the model and check the URL
        $urlFile->refresh();
        $this->assertEquals('https://updated-example.com', $urlFile->url);
    }

    /** @test */
    public function it_can_identify_url_files()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a project
        $project = Project::factory()->create([
            'owner_id' => $user->id
        ]);

        // Create a root directory
        $rootDir = File::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => '/',
            'path' => '/',
            'mime_type' => 'directory'
        ]);

        // Create a URL file
        $urlFile = File::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Example URL',
            'path' => '/Example URL',
            'mime_type' => 'url',
            'url' => 'https://example.com',
            'directory_id' => $rootDir->id
        ]);

        // Create a regular file
        $regularFile = File::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $user->id,
            'name' => 'Regular File',
            'path' => '/Regular File',
            'mime_type' => 'text/plain',
            'directory_id' => $rootDir->id
        ]);

        // Assert URL file is identified correctly
        $this->assertEquals('url', $urlFile->mime_type);
        $this->assertNotEquals('url', $regularFile->mime_type);

        // Assert directory is not a URL
        $this->assertNotEquals('url', $rootDir->mime_type);
    }
}
