<?php

namespace App\Console\Commands\Conversion;

use App\Models\Project;
use App\Models\User;
use App\Traits\HasUniqueSlug;
use Illuminate\Console\Command;
use function slugify;
use function substr;

class AddSlugToExistingProjectsAndUsersCommand extends Command
{
    use HasUniqueSlug;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-conv:add-slug-to-existing-projects-and-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a unique slug to existing projects and users';

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
        User::whereNull('slug')->get()->each(function (User $user) {
            echo "Updating slug for user {$user->email}...\n";
            $this->addUniqueSlugToUser($user);
        });

        Project::whereNull('slug')->get()->each(function (Project $project) {
            echo "Updating slug for project {$project->name}...\n";
            $this->addUniqueSlugToProject($project);
        });
        return 0;
    }
}
