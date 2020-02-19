<?php

namespace Tests\Feature\Actions\Migration\Datasets;

use App\Actions\Migration\Datasets\SetupMigratedPublishedDatasetsAction;
use App\Models\Dataset;
use Facades\Tests\Factories\DatasetFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Utils\GlobusMockUtils;
use Tests\Utils\StorageUtils;

class SetupMigratedPublishedDatasetsActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_zip_and_globus_for_migrated_published_dataset()
    {
        $globusApiMock = GlobusMockUtils::createGlobusApiMock();
        $dataset = DatasetFactory::create();
        $this->setupPublishedDataset($dataset);

        $setupMigrated = new SetupMigratedPublishedDatasetsAction($globusApiMock);
        $setupMigrated();
        $expectedGlobusPath = "/".$dataset->publishedGlobusPathPartial()."/";
        $this->assertDatabaseHas('datasets', [
            'globus_endpoint_id' => config('globus.endpoint'),
            'globus_acl_id'      => 'acl_id_1234',
            'globus_path'        => $expectedGlobusPath,
        ]);

        $this->assertFileExists($dataset->zipfilePath());

        StorageUtils::clearStorage();
    }

    private function setupPublishedDataset(Dataset $dataset)
    {
        $this->createPublishedDatasetZipfile($dataset);
        $this->createPublishedDatasetGlobusDir($dataset);
    }

    private function createPublishedDatasetZipfile(Dataset $dataset)
    {
        $zipfilePath = $dataset->zipfilePath();
        $zipfileDir = $dataset->zipfileDir();
        if (!file_exists($zipfileDir)) {
            Storage::disk('mcfs')->makeDirectory($dataset->zipfileDirPartial());
        }
        $handle = fopen($zipfilePath, "w");
        fwrite($handle, $dataset->name);
        fclose($handle);
    }

    private function createPublishedDatasetGlobusDir(Dataset $dataset)
    {
        $dsGlobusDir = $dataset->publishedGlobusPath();
        if (!file_exists($dsGlobusDir)) {
            Storage::disk('mcfs')->makeDirectory($dataset->publishedGlobusPathPartial());
        }
    }
}
