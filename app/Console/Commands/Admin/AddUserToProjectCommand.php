<?php

namespace App\Console\Commands\Admin;

use App\Models\Project;
use App\Models\User;
use Illuminate\Console\Command;

class AddUserToProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-admin:add-user-to-project {--project-id= : Project to add user to}
                                                         {--user-id= : User to add}
                                                         {--admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $project = Project::with(['team.admins', 'team.members'])->findOrFail($this->option('project-id'));
        $user = User::findOrFail($this->option('user-id'));
        if ($this->option('admin')) {
            $project->team->admins()->syncWithoutDetaching($user);
        } else {
            $project->team->members()->syncWithoutDetaching($user);
        }
        return Command::SUCCESS;
    }
}
