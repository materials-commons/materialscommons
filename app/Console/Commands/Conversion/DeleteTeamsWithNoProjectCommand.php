<?php

namespace App\Console\Commands\Conversion;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Console\Command;

class DeleteTeamsWithNoProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:delete-teams-with-no-project';

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
        Team::all()->each(function (Team $team) {
            $project = Project::with('owner')->firstWhere('team_id', $team->id);
            if (is_null($project)) {
                $this->info("Team {$team->name}/{$team->id}/{$team->owner->email}/{$team->created_at} has no project");
                $team->delete();
            }
        });
        return 0;
    }
}
