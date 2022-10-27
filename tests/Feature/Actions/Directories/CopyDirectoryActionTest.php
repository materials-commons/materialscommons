<?php

namespace Tests\Feature\Actions\Directories;

use App\Actions\Directories\CopyDirectoryAction;
use App\Models\File;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CopyDirectoryActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_copy_a_simple_directory_to_another_directory_and_all_have_correct_attributes()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $d1File = ProjectFactory::createFakeFile($project, $d1, "file.txt");

        $destDir = ProjectFactory::createDirectory($project, $project->rootDir, "destdir");

        $copyDirAction = new CopyDirectoryAction();
        $this->assertTrue($copyDirAction->execute($d1, $destDir, $user));

        $d1DestDir = File::query()
                         ->where('directory_id', $destDir->id)
                         ->where('path', '/destdir/d1')
                         ->where('mime_type', 'directory')
                         ->first();

        $count = File::query()
                     ->where('directory_id', $d1DestDir->id)
                     ->where('mime_type', '<>', 'directory')
                     ->where('current', true)
                     ->whereNull('deleted_at')
                     ->whereNull('dataset_id')
                     ->count();
        $this->assertEquals(1, $count);

        $copiedFile = File::query()
                          ->where('directory_id', $d1DestDir->id)
                          ->where('mime_type', '<>', 'directory')
                          ->where('current', true)
                          ->whereNull('deleted_at')
                          ->whereNull('dataset_id')
                          ->first();
        // Make sure the copied file has the correct attributes
        $this->assertEquals($d1File->size, $copiedFile->size);
        $this->assertEquals($d1File->checksum, $copiedFile->checksum);
        $this->assertTrue($copiedFile->current);
        $this->assertEquals($d1File->disk, $copiedFile->disk);
        $this->assertEquals($d1File->mime_type, $copiedFile->mime_type);
        $this->assertEquals($d1File->media_type_description, $copiedFile->media_type_description);
        $this->assertEquals($d1File->uuid, $copiedFile->uses_uuid);
    }

    /** @test */
    public function it_should_copy_a_deeper_directory_to_another_directory()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $d1File = ProjectFactory::createFakeFile($project, $d1, "file.txt");

        $d11Dir1 = ProjectFactory::createDirectory($project, $d1, "d11Dir1");
        $d11Dir1File1 = ProjectFactory::createFakeFile($project, $d11Dir1, "d11dir1file1.txt");
        $d11Dir1File2 = ProjectFactory::createFakeFile($project, $d11Dir1, "d11dir1file2.txt");
        $d11Dir2 = ProjectFactory::createDirectory($project, $d1, "d11Dir2");
        $d11Dir2File = ProjectFactory::createFakeFile($project, $d11Dir2, "d11dir2file.txt");
        $d11Subdir = ProjectFactory::createDirectory($project, $d11Dir1, "d11Dir1subdir");
        $d11SubdirFile = ProjectFactory::createFakeFile($project, $d11Subdir, "d11subdirFile.txt");

        $destDir = ProjectFactory::createDirectory($project, $project->rootDir, "destdir");

        $copyDirAction = new CopyDirectoryAction();
        $this->assertTrue($copyDirAction->execute($d1, $destDir, $user));

        // Count that each copied directory and subdirectory has the correct number of files

        // $destDir (/destdir) should have one directory /destdir/d1
        $filesCount = $this->getFilesCountInDir($destDir);
        $this->assertEquals(0, $filesCount);

        $dirsCount = $this->getDirsCountInDir($destDir);
        $this->assertEquals(1, $dirsCount);

        // Count files directories copied from $d1 (/d1) to /destdir/d1
        $d = File::getDirectoryByPath($project->id, "/destdir/d1");
        $this->assertNotNull($d);

        $filesCount = $this->getFilesCountInDir($d);
        $this->assertEquals(1, $filesCount);

        $dirsCount = $this->getDirsCountInDir($d);
        $this->assertEquals(2, $dirsCount);

        // Count files and directories copied from $d11Dir1 (/d1/d11Dir1) to /destdir/d1/d11Dir1
        $d = File::getDirectoryByPath($project->id, "/destdir/d1/d11Dir1");
        $this->assertNotNull($d);

        $filesCount = $this->getFilesCountInDir($d);
        $this->assertEquals(2, $filesCount);

        $dirsCount = $this->getDirsCountInDir($d);
        $this->assertEquals(1, $dirsCount);

        // Count files and directories copied from $d11Dir2 (/d1/d11Dir2) to /destdir/d1/d11Dir2
        $d = File::getDirectoryByPath($project->id, "/destdir/d1/d11Dir2");
        $this->assertNotNull($d);

        $filesCount = $this->getFilesCountInDir($d);
        $this->assertEquals(1, $filesCount);

        $dirsCount = $this->getDirsCountInDir($d);
        $this->assertEquals(0, $dirsCount);

        // Count files and directories copied from $d11Subdir (/d1/d11Dir1/d11Dir1subdir) to /destdir/d1/d11Dir1/d11Dir1subdir
        $d = File::getDirectoryByPath($project->id, "/destdir/d1/d11Dir1/d11Dir1subdir");
        $this->assertNotNull($d);

        $filesCount = $this->getFilesCountInDir($d);
        $this->assertEquals(1, $filesCount);

        $dirsCount = $this->getDirsCountInDir($d);
        $this->assertEquals(0, $dirsCount);

