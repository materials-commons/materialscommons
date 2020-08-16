<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateTeamsForProjectsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:create-teams-for-projects';

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

    public function handle()
    {
        foreach (Project::with('users', 'owner')->cursor() as $project) {
            if (is_null($project->team_id)) {
                echo "Creating team for {$project->name}\n";
                DB::transaction(function () use ($project) {
                    $team = Team::create([
                        'name'     => "Team for {$project->name}",
                        'owner_id' => $project->owner_id,
                    ]);

                    $project->update(['team_id' => $team->id]);

                    $users = $project->users->filter(function (User $user) use ($project) {
                        return $user->id !== $project->owner_id;
                    });

                    $team->members()->attach($users);
                    $team->admins()->attach($project->owner);
                });
            }
        }
    }
}
