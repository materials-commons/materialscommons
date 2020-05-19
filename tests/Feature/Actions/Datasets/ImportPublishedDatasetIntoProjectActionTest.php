<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\ImportPublishedDatasetIntoProjectAction;
use App\Models\File;
use App\Models\User;
use Facades\Tests\Factories\DatasetFactory;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Utils\StorageUtils;

class ImportPublishedDatasetIntoProjectActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_does_not_import_unpublished_datasets()
    {
        $user = factory(User::class)->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $dataset = DatasetFactory::ownedBy($user)->create();
        $dataset->update(['published_at' => null]);
        $importPublishedDatasetIntoProjectAction = new ImportPublishedDatasetIntoProjectAction();
        $imported = $importPublishedDatasetIntoProjectAction->execute($dataset, $project, "importedDS");
        $this->assertFalse($imported);
    }

    /** @test */
    public function it_imports_a_published_dataset()
    {
        $user = factory(User::class)->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $dataset = DatasetFactory::ownedBy($user)->create();
        $importPublishedDatasetIntoProjectAction = new ImportPublishedDatasetIntoProjectAction();
        $imported = $importPublishedDatasetIntoProjectAction->execute($dataset, $project, "importedDS");
        $this->assertTrue($imported);
    }

    /** @test */
    public function it_imports_a_published_datasets_files()
    {
        $user = factory(User::class)->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $dataset = DatasetFactory::create();
        $dsDir = DatasetFactory::createDirectory($dataset, $dataset->project->rootDir, "d1");
        DatasetFactory::createFile($dataset, $dsDir, "test.txt", "test");

        $fileSelection = $dataset->file_selection;
        $fileSelection["include_files"] = "/d1/test.txt";
        $dataset->update(['file_selection' => $fileSelection]);

        $importPublishedDatasetIntoProjectAction = new ImportPublishedDatasetIntoProjectAction();
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