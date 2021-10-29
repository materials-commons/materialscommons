<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DeleteExpiredTrashcanProjectsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:delete-expired-trashcan-projects';

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
        $count = Project::count();
        $days = Carbon::now()->subDays(config('trash.expires_in_days'));
        $projects = Project::with(['rootDir'])
                           ->withCount(['files'])
                           ->where('deleted_at', '<', $days)
                           ->limit(1000)
                           ->get();
        foreach ($projects as $project) {
            if ($project->files_count == 0) {
                // files have been deleted so we need to finish deleting the project
                DB::transaction(function () use ($project) {
                    $team = $project->team;
                    $project->delete();
                    $team->delete();
                });
            } elseif ($project->files_count != 1) {
                // files haven't been deleted, so just schedule file deletion. We do this by setting the root directory
                // for the project as deleted. Then in a later run the the deleted-expired-trashcan-directories command
                // will be run by the scheduler, catch that this directory is deleted, and start cleaning out all the files
                // and directories. Eventually this will all be deleted, the files_count will be 0 and the project will be
                // removed from the system.
                // Note: If files_count == 1 then only the root directory remains.
                if (!is_null($project->rootDir)) {
                    $project->rootDir->update(['deleted_at' => Carbon::now()->subDays(config('trash.expires_in_days') + 1)]);
                }
            } elseif (is_null($project->rootDir->deleted_at)) {
                // No files in project but rootdir isn't set to expire
                if (!is_null($project->rootDir)) {
                    $project->rootDir->update(['deleted_at' => Carbon::now()->subDays(config('trash.expires_in_days') + 1)]);
                }
//                $project->rootDir->update(['deleted_at' => Carbon::now()->subDays(config('trash.expires_in_days') + 1)]);
            } else {
                // files have been deleted so we need to finish deleting the project
                DB::transaction(function () use ($project) {
                    $team = $project->team;
                    $project->delete();
                    $team->delete();
                });
            }
        }
        return 0;
    }
}
