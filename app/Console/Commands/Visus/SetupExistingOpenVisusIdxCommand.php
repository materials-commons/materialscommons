<?php

namespace App\Console\Commands\Visus;

use App\Actions\Directories\ChildDirs;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Services\OpenVisusApiService;
use App\Traits\PathForFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function dirname;
use function is_null;
use function link;
use function mkdir;
use function pathinfo;
use function symlink;
use const PATHINFO_FILENAME;

class SetupExistingOpenVisusIdxCommand extends Command
{
    use PathForFile;
    use ChildDirs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-visus:setup-existing-open-visus-idx 
                                    {--project-id= : project to work on}
                                    {--dataset-id= : dataset-id optional}
                                    {--directory-id= : directory to descend through}
                                    {--file-id= : Only add a single idx file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $project = Project::findOrFail($this->option('project-id'));

        $datasetId = $this->option('dataset-id');

        $fileId = $this->option('file-id');
        if (!is_null($fileId)) {
            return $this->addSingleFile($fileId, $project, $datasetId);
        }

        return $this->addDirectoryOfFiles($project, $datasetId);
    }

    private function addSingleFile(int $fileId, Project $project, $datasetId): int
    {
        echo "Adding a single idx file...\n";
        $file = File::with(['directory'])
                    ->where('id', $fileId)
                    ->where('project_id', $project->id)
                    ->whereNull('deleted_at')
                    ->first();
        if (is_null($file)) {
            echo "No such file in project\n";
            return 1;
        }

        $this->addIdxFile($file, $file->directory, $project, $datasetId);
        return 0;
    }

    private function addDirectoryOfFiles(Project $project, $datasetId): int
    {
        echo "Adding all idx files in a directory...\n";
        $dir = File::where('id', $this->option('directory-id'))
                   ->where('mime_type', 'directory')
                   ->where('project_id', $project->id)
                   ->whereNull('deleted_at')
                   ->first();

        if (is_null($dir)) {
            echo "No such directory in project\n";
            return 1;
        }

        File::where('directory_id', $dir->id)
            ->where('mime_type', '<>', 'directory')
            ->get()
            ->each(function (File $file) use ($project, $dir, $datasetId) {
                $this->addIdxFile($file, $dir, $project, $datasetId);
            });

        return 0;
    }

    private function addIdxFile(File $file, File $dir, Project $project, $datasetId)
    {
        if (Str::endsWith($file->name, ".idx")) {
            echo "Processing {$file->name}\n";
            $path = OpenVisusApiService::addIdxForProjectToOpenVisus($project, $dir, $file);
            echo "  path = {$path}\n";
            @mkdir($path, 0777, true);
            $nameWithoutExtension = pathinfo($file->name, PATHINFO_FILENAME);
            $d = File::where('name', $nameWithoutExtension)
                     ->whereNull('deleted_at')
                     ->where('directory_id', $dir->id)
                     ->where('mime_type', 'directory')
                     ->first();
            if (is_null($d)) {
                echo "Error finding directory for {$file->name}\n";
                return;
            }

            $dirPath = dirname($path);
            $path = $dirPath."/".$file->name;

            // link idx file
            $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
            echo "Linking idx file {$file->name}:\n";
            echo "   {$uuidPath}\n";
            echo "   {$path}\n";
            echo "symlnk {$uuidPath}, {$path}\n";
            try {
                if (!symlink($uuidPath, $path)) {
                    echo "Unable to symlink {$uuidPath} to {$path}\n";
                }
            } catch (\Exception $e) {
                echo "Unable to symlink {$uuidPath} to {$path}\n";
            }

            $pathToRemove = dirname($d->path);
            $this->recursivelyRetrieveAllSubdirs($d->id, $datasetId)->each(function (File $dir) use (
                $dirPath, $pathToRemove, $datasetId
            ) {
                $pathToAdd = Str::replaceFirst($pathToRemove, "", $dir->path);
                $path = "{$dirPath}{$pathToAdd}";
                echo "mkdir {$path}\n";
                @mkdir($path, 0777, true);
                $this->linkBinFilesInDir($dir, $dirPath, $pathToAdd, $datasetId);
            });

            // Link any bin files in the top level directory
            $this->linkBinFilesInDir($d, $dirPath, Str::replaceFirst($pathToRemove, "", $d->path), $datasetId);

//            if (true) {
//                return;
//            }
            // Link bin files for idx
//            File::where('directory_id', $d->id)
//                ->whereNull('dataset_id')
//                ->whereNull('deleted_at')
//                ->get()
//                ->each(function (File $file) use ($dirPath, $d) {
//                    if (Str::endsWith($file->name, ".bin")) {
//                        $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
//                        @mkdir($dirPath."/".$d->name, 0777, true);
//                        $path = $dirPath."/".$d->name."/".$file->name;
//                        echo "   Linking:\n";
//                        echo "      {$uuidPath}\n";
//                        echo "      {$path}\n";
//                        try {
//                            if (!symlink($uuidPath, $path)) {
//                                echo "Unable to symlink {$uuidPath} to {$path}\n";
//                            }
//                        } catch (\Exception $e) {
//                            echo "Unable to symlink {$uuidPath} to {$path}\n";
//                        }
//                    }
//                });
        }
    }

    private function linkBinFilesInDir(File $dir, string $dirPath, string $pathToAdd, $datasetId)
    {
        echo "linkBinFilesInDir({$dir->id}, {$dirPath}, {$pathToAdd}, {$datasetId})\n";
        $query = File::where('directory_id', $dir->id)
                     ->whereNull('deleted_at')
                     ->where('name', 'like', '%.bin');
        if (is_null($datasetId)) {
            $query = $query->whereNull('dataset_id');
        } else {
            $query = $query->where('dataset_id', $datasetId);
        }

        $query->get()
              ->each(function (File $file) use ($dir, $dirPath, $pathToAdd) {
                  $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
                  $path = $dirPath.$pathToAdd."/".$file->name;
                  echo "   Linking:\n";
                  echo "      {$uuidPath}\n";
                  echo "      {$path}\n";
                  try {
                      if (!symlink($uuidPath, $path)) {
                          echo "Unable to symlink {$uuidPath} to {$path}\n";
                      }
                  } catch (\Exception $e) {
                      echo "Unable to symlink {$uuidPath} to {$path}\n";
                  }
              });
    }
}
