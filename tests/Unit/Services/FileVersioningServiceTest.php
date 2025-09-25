<?php

namespace Tests\Unit\Services;

use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Services\FileServices\FileVersioningService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileVersioningServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_active_toggles_current_correctly()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $dir = File::factory()->create(['project_id' => $project->id, 'mime_type' => 'directory', 'path' => '/']);
        $f1 = File::factory()->create(['project_id' => $project->id, 'directory_id' => $dir->id, 'name' => 'a.txt', 'current' => false, 'dataset_id' => null]);
        $f2 = File::factory()->create(['project_id' => $project->id, 'directory_id' => $dir->id, 'name' => 'a.txt', 'current' => true, 'dataset_id' => null]);

        $service = app(FileVersioningService::class);
        $service->setActive($f1);

        $this->assertTrue($f1->fresh()->current);
        $this->assertFalse($f2->fresh()->current);
    }

    public function test_idempotent_when_already_current()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $dir = File::factory()->create(['project_id' => $project->id, 'mime_type' => 'directory', 'path' => '/']);
        $f = File::factory()->create(['project_id' => $project->id, 'directory_id' => $dir->id, 'name' => 'b.txt', 'current' => true, 'dataset_id' => null]);

        $service = app(FileVersioningService::class);
        $fresh = $service->setActive($f);

        $this->assertTrue($fresh->current);
    }
}
