<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\UpdateDatasetFileSelectionAction;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateDatasetSelectionActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_uniquely_update_include_files()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $dataset = factory(Dataset::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $updateDatasetSelectionAction = new UpdateDatasetFileSelectionAction();

        $dataset = $updateDatasetSelectionAction(['include_file' => '/a/b'], $dataset);
        $this->assertEquals(["/a/b"], $dataset->file_selection["include_files"]);

        $dataset = $updateDatasetSelectionAction(['remove_include_file' => '/a/b'], $dataset);
        $this->assertEquals([], $dataset->file_selection["include_files"]);

        $dataset = $updateDatasetSelectionAction(['include_file' => '/a/b'], $dataset);
        $dataset = $updateDatasetSelectionAction(['include_file' => '/a/b'], $dataset);
        $this->assertEquals(["/a/b"], $dataset->file_selection["include_files"]);

        $dataset = $updateDatasetSelectionAction(['remove_include_file' => '/a/b'], $dataset);
        $dataset = $updateDatasetSelectionAction(['remove_include_file' => '/a/b'], $dataset);
        $this->assertEquals([], $dataset->file_selection["include_files"]);

        $dataset = $updateDatasetSelectionAction(['include_dir' => '/d1/d2'], $dataset);
        $this->assertEquals(["/d1/d2"], $dataset->file_selection["include_dirs"]);

        $dataset = $updateDatasetSelectionAction(['remove_include_dir' => '/d1/d2'], $dataset);
        $this->assertEquals([], $dataset->file_selection["include_dirs"]);
    }
}
