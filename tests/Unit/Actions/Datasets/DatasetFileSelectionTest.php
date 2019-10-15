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
}
