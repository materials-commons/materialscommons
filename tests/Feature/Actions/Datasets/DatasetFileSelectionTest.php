<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\DatasetFileSelection;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatasetFileSelectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function file_can_be_found_in_selection()
    {
        $project = ProjectFactory::create();
        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $f1 = ProjectFactory::createFakeFile($project, $d1, "f1.txt");
        $fs = new DatasetFileSelection([
            'include_files' => ['/d1/f1.txt'],
            'include_dirs'  => [],
            'exclude_files' => [],
            'exclude_dirs'  => [],
        ]);

        $this->assertTrue($fs->isIncludedFile("{$f1->directory->path}/{$f1->name}"));
    }
}
