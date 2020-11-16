<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\ImportDatasetIntoProjectAction;
use App\Models\File;
use App\Models\User;
use Facades\Tests\Factories\DatasetFactory;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Utils\StorageUtils;

class ImportDatasetIntoProjectActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_imports_a_datasets_files()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $dataset = DatasetFactory::create();
        $dsDir = DatasetFactory::createDirectory($dataset, $dataset->project->rootDir, "d1");
        DatasetFactory::createFile($dataset, $dsDir, "test.txt", "test");

        $fileSelection = $dataset->file_selection;
        $fileSelection["include_files"] = "/d1/test.txt";
        $dataset->update(['file_selection' => $fileSelection]);

        $importPublishedDatasetIntoProjectAction = new ImportDatasetIntoProjectAction();
        $imported = $importPublishedDatasetIntoProjectAction->execute($dataset, $project, "importedDS");
        $this->assertTrue($imported);

        $this->assertDatabaseHas('files', [
            'path'       => "/importedDS",
            'project_id' => $project->id,
        ]);

        $this->assertDatabaseHas('files', [
            'path'       => '/importedDS/d1',
            'project_id' => $project->id,
        ]);

        $dir = File::where('project_id', $project->id)
                   ->where('path', '/importedDS/d1')
                   ->first();

        $this->assertNotNull($dir);

        $this->assertDatabaseHas('files', [
            'name'         => 'test.txt',
            'directory_id' => $dir->id,
            'project_id'   => $project->id,
        ]);

        StorageUtils::clearStorage();
    }
}