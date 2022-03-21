<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\CreateDatasetFilesTableAction;
use App\Actions\Datasets\DatasetFileSelection;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Facades\Tests\Factories\DatasetFactory;
use Facades\Tests\Factories\ExperimentFactory;
use Facades\Tests\Factories\ProjectFactory;

class CreateDatasetFilesTableActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_replicate_directories()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $dataset = DatasetFactory::inProject($project)->create();

        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $f1 = ProjectFactory::createFakeFile($project, $d1, "f1.txt");
        $d1d2 = ProjectFactory::createDirectory($project, $d1, "d2");
        $d1d2d3 = ProjectFactory::createDirectory($project, $d1d2, "d3");
        $f2 = ProjectFactory::createFakeFile($project, $d1d2d3, "f2.txt");

        $fileSelection = [
            'include_files' => [],
            'include_dirs'  => ['/'],
            'exclude_files' => [],
            'exclude_dirs'  => [],
        ];
        $dataset->update(['file_selection' => $fileSelection]);

        $createDatasetFilesTableAction = new CreateDatasetFilesTableAction();
        $createDatasetFilesTableAction->execute($dataset);

        //
        // If debugging is needed then this line should be uncommented. It will print out the list of files and directories
        // and allow you to see if everything is wired up correctly.
        //
        // File::where('dataset_id', $dataset->id)->get()->each(function(File $file) {
        //     echo "name = {$file->name}, path = {$file->path}, id = {$file->id}, dir_id = {$file->directory_id}, mime_type = {$file->mime_type}\n";
        // });
        //

        $replicatedRoot = $this->getDir('/', $project->id, $dataset->id);
        $this->assertNotNull($replicatedRoot);

        $replicatedD1 = $this->getDirWithParent('/d1', $project->id, $replicatedRoot->id, $dataset->id);
        $this->assertNotNull($replicatedD1);

        $replicatedD1D2 = $this->getDirWithParent('/d1/d2', $project->id, $replicatedD1->id, $dataset->id);
        $this->assertNotNull($replicatedD1D2);

        $replicatedD1D2D3 = $this->getDirWithParent('/d1/d2/d3', $project->id, $replicatedD1D2->id, $dataset->id);
        $this->assertNotNull($replicatedD1D2D3);

        $d3ReplicatedDir = File::where('dataset_id', $dataset->id)
                               ->where('path', '/d1/d2/d3')
                               ->where('mime_type', 'directory')
                               ->whereNull('deleted_at')
                               ->where('project_id', $project->id)
                               ->where('current', true)
                               ->first();
        $this->assertNotNull($d3ReplicatedDir);

        $f2Replicated = $this->getFile("f2.txt", $project->id, $d3ReplicatedDir->id, $dataset->id);
        $this->assertNotNull($f2Replicated);

        $f1Replicated = $this->getFile('f1', $project->id, $replicatedD1->id, $dataset->id);
        $this->assertNotNull($f1Replicated);
    }

    private function getDir($path, $projectId, $datasetId)
    {
        return File::where('dataset_id', $datasetId)
                   ->where('path', $path)
                   ->where('mime_type', 'directory')
                   ->whereNull('deleted_at')
                   ->where('project_id', $projectId)
                   ->where('current', true)
                   ->first();
    }

    private function getDirWithParent($path, $projectId, $directoryId, $datasetId)
    {
        return File::where('dataset_id', $datasetId)
                   ->where('path', $path)
                   ->where('mime_type', 'directory')
                   ->whereNull('deleted_at')
                   ->where('project_id', $projectId)
                   ->where('directory_id', $directoryId)
                   ->where('current', true)
                   ->first();
    }

    private function getFile($name, $projectId, $directoryId, $datasetId)
    {
        return File::where('dataset_id', $datasetId)
                   ->where('directory_id', $directoryId)
                   ->whereNull('deleted_at')
                   ->where('project_id', $projectId)
                   ->where('current', true)
                   ->first();
    }
}
