<?php

namespace Tests\Feature\Observers;

use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileObserverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_or_deleting_a_directory_updates_project_directory_count()
    {
        $project = ProjectFactory::create();
        $rootDir = $project->rootDir;
        $this->assertEquals(1, $project->directory_count);
        $d1 = ProjectFactory::createDirectory($project, $rootDir, "D1");
        $project->refresh();
        $this->assertEquals(2, $project->directory_count);
        $d1->delete();
        $project->refresh();
        $this->assertEquals(1, $project->directory_count);
    }

    /** @test */
    public function creating_or_deleting_a_file_updates_project_file_type_counts()
    {
        $project = ProjectFactory::create();
        $rootDir = $project->rootDir;
        $f = ProjectFactory::createFakeFile($project, $rootDir, "abc.txt");
        $project->refresh();
        $this->assertEquals($f->size, $project->size);
        $this->assertEquals(1, $project->file_count);
        $this->assertEquals(1, sizeof($project->file_types));
        $this->assertTrue(isset($project->file_types['Text']));
        $this->assertEquals(1, $project->file_types['Text']);

        // Delete file and check to make sure project attributes were properly updated
        $f->delete();
        $project->refresh();
        $this->assertEquals(0, $project->size);
        $this->assertEquals(0, $project->file_count);
        $this->assertIsArray($project->file_types);
        $this->assertEquals(0, sizeof($project->file_types));
    }

    /** @test */
    public function changing_current_status_on_file_updates_the_project_attributes()
    {
        $project = ProjectFactory::create();
        $rootDir = $project->rootDir;
        $f = ProjectFactory::createFakeFile($project, $rootDir, "abc.txt");

        $project->refresh();

        // First make sure all the attributes are set correctly
        $this->assertEquals($f->size, $project->size);
        $this->assertEquals(1, $project->file_count);
        $this->assertEquals(1, sizeof($project->file_types));
        $this->assertTrue(isset($project->file_types['Text']));
        $this->assertEquals(1, $project->file_types['Text']);

        // Now set current to false
        $f->update(['current' => false]);

        // Now all the project attributes should be updated to show
        // file isn't there
        $project->refresh();
        $this->assertEquals(0, $project->size);
        $this->assertEquals(0, $project->file_count);
        $this->assertIsArray($project->file_types);
        $this->assertEquals(0, sizeof($project->file_types));

        // Now bring file back by setting current to true and then make sure that the
        // project attributes reflect the file is back
        $f->update(['current' => true]);
        $project->refresh();

        $this->assertEquals($f->size, $project->size);
        $this->assertEquals(1, $project->file_count);
        $this->assertEquals(1, sizeof($project->file_types));
        $this->assertTrue(isset($project->file_types['Text']));
        $this->assertEquals(1, $project->file_types['Text']);
    }

    /** @test */
    public function updating_directory_should_not_change_project_attributes()
    {
        $project = ProjectFactory::create();
        $rootDir = $project->rootDir;
        $this->assertEquals(1, $project->directory_count);

        $rootDir->update(['current' => true]);
        $project->refresh();
        $this->assertEquals(1, $project->directory_count);
    }
}
