<?php

namespace App\Console\Commands\Visus;

use App\Models\File;
use App\Models\Project;
use App\Services\OpenVisusApiService;
use App\Traits\PathForFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function link;
use function pathinfo;
use const PATHINFO_FILENAME;

class SetupExistingOpenVisusIdxCommand extends Command
{
    use PathForFile;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-visus:setup-existing-open-visus-idx 
                                    {--project-id= : project to work on}
                                    {--directory-id= : directory to descend through}';

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
            ->each(function (File $file) use ($project, $dir) {
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
                    File::where('directory_id', $d->id)
                        ->whereNull('deleted_at')
                        ->get()
                        ->each(function (File $file) use ($dirPath, $d) {
                            if (Str::endsWith($file->name, ".bin")) {
                                $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
                                @mkdir($dirPath."/".$d->name, 0777, true);
                                $path = $dirPath."/".$d->name."/".$file->name;
                                echo "Linking:\n";
                                echo "    {$uuidPath}\n";
                                echo "    {$path}\n";
                                try {
                                    if (!link($uuidPath, $path)) {
                                        echo "Unable to link {$uuidPath} to {$path}\n";
                                    }
                                } catch (\Exception $e) {
                                    echo "Unable to link {$uuidPath} to {$path}\n";
                                }
                            }
                        });
                }
            });

        return 0;
    }
}
