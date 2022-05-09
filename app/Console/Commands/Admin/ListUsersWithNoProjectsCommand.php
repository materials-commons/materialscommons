<?php

namespace App\Console\Commands\Admin;

use App\Models\User;
use App\Traits\Projects\UserProjects;
use Illuminate\Console\Command;

class ListUsersWithNoProjectsCommand extends Command
{
    use UserProjects;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-admin:list-users-with-no-projects';

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
        $count = 0;
        $users = [];
        User::whereNotNull('id')->orderBy('created_at')->get()->each(function(User $user) use (&$count, &$users) {
            $projects = $this->getUserProjects($user->id);
            if ($projects->count() == 0) {
                $users[] = [$user->name, $user->email, $user->id, $user->created_at];
                $count++;
            }
        });

        $this->table(["Name", "EMail", "ID", "CreatedAt"], $users);
        echo "{$count} users have no projects\n";
        return 0;
    }
}
