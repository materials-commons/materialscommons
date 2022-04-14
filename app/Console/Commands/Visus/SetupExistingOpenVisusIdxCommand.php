<?php

namespace App\Console\Commands\Visus;

use App\Models\File;
use App\Models\Project;
use Illuminate\Console\Command;

class SetupExistingOpenVisusIdxCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-visus:setup-exisitng-open-visus-idx 
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
            ->each(function (File $file) {

            });

        return 0;
    }
}
