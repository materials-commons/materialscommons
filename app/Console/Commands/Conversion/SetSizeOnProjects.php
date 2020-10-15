<?php

namespace App\Console\Commands\Conversion;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetSizeOnProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:set-size-on-projects';

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
        foreach (Project::cursor() as $project) {
            $this->info("Adding size to project {$project->name} ({$project->id})");
            $size = DB::table('files')
                      ->where('project_id', $project->id)
                      ->where('mime_type', '<>', 'directory')
                      ->where('current', true)
                      ->sum('size');
            $project->update(['size' => $size]);
        }
        return 0;
    }
}
