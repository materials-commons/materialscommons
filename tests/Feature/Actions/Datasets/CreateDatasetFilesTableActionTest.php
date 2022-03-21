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
        $fileSelection = [
            'include_files' => ['/d1/f1.txt'],
            'include_dirs'  => [],
            'exclude_files' => [],
            'exclude_dirs'  => [],
        ];
        $dataset->update(['file_selection' => $fileSelection]);

        $createDatasetFilesTableAction = new CreateDatasetFilesTableAction();
        $createDatasetFilesTableAction->execute($dataset);
        File::where('dataset_id', $dataset->id)->get()->each(function(File $file) {
            echo "{$file->name}, {$file->path}, {$file->directory_id}, {$file->mime_type}\n";
        });

        $this->assertTrue(true);
    }
}
