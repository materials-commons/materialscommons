<?php

namespace Tests\Feature\Http\Controllers\Api\Files;

use App\Models\File;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateFileApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_file_when_uploading()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $rootDir = $project->rootDir;

        $this->actingAs($user, 'api');

        Storage::fake('public');
        $file = $this->json('post', '/api/files', [
            'file'         => UploadedFile::fake()->image('random.jpg'),
            'project_id'   => $project->id,
            'directory_id' => $rootDir->id,
            'bad_param'    => 'do_not_include',
        ])
                     ->assertStatus(201)
                     ->assertJsonFragment(['name' => 'random.jpg'])
                     ->decodeResponseJson();

        $fileUuid = $file["data"]["uuid"];
        $topFileDir = $this->topDirFromUuid($fileUuid);
        $fileDir = $this->dirFromUuid($fileUuid);

        $f = File::firstWhere('uuid', $fileUuid);
        // Ensure file counts stored on project were updated
        $project->refresh();
        $this->assertEquals($f->size, $project->size);
        $this->assertEquals(1, $project->file_count);
        $this->assertTrue(isset($project->file_types['Image']));
        $this->assertEquals(1, $project->file_types['Image']);

        Storage::disk('mcfs')->assertExists("{$fileDir}/{$fileUuid}");
        Storage::deleteDirectory($topFileDir);
    }

    private function topDirFromUuid($uuid)
    {
        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        return "{$entry1[0]}{$entry1[1]}";
    }

    private function dirFromUuid($uuid)
    {
        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        return "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
    }
}
