<?php

namespace App\Console\Commands\Conversion;

use App\Models\File;
use App\Models\Project;
use App\Traits\FileType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetFileAttributesForProjectCommand extends Command
{
    use FileType;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:set-file-attributes-for-project {project : id of project to update}';

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
        ini_set("memory_limit", "4096M");
        $project = Project::findOrFail($this->argument('project'));
        $this->info("Updating project {$project->name} ({$project->id})");
        $this->info("  Adding file counts and size...");
        $project->update([
            'size'            => $this->computeProjectSize($project),
            'file_count'      => $this->getFileCount($project),
            'directory_count' => $this->getDirectoryCount($project),
            'file_types'      => [],
        ]);
        $project->fresh();
        $this->info("  Adding file type counts...");
        $this->addFileTypes($project);
        $this->info(" ");
        return 0;
    }

    private function computeProjectSize(Project $project)
    {
        return DB::table('files')
                 ->where('project_id', $project->id)
                 ->where('mime_type', '<>', 'directory')
                 ->where('current', true)
                 ->sum('size');
    }

    private function getFileCount($project)
    {
        return DB::table('files')
                 ->where('project_id', $project->id)
                 ->where('mime_type', '<>', 'directory')
                 ->where('current', true)
                 ->count();
    }

    private function getDirectoryCount($project)
    {
        return DB::table('files')
                 ->where('project_id', $project->id)
                 ->where('mime_type', 'directory')
                 ->where('current', true)
                 ->count();
    }

    private function addFileTypes($project)
    {
        $fileTypes = [];
        foreach ($this->getFilesCursorForProject($project) as $file) {
            $fileTypes = $this->incrementFileType($fileTypes, $file);
        }

        $project->update(['file_types' => $fileTypes]);
    }

    private function getFilesCursorForProject($project)
    {
        return File::where('project_id', $project->id)
                   ->where('mime_type', '<>', 'directory')
                   ->where('current', true)->cursor();
    }

    private function incrementFileType($fileTypes, File $file)
    {
        $fileType = $this->mimeTypeToDescription($file->mime_type);
        if (!array_key_exists($fileType, $fileTypes)) {
            $fileTypes[$fileType] = 1;
        } else {
            $currentCount = $fileTypes[$fileType];
            $currentCount++;
            $fileTypes[$fileType] = $currentCount;
        }
        return $fileTypes;
    }
}
