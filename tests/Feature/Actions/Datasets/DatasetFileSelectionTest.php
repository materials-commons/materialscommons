<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\DatasetFileSelection;
use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Actions\Entities\CreateEntityAction;
use Facades\Tests\Factories\DatasetFactory;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatasetFileSelectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_includes_files_in_entity_files()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $f1 = ProjectFactory::createFakeFile($project, $d1, "f1.txt");
        $dataset = DatasetFactory::inProject($project)->create();
        $createAction = new CreateEntityAction();
        $entity = $createAction([
            'name'          => 'e1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ], $project->owner_id);

        $entity->files()->sync($f1);
        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection($entity, $dataset);

        $fs = new DatasetFileSelection([
            'include_files' => [],
            'include_dirs'  => [],
            'exclude_files' => [],
            'exclude_dirs'  => [],
        ], $dataset);

        $this->assertTrue($fs->isIncludedFile("/d1/f1.txt"));
    }

    /** @test */
    public function exclude_files_will_exclude_entity_files()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $f1 = ProjectFactory::createFakeFile($project, $d1, "f1.txt");
        $dataset = DatasetFactory::inProject($project)->create();
        $createAction = new CreateEntityAction();
        $entity = $createAction([
            'name'          => 'e1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ], $project->owner_id);

        $entity->files()->sync($f1);
        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection($entity, $dataset);

        $fs = new DatasetFileSelection([
            'include_files' => [],
            'include_dirs'  => [],
            'exclude_files' => ['/d1/f1.txt'],
            'exclude_dirs'  => [],
        ], $dataset);

        $this->assertFalse($fs->isIncludedFile("/d1/f1.txt"));
    }
}
