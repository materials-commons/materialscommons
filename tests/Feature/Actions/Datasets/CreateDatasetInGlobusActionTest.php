<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\CreateDatasetInGlobusAction;
use Facades\Tests\Factories\DatasetFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Utils\GlobusMockUtils;
use Tests\Utils\StorageUtils;

class CreateDatasetInGlobusActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function published_datasets_have_files()
    {
        $dataset = DatasetFactory::create();
        DatasetFactory::createFile($dataset, $dataset->project->rootDir, "test.txt", "test");
        $fileSelection = $dataset->file_selection;
        $fileSelection["include_files"] = "/test.txt";

        $dataset->update(['file_selection' => $fileSelection]);
        $globusApiMock = GlobusMockUtils::createGlobusApiMock();

        $createDatasetInGlobusAction = new CreateDatasetInGlobusAction($globusApiMock);
        $createDatasetInGlobusAction($dataset->id, false);

        $datasetPath = storage_path("app/mcfs/__globus_published_datasets/{$dataset->uuid}");
        $this->assertDirectoryExists($datasetPath);
        $this->assertFileExists("{$datasetPath}/test.txt");

        StorageUtils::clearStorage();
    }

    /** @test */
    public function private_datasets_have_files()
    {
        $dataset = DatasetFactory::create();
        DatasetFactory::createFile($dataset, $dataset->project->rootDir, "test.txt", "test");
        $fileSelection = $dataset->file_selection;
        $fileSelection["include_files"] = "/test.txt";

        $dataset->update(['file_selection' => $fileSelection]);
        $globusApiMock = GlobusMockUtils::createGlobusApiMock();

        $createDatasetInGlobusAction = new CreateDatasetInGlobusAction($globusApiMock);
        $createDatasetInGlobusAction($dataset->id, true);
        $datasetPath = storage_path("app/mcfs/__globus_private_datasets/{$dataset->uuid}");
        $this->assertDirectoryExists($datasetPath);
        $this->assertFileExists("{$datasetPath}/test.txt");

        StorageUtils::clearStorage();
    }

    /** @test */
    public function publishing_in_globus_handles_uses_uuid_correctly()
    {
        $dataset = DatasetFactory::create();
        $file = DatasetFactory::createFile($dataset, $dataset->project->rootDir, "test.txt", "test");
        DatasetFactory::createFilePointingAt($dataset, $file, "anothername.txt");
        $fileSelection = $dataset->file_selection;
        $fileSelection["include_files"] = "/anothername.txt";

        $dataset->update(['file_selection' => $fileSelection]);
        $globusApiMock = GlobusMockUtils::createGlobusApiMock();

        $createDatasetInGlobusAction = new CreateDatasetInGlobusAction($globusApiMock);
        $createDatasetInGlobusAction($dataset->id, true);
        $datasetPath = storage_path("app/mcfs/__globus_private_datasets/{$dataset->uuid}");
        $this->assertDirectoryExists($datasetPath);
        $this->assertFileExists("{$datasetPath}/anothername.txt");

        StorageUtils::clearStorage();
    }
}
