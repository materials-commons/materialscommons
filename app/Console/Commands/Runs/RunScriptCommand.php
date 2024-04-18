<?php

namespace App\Console\Commands\Runs;

use App\Actions\Run\RunScriptAction;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Console\Command;

class RunScriptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-run:run-script {project : project id} {file : file id} {email : user email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a script synchronously';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = new RunScriptAction();
        $project = Project::findOrFail($this->argument('project'));
        $file = File::findOrFail($this->argument('file'));
        $user = User::where('email', $this->argument('email'))->first();

        $run = $action->execute($file, $project, $user, null, true);

        return 0;
    }
}