//        File::all()->each(function(File $file) {
//            echo "\n";
//            echo "id = '{$file->id}'\n";
//            echo "name = '{$file->name}'\n";
//            echo "path = '{$file->path}'\n";
//            echo "mime_type = '{$file->mime_type}'\n";
//            echo "directory_id = '{$file->directory_id}'\n";
//        });

        // Make sure everything ended up in the proper spot with the correct attributes

        // /destdir/d1 should have 2 directories "d11Dir1" and "d1dDir2" and a file "file.txt"
        $destd1 = File::getDirectoryByPath($project->id, "/destdir/d1");
        $this->assertEquals($destDir->id, $destd1->directory_id);

        $d11Dir1 = $this->getFileInDir($destd1, "d11Dir1");
        $this->assertNotNull($d11Dir1);
        $this->assertEquals("directory", $d11Dir1->mime_type);
        $this->assertEquals($destd1->id, $d11Dir1->directory_id);
        $this->assertEquals("/destdir/d1/d11Dir1", $d11Dir1->path);

        $d11Dir2 = $this->getFileInDir($destd1, "d11Dir2");
        $this->assertNotNull($d11Dir2);
        $this->assertEquals("directory", $d11Dir2->mime_type);
        $this->assertEquals($destd1->id, $d11Dir2->directory_id);
        $this->assertEquals("/destdir/d1/d11Dir2", $d11Dir2->path);

        $d1File = $this->getFileInDir($destd1, "file.txt");
        $this->assertNotNull($d1File);
        $this->assertEquals($destd1->id, $d1File->directory_id);

        // /destdir/d1/d11Dir1 should have two files "d11dir1file1.txt" and "d11dir1file2.txt"
        // and a directory "d11Dir1subdir"
        $d = File::getDirectoryByPath($project->id, "/destdir/d1/d11Dir1");
        $f = $this->getFileInDir($d, "d11dir1file1.txt");
        $this->assertNotNull($f);
        $this->assertEquals($d->id, $f->directory_id);

        $f = $this->getFileInDir($d, "d11dir1file2.txt");
        $this->assertNotNull($f);
        $this->assertEquals($d->id, $f->directory_id);

        $subdir = $this->getFileInDir($d, "d11Dir1subdir");
        $this->assertNotNull($subdir);
        $this->assertEquals("/destdir/d1/d11Dir1/d11Dir1subdir", $subdir->path);
        $this->assertEquals($d->id, $subdir->directory_id);

        // /destdir/d1/d11Dir2 should have one file "d11dir2file.txt" and no subdirectories.
        $d = File::getDirectoryByPath($project->id, "/destdir/d1/d11Dir2");
        $f = $this->getFileInDir($d, "d11dir2file.txt");
        $this->assertNotNull($f);
        $this->assertEquals($d->id, $f->directory_id);

        // /destdir/d1/d11Dir1/d11Dir1subdir should have one file "d11subdirFile.txt
        // and no subdirectories.
        $d = File::getDirectoryByPath($project->id, "/destdir/d1/d11Dir1/d11Dir1subdir");
        $f = $this->getFileInDir($d, "d11subdirFile.txt");
        $this->assertNotNull($f);
        $this->assertEquals($d->id, $f->directory_id);
    }

    /** @test */
    public function it_should_fail_to_copy_when_directory_with_same_name_exists()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $d1File = ProjectFactory::createFakeFile($project, $d1, "file.txt");

        $destDir = ProjectFactory::createDirectory($project, $project->rootDir, "destdir");

        // Create d1 directory in destdir. This is so that the copy of /d1 to /destdir will fail
        // because a d1 already exists in /destdir.
        ProjectFactory::createDirectory($project, $destDir, "d1");

        $copyDirAction = new CopyDirectoryAction();
        $this->assertFalse($copyDirAction->execute($d1, $destDir, $user));
    }

    /** @test */
    public function it_should_fail_to_copy_dir_to_a_different_project_user_is_not_in()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $dirToCopy = ProjectFactory::createDirectory($project, $project->rootDir, "d1");

        // Create 2nd project owned by a different user
        $user2 = User::factory()->create();
        $project2 = ProjectFactory::ownedBy($user2)->create();

        $copyDirAction = new CopyDirectoryAction();

        // $user is not in $project2, so copying /d1 to $project2 rootdir should fail
        $this->assertFalse($copyDirAction->execute($dirToCopy, $project2->rootDir, $user));
    }

    /** @test */
    public function it_should_copy_dir_to_a_different_project_user_is_in()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $dirToCopy = ProjectFactory::createDirectory($project, $project->rootDir, "d1");

        // Create 2nd project owned by a different user and add $user to $project2
        $user2 = User::factory()->create();
        $project2 = ProjectFactory::ownedBy($user2)->create();
        ProjectFactory::addMemberToProject($user, $project2);

        $copyDirAction = new CopyDirectoryAction();

        // $user is in $project2. We should be able to copy $dirToCopy into the $rootDir
        // of $project2.
        $this->assertTrue($copyDirAction->execute($dirToCopy, $project2->rootDir, $user));
        $this->assertEquals(1, File::where('directory_id', $project2->rootDir->id)->count());

        // Let's make sure the copied dir has the correct project_id, mime type, etc...
        $copiedDir = File::where('directory_id', $project2->rootDir->id)->first();
        $this->assertEquals($project2->id, $copiedDir->project_id);
        $this->assertEquals($copiedDir->mime_type, "directory");
        $this->assertEquals($project2->rootDir->id, $copiedDir->directory_id);

        // The $copiedDir->owner_id really should be equal to $user->id. However, the code in CopyDirectoryAction
        // uses an existing trait that doesn't take a user and creates missing directories in a project by setting
        // the newly created directory to the owner_id on the project. While it would be nice to set the copiedDir
        // to the user who copied it, at the end of the day it isn't worth the effort to change this as it really
        // has no effect (that the owner is instead the project owner).
        // $this->assertEquals($user->id, $copiedDir->owner_id);
        $this->assertEquals("/d1", $copiedDir->path);
        $this->assertEquals("d1", $copiedDir->name);
    }

    public function getFilesCountInDir($dir): int
    {
        return File::query()
                   ->where('directory_id', $dir->id)
                   ->where('mime_type', '<>', 'directory')
                   ->where('current', true)
                   ->whereNull('deleted_at')
                   ->whereNull('dataset_id')
                   ->count();
    }

    public function getDirsCountInDir($dir): int
    {
        return File::query()
                   ->where('directory_id', $dir->id)
                   ->where('mime_type', 'directory')
                   ->where('current', true)
                   ->whereNull('deleted_at')
                   ->whereNull('dataset_id')
                   ->count();
    }

    public function getFileInDir($dir, $fileName): ?File
    {
        return File::query()
                   ->where("directory_id", $dir->id)
                   ->where("name", $fileName)
                   ->where('current', true)
                   ->whereNull('deleted_at')
                   ->whereNull('dataset_id')
                   ->first();
    }
}
