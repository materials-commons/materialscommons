<?php

namespace Tests\Unit\Actions\Datasets;

use App\Actions\Datasets\DatasetFileSelection;
use Tests\TestCase;

class DatasetFileSelectionTest extends TestCase
{
    /** @test */
    public function it_should_handle_include_dirs()
    {
        $selection = $this->makeSelection();
        array_push($selection["include_dirs"], "/dir1");
        $dsFileSelection = new DatasetFileSelection($selection);
        $this->assertTrue($dsFileSelection->isIncludedFile("/dir1/file.txt"));
    }

    /** @test */
    public function include_dir_should_work_for_all_levels()
    {
        $selection = $this->makeSelection();
        array_push($selection["include_dirs"], "/dir1");
        $dsFileSelection = new DatasetFileSelection($selection);
        $this->assertTrue($dsFileSelection->isIncludedFile("/dir1/dir2/file.txt"));
        $this->assertTrue($dsFileSelection->isIncludedFile("/dir1/dir2/dir3/file.txt"));
        $this->assertTrue($dsFileSelection->isIncludedFile("/dir1/dir2/dir3/dir4/file.txt"));
    }

    /** @test */
    public function selected_file_at_top_level_should_be_included()
    {
        $selection = $this->makeSelection();
        array_push($selection["include_files"], "/file1.txt");
        $dsFileSelection = new DatasetFileSelection($selection);
        $this->assertTrue($dsFileSelection->isIncludedFile("/file1.txt"));
    }

    /** @test */
    public function exclude_files_should_override_dir()
    {
        $selection = $this->makeSelection();
        array_push($selection["include_dirs"], "/dir1");
        array_push($selection["exclude_files"], "/dir1/exclude.txt");
        $dsFileSelection = new DatasetFileSelection($selection);
        $this->assertFalse($dsFileSelection->isIncludedFile("/dir1/exclude.txt"));
        $this->assertTrue($dsFileSelection->isIncludedFile("/dir1/not-excluded.txt"));
    }

    /** @test */
    public function exclude_dirs_should_override_dir()
    {
        $selection = $this->makeSelection();
        array_push($selection["include_dirs"], "/dir1");
        array_push($selection["exclude_dirs"], "/dir1/dir2");
        $dsFileSelection = new DatasetFileSelection($selection);
        $this->assertFalse($dsFileSelection->isIncludedFile("/dir1/dir2/exclude.txt"));
    }

    /** @test */
    public function include_file_should_override_exclude_dir()
    {
        $selection = $this->makeSelection();
        array_push($selection["exclude_dirs"], "/dir1");
        array_push($selection["include_files"], "/dir1/file.txt");
        $dsFileSelection = new DatasetFileSelection($selection);
        $this->assertTrue($dsFileSelection->isIncludedFile("/dir1/file.txt"));
    }

    /** @test */
    public function include_dir_should_override_parent_exclude_dir()
    {
        $selection = $this->makeSelection();
        array_push($selection["exclude_dirs"], "/dir1");
        array_push($selection["include_dirs"], "/dir1/dir2");
        $dsFileSelection = new DatasetFileSelection($selection);
        $this->assertFalse($dsFileSelection->isIncludedFile("/dir1/file.txt"));
        $this->assertFalse($dsFileSelection->isIncludedFile("/dir1/dir3/file.txt"));
        $this->assertTrue($dsFileSelection->isIncludedFile("/dir1/dir2/file.txt"));
        $this->assertTrue($dsFileSelection->isIncludedFile("/dir1/dir2/dir3/file.txt"));
    }

    /** @test */
    public function empty_selection_should_always_return_false()
    {
        $selection = $this->makeSelection();
        $dsFileSelection = new DatasetFileSelection($selection);
        $this->assertFalse($dsFileSelection->isIncludedDir('/d1'));
        $this->assertFalse($dsFileSelection->isIncludedFile('/file1.txt'));
    }

    private function makeSelection()
    {
        return [
            'include_files' => [],
            'exclude_files' => [],
            'include_dirs'  => [],
            'exclude_dirs'  => [],
        ];
    }

    /** @test */
    public function file_can_be_found_in_selection()
    {
        $fs = new DatasetFileSelection([
            'include_files' => ['/d1/f1.txt'],
            'include_dirs'  => [],
            'exclude_files' => [],
            'exclude_dirs'  => [],
        ]);

        $this->assertTrue($fs->isIncludedFile("/d1/f1.txt"));
    }

    /** @test */
    public function selecting_root_selects_all_other_files()
    {
        $fs = new DatasetFileSelection(['include_dirs' => '/']);
        $this->assertTrue($fs->isIncludedFile("/f1.txt"));
        $this->assertTrue($fs->isIncludedFile("/f1/f2/f3.txt"));
    }

    /** @test */
    public function selecting_root_and_excluding_a_subdir_excludes_files_in_subdir()
    {
        $fs = new DatasetFileSelection(['include_dirs' => '/', 'exclude_dirs' => '/f2/f3']);
        $this->assertTrue($fs->isIncludedFile('/f2/f1.txt'));
        $this->assertFalse($fs->isIncludedFile('/f2/f3/f3.txt'));
        $this->assertTrue($fs->isIncludedFile("/f1.txt"));
    }

    /** @test */
    public function excluding_root_exludes_everything()
    {
        $fs = new DatasetFileSelection(['exclude_dirs' => '/']);
        $this->assertFalse($fs->isIncludedFile("/f1.txt"));
        $this->assertFalse($fs->isIncludedFile("/d1/f1.txt"));
        $this->assertFalse($fs->isIncludedFile("/d1/d2/f1.txt"));
    }

    /** @test */
    public function excluding_root_but_including_subdir_includes_anything_under_subdir()
    {
        $fs = new DatasetFileSelection(['include_dirs' => '/d1', 'exclude_dirs' => '/']);
        $this->assertFalse($fs->isIncludedFile("/f1.txt"));
        $this->assertFalse($fs->isIncludedFile("/d2/f1.txt"));
        $this->assertTrue($fs->isIncludedFile("/d1/file.txt"));
        $this->assertTrue($fs->isIncludedFile("/d1/d2/file.txt"));
    }

    /** @test */
    public function excluding_root_but_including_file_includes_file()
    {
        $fs = new DatasetFileSelection(['include_files' => ['/f1.txt', '/f2.txt'], 'exclude_dirs' => '/']);
        $this->assertTrue($fs->isIncludedFile('/f1.txt'));
        $this->assertTrue($fs->isIncludedFile('/f2.txt'));
        $this->assertFalse($fs->isIncludedFile('/d1/f2.txt'));
    }
}
