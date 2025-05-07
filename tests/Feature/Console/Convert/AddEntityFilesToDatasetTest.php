<?php

namespace Tests\Feature\Console\Convert;

use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Actions\Entities\CreateEntityAction;
use App\Models\Dataset;
use Facades\Tests\Factories\DatasetFactory;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AddEntityFilesToDatasetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_entity_files_to_dataset_command_works()
    {
        $this->markTestSkipped('AddEntityFilesToDatasetTest skipped');
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $dataset = DatasetFactory::ownedBy($project->owner)
                                 ->inProject($project)
                                 ->create();
        $file = ProjectFactory::createFakeFile($project, $project->rootDir, "image.jpg");
        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $fileInD1 = ProjectFactory::createFakeFile($project, $d1, "image2.jpg");
        $createAction = new CreateEntityAction();
        $entity = $createAction([
            'name'          => 'e1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ], $project->owner_id);
        $entity->files()->attach($file);
        $entity->files()->attach($fileInD1);

        $entity2 = $createAction([
            'name'          => 'e2',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ], $project->owner_id);
        $entity2->files()->attach($file);

        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection->update($entity, $dataset);
        $updateSelection->update($entity2, $dataset);

        // Now test the logic for console command.
        Artisan::call("mc-convert:add-entity-files-to-dataset {$dataset->id}");
//        $command = new AddEntityFilesToDatasetCommand();
//        $command->addDatasetEntityFilesToDataset($dataset);
        $dataset = Dataset::findOrFail($dataset->id);
        $this->assertEquals(2, sizeof($dataset->file_selection['include_files']));
        $this->assertEquals(["/image.jpg", "/d1/image2.jpg"], $dataset->file_selection['include_files']);
    }
}
